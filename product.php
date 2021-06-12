<?php
    declare(strict_types=1);

    ini_set('error_reporting', (string)E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    include_once('function.php');

    session_start();

    if (isset($_POST['add-product'])) {
        if (isset($_SESSION['login'])) {
            $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
            if (!$db) {
              exit("Не удалось соединиться:" . mysqli_connect_errno());
            }

            $select_users = mysqli_fetch_assoc(mysqli_query($db, "SELECT `id`, `role`, `login` FROM `users` WHERE `login` = '{$_SESSION['login']}'"));

            $select_carts = mysqli_fetch_assoc(mysqli_query($db, 
            "SELECT `id`, `id_customer`, `id_product`, `quantity` FROM `carts` 
            WHERE `id_customer` = '{$select_users["id"]}' AND `id_product` = '{$_POST["add-product"]}'"));

            if (isset($select_carts['id'])) {
                mysqli_query($db, "UPDATE `carts` SET `quantity` =  `quantity` + 1 
                WHERE `id_customer` = '{$select_users["id"]}' AND `id_product` = '{$_POST["add-product"]}'");
            } else {
                mysqli_query($db, "INSERT INTO `carts` ( `id_customer`, `id_product`, `quantity`) 
                VALUES ('{$select_users["id"]}', '{$_POST["add-product"]}', '1')");
            }
        } else {
            echo 'Добавлять товары в корзину могут только авторизованные пользователи <br>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php

        show_product();
    ?>
</body>
</html>