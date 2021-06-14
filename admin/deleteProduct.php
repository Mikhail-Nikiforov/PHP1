<?php
    declare(strict_types=1);

    ini_set('error_reporting', (string)E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');

    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
  
    if (!$db) {
        exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    if (empty($_GET['id'])) {
        die("Нет, ID");
    }
    $id = (int)$_GET['id'];

    $select = mysqli_query($db, "SELECT `id`, `adress`, `pic_name`, `views`, `product_name`, `price`, `description`FROM `products` WHERE id={$id}");   
    $product = mysqli_fetch_assoc($select);

    if (isset($_POST['delete_product'])) {
        header('Location: '.'admin.php?deleted=1');
        unlink("../{$product['adress']}/{$product['pic_name']}");
        $select = mysqli_query($db, 
        "DELETE FROM `products`
        WHERE `id` = {$id}"
        );
        exit();
    }

    mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit product</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h3>Форма удаления товара</h3>
    <form method="post" class="form" enctype="multipart/form-data">
    <label for="submit">Удалить товар "<?= $product['product_name'] ?>" ?</label>
    <br>
    <input type="submit" name="delete_product" value="Да">
    </form>
</body>
</html>