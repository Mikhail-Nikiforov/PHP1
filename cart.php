<?php
    declare(strict_types=1);

    ini_set('error_reporting', (string)E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');

    include_once('function.php');

    session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cart</title>
</head>
<body>
    <div class="cart">
        <?php
            if (isset($_SESSION['login'])) {
                show_cart();
            } else {
                echo 'Авторизуйтесь для просмотра корзины';
            }
            
        ?>
    </div>
    <script type='text/javascript'>
        let buttons = document.getElementsByClassName('delete-product_id');
        let itemCount = document.querySelectorAll('.item').length;
        Array.from(buttons).forEach(element => {
            element.addEventListener('click', () => {
                async function deleteProduct() {
                const res = await fetch('api.php', {
                    method: 'POST',
                    headers: new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify({
                        productId: element.value,
                        login: '<?= $_SESSION['login'] ?>', 
                        action: 'delete',
                    }),  
                })
                .then(res => res.json())
                .then(res =>  {
                    document.getElementById(`quantity${element.value}`).textContent  = res['quantity'];
                    document.getElementById(`product${element.value}_sum`).textContent = res['product_sum'];
                    let prevTotalSum = document.getElementById(`total_sum`).textContent;
                    let totalSum = prevTotalSum - res['price'];
                    document.getElementById(`total_sum`).textContent = totalSum.toFixed(2);

                    if (res['quantity'] == 0) {
                        document.getElementById(`itemId${element.value}`).classList.add('hide');
                        --itemCount;
                    }
                    
                    if (itemCount === 0) {
                        document.getElementById('to_order').style.display = 'none';
                    }
                    
                })
                .catch(err => console.error(err));
                };

                deleteProduct();
            });
        });
        
    </script>
</body>
</html>
