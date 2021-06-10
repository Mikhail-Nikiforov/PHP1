<?php
    declare(strict_types=1);

    ini_set('error_reporting', (string)E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');

    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
    
    if (!$db) {
      exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    if (isset($_POST['create_product'])) {
       
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            if (preg_match("/^.*(\.jpg|\.jpeg|\.png)$/", $_FILES['image']['name'])) {
                if ($_FILES['image']['size'] < 300000) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], '../pub/img/'. $_FILES['image']['name'])) {
                        $adress = 'pub/img';
                        $pic_name = $_FILES['image']['name'];
                        $product_name = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($_POST['product_name'])));
                        $price = (int)$_POST['price'];
                        $description = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($_POST['description'])));

                        $select = mysqli_query($db, 
                        "INSERT INTO `products` (`adress`, `pic_name`, `product_name`, `price`,`description`) VALUES ('{$adress}', '{$pic_name}', '{$product_name}', '{$price}', '{$description}')"
                        );
            
                        header('Location: '.'admin.php?added=1');
                        
                        exit();
                    } else {
                        echo "Ошибка при сохранении картинки";
                    }
                } else {
                    echo "Картинка весит больше 300 кБ!";
                }
                
            } else {
                echo "Картинка не того формата!";
            }
        } else {
            echo "Картинки нет";
        }


    }

    

    mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <h3>Форма добавления товара</h3>
    <form method="post" class="form" enctype="multipart/form-data">
        <label for="product_name">Название товара: </label>
        <input type="text" name="product_name">
        <br>
        <label for="price">Цена товара: </label>
        <input type="text" name="price">        
        <br>
        <label for="description">Описание товара: </label>
        <input type="text" name="description">        
        <br>
        <label for="image">Картинка товара: </label>
        <input type="file" name="image">        
        <br>
        <input type="submit" name="create_product">
    </form>
</body>

</html>