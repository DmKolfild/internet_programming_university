import re

# Считывание данных из конфигурационного файла в словарь
def read_config(config_file_name):
    result = dict()
    with open(config_file_name, "r") as config_file:
        for line in config_file:
            param_name, value = [i for i in re.split(r"[=\s]", line) if i != '']
            result[param_name] = value
    return result