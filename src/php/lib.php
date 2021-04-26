<?

# Функция - получение всех проектов пользователя
# аргументы:    $connect - дескриптор подключения к БД
#               $user_id - id авторизованного пользователя
# возвращает: массив проектов авторизованного пользователя
function get_all_user_projects($connect, $user_id){
    $sql = mysqli_query($connect, "SELECT A.* FROM projects A LEFT JOIN users_in_projects B ON A.id=B.project_id WHERE user_id=$user_id ORDER BY start_date DESC");

    $all_projects = array();
    while($result = mysqli_fetch_assoc($sql)){
        array_push($all_projects, $result);
    }
    return $all_projects;
}


# Функция - получение всех пользователей, не учавствующих в проекте
# аргументы:    $connect - дескриптор подключения к БД
#               $project_id - id проекта
# возвращает: массив пользователей, не участвующих в проекте
function get_all_users_not_participating_in_the_project($connect, $project_id){

    $sql = mysqli_query($connect, "SELECT user_id FROM users_in_projects WHERE project_id=$project_id"); #Все пользователи в проекте
    $users_in_project = array(); #Массив всех пользователей в проекте
    while($result = mysqli_fetch_assoc($sql)){
        array_push($users_in_project, $result);
    }
    mysqli_free_result($sql);

    $query = "SELECT * FROM users WHERE ";
    for($i = 0; $i < count($users_in_project); $i++){
        if($i == 0){
            $query = $query."id!={$users_in_project[$i]['user_id']}";
        } else {
            $query = $query." AND id!={$users_in_project[$i]['user_id']}";
        }
    }
    $sql = mysqli_query($connect, $query);
    $users_out_project = array(); #Массив пользователей не участвующих в проекте
    while($result = mysqli_fetch_assoc($sql)){
        array_push($users_out_project, $result);
    }
    mysqli_free_result($sql);
    
    return $users_out_project;
}


# Функция - получение всех пользователей проекта
# аргументы:    $connect - дескриптор подключения к БД
#               $project_id - id проекта
# возвращает: массив со всеми пользователями проекта
function get_all_users_in_project($connect, $project_id){
    $sql = mysqli_query($connect, "SELECT A.* FROM users A LEFT JOIN users_in_projects B ON A.id=B.user_id WHERE project_id=$project_id ORDER BY full_name");

    $all_users = array();
    while($result = mysqli_fetch_assoc($sql)){
        array_push($all_users, $result);
    }
    return $all_users;
}


# Функция - получение информации о проекте
# аргументы:    $connect - дескриптор подключения к БД
#               $project_id - id проекта
# возвращает: массив со всей информацией о проекте
function get_project_data($connect, $project_id){
    $sql = mysqli_query($connect, "SELECT * FROM projects WHERE id='{$project_id}'");
    $project_data = array();
    while($result = mysqli_fetch_assoc($sql)){
        array_push($project_data, $result);
    }

    return $project_data;
}

# Функция - удаление проекта
# аргументы:    $connect - дескриптор подключения к БД
#               $project_id - id проекта
# возвращает: массив [$status => boolean, $msg => string]
function delete_project($connect, $project_id){
    mysqli_query($connect, "DELETE FROM users_in_projects WHERE project_id='{$project_id}'");
    mysqli_query($connect, "DELETE FROM projects_tasks WHERE project_id='{$project_id}'");
    $sql = mysqli_query($connect, "DELETE FROM projects WHERE id='{$project_id}'");

    if($sql){
        $status = true;
        $msg = "project with id={$project_id} successfully deleted!";
        $response = [
            'status' => $status,
            'msg' => $msg
        ];
        return $response;
    } else {
        $status = false;
        $msg = "Failed to delete project with id ={$project_id}!";
        $response = [
            'status' => $status,
            'msg' => $msg
        ];
        return $response;
    }
}


# Функция - удаление задачи из проекта
# аргументы:    $connect - дескриптор подключения к БД
#               $project_id - id проекта
#               $task_id - id задачи
# возвращает: массив [$status => boolean, $msg => string]
function delete_project_task($connect, $project_id, $task_id){
    $sql = mysqli_query($connect, "DELETE FROM projects_tasks WHERE id = '{$task_id}' AND project_id = '{$project_id}'");
    if($sql){
        $status = true;
        $msg = "project task with id={$task_id} successfully deleted!";
        $response = [
            'status' => $status,
            'msg' => $msg
        ];
        return $response;
    } else {
        $status = false;
        $msg = "Failed to delete project task with id ={$task_id}!";
        $response = [
            'status' => $status,
            'msg' => $msg
        ];
        return $response;
    }
} 


# Функция - удаление пользователя из проекта
# аргументы:    $connect - дескриптор подключения к БД
#               $project_id - id проекта
#               $user_id - id пользователя
# возвращает: массив [$status => boolean, $msg => string]
function delete_project_user($connect, $project_id, $user_id){
    mysqli_query($connect, "DELETE FROM projects_tasks WHERE project_id='{$project_id}' AND user_id='{$user_id}'"); # Удаляем задачи пользователя из проекта
    $sql = mysqli_query($connect, "DELETE FROM users_in_projects WHERE project_id='{$project_id}' AND user_id='{$user_id}'"); # Удаляем пользователя из проекта
    
    if($sql){
        $status = true;
        $msg = "project user with id={$user_id} successfully deleted!";
        $response = [
            'status' => $status,
            'msg' => $msg
        ];
        return $response;
    } else {
        $status = false;
        $msg = "Failed to delete project user with id ={$task_id}!";
        $response = [
            'status' => $status,
            'msg' => $msg
        ];
        return $response;
    }
}


# Функция - выполнение задачи в проекте
# аргументы:    $connect - дескриптор подключения к БД
#               $task_id - id задачи
# возвращает: массив [$status => boolean, $msg => string]
function complete_project_task($connect, $task_id){
    $current_date = date("Y-m-d");
    $sql = mysqli_query($connect, "UPDATE projects_tasks SET status = '1', `finish_date` = '{$current_date}' WHERE `projects_tasks`.`id` = '{$task_id}'"); 
    
    
    if($sql){
        $status = true;
        $msg = "project task with id={$task_id} successfully updated!";
        $response = [
            'status' => $status,
            'msg' => $msg
        ];
        return $response;
    } else {
        $status = false;
        $msg = "Failed to update project task with id ={$task_id}!";
        $response = [
            'status' => $status,
            'msg' => $msg
        ];
        return $response;
    }
}

?>