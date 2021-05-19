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

        $response = $response."</select>";
        //<div class='invalid-feedback' id='validation_project_task_executor'>Пожалуйста, выберите исполнителя.</div>

        $response = $response."        
        <label for='task-end-date-field' class='form-label mt-3'>Дата завершения</label>
        <input type='date' name='task_end_date' class='form-control' id='task-end-date-field' aria-describedby='validation_project_task_end_date'>
        <div class='invalid-feedback'>Пожалуйста, введите данные во все поля.</div>";
        //<div class='invalid-feedback' id='validation_project_task_end_date'>Пожалуйста, введите дату сдачи задания.</div>


        echo $response;
        die();
    }

    if($_POST['modal']=='add_file'){
        $response = "
            <label for='add_project_file_field' class='form-label'>Файл проекта</label>
            <input class='form-control mb-3' type='file' id='add_project_file_field' name='project_file'>
        ";

        echo $response;
        die();
    }

    if($_POST['modal']=='edit_project_data'){
        header('Content-Type: text/html; charset=utf-8');
        $project_id = $_POST['project_id'];
        $project_data = get_project_data($connect, $project_id);

        $response = "
            <label for='project-name-field' class='form-label mt-3'>Название проекта</label>
            <input type='text' name='project_name' class='form-control' id='project-name-field' placeholder='Введите название проекта' value='{$project_data[0]['name']}' required>

            <label for='project-description-field' class='form-label mt-3'>Описание проекта</label>
            <input type='text' name='project_description' class='form-control' id='project-description-field' placeholder='Введите описание проекта' value='{$project_data[0]['description']}'>

            <label for='project-address-field' class='form-label mt-3'>Адрес проекта</label>
            <input type='text' name='project_address' class='form-control' id='project-address-field' placeholder='Введите адрес проекта' value='{$project_data[0]['address']}'>

            <label for='project-photo-field' class='form-label mt-3'>Изображение профиля</label>
            <input class='form-control' type='file' id='project-photo-field' name='project_photo' value='{$project_data[0]['photo']}'>

            <label for='project-start-date-field' class='form-label mt-3'>Дата начала</label>
            <input type='date' name='project_start_date' class='form-control' id='project-start-date-field' value='{$project_data[0]['start_date']}' required>

            <label for='project-end-date-field' class='form-label mt-3'>Дата окончания</label>
            <input type='date' name='project_end_date' class='form-control' id='project-end-date-field' value='{$project_data[0]['end_date']}' required>
        ";

        echo $response;
        die();
    }

    if($_POST['modal']=='edit_project_task'){
        header('Content-Type: text/html; charset=utf-8');
        $task_id = $_POST['task_id'];
        $project_id = $_POST['project_id'];

        $users_in_project = get_all_users_in_project($connect, $project_id);
        $project_task = get_project_task($connect, $task_id);
        $response = "";
        if($_SESSION['user']['status']=='10'){
            $response = "
            <input type='text' name='edit_task_id' value='{$task_id}' hidden>

            <label for='task-name-field' class='form-label mt-3'>Название задачи</label>
            <input type='text' name='edit_task_name' class='form-control' id='task-name-field' placeholder='Введите название задачи' value='{$project_task[0]['name']}' required>

            <label for='task-status-field' class='form-label mt-3'>Статус задачи</label>
            <select class='form-select' name='edit_task_status' id='task-status-field'>
                <option value='0' ".(($project_task[0]['status'] == '0') ? 'selected':'').">Не выполнено</option>
                <option value='1' ".(($project_task[0]['status'] == '1') ? 'selected':'').">Выполнено</option>
            </select>

            <label for='task-end-date-field' class='form-label mt-3'>Дата завершения</label>
            <input type='date' name='edit_task_end_date' class='form-control' id='task-end-date-field' value='{$project_task[0]['end_date']}'>

            <label for='task-executor-field' class='form-label mt-3'>Исполнитель</label>
            <select class='form-select ' name='edit_task_user' id='task-executor-field' aria-describedby='validation_project_task_executor' required>";
            for($i = 0; $i < count($users_in_project); $i++){
                $response = $response."<option value='{$users_in_project[$i]['id']}' ".(($project_task[0]['user_id'] == $users_in_project[$i]['id']) ? 'selected':'').">{$users_in_project[$i]['full_name']}</option>";
            }

            $response = $response."</select>";
        } else {
            $response = "
            <input type='text' name='edit_task_id' value='{$task_id}' hidden>

            <input hidden type='text' name='edit_task_name' class='form-control' id='task-name-field' placeholder='Введите название задачи' value='{$project_task[0]['name']}' required>

            <select class='form-select' name='edit_task_status' id='task-status-field'>
                <option value='0' ".(($project_task[0]['status'] == '0') ? 'selected':'').">Не выполнено</option>
                <option value='1' ".(($project_task[0]['status'] == '1') ? 'selected':'').">Выполнено</option>
            </select>

            <input hidden type='date' name='edit_task_end_date' class='form-control' id='task-end-date-field' value='{$project_task[0]['end_date']}'>

            <select hidden class='form-select ' name='edit_task_user' id='task-executor-field' aria-describedby='validation_project_task_executor' required>";
            for($i = 0; $i < count($users_in_project); $i++){
                $response = $response."<option value='{$users_in_project[$i]['id']}' ".(($project_task[0]['user_id'] == $users_in_project[$i]['id']) ? 'selected':'').">{$users_in_project[$i]['full_name']}</option>";
            }

            $response = $response."</select>";
        }
        
        echo $response;
        die();
    }

    if($_POST['modal']=='edit_user'){
        header('Content-Type: text/html; charset=utf-8');
        $user_id = $_POST['user_id'];
        $user_data = get_user_data($connect, $user_id);
        
        $response = "
            <input type='text' name='user_id' value='{$user_data[0]['id']}' hidden>

            <label for='user-full-name-field' class='form-label mt-3'>ФИО</label>
            <input type='text' name='user_full_name' class='form-control' id='user-full-name-field' placeholder='Введите ФИО' value='{$user_data[0]['full_name']}' required>

            <label for='user-login-field' class='form-label mt-3'>Логин</label>
            <input type='text' name='user_login' class='form-control' id='user-login-field' placeholder='Введите логин' value='{$user_data[0]['login']}'>

            <label for='user-email-field' class='form-label mt-3'>Почта</label>
            <input type='text' name='user_email' class='form-control' id='user-email-field' placeholder='Введите эл. почту' value='{$user_data[0]['email']}'>

            <label for='user-password-field' class='form-label mt-3'>Новый пароль</label>
            <input type='text' name='user_password' class='form-control' id='user-password-field' placeholder='Введите новый пароль' value=''>

            <label for='user-avatar-field' class='form-label mt-3'>Изображение профиля</label>
            <input class='form-control' type='file' id='user-avatar-field' name='user_avatar' value='{$user_data[0]['avatar']}'>

            <label for='user-status-field' class='form-label mt-3'>Роль пользователя</label>
            <select class='form-select' name='user_status' id='user-status-field'>
                <option value='10' ".(($user_data[0]['status'] == '10') ? 'selected':'').">Администратор</option>
                <option value='1' ".(($user_data[0]['status'] == '1') ? 'selected':'').">Обычный пользователь</option>
            </select>
        ";

        echo $response;
        die();
    }
    
}

