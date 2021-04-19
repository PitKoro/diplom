<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: ../index.php');
}
if ($_SESSION['user']['status'] != 10) {
    header('Location: ../index.php');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">

    <!-- Bootstrap CSS -->
    <link type="text/css" rel="stylesheet" href="../../public/vendor/bootstrap/css/bootstrap.min.css">

    <title>Новый пользователь</title>

</head>
<body>

    <? require "blocks/navbar.php"; ?>

    <div class="container-sm mt-5">
        <div class="reg-form px-3">
            <div class="form-header">
                <p>Регистрация нового пользователя</p>
            </div>
            <div class="auth-error mb-3 none "></div>
            <div class="form-body mb-4">
                <form>
                    <label for="reg_fullname_field" class="form-label">ФИО</label>
                    <input type="text" name="full_name" class="form-control mb-3" id="reg_fullname_field" placeholder="Введите свое полное имя">

                    <label for="reg_login_field" class="form-label">Логин</label>
                    <input type="text" name="login" class="form-control mb-3" id="reg_login_field" placeholder="Введите свой логин">

                    <label for="reg_email_field" class="form-label">Почта</label>
                    <input type="email" name="email" class="form-control mb-3" id="reg_email_field" placeholder="Введите адрес своей почты">
                    
                    <label for="reg_avatar_field" class="form-label">Изображение профиля</label>
                    <input class="form-control mb-3" type="file" id="reg_avatar_field" name="avatar">

                    <label for="reg_password_field" class="form-label">Пароль</label>
                    <input  class="form-control mb-3" id="reg_password_field" type="password" name="password" placeholder="Введите пароль">

                    <label for="reg_confirm_password_field" class="form-label">Подтверждение пароля</label>
                    <input class="form-control mb-3" id="reg_confirm_password_field" type="password" name="password_confirm" placeholder="Подтвердите пароль">
                    
                    <select class="form-select" aria-label="Default select example" name="user_status">
                        <option value="" selected>Выберите роль пользователя</option>
                        <option value="10">Администратор</option>
                        <option value="1">Обычный пользователь</option>
                    </select>

                    <button type="submit" class="register-btn btn btn-success mt-3">Зарегистрировать</button>
                </form>
            </div>

            <!-- <div class="form-footer my-3">
                У вас уже есть аккаунт? - <a href="../index.php">авторизируйтесь</a>!
            </div> -->
        </div>
    </div>


    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="../js/registration.js"></script>
</body>
</html>