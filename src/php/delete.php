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
        if($_POST['action'] == 'delete_project'){
            $project_id = $_POST['project_id'];
            $response = delete_project($connect, $project_id);
            echo json_encode($response);
        }

        if($_POST['action'] == 'delete_project_task'){
            $project_id = $_POST['project_id'];
            $task_id = $_POST['task_id'];
            $response = delete_project_task($connect, $project_id, $task_id);
            echo json_encode($response);
        }

        if($_POST['action'] == 'delete_project_user'){
            $project_id = $_POST['project_id'];
            $user_id = $_POST['user_id'];
            $response = delete_project_user($connect, $project_id, $user_id);
            echo json_encode($response);
        }
    }
}






























?>