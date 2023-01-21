<?php
$mysql = new mysqli('localhost', 'root', '', 'users');
if ($mysql->connect_errno) exit('Ошибка подключения к БД');
$mysql->set_charset('utf8');
$mysql->close();
?>

