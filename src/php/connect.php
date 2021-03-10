<?php
    
    #Устанавливаем соединение с сервером MySQL
    $connect = mysqli_connect('localhost', 'root', 'root', 'diplom'); 

    #Если установить соединение не удалось, то остановить выполнение скрипта с ошибкой 'Error connect to DataBase'
    if (!$connect) {
        die('Error connect to DataBase');
    }