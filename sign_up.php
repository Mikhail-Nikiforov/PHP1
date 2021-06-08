<?php
declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once('function.php');

session_start();

// if (isset($_GET['logout'])) {
//     unset($_SESSION['login']);
//     header('Location: index.php');
//     exit();
// } 

if (isset($_POST['signup_form'])) {
    authorization($_POST['su_login'], $_POST['su_password']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Форма регистрации</h2>
    <form method='post'>
        <input type='text' name='su_login' placeholder='Логин'>
        <input type='password' name='su_password' placeholder='Пароль'>
        <input type='submit' name='signup_form' value="Зарегистрироваться">
    </form>
</body>
</html>