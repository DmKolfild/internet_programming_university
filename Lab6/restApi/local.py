import requests
import time
import uuid
from validate_email import validate_email

while True:

    print("Список команд:\n1 - вывести все письма\n2 - вывести письмо по id\n"
          "3 - добавить новое письмо\n4 - удалить письмо по id\n0 - выход")
    command = int(input("Введите номер команды: "))
    if command == 1:
        res = requests.get("http://127.0.0.1:3000/api/mails/0")
        u = res.json()
        for item in u.items():
            print(item)
    if command == 2:
        while True:
            try:
                mail_id = input("Введите id письма: ")
                res = requests.get("http://127.0.0.1:3000/api/mails/" + mail_id)
                print(res.json())
                break
            except:
                print('неверный id!')
    if command == 3:
        sub = input("Тема письма: ")
        while True:
            fr = input("Почта : ")
            is_valid = validate_email(fr)
            if is_valid:
                break
            print("Неверный формат почты")
        while True:
            mes = input("Сообщение: ")
            if len(mes) != 0:
                break
            print("Сообщение не должно быть пустым")
        res = requests.post("http://127.0.0.1:3000/api/mails/" + str(uuid.uuid4()),
                            json={"subject": sub, "from": fr, "message": mes})
        for item in res.json().items():
            print(item)

    if command == 4:
        while True:
            try:
                mail_id = input("Введите id письма: ")
                res = requests.delete("http://127.0.0.1:3000/api/mails/" + mail_id)
                for item in res.json().items():
                    print(item)
                break
            except:
                print("неверный id!")
    time.sleep(2)
    if command == 0:
        break
