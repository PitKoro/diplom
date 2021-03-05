<?

    session_start();
    require_once 'connect.php';

    $full_name = $_POST['full_name'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $status = 10;


    $check_login = mysqli_query($connect, "SELECT * FROM `users` WHERE `login`='$login'");
    if(mysqli_num_rows($check_login) > 0) {
        $response = [
            "status" => false,
            "message" => "Такой логин уже существует",
            "type" => 1,
            "fields" => ['login']
        ];

        echo json_encode($response);
        die();
    }

    $error_fields = [];

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
        $error_fields[] = 'avatar';
    }

    if( !empty($error_fields) ) {
        $response = [
            "status" => false,
            "message" => "Проверьте правильность полей",
            "type" => 1,
            "fields" => $error_fields
        ];

        echo json_encode($response);

        die();
    }

    if ($password === $password_confirm) {

        $path = 'public/img/uploads/' . time() . $_FILES['avatar']['name'];
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], '../../' . $path)) {
            $response = [
                "status" => false,
                "message" => "ошибка при загрузке изображения",
                "type" => 2
            ];
    
            echo json_encode($response);
        }

        $password = md5($password);
        mysqli_query($connect, "INSERT INTO `users` (`id`, `login`, `email`, `password`, `full_name`, `avatar`, `status`) VALUES (NULL, '$login', '$email', '$password', '$full_name', '$path', '$status')");
        
        $response = [
            "status" => true,
            "message" => "Регистрация прошла успешно!"
        ];

        echo json_encode($response);
        die();


    } else {
        $response = [
            "status" => false,
            "message" => "Пароли не совпадают!",
            "type" => 1,
            "fields" => ['password', 'password_confirm']
        ];

        echo json_encode($response);
    }

?>
