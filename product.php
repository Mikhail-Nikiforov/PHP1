<?php
    declare(strict_types=1);

    ini_set('error_reporting', (string)E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    include_once('function.php');

    session_start();

    if (empty($_GET['id'])) {
        die("Нет, ID");
    }
    $product_id = (int)$_GET['id'];

    if (empty($_SESSION['login'])) {
        echo 'Добавлять товары в корзину могут только авторизованные пользователи <br>';
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
        show_product($product_id);
        if (isset($_SESSION['login'])) {
            echo "
            <script type='text/javascript'>
            let button = document.getElementById('add-product');
            button.addEventListener('click', () => {
            async function addProduct() {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify({
                        productId: button.value,
                        login: '{$_SESSION['login']}',
                        action: 'add',
                    }),  
                })
                .then(res => res.text())
                .catch(err => console.error(err));
            };
            addProduct();
            });       
            </script>
            ";
        }
    ?>

</body>
</html>