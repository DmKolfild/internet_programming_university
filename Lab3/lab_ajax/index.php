<?php
require_once('city.php'); // подключаем список с городами

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

// возвращаем список городов
if ($action == 'getCity')
{
    if (isset($city[$_GET['region']]))
    {
        echo json_encode($city[$_GET['region']]); // возвраащем данные в JSON формате;
    }
    else
    {
        echo json_encode(array('Выберите область'));
    }

    exit;
}

// выводим данные
if ($action == 'postResult')
{
    echo print_r($_POST, true);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Зависимые списки</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    
    <script type="text/javascript">
        function loadCity(select)
        {
            var citySelect = $('select[name="city"]');
            citySelect.attr('disabled', 'disabled'); // делаем список городов не активным
            
            // послыаем AJAX запрос, который вернёт список городов для выбранной области
            $.getJSON('index.php', {action:'getCity', region:select.value}, function(cityList){
                citySelect.html(''); // очищаем список городов
                // заполняем список городов пришедшими данными
                $.each(cityList, function(i){
                    citySelect.append('<option value="' + i + '">' + this + '</option>');
                });
                
                citySelect.removeAttr('disabled'); // делаем список городов активным
                
            });
        }
    </script>
</head>
<body>
    <form action="index.php" method="post">
        <select name="region" onchange="loadCity(this)">
            <option>Выберите страну</option>
            
            <?php
            // заполняем список областей
            foreach ($city as $region => $cityList)
            {
                echo '<option value="' . $region . '">' . $region . '</option>' . "\n";
            }
            ?>
            
        </select>
        
        <select name="city" disabled="disabled">
            <option>Выберите город</option>
        </select>

        <input type="hidden" name="action" value="postResult" />
        <input type="submit" value="отправить" />
    </form>
  
</body>
</html>
