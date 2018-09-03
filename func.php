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
    //echo $address_orders['street']. "<br />";
    $time_order = date('d.m.Y H.i'); // фиксируем текущее время
    $file = 'letters/letter_order.html';
    $title = "Заказ № $order_id<br>";
    $time = "Время заказа - $time_order<br/>";
    //$fullAddress = "Ваш заказ будет доставлен по адресу - ул. $zakaz['street']. , дом $home, корпус $part, квартира $appt, этаж $floor<br>";
    $text = "DarkBeefBurger за 500 рублей, 1 шт<br>";
    if ($number_orders == 1) {
        $count_order = "Спасибо - это ваш первый заказ!";
    } else {
        $count_order = "Спасибо! Это уже $number_orders заказ!";
    }
    echo $orderMessage = $title . $time . $text;
    echo "ул." . $address_orders['street'] . ", дом " . $address_orders['home'] . ", корпус " . $address_orders['part'] . ", квартира " . $address_orders['appt'] . ", этаж " . $address_orders['floor'] . "<br/>";
    echo $count_order;
// Записываем в файл
    $writeOrder = file_get_contents($file);
    $writeOrder .= $orderMessage;
    file_put_contents($file, $writeOrder);
}


































//function authoriz_registr($name, $email, $phone, $street, $home, $appt, $floor, $comment, $part, $pdo)
//{
//    $authoriz = "SELECT `id` FROM `users` WHERE `email` = '$email'"; // получаем id пользователя из базы
//    $sql = $pdo->prepare($authoriz);
//    $sql->execute(); // запрос на выполнение
//    $result = $sql->fetch(PDO::FETCH_ASSOC);// получаем данные (id пользователя) в виде массива
//    if ($result === false) { // Если это новый посетитель
//        $authoriz = "INSERT INTO `users`(`name`, `email`, `phone`) VALUES ('$name', '$email', '$phone');"; // Добавляем его в базу
//        $sql = $pdo->prepare($authoriz);
//        $sql->execute();
//        $authoriz = "SELECT `id` FROM `users` WHERE `email` = '$email'";
//        $sql = $pdo->prepare($authoriz);
//        $sql->execute();
//        $result = $sql->fetch(PDO::FETCH_ASSOC);
//    } else {
//        $authoriz = "INSERT INTO `orders`(`id_users`, `street`, `home`, `floor`, `appt`, `name`, `corp`, `comment`) VALUES ({$result['id']}, '$street', '$home', '$floor', '$appt', '$name', '$part', '$comment');";
//        $sql = $pdo->prepare($authoriz);
//        $sql->execute();
//    }
//}
//$last_id = $pdo->lastInsertId(); // Номер заказ
//function countOrder($result, $pdo)
//{
//    $query = "SELECT COUNT(*) as number_orders FROM `orders` WHERE `id_users` = {$result['id']}";
//    $result = $pdo->prepare($query);
//    $result->execute();
//    $number_of_rows = $result->fetch(PDO::FETCH_ASSOC);// получаем количество заказов
//    $cout_orders = $number_of_rows[number_orders]; // Используем переменную для прямого доступа к значению количества заказов
//
//    if ($cout_orders == 1) {
//        $count_order = "Спасибо - это ваш первый заказ!";
//
//    } else {
//        $count_order = "Спасибо! Это уже $cout_orders заказ!";
//
//    }
//    return $count_order;
//}
//$last_id = $pdo->lastInsertId(); // Номер заказа
//
//function user() {
//    global $pdo;
//    $stmt = $pdo->query('SELECT * FROM users');
//    $data = $stmt->fetchAll();
//    return $data;
//}
//$datas = user();
//var_dump($datas);