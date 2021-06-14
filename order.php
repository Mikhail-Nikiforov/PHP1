<?php
    declare(strict_types=1);

    ini_set('error_reporting', (string)E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');

    include_once('function.php');+

    session_start();

    if (empty($_SESSION['login'])) {
        echo "Авторизуйтесь для оформления заказа!";
        die();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Order</title>
</head>
<body>
    <h1>Форма заказа</h1>
    <h3 id="order_status"></h3>
    <form action="" class="form" method="post">
        <label for="customer_name">Ваше имя: </label>
        <input type="text" name="customer_name" id='customer_name'>
        <br>
        <label for="phone">Номер телефона: </label>
        <input type="text" name="phone" maxlength="11" id="phone">        
        <br>
        <label for="adress">Адрес: </label>
        <input type="text" name="adress" id="adress">        
        <br>
        <input type="button" class='create-order' name="create-order" id="create-order" value="Заказать">
    </form>
    <script type='text/javascript'>
            let button = document.getElementById('create-order');

            button.addEventListener('click', () => {

                let customerName = document.getElementById(`customer_name`);
                let phone = document.getElementById(`phone`);
                let adress = document.getElementById(`adress`);

                async function createOrder() {
                    const response = await fetch('api.php', {
                    method: 'POST',
                    headers: new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify({
                        productId: null,
                        login: '<?= $_SESSION['login'] ?>', 
                        action: 'create_order',
                        customerName: customerName.value,
                        phone: phone.value,
                        adress: adress.value,

                    }),  
                })
                .then(res => res.json())
                .then(res => {
                    document.getElementById('order_status').innerHTML = res[0] ;
                    document.location.href = "index.php?success_order=1";
                })
                .catch(err => console.error(err));
            };
            createOrder();
            });       
    </script>
</body>
</html>