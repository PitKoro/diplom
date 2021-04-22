<?php

session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
include '../php/connect.php';
include '../php/lib.php';

# для дебага
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(isset($_POST['modal'])){
    if($_POST['modal'] == 'add_users'){
        header('Content-Type: application/json; charset=utf-8');
        $project_id = $_POST['project_id'];
        $users_out_project = get_all_users_not_participating_in_the_project($connect, $project_id);

        if(!empty($users_out_project)){
            $status = true;
            $html = "";
            for($i = 0; $i < count($users_out_project); $i++){
                $html = $html."
                <div class='form-check'>
                    <input class='form-check-input' type='checkbox' name='user_id[]' value='{$users_out_project[$i]['id']}' id='check-user-{$users_out_project[$i]['id']}'>
                    <label class='form-check-label' for='check-user-{$users_out_project[$i]['id']}'>
                        {$users_out_project[$i]['login']} | {$users_out_project[$i]['full_name']}
                    </label>
                </div>
                ";
            }
            $response = [
                'status'=>$status,
                'html'=>$html
            ];
            echo json_encode($response);
            die();
        } else {
            $status = false;
            $html = "<div class='msg'><h2>Все существуюшие пользователи добавлены в проект</h2></div>";
            $response = [
                'status'=>$status,
                'html'=>$html
            ];
            echo json_encode($response);
            die();
        }

        
    }

    if($_POST['modal'] == 'add_task'){
        header('Content-Type: text/html; charset=utf-8');
        $project_id = $_POST['project_id'];
        $users_in_project = get_all_users_in_project($connect, $project_id);

        $response = "
            <label for='task-name-field' class='form-label'>Название задачи</label>
            <input type='text' name='task_name' class='form-control' id='task-name-field' aria-describedby='validation_project_task_name' placeholder='Введите название задачи' required>
            

            <label for='task-executor-field' class='form-label mt-3'>Исполнитель</label>
            <select class='form-select ' name='task_user' id='task-executor-field' aria-describedby='validation_project_task_executor' required>
                <option value='' selected>Ответственный за выполнение</option>
        ";
        //<div class='invalid-feedback' id='validation_project_task_name'>Пожалуйста, введите название для задачи.</div>

        for($i = 0; $i < count($users_in_project); $i++){
            $response = $response."<option value='{$users_in_project[$i]['id']}'>{$users_in_project[$i]['full_name']}</option>";
        }

        $response = $response."</select>
        ";
        //<div class='invalid-feedback' id='validation_project_task_executor'>Пожалуйста, выберите исполнителя.</div>

        $response = $response."        
        <label for='task-end-date-field' class='form-label mt-3'>Дата завершения</label>
        <input type='date' name='task_end_date' class='form-control' id='task-end-date-field' aria-describedby='validation_project_task_end_date'>
        <div class='invalid-feedback'>Пожалуйста, введите данные во все поля.</div>";
        //<div class='invalid-feedback' id='validation_project_task_end_date'>Пожалуйста, введите дату сдачи задания.</div>


        echo $response;
        die();
    }
}

