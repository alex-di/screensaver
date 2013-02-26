<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set('error_reporting', E_ALL);
if (isset($_POST['submit']) && $_POST['submit'] != '') {
    if (isset($_POST['host1']) && $_POST['host1'] != '') {
        $host = (strpos(trim($_POST['host1']), 'http://') === FALSE) ? 'http://' . trim($_POST['host1']) : trim($_POST['host1']);
        $nameArr = explode('/', $host);
        $name = $nameArr[2] . '_' . time();
        $command = "mkdir /home/ald/screensaver/shots/" . $nameArr[2];
        exec($command);
        $command = 'xvfb-run --server-args="-screen 0, 1280x1024x24" cutycapt --url=' . $host . ' --out=/home/ald/screensaver/shots/' . $nameArr[2] . '/' . $name . '.jpg --delay=15000 --min-width=1024';
        $out = array();
        //echo $command;
        exec($command, $out);
        //var_dump($out);
    }
    else
        header('Location: /?status=2');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <a href="/">Назад</a><br/>
        <img src="/shots/<? echo $nameArr[2].'/'.$name; ?>.jpg"/>
    </body>
</html>