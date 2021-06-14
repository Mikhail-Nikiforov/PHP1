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

  function show_product($id) {
    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
  
    if (!$db) {
        exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

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
      <br>" . (isset($_SESSION['login']) ? "<button class='add-product_id' name='add-product' id='add-product' value='{$select['id']}'>Добавить в корзину</button>     
      <br>" : "") . "<span> Колличество просмотров: {$select['views']}</span>
    ";

    mysqli_close($db);
  }

  function show_cart()  {

    echo "<h2>Корзина</h2>";

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

    if (empty($select_carts->num_rows)) {
      echo "В корзине нет товаров";
      exit();
    }

    $count = 0;
    $total_sum = 0;
    foreach ($select_carts as $product) {
      echo "
        <div class='item' id='itemId{$product['id_product']}'>
        <a class='product_link' id='myLink'" . $count . " href='../product.php?id={$product['id_product']}'>
            <img src='/{$product['adress']}/{$product['pic_name']}' width='100'>
        </a>
        <div class='item_info'>
          <h3 class='item_title'>{$product['product_name']}</h3>
          <span>{$product['price']} у.е.</span>
          <br>
          <span> Колличество в корзине: <span id='quantity{$product['id_product']}'>{$product['quantity']}</span></span>
          <br>
          <button class='delete-product_id' name='delete-product' id='delete-product' value='{$product['id_product']}'>Удалить из корзины</button>
          <br>
          <span> Цена товаров: <span id='product{$product['id_product']}_sum'>" . $product_sum = $product['quantity'] * $product['price'] . "</span></span>
          <br>
        </div></div>
        ";

      $total_sum += floatval($product_sum);
    }
    echo "Сумма товаров в корзине: <span id='total_sum'>{$total_sum}</span> у.е.           
    <br>
    <form action='order.php'>
      <button type='submit' class='to_order' name='to_order' id='to_order' value='true'>Оформить заказ</button>
    </form>";
  }

  function show_order_list() {
    echo "<h2>Список заказов</h2>
    <h3 id='edit_status'></h3>
    ";

    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');   
    if (!$db) {
      exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    $select_orders = mysqli_query($db, 
    "SELECT *
    FROM orders
    "
    );

    if (gettype($select_orders) == 'object') {

      foreach ($select_orders as $order) {
        $order_status = $order['order_status'];
        $total_sum = 0;
        echo "    
        <div class='order'>
          <form action='' class='form' method='post' >
              <label for='customer_name'>Имя заказчика: </label>
              <input type='text' name='customer_name' id='customer_name{$order['id']}' value='{$order['customer_name']}'>
              <br>
              <label for='phone'>Номер телефона: </label>
              <input type='text' name='phone' maxlength='11' id='phone{$order['id']}' value='{$order['phone']}'>        
              <br>
              <label for='adress'>Адрес: </label>
              <input type='text' name='adress' id='adress{$order['id']}' value='{$order["customer_adress"]}'>        
              <br>
              <select name='order_status' id='order_status{$order['id']}'>
                <option value='ordered' " . ($order_status == 'ordered' ? 'selected' : '') . ">заказано</option>
                <option value='sent' " . ($order_status == 'sent' ? 'selected': '') . ">отправлено</option>
                <option value='canceled' " . ($order_status == 'canceled' ? 'selected': '') . ">отменено</option>
              </select>
              <br>
              <input type='button' class='edit-order_id' name='edit-order_id' data-order_id='{$order['id']}'' value='Отредактировать'>
              
          </form>
          <div class='order_item_list'>";
        $select_order_items = mysqli_query($db, 
        "SELECT order_items.*, products.product_name
        FROM order_items
        LEFT JOIN products
        ON order_items.product_id = products.id
        WHERE order_id = '{$order["id"]}'
        "
        );
        foreach ($select_order_items as $order_item) {
          echo "
            <div class='order_item'>
                <h3 class='item_title'>{$order_item['product_name']}</h3>
                <span>Цена за 1 шт.: <span>{$order_item['price']}</span> у.е.</span>
                <span>Колличество: <span>{$order_item['quantity']}</span></span>
                <span>Сумма за товар: <span>". $order_item['quantity'] * $order_item['price'] ."</span></span>
            </div>
          ";
          $total_sum += $order_item['quantity'] * $order_item['price'];
        }
        echo "
            <h3 class='order_sum'>Сумма заказа:<span>{$total_sum}</span></h3>
          </div>
        </div>";
      }
    } else {

    }

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