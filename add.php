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
        if (isset($_POST['host']) && $_POST['host'] != '') {
            $host = (strpos(trim($_POST['host']), 'http://') === FALSE) ? 'http://' . trim($_POST['host']) : trim($_POST['host']);
            $sql = "SELECT `id` FROM `sites` WHERE `host` = '" . $host . "'";
            if (!$res = mysqli_query($link, $sql)) {
                header("Location: /?status=3");
            }
            if (mysqli_fetch_assoc($res) === NULL) {
                $sql = "INSERT INTO `sites` (`host`) VALUES ('" . $host . "')";
                if (!mysqli_query($link, $sql)) {
                    header("Location: /?status=3");
                }
                $nameArr = explode('/', $host);
                $command = "mkdir /home/ald/screensaver/shots/".$nameArr[2]."; chmod 777 -R /home/ald/screensaver/shots/".$nameArr[2];
                exec($command);
                header("Location: /?status=1");
            }
            else
                header("Location: /?status=3");
        }
        else
            header("Location: /?status=2");
    }
}
else
    header("Location: /")
?>
