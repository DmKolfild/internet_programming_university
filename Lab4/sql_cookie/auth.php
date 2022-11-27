<?php
    // получение введенных данных
    $login = filter_var(trim($_POST['login']),FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);

    //хэширование пароля
    $pass = md5($pass."lsjfwoeij32");

    // запись в базу данных
    $mysql = new mysqli('localhost','root','','register');
    
    $result = $mysql->query("SELECT * FROM `users` WHERE `login` = '$login' AND `pass` = '$pass'");

    $user = $result->fetch_assoc();
    if(count($user) == 0){
        echo "Такой пользователь не найден";
        exit();
    }
    
    setcookie('user', $user['name'], time() + 3600, "/"); // time() - время жизни куки
    
   
    
    $mysql->close();
    
    header('Location: /'); //возврат на исходную страничку
?>

