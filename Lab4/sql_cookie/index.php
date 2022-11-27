<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset=utf-8">
        <meta name="viewport" content="width=device-width", initial-scale=1.0/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Форма регистрации</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            form {
                width: 400px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-4">
            <?php
            if ($_COOKIE['user'] == ''):
                ?>
                <div class="row position-absolute top-50 start-50 translate-middle">
                    <div class="col">
                        <h1>Форма авторизации</h1><br>
                        <form action="auth.php" method="post">
                            <input type="text" class="form-control" name="login"
                                   id="login" placeholder="Введите логин"><br>
                            <input type="password" class="form-control" name="password"
                                   id="login" placeholder="Введите пароль"><br>
                            <button class="btn btn-success form-control"
                                    type="submit">Войти</button><br><br><br>
                            <a href="autorization.php" >
                                <button type="button" class="btn btn-success form-control">Зарегистрироваться</button>
                            </a> 
                        </form>
                    </div>
                </div>
            <?php else: ?>

                <?php
                $mysql = new mysqli('localhost', 'root', '', 'register');

                $flag = $mysql->query("SELECT * FROM `banners`");

                //число банеров
                $res = $mysql->query("SELECT count(*) FROM `banners`");
                $row = $res->fetch_row();
                $count = $row[0];
                
                ?>


                <div class="container">
                    <div class="row">
                        <?php
                        //если куки не установлены, то считывем данные из базы, и выводим случайный банер
                        if ($_COOKIE['banners'] == ''):
                        $link = array();
                        $watch = array();
                        $name = array();
                        //выгружаем данные
                        while ($result = $flag->fetch_assoc()) {
                        array_push($link, $result['link']);
                        array_push($watch, $result['watch']);
                        array_push($name, $result['name']);
                            ?>
                            <!--<div class="col-md-4">
                                <a href="<?php //echo $result['link']; ?>"><img src="image/<?php //echo $result['name']; ?>.png" alt="<?php //echo $result['name']; ?>"></a>
                            </div>-->
                            <?php
                        }
                        //выбор случайного банера
                        $rnd = rand(0,$count-1);
                        $watch[$rnd] = $watch[$rnd] - 1;
                        //запись куки (число отсавшихся показов банеров)
                        $cookie = implode(' ', $watch);
                        setcookie('banners', $cookie, time() + 3600, "/");
                        ?>
                            <div class="col-md-1">
                                <a href="<?php echo $link[$rnd]; ?>"><img src="image/<?php echo $name[$rnd]; ?>.png" alt="<?php echo $name[$rnd]; ?>"></a>
                            </div>
                        <?php
                        //куки банеров уже были
                        else:
                            $link = array();
                            $watch = array();
                            $name = array();
                            //выгружаем ссылки и имена картинок
                            while ($result = $flag->fetch_assoc()) {
                                array_push($link, $result['link']);
                                array_push($name, $result['name']);
                            }
                            //из куки получаем число оставшихся показов для банеров
                            $the_cookie = $_COOKIE['banners'];
                            $watch = explode(' ', $the_cookie);
                            
                            $bool = 0;
                            for ($i = 0; $i < $count; $i++) {
                                if ($watch[$i] > 0) {
                                    $bool = 1;
                                }
                            }
                            //если все банеры показаны, то начинаем их показ заново
                            if ($bool == 0){
                                $flag = $mysql->query("SELECT * FROM `banners`");
                                while ($result = $flag->fetch_assoc()) {
                                    $watch[$result['id']-1] = $result['watch'];
                                }
                            }
                            
                            
                            $rnd = rand(0,$count-1);
                            while ($watch[$rnd] == 0){
                                $rnd = rand(0,$count-1);
                            }
                            
                            //устанавливаем куки
                            $watch[$rnd] = $watch[$rnd] - 1;
                            $cookie = implode(' ', $watch);
                            setcookie('banners', $cookie, time() + 3600, "/");
                            ?>
                                <div class="col-md-1">
                                    <a href="<?php echo $link[$rnd]; ?>"><img src="image/<?php echo $name[$rnd]; ?>.png" alt="<?php echo $name[$rnd]; ?>"></a>
                                </div>
                            <?php
                        ?>
                    </div>
                </div>

                <?php
                $mysql->close();
                ?>
                <?php endif; ?>
                    <p><?= $_COOKIE['users'] ?>Чтобы выйти нажмите <a href="/exit.php">здесь</a></p>    
            
            <?php endif; ?>
        </div>
    </body>
</html>