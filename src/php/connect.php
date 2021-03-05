<?php

    $connect = mysqli_connect('localhost', 'root', 'root', 'diplom');

    if (!$connect) {
        die('Error connect to DataBase');
    }