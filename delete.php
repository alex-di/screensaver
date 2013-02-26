<?php

if (isset($_POST['submit']) && $_POST['submit'] != '') {
    require_once './conf.php';
    if (!$link = mysqli_connect($mysql_server, $mysql_user, $mysql_pass, 'screen_db')) {
        
        header("Location: /?status=3");
    } else {
        /* $sql = "SELECT * FROM `sites`";
          if(!$res = mysqli_query($link,$sql)){
          exit("Mysql Query Error. Please, contact admin ".mysqli_error($link));
          }
          else var_dump(mysqli_fetch_assoc($res)); */
        if (isset($_POST['id']) && $_POST['id'] != '') {
            $sql = "SELECT `host` FROM `sites` WHERE `id` = '" . $_POST['id'] . "'";
            
            if (!$res = mysqli_query($link, $sql)) {
        echo mysqli_error($link).__LINE__;
                exit();
               //header("Location: /?status=3");
            }
            if (is_array($row = mysqli_fetch_assoc($res))) {
                /*
                 * Вот тут что-то не так
                 */
                $host = $row['host'];
                session_start();
                if ($_SESSION['hash'] != md5($host)) {
                    if (isset($_SESSION['hash']))
                        unset($_SESSION['hash']);
                    $_SESSION['hash'] = md5($host);
                    echo <<<EOD
                    <!DOCTYPE html>
                        <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html;charset=utf-8" /></head>
                            <body><h3>Вы уверены что хотите удалить из базы данных $row[host]?</h3>
                            <a href="/">Назад</a><form action="" method="POST"><input type="hidden" value="$_POST[id]" name="id"/><input type="submit" name="submit" value="Да, я уверен(а). Удаляем всё к чертям!"/></form></body>
                        </html>
EOD;
                } else {
                    $sql = "DELETE FROM `sites` WHERE `id` = " . $_POST['id'];
                    if (!mysqli_query($link, $sql)) {
                        header("Location: /?status=3");
                    }
                    $nameArr = explode('/', $host);
                    $command = "rm -R /home/ald/screensaver/shots/" . $nameArr[2] . "/";
                    exec($command);
                    echo $command;
                    unset($_SESSION['hash']);
                    header("Location: /?status=1");
                }
            }
            else
                header("Location: /?status=3");
        }
        else
            header("Location: /?status=4");
    }
}
else
    header("Location: /")
?>
