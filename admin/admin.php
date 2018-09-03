<?php
$dsn = "mysql:host=127.0.0.1;dbname=burgers;charset=utf8";
$pdo = new PDO($dsn, 'root', '002574100');
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Панель администратора</title>
</head>
<body>
<?php
$db = $pdo->query('SELECT * FROM `users`');
$dataUsers = $db->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>База пользователей</h2>
<?php
foreach ($dataUsers as $key => $value) {
        echo '<br>';
    foreach ($value as $k => $v) {
        echo $k . ' : '  .$v . '<br>';
    }
}
?>
<?php
$db = $pdo->query('SELECT * FROM `orders`');
$dataOrders = $db->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>База заказов</h2>
<?php
foreach ($dataOrders as $key => $value) {
    echo '<br>';
    foreach ($value as $k => $v) {
        echo $k . ' : '  .$v . '<br>';
    }
}
?>
</body>
</html>