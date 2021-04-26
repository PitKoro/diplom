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

        if($_POST['action']=='complete_project_task'){
            $task_id = $_POST['task_id'];
            $response = complete_project_task($connect, $task_id);
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

            // $response = [
            //     'POST_photo' => $_POST['project_photo']
            // ];
            // echo json_encode($response);
            // die();
            

            $path_to_photo = null;
            if($_POST['is_change_photo']=='true'){
                
                // if( !$_FILES['project_photo']) {
                //     $path_to_photo = 'public/img/uploads/default/default_project_photo.jpg';
                // }

                if($path_to_photo != 'public/img/uploads/default/default_project_photo.jpg'){

                    $path_to_photo = 'public/img/uploads/avatar/' . time() . $_FILES['project_photo']['name']; # добавляем в название аватарки числа текущего времени (чтобы не возникал конфликт имен)
                    # перемещяем загруженное изображение в public/img/uploads/
                    if (!move_uploaded_file($_FILES['project_photo']['tmp_name'], '../../' . $path_to_photo)) {
                        # Если не удалось переместить, то формируем ответ с ошибкой
                        $response = [
                            "status" => false,
                            "message" => "ошибка при загрузке изображения",
                            "type" => 2
                        ];
                
                        echo json_encode($response); # Возвращаем ответ в формате json
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
    }
    


}