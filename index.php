<!DOCTYPE html >
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>Сервис "Автоскрин"</title>
        <style>
            body {font-size:0.9em;font-family: georgia,verdana,Helvetica}
            label {width:200px;display:inline-block;}
            input[type="text"] {width:600px;display:inline-block;}
            input[type="submit"] {width:100px;margin-left:10px;}
            .status_block {color:red;font-weight:bold;font-size:110%;margin:10px 0;}
            
        </style>
    </head>
    <body>
        <h1>Сервис "Автоскрин"</h1>
        <div class="status_block">
            <?php
            if (isset($_GET['status'])) {
                switch ($_GET['status']) {
                    case 1:
                        echo 'Сайт успешно добавлен';
                        break;
                    case 2:
                        echo 'Не валидный адрес';
                        break;
                    case 3:
                        echo 'Ошибка базы данных. Обратитесь к администратору';
                        break;
                    case 4:
                        echo 'Неверный запрос';
                        break;
                    
                }
            }
            ?>
        </div>
        <div><a href="/screen_saver_arch/">Перейти на архив</a></div>
        <div><form action="/add.php" method="POST"><label for="host">Добавить сайт </label><input type="text" id="host" name="host"/><input value="Добавить" type="submit" name="submit"/></form></div>
        <div><form action="/takeashot.php" method="POST"><label for="host1">Сделать снимок сайта</label><input type="text" id="host1" name="host1"/><input value="Снять скрин" type="submit" name="submit"/></form></div>
                <?php
                require_once '/home/ald/screensaver/conf.php';
                if (!$link = mysqli_connect($mysql_server, $mysql_user, $mysql_pass, 'screen_db')) {
                    mail('mm77707@gmail.com', 'А у нас скрины не выводятся', mysqli_errno($link) . __LINE__);
                    exit();
                }
                $sql = "SELECT * FROM `sites`";
                if (!$res = mysqli_query($link, $sql)) {
                    mail('mm77707@gmail.com', 'А у нас скрины не выводятся', mysqli_errno($link) . __LINE__);
                    exit();
                }
                $resArr = Array();
                while ($row = mysqli_fetch_assoc($res)) {

                    $nameArr = explode('/', $row['host']);
                    if (in_array($nameArr[2], $resArr))
                        continue;
                    echo '<div style="width:500px;overflow:hidden;line-height:24px"><form action="/delete.php" style="float:left;margin-right:20px" method="POST"><input type="hidden" name="id" value="'.$row['id'].'"/><input value="Удалить" name="submit" type="submit"/></form><a href="/shots/' . $nameArr[2] . '">' . $nameArr[2] . '</a></div>';
                    $resArr[] = $nameArr[2];
                }
                ?>
    </body>
</html> 