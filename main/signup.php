<?php
        session_start();
        require_once 'connect.php';

    $name = $_POST['name'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];



$password = md5($password);

mysqli_query($connect,"INSERT INTO `users` (`id`, `name`, `login`, `email`, `password`) VALUES ( 1, '$name', '$login', '$email', '$password')");

 header('Location: ../index.php');