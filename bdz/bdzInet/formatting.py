# формирование письма для отправки админу на почту

msg_template = """{name}"""

def format_msg(my_name="Justin", my_website="cfe.sh"):
    my_msg = msg_template.format(name=my_name, website=my_website)
    return my_msg