<?php
declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once('../function.php');

session_start();



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

        if (isset($_SESSION['login']) && ($_SESSION['$admin_access'] == 1)) {
            echo "<h2>Каталог товаров</h2>";
            if (isset($_GET['added'])) {
                echo "Товар добавлен";
            } elseif (isset($_GET['edited'])) {
                echo "Товар отредактирован";
            } elseif (isset($_GET['deleted'])) {
                echo "Товар удален";
            }
            show_catalog($_SESSION['$admin_access']);
            show_order_list();
        } else {
            echo "Страница только для администраторов!";
            die();
        }
    ?>
    <script type='text/javascript'>
        let buttons = document.getElementsByClassName('edit-order_id');

        Array.from(buttons).forEach(element => {
            element.addEventListener('click', () => {

                let orderId = element.dataset.order_id;
                let customerName = document.getElementById(`customer_name${orderId}`);
                let phone = document.getElementById(`phone${orderId}`);
                let adress = document.getElementById(`adress${orderId}`);
                let orderStatus = document.getElementById(`order_status${orderId}`);

                async function deleteProduct() {
                const res = await fetch('../api.php', {
                    method: 'POST',
                    headers: new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify({
                        productId: null,
                        orderId: orderId,
                        customerName: customerName.value,
                        phone: phone.value,
                        adress: adress.value,
                        orderStatus: orderStatus.value,
                        login: '<?= $_SESSION['login'] ?>', 
                        action: 'edit_order',
                    }),  
                })
                .then(res => res.json())
                .then(res =>  {
                    document.getElementById('edit_status').innerHTML = res[0];
                })
                .catch(err => console.error(err));
                };

                deleteProduct();
            });
        });
        
    </script>
</body>
</html>