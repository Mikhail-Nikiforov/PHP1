<?php
  declare(strict_types=1);

  ini_set('error_reporting', (string)E_ALL);
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');

  function show_gallery() {
    $db = mysqli_connect('localhost', 'root', 'root', 'gbphp1');
    
    if (!$db) {
      exit("Не удалось соединиться:" . mysqli_connect_errno());
    }

    $select = mysqli_query($db, "SELECT `id`, `adress`, `name`, `views`FROM `images` ORDER BY `views` DESC");

    $count = 0;
    foreach ($select as $photo) {
          echo "
            <div class='item'>
            <a id='myLink" . $count . "' data-id='{$photo['id']}' href='image.php'>
                <img src='/{$photo['adress']}/{$photo['name']}' width='100'>
            </a>
            <span> Колличество просмотров: {$photo['views']}</span>
            </div>
            <div id='myModal" . $count . "' class='modal'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <span class='close" . $count++ . "'>&times;</span>
                </div>
                <div class='modal-body'>
                <img class='pic-full' src='/{$photo['adress']}/{$photo['name']}'>
                </div>
              </div>
            </div>
            ";
    }
    mysqli_close($db);
  }    
?>