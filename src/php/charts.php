<?
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL

if(isset($_POST['show'])){

    if($_POST['show']=='tasks'){
        $completed_tasks = mysqli_query($connect, "SELECT * FROM `projects_tasks` WHERE `status`='1'");
        $not_completed_tasks = mysqli_query($connect, "SELECT * FROM `projects_tasks` WHERE `status`='0'");

        $completed_tasks_cnt = mysqli_num_rows($completed_tasks);
        $not_completed_tasks_cnt = mysqli_num_rows($not_completed_tasks);

        $response = [
            'completed_tasks'=>$completed_tasks_cnt,
            'not_completed_tasks'=>$not_completed_tasks_cnt
        ];

        echo json_encode($response);
    }
}