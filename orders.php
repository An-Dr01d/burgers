<?php
echo"<pre>";
require_once "login.php";
require "func.php";
global $pdo;

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$street = $_POST['street'];
$home = $_POST['home'];
$appt = $_POST['appt'];
$floor = $_POST['floor'];
$comment = $_POST['comment'];
$part = $_POST['part'];

$user_id = authoriz($name, $email, $phone, $pdo);
$order_id = creat_new_order($user_id, $street, $home, $appt, $floor, $comment, $part, $name, $pdo);
$number_orders = get_orders_count($user_id, $pdo);
send_email($order_id, $number_orders, $pdo);
