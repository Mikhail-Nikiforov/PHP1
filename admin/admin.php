<?php
declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once('../function.php');

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
        if (isset($_GET['added'])) {
            echo "Товар добавлен";
        } elseif (isset($_GET['edited'])) {
            echo "Товар отредактирован";
        } elseif (isset($_GET['deleted'])) {
            echo "Товар удален";
        }
        show_catalog(1)
    ?>
    <a href="createProduct.php" class="link">Добавить товар</a>
</body>
</html>