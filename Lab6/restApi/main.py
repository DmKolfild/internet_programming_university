from flask import Flask
from flask_restful import Api, Resource, reqparse
import json

app = Flask(__name__)
api = Api()

with open('maillist.json', 'r', encoding='utf-8') as fh:  # открываем файл на чтение
    mails = json.load(fh)  # загружаем из файла данные в словарь mails


class Main(Resource):
    def get(self, mail_id):
        if mail_id == "0":
            return mails
        else:
            return mails[mail_id]

    def post(self, mail_id):
        parser = reqparse.RequestParser()
        parser.add_argument("subject", type=str)
        parser.add_argument("from", type=str)
        parser.add_argument("message", type=str)
        mails[mail_id] = parser.parse_args()
        with open('maillist.json', 'w', encoding='utf-8') as fh:  # открываем файл на запись
            fh.write(json.dumps(mails,
                                ensure_ascii=False))  # преобразовываем словарь mails в unicode-строку и записываем в файл
        return mails

    # сохранить в json
    def delete(self, mail_id):
        del mails[mail_id]
        with open('maillist.json', 'w', encoding='utf-8') as fh:  # открываем файл на запись
            fh.write(json.dumps(mails,
                                ensure_ascii=False))  # преобразовываем словарь mails в unicode-строку и записываем в файл
        return mails


api.add_resource(Main, "/api/mails/<string:mail_id>")
api.init_app(app)

if __name__ == "__main__":
    app.run(debug=True, port=3000, host="127.0.0.1")
