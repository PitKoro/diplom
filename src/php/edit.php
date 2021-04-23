<?
header('Content-Type: application/json; charset=utf-8');
session_start();
include './connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL
include './lib.php';


# для дебага
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(isset($_POST)){
    if(isset($_POST['action'])){
        if($_POST['action']=='complete_project_task'){
            $task_id = $_POST['task_id'];
            $response = complete_project_task($connect, $task_id);
            echo json_encode($response);
            die();
        }
    }
    


}