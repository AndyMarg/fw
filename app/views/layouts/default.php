<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/public/css/style.css">

    <?php \fw\core\base\View::printMeta(); ?>
</head>
<body>

<div class="container">

    <ul class="nav nav-pills">
        <li><a href="/page/about">About</a></li>
        <li><a href="/user/signup">Signup</a></li>
        <li><a href="/user/login">Login</a></li>
        <li><a href="/user/logout">Logout</a></li>
        <li><a href="/admin">Админка</a></li>
    </ul>

    <!-- Показать ошибки, если есть  -->
    <?php if(isset($_SESSION['errors'])):  ?>
        <div class="alert alert-danger">
            <?php
                echo $_SESSION['errors'];
                unset($_SESSION['errors']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Если действие успешно -->
    <?php if(isset($_SESSION['success'])):  ?>
        <div class="alert alert-success">
            <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>


    <!-- Контент из View -->
    <?=$content?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/public/bootstrap/js/bootstrap.min.js"></script>

<?php foreach ($scripts as $script) {
    echo $script . "\n";
} ?>

</body>
</html>