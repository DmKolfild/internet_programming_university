<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <button>
            <a href="index.php">task1</a>
    </button>
    <button>
            <a href="task3.php">task3</a>
    </button>
        <br><br>
    Формирование json файла:
    <form method=post>    
        <input name="jsonCreator" type=submit value="Сформировать">   
    <form><br> 
    <!--Формирование json файла. Отбор нужных элементов и атрибутов из xml файла для формирования json файла.-->
    <?php
        function xml_to_json($dom) {
            $s = '<projects>';
            foreach ($dom->project as $temp) {
                $s = $s.'<project'.' id="'.$temp['id'].'"'.' year="'.$temp['year'].'"'.'>'.'<title>'.$temp->title.'</title>';
                $s = $s.'<authors>';
                foreach ($dom->project->authors->author as $auth) {
                    $s = $s.'<author>'.$auth.'</author>';
                }
                $s = $s.'</authors>';
                $s = $s.'</project>';
            }
            $s = $s.'</projects>';
            $xml = simplexml_load_string($s);
            $json = json_encode($xml, JSON_UNESCAPED_UNICODE);
            return $json;
        }
    ?>
        
    <?php
    if (isset($_POST['jsonCreator'])) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'on');
        $dom = simplexml_load_file('portfolio.xml');
        if (count($dom->project) == 0)
            echo "не найдено";
        else {
            //преобразование трубуемой выборки в json формат
            $s = xml_to_json($dom);
        }    
        //формирование json файла
        echo '<br><br>'.$s;
        file_put_contents('newjson.json', $s);
    }
    ?>
    </body>
</html>
