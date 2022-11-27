<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <button>
            <a href="task2.php">task2</a>
    </button>
    <button>
            <a href="task3.php">task3</a>
    </button>
    <br><br>
    Вывод значений элементов и их атрибутов из файла portfolio.xml в виде html страницы: 
    <form method=post>    
        <input name="jsonCreator" type=submit value="Вывести">   
    <form><br>
    <!--Оборачивание необходимых элементов и необходимых атрибутов в html теги-->
    <?php
        function xml_to_html($arr) {
            $str = '';
            foreach ($arr->project as $temp) {
                $str = $str . '<pre>';
                $str = $str . 'ID: '. $temp['id'] . '<br>Year: '. $temp['year'] . '<br>Title: '. $temp->title . '<br>Authors: ' . '<br>';
                foreach ($arr->project->authors->author as $auth) {
                    $str = $str . '&#9' . $auth . '<br>';
                }
                $str = $str . '<pre>';
            }
            return $str;
        }
    ?>
    
    <!--Загрузка xml фала и вызов функции для преобразование в html-->
    <?php
    if (isset($_POST['jsonCreator'])) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'on');

        $dom = simplexml_load_file('portfolio.xml');
        if (count($dom->project) == 0)
            echo "не найдено";
        else {
            //отбор значений элементов и их атрибутов
            $s = xml_to_html($dom);
            echo $s;
        }
    }
    ?>
    </body>
</html>
