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
    
    <!-- Форма авторизации -->

    <form>
        <label>Логин</label>
        <input type="text" name="login" placeholder="Введите свой логин">
        <label>Пароль</label>
        <input type="password" name="password" placeholder="Введите пароль">
        <button type="submit" class="mt-3 login-btn" >Войти</button>
        <p>
            У вас нет аккаунта? - <a href="templates/register.php">зарегистрируйтесь</a>!
        </p>
        <p class="msg none"></p>
    </form>

    <!-- Bootstrap and jQuery JS -->
    <script src="../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>