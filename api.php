<?php

declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

$data = json_decode(file_get_contents('php://input'));

$db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
if (!$db) {
    exit("Не удалось соединиться:" . mysqli_connect_errno());
}

$select_users = mysqli_fetch_assoc(mysqli_query($db, 
"SELECT `id`, `role`, `login` 
FROM `users`
WHERE `login` = '{$data->login}'"
));

$select_carts = mysqli_fetch_assoc(mysqli_query($db, 
"SELECT `carts`.`id`, `id_customer`, `id_product`, `quantity`, `price` 
FROM `carts`
LEFT JOIN `products`
ON `carts`.`id_product` = `products`.`id`
WHERE `id_customer` = '{$select_users["id"]}' 
AND `carts`.`id_product` = '{$data->productId}'"
));

switch ($data->action) {
    case 'add':

        if (isset($select_carts['id'])) {
            mysqli_query($db, 
            "UPDATE `carts` 
            SET `quantity` =  `quantity` + 1 
            WHERE `id_customer` = '{$select_users["id"]}' 
            AND `id_product` = '{$data->productId}'");
        } else {
            mysqli_query($db, 
            "INSERT INTO `carts` (`id_customer`, `id_product`, `quantity`) 
            VALUES ('{$select_users["id"]}', '{$data->productId}', '1')");
        }
        
        $response = [
            'quantity' => $select_carts['quantity'],
            'price' => $select_carts['price'], 
        ];

        break;
    
    case 'delete':
        if ($select_carts['quantity'] == 1) {
            mysqli_query($db, 
            "DELETE 
            FROM `carts`
            WHERE `id_customer` = '{$select_users["id"]}' 
            AND `id_product` = '{$data->productId}'");
            $new_quantity = 0;
            $new_sum = 0;
        } else if ($select_carts['quantity'] > 1) {
            mysqli_query($db, 
            "UPDATE `carts` 
            SET `quantity` =  `quantity` - 1 
            WHERE `id_customer` = '{$select_users["id"]}' 
            AND `id_product` = '{$data->productId}'");
            $new_quantity = $select_carts['quantity'] - 1;
            $new_sum = $new_quantity * $select_carts['price'];
        }

        $response = [
            'quantity' => $new_quantity,
            'price' => $select_carts['price'],
            'product_sum' => $new_sum, 
        ];

        break;
    
    case 'edit_order':

        $validation = 'Заказ отредактирован';
        
        if (!empty($data->customerName)) {
            if (preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $data->phone)) {
                if (!empty($data->adress)) {
                    $select_order = mysqli_query($db, 
                    "UPDATE `orders`
                    SET `customer_name` = '{$data->customerName}', `phone`= '{$data->phone}', `customer_adress` = '{$data->adress}', `order_status` = '{$data->orderStatus}' 
                    WHERE `id` = '{$data->orderId}'"
                    );

                } else {
                    $validation = "<h3>Не указан адрес заказа!</h3>";
                }
            } else {
                $validation = "<h3>Неверно указан номер телефона!</h3>";
            }
        } else {
            $validation = "<h3>Не указано имя заказчика!</h3>";        
        }



        $response = [
            $status = $validation,
            $name = $data->adress,
        ];
        
        break;
    
    case 'create_order':

        $validation = 'Заказ отправлен на обработку';

        if (!empty($data->customerName)) {
            if (preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $data->phone)) {
                if (!empty($data->adress)) {

                    $customer_name = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($data->customerName)));
                    $phone = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($data->phone)));
                    $customer_adress = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($data->adress)));

                    mysqli_query($db,
                    "INSERT INTO `orders` (`customer_name`, `phone`, `customer_adress`)
                    VALUES ('{$customer_name}', '{$phone}', '{$customer_adress}')" 
                    );
                    $order_id = mysqli_insert_id($db);

                    $select_users = mysqli_fetch_assoc(mysqli_query($db, 
                    "SELECT `id`, `role`, `login` 
                    FROM `users` 
                    WHERE `login` = '{$data->login}'"));
                    $select_carts = mysqli_query($db, 
                    "SELECT carts.*, products.* 
                    FROM carts
                    LEFT JOIN products
                    ON carts.id_product = products.id
                    WHERE carts.id_customer = '{$select_users["id"]}'"
                    );

                    mysqli_query($db, 
                    "INSERT INTO order_items (order_id, product_id, price, quantity) 
                    SELECT '{$order_id}', products.id, products.price, carts.quantity
                    FROM carts
                    LEFT JOIN products
                    ON carts.id_product = products.id
                    WHERE carts.id_customer = '{$select_users["id"]}'"
                    );
                    mysqli_query($db, 
                    "DELETE FROM carts
                    WHERE id_customer = '{$select_users["id"]}'
                    "
                    );

                } else {
                    $validation = "Не указан адрес заказа!";
                }
            } else {
                $validation = "Неверно указан номер телефона!";
            }
        } else {
            $validation = "Не указано имя заказчика!";        
        }


        $response = [
            $status = $validation,
            $name = $data,
        ];


        break;
    
    default:
        
        break;
}



mysqli_close($db);
header("Content-type: application/json");
echo json_encode($response);