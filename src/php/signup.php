<? # скипт для регистрации
    header('Content-Type: application/json; charset=utf-8');
    session_start(); # Создаем сессию
    require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL

    # забираем данные из полей
    $full_name = $_POST['full_name'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $user_status = $_POST['user_status'];

    $path_to_avatar = null;

    # проверяем введеный логин на наличие в БД
    $check_login = mysqli_query($connect, "SELECT * FROM `users` WHERE `login`='$login'"); # Запрос в БД на выборку записи с таким же логином
    if(mysqli_num_rows($check_login) > 0) { # если в БД уже есть такой логин
        #формируем ответ с ошибкой
        $response = [
            "status" => false,
            "message" => "Такой логин уже существует",
            "type" => 1,
            "fields" => ['login']
        ];

        echo json_encode($response); # Возвращаем ответ в формате json
        die(); # Останавливаем выполнение скрипта
    }

    $error_fields = []; # инициализируем массив под названия полей с ошибками

    #Если пользователь отправил пустое поле, то добавляем название поля в конец массива $error_fields
    if( $login === '' ) {
        $error_fields[] = 'login';
    } 

    if( $password === '' ) {
        $error_fields[] = 'password';
    }

    if( $password_confirm === '' ) {
        $error_fields[] = 'password_confirm';
    }

    if( $full_name === '' ) {
        $error_fields[] = 'full_name';
    }

    if( $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_fields[] = 'email';
    }

    if( !$_FILES['avatar']) {
        #$error_fields[] = 'avatar';
        $path_to_avatar = 'public/img/uploads/default/default.jpg';
    }

    if( $user_status === '' ) {
        $error_fields[] = 'user_status';
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

    # проверяем введеные пароли на соответствие друг к другу
    if ($password === $password_confirm) {
        if($path_to_avatar != 'public/img/uploads/default/default.jpg'){

            $path_to_avatar = 'public/img/uploads/avatar/' . time() . $_FILES['avatar']['name']; # добавляем в название аватарки числа текущего времени (чтобы не возникал конфликт имен)
            # перемещяем загруженное изображение в public/img/uploads/
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], '../../' . $path_to_avatar)) {
                # Если не удалось переместить, то формируем ответ с ошибкой
                $response = [
                    "status" => false,
                    "message" => "ошибка при загрузке изображения",
                    "type" => 2
                ];
        
                echo json_encode($response); # Возвращаем ответ в формате json
                die();
            }
        }
        

        # шифруем пароль
        $password = md5($password);
        # вставляем в БД новую запись (добавляем нового пользователя)
        mysqli_query($connect, "INSERT INTO `users` (`id`, `login`, `email`, `password`, `full_name`, `avatar`, `status`) VALUES (NULL, '$login', '$email', '$password', '$full_name', '$path_to_avatar', '$user_status')");
        
        # формируем ответ
        $response = [
            "status" => true,
            "message" => "Регистрация прошла успешно!"
        ];

        echo json_encode($response); # Возвращаем ответ в формате json
        die(); # Останавливаем выполнение скрипта


    } else { # Если введенные пароли не совпали
        #формируем ответ с ошибкой
        $response = [
            "status" => false,
            "message" => "Пароли не совпадают!",
            "type" => 1,
            "fields" => ['password', 'password_confirm']
        ];

        echo json_encode($response); # Возвращаем ответ в формате json
    }

?>
