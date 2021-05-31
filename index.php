<?php
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    if (preg_match("/^.*(\.jpg|\.jpeg|\.png)$/", $_FILES['image']['name'])) {
        if ($_FILES['image']['size'] < 300000) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], 'pub/img/'. time() . "_" . $_FILES['image']['name'])) {
                header('Location: '.$_SERVER['PHP_SELF']);
                echo "Картинка загружена";
                exit();
            } else {
                echo "Ошибка при сохранении картинки";
            }
        } else {
            echo "Картинка весит больше 300 кБ!";
        }
        
    } else {
        echo "Картинка не того формата!";
    }
} else {
    echo "Картинки нет";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="image">
    <input type="submit">
</form>
<?php
function show_gallery() {
    $photos_arr = array_filter(scandir("./pub/img"), function($file) {
        return !preg_match("/^\..*$/", $file) && !is_dir($file);
    });
    $count = 0;
    foreach ($photos_arr as $value) {
        echo "
        <button id='myBtn" . $count . "'>
            <img src='pub/img/$value' width='100'>
        </button>
        <div id='myModal" . $count . "' class='modal'>
          <div class='modal-content'>
            <div class='modal-header'>
              <span class='close" . $count++ . "'>&times;</span>
            </div>
            <div class='modal-body'>
            <img class='pic-full' src='pub/img/$value'>
            </div>
          </div>
        </div>
        ";
    }
}
show_gallery();
?>
<script>
    let modal = document.getElementsByClassName('modal');
    console.log(modal, modal.length, modal[0]);
    for (let i = 0; i < modal.length; i++) {
        let element = modal[i];
        let link = document.getElementById(`myBtn${i}`);
        let span =document.getElementsByClassName(`close${i}`)[0];
        link.onclick = function() {
            element.style.display = "block";
        }
        span.onclick = function() {
            element.style.display = "none";
        }
    }
</script>
</body>
</html>

