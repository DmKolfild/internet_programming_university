import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import re
from formatting import format_msg
from config import read_config

# Словарь, хранящий данные из .env файла
params_dict = read_config(".env")


def send_mail(text='Email Body', subject='Ошибки работы сеанса', from_email=params_dict['EMAIL_LOGIN'], to_emails=None, html=None):
    assert isinstance(to_emails, list)
    # формирование письма
    msg = MIMEMultipart('alternative')
    msg['From'] = from_email
    msg['To'] = ", ".join(to_emails)
    msg['Subject'] = subject
    txt_part = MIMEText(text, 'plain')
    msg.attach(txt_part)
    if html != None:
        html_part = MIMEText(html, 'html')
        msg.attach(html_part)
    msg_str = msg.as_string()
    # login to my smtp server
    server = smtplib.SMTP(host='smtp.yandex.ru', port=587)
    server.ehlo()
    server.starttls()
    server.login(params_dict['EMAIL_LOGIN'], params_dict['EMAIL_PASSWORD'])
    server.sendmail(from_email, to_emails, msg_str)
    server.quit()

def send(name):
    to_email = params_dict['EMAIL_ADMIN']
    msg = format_msg(my_name=name)
    # send the message
    try:
        send_mail(text=msg, to_emails=[to_email], html=None)
    except:
        print('Собщение не удалось отправить.')