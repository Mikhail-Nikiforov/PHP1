<?php
  declare(strict_types=1);

  ini_set('error_reporting', (string)E_ALL);
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');

  function show_catalog($admin_access = 0) {
    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
    
    if (!$db) {
      exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    $select = mysqli_query($db, "SELECT `id`, `adress`, `pic_name`, `views`, `product_name`, `price` FROM `products` ORDER BY `views` DESC");

    $count = 0;
    foreach ($select as $product) {
      echo "
        <div class='item'>
        <a class='product_link' id='myLink'" . $count . " href='../product.php?id={$product['id']}'>
            <img src='/{$product['adress']}/{$product['pic_name']}' width='100'>
        </a>
        <div class='item_info'>
          <h3 class='item_title'>{$product['product_name']}</h3>
          <span>{$product['price']} у.е.</span>
          <br>
          <span> Колличество просмотров: {$product['views']}</span>
          <br>
          <br>
          <br>
        ";
      if ($admin_access) {
        echo "
          <a href='editProduct.php?id={$product['id']}'>Редактировать товар</a>
          <a href='deleteProduct.php?id={$product['id']}'>Удалить товар</a>
        ";
      }
      echo "</div></div>";
    };
    mysqli_close($db);
  }

  function show_product() {
    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
  
    if (!$db) {
        exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    if (empty($_GET['id'])) {
        die("Нет, ID");
    }
    $id = (int)$_GET['id'];

    mysqli_query($db, "UPDATE `products` SET `views` =  `views` + 1 WHERE `id`={$id}");
    $select = mysqli_query($db, "SELECT `id`, `adress`, `pic_name`, `views`, `product_name`, `price`, `description`FROM `products` WHERE id={$id}");
    
    $select = mysqli_fetch_assoc($select);
    echo "
      <img src='/{$select['adress']}/{$select['pic_name']}' alt='Кот' width='400'>
      <h3>{$select['product_name']}</h3>
      <span>Описание: {$select['description']}</span>
      <br>
      <br>
      <span>Цена: {$select['price']} у.е.</span>
      <br>
      <br>
      <span> Колличество просмотров: {$select['views']}</span>
    ";

    mysqli_close($db);
  }
?>