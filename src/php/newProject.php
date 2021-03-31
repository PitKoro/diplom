<? # скипт для создание нового проекта
    header('Content-Type: application/json; charset=utf-8');
    session_start(); # Создаем сессию
    require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL

    # забираем данные из полей
    $name = $_POST['name'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // $path_to_avatar = null;
    $path_to_project_photo = null;

    $error_fields = []; # инициализируем массив под названия полей с ошибками

    #Если пользователь отправил пустое поле, то добавляем название поля в конец массива $error_fields
    if( $name === '' ) {
        $error_fields[] = 'name';
    }

    if( $description === '' ) {
        // $error_fields[] = 'description';
        $description = NULL;
    }

    if( $address === '' ) {
        // $error_fields[] = 'address';
        $address = NULL;
    }

    if( $start_date === '' ) {
        $error_fields[] = 'start_date';
    }

    if( $end_date === '' ) {
        $error_fields[] = 'end_date';
    }


    if( !$_FILES['project_photo']) {
        $path_to_project_photo = 'public/img/uploads/default/default_project_photo.jpg';
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


    if($path_to_project_photo != 'public/img/uploads/default/default_project_photo.jpg'){

        $path_to_project_photo = 'public/img/uploads/project_photo/' . time() . $_FILES['project_photo']['name']; # добавляем в название аватарки числа текущего времени (чтобы не возникал конфликт имен)
        # перемещяем загруженное изображение в public/img/uploads/
        if (!move_uploaded_file($_FILES['project_photo']['tmp_name'], '../../' . $path_to_project_photo)) {
            # Если не удалось переместить, то формируем ответ с ошибкой
            $response = [
                "status" => false,
                "message" => "ошибка при загрузке изображения",
                "type" => 2
            ];
    
            echo json_encode($response); # Возвращаем ответ в формате json
            die(); # Останавливаем выполнение скрипта
        }
    }
    

    # вставляем в БД новую запись (добавляем новый проект)
    mysqli_query($connect, "INSERT INTO `project` (`id`, `name`, `description`, `address`, `photo`, `start_date`, `end_date`) VALUES (NULL, '$name', '$description', '$address', '$path_to_project_photo', '$start_date', '$end_date')");
    
    # формируем ответ
    $response = [
        "status" => true,
        "message" => "Новый проект успешно добавлен!"
    ];

    echo json_encode($response); # Возвращаем ответ в формате json
    die(); # Останавливаем выполнение скрипта

?>
