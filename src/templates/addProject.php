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

    <title>Новый проект</title>

</head>
<body>

    <? require "blocks/navbar.php"; ?>

    <div class="container-sm mt-5">
        <div class="reg-form px-3">
            <div class="form-header">
                <p>Создание нового проекта</p>
            </div>
            <div class="form-body mb-5">
                <form>
                    <label for="add_project_name_field" class="form-label">Название</label>
                    <input type="text" name="name" class="form-control" id="add_project_name_field" aria-describedby="validation_project_name" placeholder="Введите название для проекта" required>
                    <div class="invalid-feedback" id="validation_project_name">Пожалуйста, введите название для проекта.</div>

                    <label for="add_project_description_field" class="form-label mt-3">Описание</label>
                    <input type="text" name="description" class="form-control" id="add_project_description_field" placeholder="Введите описание">

                    <label for="add_project_address_field" class="form-label mt-3">Адрес</label>
                    <input type="text" name="address" class="form-control" id="add_project_address_field" placeholder="Введите адрес объекта">
                    
                    <label for="add_project_photo_field" class="form-label mt-3">Изображение проекта</label>
                    <input class="form-control" type="file" id="add_project_photo_field" name="photo">

                    <label for="add_project_start_date_field" class="form-label mt-3">Дата начала</label>
                    <input  class="form-control" id="add_project_start_date_field" type="date" name="start_date">
                    <div class="invalid-feedback" id="validation_project_start_date">Пожалуйста, введите дату начала проекта.</div>

                    <label for="add_project_end_date_field" class="form-label mt-3">Дата завершения</label>
                    <input class="form-control" id="add_project_end_date_field" type="date" name="end_date">
                    <div class="invalid-feedback" id="validation_project_end_date">Пожалуйста, введите дату завершения проекта.</div>

                    <button type="submit" class="add-project-btn btn btn-success mt-3">Создать проект</button>
                </form>
            </div>

        </div>
    </div>


    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/add_project.js"></script>
</body>
</html>