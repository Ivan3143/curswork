<?php

    session_start();
    require_once 'connect.php';

    $login = $_POST['login'];
    $password = md5($_POST['password'].'qwerty');

    $check_user = mysqli_query($connect, "SELECT * FROM `registeredusers` WHERE `login` = '$login' AND `password`= '$password'");
   if(mysqli_num_rows($check_user)>0){
    $user = mysqli_fetch_assoc($check_user);
    $_SESSION['user'] = [
        "id" => $user['id'],
        "name" => $user['name']
    ];
    header('Location: ../hello.php');
    } 
    else {
    $_SESSION['message'] = 'Ошибка!';
        header('Location: ../authorization.php');
   }
?>
  

    