<?php 

/**
 * инициалоизация подключения к базе данных
 */

$dblocation = "127.0.0.1";
$dbname = "myshop";
$dbuser = "root";
$dbpasswd = "";

// соединяемся с БД
$mysqli = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname);

if ($mysqli->connect_error) {
    die('Ошибка подключения (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

// устанавливаем кодировку по умолчанию для текущего соединения
$mysqli->set_charset("utf8");

