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

    $select = mysqli_query($db, "SELECT `id`, `adress`, `name`, `views`FROM `images`");

    // $photos_arr = array_filter(scandir("./pub/img"), function($file) {
    //     return !preg_match("/^\..*$/", $file) && !is_dir($file);
    // });
    $count = 0;
    foreach ($select as $photo) {
          echo "
            <button id='myBtn" . $count . "'>
                <img src='/{$photo['adress']}' width='100'>
            </button>
            <div id='myModal" . $count . "' class='modal'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <span class='close" . $count++ . "'>&times;</span>
                </div>
                <div class='modal-body'>
                <img class='pic-full' src='/{$photo['adress']}'>
                </div>
              </div>
            </div>
            ";
    }
    mysqli_close($db);
  }    
?>