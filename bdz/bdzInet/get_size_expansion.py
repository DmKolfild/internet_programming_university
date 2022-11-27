
import os

# определение размера файла и его расширения
def get_size_expansion(start_path):
    total_size = 0
    list_fail_size_expansion = []
    dict_file_size_expansion = {}
    for dirpath, dirnames, filenames in os.walk(start_path):
        dict_file_size_expansion = {}
        for f in filenames:
            fp = os.path.join(dirpath, f)
            # skip if it is symbolic link
            if not os.path.islink(fp):
                _, file_extension = os.path.splitext(fp)
                total_size = str((os.path.getsize(fp))/1024) + 'КБ'
                list_fail_size_expansion.append({'filename': f, 'extension': file_extension,'size': total_size,'path': start_path})
    return list_fail_size_expansion