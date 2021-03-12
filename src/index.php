<?
session_start();

if ($_SESSION['user']) {
    header('Location: templates/profile.php');
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">

    <!-- Bootstrap CSS -->
    <link type="text/css" rel="stylesheet" href="../public/vendor/bootstrap/css/bootstrap.min.css">

    <title>Авторизация</title>

</head>
<body>
    
    <div class="auth-form px-3 mt-5">
        <div class="form-header">
            <p>Авторизация</p>
        </div>

        <div class="auth-error mb-3 none "></div>

        <div class="form-body">
            <form>
                <label class="form-label" name="login" for="auth_login_field">Логин</label>
                <input class="form-control mb-3" id="auth_login_field" type="text" name="login" placeholder="Введите свой логин" required>

                <label class="form-label" for="auth_password_field">Пароль</label>
                <input class="form-control mb-3" id="auth_password_field" name="password" type="password" name="password" placeholder="Введите пароль" required>
                <button type="submit" class="login-btn btn btn-success" >Войти</button>
            </form>
        </div>

        <div class="form-footer mt-3">
            У вас нет аккаунта? - <a href="templates/register.php">зарегистрируйтесь</a>!
        </div>
    </div>

    <!-- Bootstrap and jQuery JS -->
    <script src="../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>