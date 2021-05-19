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
    </script>
</body>
</html>