<?php
    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
    
    if (!$db) {
    exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    mysqli_query($db, "UPDATE `images` SET `views` =  `views` + 1 WHERE `id`={$_COOKIE['pic_id']}");
    $select = mysqli_query($db, "SELECT `id`, `adress`, `name`, `views`FROM `images` WHERE id={$_COOKIE['pic_id']}");
    
    $select = mysqli_fetch_assoc($select);
    echo "
    <img src='/{$select['adress']}/{$select['name']}' alt='' height='80%'>
    <h3>Количество просмотров: {$select['views']}</h3>
    ";

    mysqli_close($db);
?>