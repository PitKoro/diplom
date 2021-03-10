<?php #Скрипт для выхода из учетной записи

#Запускаем сессию
session_start();
#Очищаем глобальную переменную $_SESSION['user']
unset($_SESSION['user']);
#Переходим на страницу авторизации (index.php)
header('Location: ../index.php');