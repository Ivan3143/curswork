<?php 
 session_start();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
	<form>
		<label>Добро пожаловать</label>
		<h2><?= $_SESSION['user']['name'] ?></h2>
		<label>Вы успешно прошли авторизацию, поэтому можете перейти в наш телеграмм канал по ссылке:</label>
	</form>
</body>
</html>>
