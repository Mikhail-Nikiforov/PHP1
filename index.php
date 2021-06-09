<?php
declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once('function.php');
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire'); // works
//session_cache_limiter('public'); // works too
session_start();

if (isset($_GET['logout'])) {
    unset($_SESSION['login']);
    header('Location: index.php');
    exit();
}

if (isset($_GET['signup'])) {
    echo "Вы успешно зарегистрировались";
} 

if (isset($_POST['login_form']) && isset($_POST['login'])) {
    authentication($_POST['login'], $_POST['password']);
}
if (empty($_SESSION['login'])){
    echo "
    <h2>Форма авторизации</h2>
    <form method='post'>
        <input type='text' name='login' placeholder='Логин'>
        <input type='password' name='password' placeholder='Пароль'>
        <input type='submit' name='login_form'>
    </form>
    <a href='sign_up.php'>Регистрация нового пользователя</a>
    ";
} else {
    echo "<br><br><a href='?logout'>Logout</a>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Catalog</title>
</head>
<body>
    <a href="sign_up.php"></a>
    <h2>Каталог</h2>
    <?php
        show_catalog();
        
    ?>
    <a href="cart.php">Корзина</a>
    <a href="admin/admin.php">Админка</a>
</body>
</html>

