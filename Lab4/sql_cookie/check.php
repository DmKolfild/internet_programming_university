<?php
    // получение введенных данных
    $login = filter_var(trim($_POST['login']),FILTER_SANITIZE_STRING);
    $name = filter_var(trim($_POST['name']),FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);
    
    if(mb_strlen($login) < 5 || mb_strlen($login) > 90) {
        echo "Недопустимая длина логина";
        exit();
    }
    if(mb_strlen($name) < 3 || mb_strlen($name) > 50) {
        echo "Недопустимая длина имени";
        exit();
    }
    if(mb_strlen($password) < 2 || mb_strlen($password) > 6) {
        echo "Недопустимая длина пароля (от 2 до 6 символо)";
        exit();
    }
    
    //хэширование пароля
    $password = md5($password."lsjfwoeij32");
    
    // запись в базу данных
    $mysql = new mysqli('localhost','root','','register');
    
    $result = $mysql->query("SELECT * FROM `users` WHERE `login` = '$login'");
    $user = $result->fetch_assoc();
    if($user){
        echo "Такой пользователь уже зарегистрирован";
        exit();
    }
    
    $mysql->query("INSERT INTO `users` (`login`, `pass`, `name`) VALUES('$login', '$password', '$name')");
    $mysql->close();
    
    header('Location: index.php'); //возврат на исходную страничку
?>

