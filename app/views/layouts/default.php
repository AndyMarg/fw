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

    <!-- MENU -->
    <?php if(!empty($menu)): ?>
        <ul class="nav nav-pills">
            <?php foreach ($menu as $item):  ?>
                <li><a href="category/<?=$item['id']?>"><?=$item['name']?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h1>default layout</h1>
    <?=$content?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/public/bootstrap/js/bootstrap.min.js"></script>

<?php foreach ($scripts as $script) {
    echo $script . "\n";
} ?>

</body>
</html>