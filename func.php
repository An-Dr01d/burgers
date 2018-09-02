<?php

function authoriz_registr($name, $email, $phone, $street, $home, $appt, $floor, $comment, $part, $pdo)
{
    $authoriz = "SELECT `id` FROM `users` WHERE `email` = '$email'"; // получаем id пользователя из базы
    $sql = $pdo->prepare($authoriz);
    $sql->execute(); // запрос на выполнение
    $result = $sql->fetch(PDO::FETCH_ASSOC);// получаем данные (id пользователя) в виде массива
    if ($result === false) { // Если это новый посетитель
        $authoriz = "INSERT INTO `users`(`name`, `email`, `phone`) VALUES ('$name', '$email', '$phone');"; // Добавляем его в базу
        $sql = $pdo->prepare($authoriz);
        $sql->execute();
        $authoriz = "SELECT `id` FROM `users` WHERE `email` = '$email'";
        $sql = $pdo->prepare($authoriz);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
    } else {
        $authoriz = "INSERT INTO `orders`(`id_users`, `street`, `home`, `floor`, `appt`, `name`, `corp`, `comment`) VALUES ({$result['id']}, '$street', '$home', '$floor', '$appt', '$name', '$part', '$comment');";
        $sql = $pdo->prepare($authoriz);
        $sql->execute();
    }
}
$last_id = $pdo->lastInsertId(); // Номер заказ
function countOrder($result, $pdo)
{
    $query = "SELECT COUNT(*) as number_orders FROM `orders` WHERE `id_users` = {$result['id']}";
    $result = $pdo->prepare($query);
    $result->execute();
    $number_of_rows = $result->fetch(PDO::FETCH_ASSOC);// получаем количество заказов
    $cout_orders = $number_of_rows[number_orders]; // Используем переменную для прямого доступа к значению количества заказов

    if ($cout_orders == 1) {
        $count_order = "Спасибо - это ваш первый заказ!";

    } else {
        $count_order = "Спасибо! Это уже $cout_orders заказ!";

    }
    return $count_order;
}
$last_id = $pdo->lastInsertId(); // Номер заказа

function user() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM users');
    $data = $stmt->fetchAll();
    return $data;
}
$datas = user();
var_dump($datas);