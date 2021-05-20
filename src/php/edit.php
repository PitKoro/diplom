<?
header('Content-Type: application/json; charset=utf-8');
session_start();
include './connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL
include './lib.php';

// ini_set('upload_max_filesize', '20M'); //ограничение загрузки файла в 20 мб
# для дебага
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(isset($_POST)){
    if(isset($_POST['action'])){

        if($_POST['action']=='complete_project_task'){
            $task_id = $_POST['task_id'];
            $task_comment = $_POST['task_comment'];
            $response = complete_project_task($connect, $task_id, $task_comment);
            echo json_encode($response);
            die();
        }

        if($_POST['action']=='edit_project_data'){
            $project_id = $_POST['project_id'];
            $project_name = $_POST['project_name'];
            $project_description = $_POST['project_description'];
            $project_address = $_POST['project_address'];
            $project_start_date = $_POST['project_start_date'];
            $project_end_date = $_POST['project_end_date'];

            $path_to_photo = null;
            if($_POST['is_change_photo']=='true'){
                
                // if( !$_FILES['project_photo']) {
                //     $path_to_photo = 'public/img/uploads/default/default_project_photo.jpg';
                // }

                if($path_to_photo != 'public/img/uploads/default/default_project_photo.jpg'){

                    $path_to_photo = 'public/img/uploads/project_photo/' . time() . $_FILES['project_photo']['name']; # добавляем в название аватарки числа текущего времени (чтобы не возникал конфликт имен)
                    # перемещяем загруженное изображение в public/img/uploads/
                    if (!move_uploaded_file($_FILES['project_photo']['tmp_name'], '../../' . $path_to_photo)) {
                        # Если не удалось переместить, то формируем ответ с ошибкой
                        $response = [
                            "status" => false,
                            "message" => "error loading image",
                            "type" => 2
                        ];
                
                        echo json_encode($response); # Возвращаем ответ в формате json
                        die();
                    }
                }
            } else if($_POST['is_change_photo']=='false') {
                $path_to_photo = 'false';
            }
            
            $project_data=[
                'id' => $project_id,
                'name' => $project_name,
                'description' => $project_description,
                'address' => $project_address,
                'photo' => $path_to_photo,
                'start_date' => $project_start_date,
                'end_date' => $project_end_date                
            ];

            $response = edit_project_data($connect, $project_data);
            echo json_encode($response);
            die();
        }

        if($_POST['action']=='edit_project_task'){
            $task_id = $_POST['task_id'];
            $task_name = $_POST['task_name'];
            $task_status = $_POST['task_status'];
            $task_end_date = $_POST['task_end_date'];
            $task_user = $_POST['task_user'];

            $task_data=[
                'id' => $task_id,
                'name' => $task_name,
                'status' => $task_status,
                'end_date' => $task_end_date,
                'user_id' => $task_user              
            ];

            $response = edit_project_task($connect, $task_data);
            echo json_encode($response);
            die();
        }

        if($_POST['action']=='edit_user_data'){
            $user_id = $_POST['user_id'];
            $user_full_name = $_POST['user_full_name'];
            $user_login = $_POST['user_login'];
            $user_email = $_POST['user_email'];
            $user_status = $_POST['user_status'];
            $user_password = $_POST['user_password'];

            $path_to_photo = null;
            if($_POST['is_change_photo']=='true'){
                
                if($path_to_photo != 'public/img/uploads/default/default.jpg'){

                    $path_to_photo = 'public/img/uploads/avatar/' . time() . $_FILES['user_avatar']['name']; # добавляем в название аватарки числа текущего времени (чтобы не возникал конфликт имен)
                    # перемещяем загруженное изображение в public/img/uploads/
                    if (!move_uploaded_file($_FILES['user_avatar']['tmp_name'], '../../' . $path_to_photo)) {
                        # Если не удалось переместить, то формируем ответ с ошибкой
                        $response = [
                            "status" => false,
                            "message" => "error loading image",
                            "type" => 2
                        ];
                
                        echo json_encode($response); # Возвращаем ответ в формате json
                        die();
                    }
                }
            } else if($_POST['is_change_photo']=='false') {
                $path_to_photo = 'false';
            }
            
            $user_data=[
                'id' => $user_id,
                'full_name' => $user_full_name,
                'login' => $user_login,
                'email' => $user_email,
                'avatar' => $path_to_photo,
                'status' => $user_status,
                'password' => $user_password               
            ];

            $response = edit_user_data($connect, $user_data);
            echo json_encode($response);
            die();
        }
    }
    


}