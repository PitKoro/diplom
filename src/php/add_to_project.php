<?
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL

$error_fields = []; # инициализируем массив под названия полей с ошибками

if(isset($_POST['add'])){
    if($_POST['add'] == 'task'){

        $project_id = $_POST['project_id'];
        $task_name = $_POST['task_name'];
        $user_id = $_POST['user_id'];

        #Если пользователь отправил пустое поле, то добавляем название поля в конец массива $error_fields
        if( $task_name === '' ) {
            $error_fields[] = 'task_name';
        }
        if( $user_id === '' ){
            $error_fields[] = 'user';
        }

        # Если хотя бы одно поле оказалось пустым
        if( !empty($error_fields) ) {
            #формируем ответ с ошибкой
            $response = [
                "status" => false,
                "message" => "Проверьте правильность полей",
                "type" => 1,
                "fields" => $error_fields
            ];

            echo json_encode($response); # Возвращаем ответ в формате json
            die(); # Останавливаем выполнение скрипта
        }

        mysqli_query($connect, "INSERT INTO projects_tasks (id, project_id, name, user_id) VALUES (NULL, $project_id, '{$task_name}', '{$user_id}')");
        # формируем ответ
        $response = [
            "status" => true,
            "message" => "Новая задача успешно добавлена!"
        ];
    
        echo json_encode($response); # Возвращаем ответ в формате json
        die(); # Останавливаем выполнение скрипта
    }

    if($_POST['add'] == 'users'){
        $project_id = $_POST['project_id'];
        $users_id = $_POST['users_id'];

        if( $users_id === '' ) {
            $error_fields[] = 'select_user';
        }
        if( !empty($error_fields) ) {
            #формируем ответ с ошибкой
            $response = [
                "status" => false,
                "message" => "Пожалуйста, выберите пользователя!",
                "type" => 1,
                "fields" => $error_fields
            ];
    
            echo json_encode($response); # Возвращаем ответ в формате json
            die(); # Останавливаем выполнение скрипта
        }

        $arr_users_id = explode(',', $users_id);
        $query = "INSERT INTO users_in_projects (user_id, project_id) VALUES ";
        for($i = 0; $i < count($arr_users_id); $i++){
            if(count($arr_users_id) == 1){
                $query = $query."({$arr_users_id[$i]},{$project_id})";
                break;
            } else if($i == 0){
                $query = $query."({$arr_users_id[$i]},{$project_id})";
            } else {
                $query = $query.", ({$arr_users_id[$i]},{$project_id})";
            }
        }
        mysqli_query($connect, $query);

        # формируем ответ
        $response = [
            "status" => true,
            "message" => "Пользователи успешно добавлены в проект!"
        ];
    
        echo json_encode($response); # Возвращаем ответ в формате json
        die(); # Останавливаем выполнение скрипта
    }
}
?>