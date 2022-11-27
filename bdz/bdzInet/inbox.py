import imaplib
import email
import os
import uuid
import json
import logging
import datetime
from config import read_config
from send_mail import send
from get_size_expansion import get_size_expansion



# DELETE_MAIL=False/True
# EMAIL_ADMIN=None/...@yandex.ru
# KEY_WORDS=news.bug.feedback

# запись логов об ошибках
logging.basicConfig(filename='log.txt', filemode='a', level=logging.DEBUG)
# Словарь, хранящий данные из .env файла
params_dict = read_config(".env")
kew_words = str(params_dict['KEY_WORDS']).split('.')  # получение ключевых слов

def get_inbox(error_mail=""):
    try:
        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                           'Подключение к почтовому ящику...', file=botlogfile)
        mail = imaplib.IMAP4_SSL(params_dict['IMAP_HOST'], params_dict['IMAP_PORT'])
        mail.login(params_dict['EMAIL_LOGIN'], params_dict['EMAIL_PASSWORD'])
        mail.select("inbox", readonly=False)

        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                           'Успешное подключение к почтовому ящику', file=botlogfile)
    except:
        print(dtn.strftime("%d-%m-%Y %H:%M"), 'Ошибка подключения к почте', file=botlogfile)

        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                           'Не удалось подключиться к почтовому ящику', file=botlogfile)

    # чтение почтового ящика
    _, search_data = mail.search(None, 'UNSEEN')
    my_message = []
    for num in search_data[0].split():
        email_data = {}
        _, data = mail.fetch(num, '(RFC822)')
        _, b = data[0]


        try:
            email_message = email.message_from_bytes(b)
        except:
            print(dtn.strftime("%d-%m-%Y %H:%M"), 'Ошибка обработки письма', file=botlogfile)
            mail.store(num, '-FLAGS', '(\Seen)')
        print("--- нашли письмо от: ", email.header.make_header(email.header.decode_header(email_message['From'])))

        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                           'Данные писем получены', file=botlogfile)

        # расскоментировать, если необходимо, чтобы статус писем оставался "непрочитано"
        # mail.store(num, '-FLAGS', '(\Seen)')


        flag_key = 0  # флаг для проверки наличия ключевых слов
        for key in kew_words:
            if key in email_message['subject']:
                email_data['key'] = key
                flag_key = 1
                break
        # в случае, если не найдены ключевые слова, то приписывается значение nope
        if (flag_key == 0):
            email_data['key'] = 'nope'
        # обработка письма
        for header in ['subject', 'to', 'from', 'date']:
            email_data[header] = email_message[header]
        flag_mkdir = 0
        for part in email_message.walk():
            # письмо
            if part.get_content_type() == "text/plain":
                body = part.get_payload(decode=True)
                email_data['body'] = body.decode()
            elif part.get_content_type() == "text/html":
                html_body = part.get_payload(decode=True)
                email_data['html_body'] = html_body.decode()
            # обработка вложений письма и создание пути для их сохранения
            if "application" in part.get_content_type():
                if flag_mkdir == 0:
                    path = params_dict['PATH_ATTACHMENT'] + str(uuid.uuid4())
                    try:
                        os.mkdir(path)
                        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                                           'Новоя директория создана',
                                                                           file=botlogfile)
                    except OSError:
                        print("Создать директорию %s не удалось" % path)
                        print(dtn.strftime("%d-%m-%Y %H:%M"), 'Создать директорию не удалось', file=botlogfile)
                        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                                           'Создать директорию не удалось',
                                                                           file=botlogfile)
                    else:
                        print("Успешно создана директория %s " % path)
                        print(dtn.strftime("%d-%m-%Y %H:%M"), 'Успешно создана директория', file=botlogfile)

                filename = part.get_filename()
                filename = str(email.header.make_header(email.header.decode_header(filename)))
                if not (filename):
                    filename = "test.txt"
                print("---- нашли вложение ", filename)
                fp = open(os.path.join(path, filename), 'wb')
                part_file = path+'/'+filename
                fp.write(part.get_payload(decode=1))
                fp.close
                flag_mkdir = 1

        # формирование характеристик файла
        if (flag_mkdir == 1):
            list_fail_size_expansion = get_size_expansion(path)
            email_data['attachments'] = list_fail_size_expansion

        try:
            # удаление письма, если выставлена соответсвующая настройка в конфигурационном файле
            if str(params_dict['DELETE_MAIL']) == 'True':
                print("-- удаляем письмо");
                mail.store(num, '+FLAGS', '(\Deleted)')
                mail.expunge()
                if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                                   'Письмо успешно удалено', file=botlogfile)
        except:
            if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                               'Письмо не удалено - fail', file=botlogfile)
            error_mail = error_mail +'\nПисьмо не удалено - fail'

        my_message.append(email_data)

    # запись данных письма в json файл
    data = []
    try:
        if (os.stat("data.json").st_size != 0):
            with open('data.json') as json_file:
                data = json.load(json_file)
                if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                                   'Файл data.json прочтен', file=botlogfile)
    except:
        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"), 'Ошибка открытия файла data.json', file=botlogfile)
        error_mail = error_mail +'\nОшибка открытия файла data.json'
    try:
        with open('data.json', 'w') as outfile:
            json.dump(data+my_message, outfile)
            if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                               'Почта сохранена в json формате', file=botlogfile)
    except:
        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                           'Ошибка записи файлов data+my_message', file=botlogfile)
        error_mail=error_mail+"\nОшибка записи файлов data+my_message"

    # Отправка сообщения админу о возникших ошибках
    if (params_dict['EMAIL_ADMIN'] != 'None'):
        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"),
                                                            'Отправка сообщения о сеансе на почту админа',
                                                            file=botlogfile)
        if error_mail=='':
            error_mail='Ошибок нет'
        send(error_mail) # Записываем логи

    return my_message

if __name__ == "__main__":
    dtn = datetime.datetime.now()
    botlogfile = open(params_dict['LOG_PATH'], 'a')

    if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"), 'Начало сеанса: чтение почты', file=botlogfile)
    try:
        my_inbox = get_inbox()  # чтение почты
        print(my_inbox)
        if (my_inbox == []):
            if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"), 'Программа выполнена ящик пуст', file=botlogfile)
            print('Ящик пуст')
    except KeyboardInterrupt: # ошибка управления клавиатурой
        if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"), 'KeyboardInterrupt (преджевременная оставновка программы)', file=botlogfile)
        exit()

    if params_dict['LOG_ERROR_SUCCESS'] == 'NO': print(dtn.strftime("%d-%m-%Y %H:%M"), 'Сеанс окончен', file=botlogfile)
    botlogfile.close()


