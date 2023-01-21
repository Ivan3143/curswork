<?php

    session_start();
    require_once 'connect.php';

    $name = $_POST['name'];
    $login = $_POST['login'];
    $car_number = $_POST['car_number'];
    $password = md5($_POST['password'].'qwerty');

        mysqli_query($connect, "INSERT INTO `registeredusers` (`id`, `name`, `login`, `car_number`, `password`) VALUES (NULL, '$name', '$login', '$car_number', '$password')");
        $_SESSION['message'] = 'Регистрация прошла успешно!';
        header('Location: ../authorization.php');

?>
