<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Bootstrap CSS -->
    <link type="text/css" rel="stylesheet" href="../../public/vendor/bootstrap/css/bootstrap.min.css">

    <title>Главная</title>

</head>
<body>

    <? require "blocks/navbar.php"; ?>


    <div class="container mt-5">
        <div class="row gy-0">            
            <div class="col">
                <div class="row mb-2">
                    <div class="col">
                        <div class="mx-auto" style="width:129px;">
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <button type="button" class="btn btn-outline-primary js-project-tasks-btn active">Пользователи</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col">
                        <div class="table-responsive">
                            <div class="fixed-height-table">
                                <table class="table table-bordered table-hover pl-2 pr-2 text-center js-all-users-table">

                                </table>
                                
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

     <!-- Modal for edit project task -->
     <div class="modal fade" id="edit-user-modal" tabindex="-1" aria-labelledby="edit-user-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-user-modal-label">Редактирование пользователя</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-body js-edit-user-modal-body">

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger js-edit-user-close-btn" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success js-edit-user-submit-btn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/popper/popper.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function show_all_users(){
            $.ajax({
                type: "POST",
                url: "../php/get_db_table.php",
                data: {show: "all_users"},
                success: function(html){
                    $(".js-all-users-table").html(html);
                } 
            });
        }

        $(document).ready(function(){
            show_all_users();
        });


        // УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ ИЗ ПРОЕКТА
        $('.js-all-users-table').on('click', '.js-delete-project-user-btn', function(event){
            event.preventDefault();
            let isDelete = confirm("Вы точно хотите удалить этого пользователя?");
            if(isDelete){
                let user_id = $(this).val();

                $.ajax({
                    method: 'POST',
                    url: '../php/delete.php',
                    data: {
                        action: 'delete_user',
                        user_id: user_id
                    },
                    success: function(response){
                        if(response.status){
                            show_all_users();
                        } else {
                            console.log(response.msg);
                        }
                    }
                });
            }
        });

        // ДОБАЛЕНИЕ ПОЛЕЙ В МОДАЛЬНОЕ ОКНО ДЛЯ РЕДАКТИРОВАНИЯ ЗАДАЧИ В ПРОЕКТЕ
        $(document).on('click', '.js-edit-user-btn', function(event){
            let user_id = $(this).val();

            $.ajax({
                method: 'POST',
                url: '../php/modal.php',
                data: {
                    modal: 'edit_user',
                    user_id: user_id
                },
                success: function(response){
                    $('.js-edit-user-modal-body').empty().append(response);
                }
            });


        });

        // РЕДАКТИРОВАНИЕ ИНФОРМАЦИИ О ПОЛЬЗОВАТЕЛЕ
        // Получение изображения с поля
        let user_avatar = false;
        $(document).on('change', 'input[name="user_avatar"]', function(e){
            user_avatar = e.target.files[0];
            console.log(user_avatar);
        });

        $('.js-edit-user-submit-btn').on('click', function(event){
            event.preventDefault();

            let user_id = $("input[name='user_id']").val();
            let user_full_name = $("input[name='user_full_name']").val();
            let user_login = $("input[name='user_login']").val();
            let user_email = $("input[name='user_email']").val();
            let user_password = $("input[name='user_password']").val();
            let user_status = $("select[name='user_status']").val();


            
            let formData = new FormData();
            formData.append('action', 'edit_user_data');
            formData.append('user_id', user_id);
            formData.append('user_full_name', user_full_name);
            formData.append('user_login', user_login);
            formData.append('user_email', user_email);
            formData.append('user_avatar', user_avatar);
            formData.append('user_status', user_status);
            formData.append('user_password', user_password);

            if(user_avatar){
                formData.append('is_change_photo', 'true');
            } else if(!user_avatar){
                formData.append('is_change_photo', 'false');
            }

            console.log(user_avatar);

            $.ajax({
                method: 'POST',
                url: '../php/edit.php',
                dataType: 'json',
                processData: false, // Не обрабатываем файлы
                contentType: false, // Так jQuery скажет серверу, что это строковый запрос
                cache: false,
                data: formData,
                success: function(response){
                    if(response.status){
                        //console.log(response.msg);
                        $('.js-edit-user-close-btn').trigger('click');
                        show_all_users();
                        
                    } else {
                        console.log(response.msg);
                    }
                    
                }
            });
            
            
        });
    </script>
</body>
</html>