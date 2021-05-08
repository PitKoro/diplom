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

    if($_POST['show']=='users'){

        $response = [];
        $all_users_with_tasks_query = mysqli_query($connect, "SELECT U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id GROUP BY U.full_name"); // ЗАПРОС НА ПОЛУЧЕНИЕ full_name ВСЕХ users У КОТОРЫХ ЕСТЬ ХОТЯ БЫ ОДНА ЗАДАЧА
        $all_tasks_query = mysqli_query($connect, "SELECT A.*, U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id");// WHERE A.project_id='{$project_id}' ORDER BY A.id DESC
        
        while($all_users_with_tasks = mysqli_fetch_array($all_users_with_tasks_query)){
            $response[$all_users_with_tasks["full_name"]] = [
                "completed_tasks" => 0,
                "completing_soon_tasks" => 0,
                "overdue_tasks" => 0,
                "current_tasks" => 0
            ];
        }
        
        while($all_tasks=mysqli_fetch_array($all_tasks_query)){
            $task_end_date = date("d.m.Y",strtotime($all_tasks['end_date']));
            $current_date = time();
            $days_to_finish = ceil(( strtotime($task_end_date) - $current_date ) / (60*60*24));
        
            if($all_tasks["status"] == "1"){
                $response[$all_tasks["full_name"]]["completed_tasks"] = $response[$all_tasks["full_name"]]["completed_tasks"] + 1;
            } else if(($days_to_finish<=5) && ($days_to_finish>=0) && ($all_tasks['status']!='1')){
                $response[$all_tasks["full_name"]]["completing_soon_tasks"] = $response[$all_tasks["full_name"]]["completing_soon_tasks"] + 1;
            } else if(($days_to_finish<0) && ($all_tasks['status']!='1')){
                $response[$all_tasks["full_name"]]["overdue_tasks"] = $response[$all_tasks["full_name"]]["overdue_tasks"] + 1;
            } else {
                $response[$all_tasks["full_name"]]["current_tasks"] = $response[$all_tasks["full_name"]]["current_tasks"] + 1;
            }
        }

        echo json_encode($response);
    }
}