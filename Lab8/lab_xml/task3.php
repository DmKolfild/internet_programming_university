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
            <a href="task2.php">task2</a>
    </button>
        <br><br>
    <!--    приведение мобильных телефон к единому формату-->
    <?php
        function phone_format($phone) 
        {
            $phone = trim($phone);
            $res = preg_replace(
                    array(
                            '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                            '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                            '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                            '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',	
                            '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                            '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',					
                    ), 
                    array(
                            '+7($2)$3-$4-$5', 
                            '+7($2)$3-$4-$5', 
                            '+7($2)$3-$4-$5', 
                            '+7($2)$3-$4-$5', 	
                            '+7($2)$3-$4', 
                            '+7($2)$3-$4', 
                    ), 
                    $phone
            );

            return $res;
        }
    ?>
        
        
    Генератор номеров мобильных операторов: 
    <br>
    <form method=post>    
    Кол-во:
    <input type=text name=m value="50">  <br>
    <input name="myActionName" type=submit value="Сгенерировать номера">   
    <form><br> 

<!-- формирование различных форматов мобильных номеров-->
<?php 
$cod_arr = array('920', '938', '964', '909', '916', '911', '914', '978', '962', '950', '906', '919', '952', '922', '960', '968', '961', '913', '983', '917', '912', '921', '937', '965', '900', '927', '951', '904', '903', '999', '953', '924', '702', '777', '966', '905', '910', '984', '981', '963', '701', '929', '925', '707', '908', '918', '915');
$num_arr = array('0','1','2','3','4','5','6','7','8','9');
$i = "1000000"; 
$stack = array();
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz'; //для генерации случайной строки
if (isset($_POST['myActionName'])) {
    $m = ($_POST['m']); 
    for($n=0; $n<$m; $n++)
    {
        $count = array_rand(array('0','1','2','3','4','5','6'));
        switch ($count) {
            case 0:
                //Тип: +7xxxxxxxxxx
                $stack[$n] = '+7'. $cod_arr[array_rand($cod_arr)] . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)];
                break;
            case 1:
                //Тип: 8xxxxxxxxxx
                $stack[$n] = '8'. $cod_arr[array_rand($cod_arr)] . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]; 
                break;
            case 2:
                //Тип: +7(xxx)xxxxxxx
                $stack[$n] = '+7'. '(' . $cod_arr[array_rand($cod_arr)] . ')' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]; 
                break;
            case 3:
                //Тип: 8(xxx)xxxxxxx
                $stack[$n] = '8'. '(' . $cod_arr[array_rand($cod_arr)] . ')' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]. $num_arr[array_rand($num_arr)]; 
                break;
            case 4:
                //Тип: 8-xxx-xxx-xx-xx
                $stack[$n] = '8'. '-' . $cod_arr[array_rand($cod_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)]; 
                break;
            case 5:
                //Тип: +7-xxx-xxx-xx-xx
                $stack[$n] = '+7'. '-' . $cod_arr[array_rand($cod_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)]; 
                break;
            case 5:
                //Тип: 
                $stack[$n] = '+7'. '-' . $cod_arr[array_rand($cod_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)] . '-' . $num_arr[array_rand($num_arr)] . $num_arr[array_rand($num_arr)]; 
                break;
            case 6:
                //Тип: численно буквенная строка
                $stack[$n] = substr(str_shuffle($permitted_chars), 0, 11);
                break;
        }
    }

    //  проверка случайно сгенерированных номеров при помощи разных различных регулярных выражений
    //  И отсчет времени, которое необходимо для проверки телефонных номеров при помощи данных регулярных выражений
    
    $count_right = 0; // счётчик корректных номеров
    $time_start = microtime(true);
    foreach ($stack as $value) {
        if (preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $value)) {
           $count_right = $count_right + 1;
        }
    }
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo '<br>'.'Способ 1:'.'<br>';
    echo 'Количество правильных номеров:'.$count_right.' из '.$m.'<br>';
    echo 'Время выполнения: '.$time.' мкс'.'<br>';
    
    
    
    $count_right = 0; // счётчик корректных номеров
    $time_start = microtime(true);
    foreach ($stack as $value) {
        if (preg_match("/^\+?[78][-\(]?\d{3}\)?-?\d{3}-?\d{2}-?\d{2}$/", $value)) {
           $count_right = $count_right + 1;
        }
    }
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo '<br>'.'Способ 2:'.'<br>';
    echo 'Количество правильных номеров:'.$count_right.' из '.$m.'<br>';
    echo 'Время выполнения: '.$time.' мкс'.'<br>';
    
    
    
    $count_right = 0; // счётчик корректных номеров
    $time_start = microtime(true);
    foreach ($stack as $value) {
        if (preg_match("/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/", phone_format($value))) {
           $count_right = $count_right + 1;
        }
    }
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo '<br>'.'Способ 3:'.'<br>';
    echo 'Количество правильных номеров:'.$count_right.' из '.$m.'<br>';
    echo 'Время выполнения: '.$time.' мкс'.'<br>';
    
    
    
    $count_right = 0; // счётчик корректных номеров
    $time_start = microtime(true);
    foreach ($stack as $value) {
        if (preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3,4}\)?[\- ]?)?[\d\- ]{5,10}$/", $value)) {
           $count_right = $count_right + 1;
        }
    }
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo '<br>'.'Способ 4:'.'<br>';
    echo 'Количество правильных номеров:'.$count_right.' из '.$m.'<br>';
    echo 'Время выполнения: '.$time.' мкс'.'<br>';
}


 ?>

    </body>
</html>
