<?php
declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once('../function.php');

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        if (isset($_SESSION['login']) && ($_SESSION['$admin_access'] == 1)) {
            if (isset($_GET['added'])) {
                echo "Товар добавлен";
            } elseif (isset($_GET['edited'])) {
                echo "Товар отредактирован";
            } elseif (isset($_GET['deleted'])) {
                echo "Товар удален";
            }
            show_catalog($_SESSION['$admin_access']);
        } else {
            echo "Страница только для администраторов!";
        }

    ?>
    
</body>
</html>