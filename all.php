<?php

ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set('error_reporting', E_ALL);

function logit($text) {
    $path = '/home/ald/screensaver/logs.log';
    $fp = fopen($path, 'a');
    fputs($fp, $text . " \r\n");
    fclose($fp);
}

logit("Файл открыт");
require_once '/home/ald/screensaver/conf.php';
if (!$link = mysqli_connect($mysql_server, $mysql_user, $mysql_pass, 'screen_db')) {
    mail('mm77707@gmail.com', 'А у нас скрины не делаются', mysqli_errno($link) . __LINE__);
    exit();
}
$sql = "SELECT `value` FROM `interations` WHERE  `id` = (SELECT MAX(`id`) FROM `interations`)";
if (!$res = mysqli_query($link, $sql)) {
    mail('mm77707@gmail.com', 'А у нас скрины не делаются', mysqli_errno($link) . __LINE__);
    exit();
}
$count = mysqli_fetch_assoc($res);
$sql = "SELECT * FROM `sites` LIMIT " . $count['value'] . ",20";
if (!$res = mysqli_query($link, $sql)) {
    mail('mm77707@gmail.com', 'А у нас скрины не делаются', mysqli_errno($link) . __LINE__);
    exit();
}
$fetch_count = mysqli_num_rows($res);
$i = 0;
while ($row = mysqli_fetch_assoc($res)) {
    //var_dump($row);
    $host = $row['host'];
    $nameArr = explode('/', $host);
    unset($nameArr[0], $nameArr[1]);
    $name = implode('_', $nameArr) . '_' . time();
    $command = "mkdir /home/ald/screensaver/shots/" . $nameArr[2];
    //exec($command);
    $command = 'xvfb-run --auto-servernum --server-args="-screen ' . $i . ', 1280x1024x24" cutycapt --url=' . $host . ' --out=/home/ald/screensaver/shots/' . $nameArr[2] . '/' . $name . '.jpg --delay=15000 --min-width=1280';
    echo $command . '<br>';
    if (exec($command)) {
        $i++;
        logit($name . " снимок сделан " . date("l dS of F Y h:I:s A"));
    } else {
        mail('mm77707@gmail.com', 'А у нас скрины не делаются', $name . " снимок не сделан " . date("l dS of F Y h:I:s A") . __LINE__);
        logit($name . " снимок не сделан " . date("l dS of F Y h:I:s A") . ' '.$command);
    }
    sleep(2);
}
if ($fetch_count == 20)
    $count = $count['value'] + 20;
else
    $count = 0;
$sql = "INSERT INTO `interations` (`value`,`count`) VALUES (" . $count . "," . $i . ")  ";
if (!mysqli_query($link, $sql)) {
    mail('mm77707@gmail.com', 'А у нас скрины не делаются', mysqli_errno($link) . __LINE__);
    exit();
}
logit($i . " скринов сделано \r\n");
?>
