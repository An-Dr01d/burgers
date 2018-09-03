<?php

function authoriz($name, $email, $phone, $pdo)
{
    $query = "SELECT `id` FROM `users` WHERE `email` = '$email'"; // получаем id пользователя из базы
    $sql = $pdo->prepare($query);
    $sql->execute(); // запрос на выполнение
    $result = $sql->fetch(PDO::FETCH_ASSOC)['id'];// получаем данные (id пользователя) в виде массива

    if (empty($result)) { // Если это новый посетитель
        $query = "INSERT INTO `users`(`name`, `email`, `phone`) VALUES ('$name', '$email', '$phone');"; // Добавляем его в базу
        $sql = $pdo->prepare($query);
        $sql->execute();
        $result = $pdo->lastInsertId(); // Номер заказа
    }
    return $result;
}

function creat_new_order($user_id, $street, $home, $appt, $floor, $comment, $part, $name, $pdo)
{
    $query = "INSERT INTO `orders`(`id_users`, `street`, `home`, `floor`, `appt`, `name`, part, `comment`) VALUES ('$user_id', '$street', '$home', '$floor', '$appt', '$name', '$part', '$comment');";
    $sql = $pdo->prepare($query);
    try {
        $sql->execute();
    } catch (\PDOException $exception){
        echo $exception->getMessage();
    }

    return $pdo->lastInsertId(); // Номер заказа
}

function get_orders_count($user_id, $pdo)
{
    $query = "SELECT COUNT(*) as number_orders FROM `orders` WHERE `id_users` = '$user_id'";
    $sql = $pdo->prepare($query);
    try {
        $sql->execute();
    } catch (\PDOException $exception){
        echo $exception->getMessage();
    }
    $number_of_rows = $sql->fetch(PDO::FETCH_ASSOC);// получаем количество заказов
    return $number_of_rows['number_orders']; // Используем переменную для прямого доступа к значению количества заказов

}

function send_email($order_id, $number_orders, $pdo)
{
    $query = "SELECT * FROM `orders` WHERE `id` = '$order_id'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $address_orders = $sql->fetch(PDO::FETCH_ASSOC);
    $time_order = date('d.m.Y H.i'); // фиксируем текущее время
    $file = 'letters/letter_order.html';
    $title = "Заказ № $order_id<br>";
    $time = "Время заказа - $time_order<br/>";
    $text = "DarkBeefBurger за 500 рублей, 1 шт<br>";
    if ($number_orders == 1) {
        $count_order = "Спасибо - это ваш первый заказ!";
    } else {
        $count_order = "Спасибо! Это уже $number_orders заказ!";
    }
    echo $order_message = $title . $time . $text;
    echo "ул." . $address_orders['street'] . ", дом " . $address_orders['home'] . ", корпус " . $address_orders['part'] . ", квартира " . $address_orders['appt'] . ", этаж " . $address_orders['floor'] . "<br/>";
    echo $count_order;
// Записываем в файл
    $write_order = file_get_contents($file);
    $write_order .= $order_message;
    file_put_contents($file, $write_order);
}
