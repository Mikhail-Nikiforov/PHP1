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

        ";
      if ($admin_access) {
        echo "
          <a href='editProduct.php?id={$product['id']}'>Редактировать товар</a>
          <a href='deleteProduct.php?id={$product['id']}'>Удалить товар</a>
        ";
      }
      echo "</div></div>";
    };
    if ($admin_access == 1) {
      echo "
      <a href='createProduct.php' class='link'>Добавить товар</a>
      ";
    }
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
      <form method='post' enctype='multipart/form-data'>
      <button class='add-product_id' name='add-product' value='{$select['id']}'>Добавить в корзину</button>
      </form>      
      <br>
      <span> Колличество просмотров: {$select['views']}</span>
    ";

    mysqli_close($db);
  }

  function show_cart()  {
    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
    
    if (!$db) {
      exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    $select_users = mysqli_fetch_assoc(mysqli_query($db, 
    "SELECT `id`, `role`, `login` 
    FROM `users` 
    WHERE `login` = '{$_SESSION["login"]}'"));

    $select_carts = mysqli_query($db, 
    "SELECT carts.*, products.* 
    FROM carts
    LEFT JOIN products
    ON carts.id_product = products.id
    WHERE carts.id_customer = '{$select_users["id"]}'"); 

    if (empty($select_carts)) {
      echo "В корзине нет товаров";
      exit();
    }

    $count = 0;
    $total_sum = 0;
    foreach ($select_carts as $product) {
      echo "
        <div class='item'>
        <a class='product_link' id='myLink'" . $count . " href='../product.php?id={$product['id_product']}'>
            <img src='/{$product['adress']}/{$product['pic_name']}' width='100'>
        </a>
        <div class='item_info'>
          <h3 class='item_title'>{$product['product_name']}</h3>
          <span>{$product['price']} у.е.</span>
          <br>
          <span> Колличество в корзине: {$product['quantity']}</span>
          <br>
          <span> Цена товаров: " . $product_sum = $product['quantity'] * $product['price'] . "</span>
          <br>
        </div></div>
        ";

      $total_sum += floatval($product_sum);
    }
    echo "Сумма товаров в корзине: {$total_sum} у.е.";
  }

  function authentication($login, $password) {
    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
    if (!$db) {
      exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    $login = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($login)));
    $password = $password ?? "";
    $select = mysqli_query($db, "SELECT `password_hash`, `role` FROM `users` WHERE `login`='{$login}'");

    if ($user = mysqli_fetch_assoc($select)) {
        if (password_verify($password, $user['password_hash'])) {
            echo "Пароль верный!";
            $_SESSION['login'] = $login;
            $_SESSION['isAuth'] = 1;
            $_SESSION['$admin_access'] = 0;
            if ($user['role'] == 'administrator') {
              $_SESSION['$admin_access'] = 1;
            }
        } else {
            echo "Пароль неверный!";
        }
    } else {
        echo "Пользователь с таким логином незарегестрирован!";
        unset($_POST['login_form']);
        unset($_POST['login']);
    }
  }
  function authorization($login, $password) {
    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
    if (!$db) {
      exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    $login = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($login)));
    $select = mysqli_fetch_assoc(mysqli_query($db, 
      "SELECT `id`, `password_hash`, `role` 
      FROM `users` 
      WHERE `login`='{$login}'"
    ));
    if (isset($select['id'])) {
      echo "Пользователь с таким логином уже зарегестрирован";
      unset($_POST['su_login']);
    } else {
      $password = $password ?? "";
      $hash = password_hash($password, PASSWORD_BCRYPT);
      mysqli_query($db, 
        "INSERT INTO users (`login`, `password_hash`)
        VALUES ('{$login}', '{$hash}')"
      );
      $_SESSION['login'] = $login;
      $_SESSION['isAuth'] = 1;
      $_SESSION['$admin_access'] = 0;

      header('Location: '.'index.php?signup=1');             
      exit();
    }
    
  }
?>