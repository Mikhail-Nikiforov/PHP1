<?php
    echo "Первое задание <br><br>"; 
    $a = -1;
    while ($a++ < 100) {
        if (!($a % 3)) {
        echo "$a ";
        }
    }
    echo "<br><br>Второе задание <br><br>";
    $b = 0;
    do {
        if ($b == 0) {
            echo "$b - ноль ";
        } else if ($b % 2) {
            echo "$b - нечетное число ";
        } else {
            echo "$b - четное число ";
        }
        $b++;  
    } while ($b <= 10);
    echo "<br><br>Третье задание <br><br>";
    $c = [
        'Московская область' => ['Москва','Зеленоград', 'Клин'],
        'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт'],
        'Краснодарский край' => ['Краснодар', 'Новороссийск', 'Сочи', 'Тихорецк'],
    ];
    foreach ($c as $key => $value) {
        echo "<br>$key <br>";
        echo implode(", ",$value) . "<br>";
    }
    echo "<br><br>Четвертое задание <br><br>";
    $d = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'tch',
        'ъ' => '\'\'',
        'ы' => 'y\'',
        'ь' => '\'',
        'э' => 'e\'',
        'ю' => 'yu',
        'я' => 'ya', 
    ];
    function transliter($cyrillic) {
        global $d; 
        $latin = strtr($cyrillic, $d);
        return $latin;
    }
    var_dump(transliter('дом'));
    echo "<br><br>Пятое задание <br><br>";
    function underscore($txt) {
        $txt = strtr($txt, " ", "-");
        return $txt;
    }
    var_dump(underscore('Один Два Три'));
    echo "<br><br>Шестое задание <br><br>";
    $menu = [
        'main_page' => [
            'label' => 'Главная страница',
            'url' => 'index.php',
        ],
        'info' => [
            'label' => 'Информация',
            'url' => 'info.html',
            'submenu' => [
                'contacts' => [
                    'label' => 'Контакты',
                    'url' => 'contacts.html',
                ],
                'about_us' => [
                    'label' => 'О нас',
                    'url' => 'about_us.html',
                ],
            ]
        ],
        'documentation' => [
            'label' => 'Документация',
            'url' => 'documentation.php',
        ],
    ];
    function menu_show ($menu) {
        printf("<ul>");
        foreach ($menu as $value) {
            printf("<li>{$value['label']}</li>");
            if ($value['submenu']) {
                menu_show($value['submenu']);
            }
        };
        printf("</ul>");
    };
    menu_show($menu);
    echo "<br><br>Седьмое задание <br><br>";
    for ($i=0; $i < 10; printf($i++)) { 
        # code...
    }
    echo "<br><br>Восьмое задание <br><br>";
    $c = [
        'Московская область' => ['Москва','Зеленоград', 'Клин'],
        'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт'],
        'Краснодарский край' => ['Краснодар', 'Новороссийск', 'Сочи', 'Тихорецк'],
    ];
    foreach ($c as $key => $value) {
        echo "<br>$key <br>";
        foreach ($value as $value1) {
            if (strpos($value1, 'К') === 0) {
                echo "$value1 ";
            }
        }
    }
?>