<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>

    </title>
</head>

<body>
    <?php
    echo "Первый пункт ДЗ.<br><br>";
    $a = rand(-10, 10);
    $b = rand(-10, 10);
    var_dump($a);
    var_dump($b);
    if ($a >= 0 && $b >= 0) {
        echo "Переменные положительные. Разность: " . ($a - $b);
    } elseif ($a < 0 && $b < 0) {
        echo "Переменные отрицательные. Произведение: " . ($a * $b);
    } elseif ($a * $b < 0) {
        echo "Переменные разных знаков. Сумма: " . ($a + $b);
    }
    echo "<br><br>Второй пункт ДЗ.<br><br>";
    $a = rand(0, 15);
    switch ($a) {
        case '0':
            echo $a++ . "<br>";
        case '1':
            echo $a++ . "<br>";
        case '2':
            echo $a++ . "<br>";
        case '3':
            echo $a++ . "<br>";
        case '4':
            echo $a++ . "<br>";
        case '5':
            echo $a++ . "<br>";
        case '6':
            echo $a++ . "<br>";
        case '7':
            echo $a++ . "<br>";
        case '8':
            echo $a++ . "<br>";
        case '9':
            echo $a++ . "<br>";
        case '10':
            echo $a++ . "<br>";
        case '11':
            echo $a++ . "<br>";
        case '12':
            echo $a++ . "<br>";
        case '13':
            echo $a++ . "<br>";
        case '14':
            echo $a++ . "<br>";
        case '15':
            echo $a++ . "<br>";
    }
    echo "<br><br>Третий пункт ДЗ.<br><br>";
    function sum($a, $b)
    {
        return $a + $b;
    }
    function diff($a, $b)
    {
        return $a - $b;
    }
    function mult($a, $b)
    {
        return $a * $b;
    }
    function div($a, $b)
    {
        return $a / $b;
    }
    echo "Сумма 4 и 5: " . (sum(4, 5)) . "<br>" . "Разность 4 и 5: " . (diff(4, 5)) . "<br>" . "Произведение 4 и 5: " . (mult(4, 5)) . "<br>" . "Частное 4 и 5: " . (div(4, 5)) . "<br>";
    echo "<br><br>Четвертый пункт ДЗ.<br><br>";
    function mathOperation($arg1, $arg2, $operation)
    {
        switch ($operation) {
            case 'sum':
                echo "Сумма $arg1 и $arg2: " . ($operation($arg1, $arg2));
                break;
            case 'diff':
                echo "Разность $arg1 и $arg2: " . ($operation($arg1, $arg2));
                break;
            case 'mult':
                echo "Произведение $arg1 и $arg2: " . ($operation($arg1, $arg2));
                break;
            case 'div':
                echo "Частное $arg1 и $arg2: " . ($operation($arg1, $arg2));
                break;
        }
    };
    mathOperation(6, 2, 'diff');
    echo "<br><br>Пятый пункт ДЗ.<br><br>";
    echo date("Y");
    echo "<br><br>Шестой пункт ДЗ.<br><br>";
    function power($val, $pow)
    {
        if ($pow === 0) {
            return "1";
        } elseif ($pow > 0) {
            return $val *= $pow == 1 ? 1 : power($val, $pow - 1);
        } elseif ($pow < 0) {
            $pow = -$pow;
            return 1 / ($val *= $pow < 0 ? 1 : power($val, $pow - 1));
        } else {
            return "Что-то пошло не так...";
        }
    }
    echo power(14, -3);
    echo "<br><br>Седьмой пункт ДЗ.<br><br>";
    function discribe_time($value, $words)
    {
        $num = $value % 100;
        if ($num > 19) {
            $num = $num % 10;
        }
        $result = $value . ' ';
        switch ($num) {
            case 1:
                $result .= $words[0];
                break;
            case 4:
                $result .= $words[1];
                break;
            default:
                $result .= $words[2];
                break;
        }

        return $result;
    }
    echo discribe_time(1, array('час', 'часа', 'часов')) . " " . discribe_time(1, array('минута', 'минуты', 'минут'));
    ?>
</body>


</html>