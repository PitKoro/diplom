<?
# аргументы:    $connect - дескриптор подключения к БД
#               $user_id - id авторизованного пользователя
# возвращает: массив проектов авторизованного пользователя
function get_all_user_projects($connect, $user_id){
    // $user_id = 1;
    $sql = mysqli_query($connect, "SELECT A.* FROM projects A LEFT JOIN users_in_projects B ON A.id=B.project_id WHERE user_id=$user_id");

    $all_projects = array();
    while($result = mysqli_fetch_assoc($sql)){
        array_push($all_projects, $result);
    }
    return json_encode($all_projects);
}

?>