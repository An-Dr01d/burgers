<?php
$dsn = 'mysql:host=127.0.0.1;dbname=burgers;charset=utf8';
$pdo = new PDO($dsn, 'root', '002574100');
//$db = new PDO('mysql:host=127.0.0.1', 'root', '002574100');
return $pdo;

/*$db_host = "127.0.0.1";
$db_username = "root";
$db_password = "root";
$db_name = "burgers";


try {
    $connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $warning) {
    print "Внимание! Возникла проблема при соединение с сервером!<br>" . $warning->getMessage();
    exit();
}*/