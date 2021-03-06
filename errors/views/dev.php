<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ошибка!</title>
</head>
<body style="background-color: #e4b9b9">
    <h1>Произошла ошибка</h1>
    <p><b>Уровень ошибки: </b><?= $errLevel ?></p>
    <p><b>Текст ошибки: </b><?= $errstr ?></p>
    <p><b>Файл, в котором произошла ошибка: </b><?= $errfile ?></p>
    <p><b>Строка, в которой произошла ошибка: </b><?= $errline ?></p>

    <?php if (!empty($trace)): ?>
        <p><b>Трассировка вызова: </b></p>
        <?php foreach (explode('#', $trace) as $item): { ?>
            <?= $item; ?><br>
        <?php } endforeach; ?>
    <?php endif; ?>

</body>
</html>