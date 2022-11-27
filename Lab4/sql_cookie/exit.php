<?php
    setcookie('user', $user['name'], time() - 3600, "/");
    header('Location: /'); //возврат на исходную страничку
?>

