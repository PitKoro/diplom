<?php #Скрипт для входа в учетную запись

    session_start(); # Создаем сессию
    require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL

    # забираем данные из полей
    $login = $_POST['login'];
    $password = $_POST['password'];

    $error_fields = []; # инициализируем массив под названия полей с ошибками

    # Если пользователь отправил пустые данные, то добавляем в конец массива $error_fields название пустого поля
    # Это нужно, чтобы знать какие поля красить в красный в случае ошибки
    if( $login === '' ) {
        $error_fields[] = 'login';
    } 

    if( $password === '' ) {
        $error_fields[] = 'password';
    }

    # Формирование ответа, если пользователь прислал хотя бы одно пустое поле
    if( !empty($error_fields) ) {
        $response = [
            "status" => false,
            "message" => "Проверьте правильность полей",
            "type" => 1,
            "fields" => $error_fields
        ];

        echo json_encode($response); # Возвращаем ответ в формате json
        die(); # Останавливаем выполнение скрипта
    }

    # Если пользователь заполнил все поля (из index.php), то зашифровываем пароль
    $password = md5($_POST['password']);

    # Запрос на выборку записи из БД, в которой логин и пароль совпадают с введенными пользователем данными
    $check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");

    # Если в БД запись с таким логином и паролем нашлась, то
    if (mysqli_num_rows($check_user) > 0) {

        $user = mysqli_fetch_assoc($check_user); # записываем ответ запроса в виде ассоциативного массива

        # формируем глобальную переменную сессии
        $_SESSION['user'] = [
            "id" => $user['id'],
            "full_name" => $user['full_name'],
            "avatar" => $user['avatar'],
            "email" => $user['email']
        ];

        # формируем ответ
        $response = [
            "status" => true
        ];
        
        echo json_encode($response); # Возвращаем ответ в формате json
        die(); # Останавливаем выполнение скрипта

    } else { # Если в БД запись с таким логином и паролем не нашлась, то

        # формируем ответ с сообщение об ошибке
        $response = [
            "status" => false,
            "message" => 'Неверный логин или пароль'
        ];

        echo json_encode($response); # Возвращаем ответ в формате json
        die(); # Останавливаем выполнение скрипта
    }
    ?>

