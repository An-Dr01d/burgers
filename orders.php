<?php
echo"<pre>";
$pdo = require_once "login.php";

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$street = $_POST['street'];
$home = $_POST['home'];
$appt = $_POST['appt'];
$floor = $_POST['floor'];
$comment = $_POST['comment'];
$part = $_POST['part'];

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
authoriz_registr($name, $email, $phone, $street, $home, $appt, $floor, $comment, $part, $pdo);
$last_id = $pdo->lastInsertId(); // Номер заказа

function countOrder($result, $pdo)
{
    $query = "SELECT COUNT(*) as number_orders FROM `orders` WHERE `id_users` = {$result['id']}";
    $result = $pdo->prepare($query);
    $result->execute();
    $number_of_rows = $result->fetch(PDO::FETCH_ASSOC);// получаем количество заказов
    $count_orders = $number_of_rows[number_orders]; // Используем переменную для прямого доступа к значению количества заказов
    if ($count_orders == 1) {
        $count_order = "Спасибо - это ваш первый заказ!";
    } else {
        $count_order = "Спасибо! Это уже $count_orders заказ!";
    }
    return $count_order;
}
countOrder($result, $pdo);
function check($last_id, $street, $home, $part, $appt, $floor, $count_order)
{
    $time_order = date('d.m.Y H.i'); // фиксируем текущее время
    $file = 'letters/letter_order.html';
    $title = "Заказ № $last_id<br>";
    $time = "Время заказа - $time_order<br/>";
    $fullAddress = "Ваш заказ будет доставлен по адресу - ул. $street, дом $home, корпус $part, квартира $appt, этаж $floor<br>";
    $text = "DarkBeefBurger за 500 рублей, 1 шт<br>";
    echo $orderMessage = $title . $time . $fullAddress . $text . $count_order;
// Записываем в файл
    $writeOrder = file_get_contents($file);
    $writeOrder .= $orderMessage;
    file_put_contents($file, $writeOrder);
}
check($last_id, $street, $home, $part, $appt, $floor, $count_order);
