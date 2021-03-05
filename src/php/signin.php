<?php

    session_start();
    require_once 'connect.php';

    $login = $_POST['login'];
    $password = $_POST['password'];

    $error_fields = [];

    if( $login === '' ) {
        $error_fields[] = 'login';
    } 

    if( $password === '' ) {
        $error_fields[] = 'password';
    }

    if( !empty($error_fields) ) {
        $response = [
            "status" => false,
            "message" => "Проверьте правильность полей",
            "type" => 1,
            "fields" => $error_fields
        ];

        echo json_encode($response);

        die();
    }

    $password = md5($_POST['password']);

    $check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");
    if (mysqli_num_rows($check_user) > 0) {

        $user = mysqli_fetch_assoc($check_user);

        $_SESSION['user'] = [
            "id" => $user['id'],
            "full_name" => $user['full_name'],
            "avatar" => $user['avatar'],
            "email" => $user['email']
        ];

        $response = [
            "status" => true
        ];
        
        echo json_encode($response);
        die();

    } else {
        $response = [
            "status" => false,
            "message" => 'Неверный логин или пароль'
        ];

        echo json_encode($response);
        die();
    }
    ?>

