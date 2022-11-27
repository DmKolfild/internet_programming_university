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
                width: 500px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-4">
            <?php
                if($_COOKIE['user'] == ''):
            ?>
            <div class="row position-absolute top-50 start-50 translate-middle">
                <div class="col">
                    <h1>Форма регистрации</h1><br>
                    <form action="check.php" method="post">
                        <input type="text" class="form-control" name="login"
                               id="login" placeholder="Введите логин"><br>
                        <input type="text" class="form-control" name="name"
                               id="login" placeholder="Введите имя"><br>
                        <input type="password" class="form-control" name="password"
                               id="login" placeholder="Введите пароль"><br>
                        <button class="btn btn-success form-control"
                                type="submit">Зарегистрироваться</button>
                    </form>
                </div>
               
            </div>
            <?php else: ?>
            <p>Привет <?=$_COOKIE['users']?>. Чтобы выйти нажмите <a href="/exit.php">здесь</a></p>
            <?php endif;?>
        </div>
    </body>
</html>