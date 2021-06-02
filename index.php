<?php
declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once('function.php');

$db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
    
if (!$db) {
  exit("Не удалось соединиться:" . mysqli_connect_errno());
}

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    if (preg_match("/^.*(\.jpg|\.jpeg|\.png)$/", $_FILES['image']['name'])) {
        if ($_FILES['image']['size'] < 300000) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], 'pub/img/'. $_FILES['image']['name'])) {
                $select = mysqli_query($db, 
                "INSERT INTO `images` (`adress`, `name`) 
                VALUES ('pub/img', '{$_FILES['image']['name']}')"
                );

                header('Location: '.$_SERVER['PHP_SELF']);
                echo "Картинка загружена";
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

mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="image">
    <input type="submit">
</form>
<?php

show_gallery();
?>
<script>
    let modal = document.getElementsByClassName('modal');
    console.log(modal, modal.length, modal[0]);
    for (let i = 0; i < modal.length; i++) {
        let element = modal[i];
        let link = document.getElementById(`myLink${i}`);
        let span =document.getElementsByClassName(`close${i}`)[0];
        link.onclick = function() {
            // element.style.display = "block";
            document.cookie = `pic_id=${link.dataset.id}`;
        }
        span.onclick = function() {
            element.style.display = "none";
        }
    }
</script>
</body>
</html>

