<?
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


# аргументы:    $connect - дескриптор подключения к БД
# возвращает: массив со всеми пользователями проекта
function get_all_users_in_project($connect, $project_id){
    $sql = mysqli_query($connect, "SELECT A.* FROM users A LEFT JOIN users_in_projects B ON A.id=B.user_id WHERE project_id=$project_id ORDER BY full_name");

    $all_users = array();
    while($result = mysqli_fetch_assoc($sql)){
        array_push($all_users, $result);
    }
    return $all_users;
}

?>