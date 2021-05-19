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

    <title>Профиль</title>

</head>
<body>
    <? require "blocks/navbar.php"; ?>

    <div class="container mt-5">
        <div class="row gy-0">
            <div class="col-sm-3">
                <div class="col mb-5">
                    <div class="card">
                        <img class="rounded mx-auto my-1 d-block" src="<?= '../../'.$_SESSION['user']['avatar'] ?>" width="200" alt="">
                        <div class="card-body">
                            <?echo"<input name='user_id' value='{$_SESSION['user']['id']}' hidden>";?>
                            <h4 class="card-title js-project-name"><?=$_SESSION['user']['full_name']?></h4>
                            <p class="card-text js-project-description">Логин: <?=$_SESSION['user']['login']?></p>
                            <p class="card-text js-project-description">Почта: <?=$_SESSION['user']['email']?></p>
                            <p class="card-text js-project-description">Роль: <? echo ($_SESSION['user']['status']=='10') ? "Администратор":"Обычный пользователь"; ?></p>
                        </div>
                        <?if($_SESSION['user']['status']=='10'): ?>
                            <div class="card-body text-center">
                            <button style='width: 42px' class="btn btn-danger js-delete-project-btn" value="" title="Удалить проект"><i class="fas fa-trash-alt"></i></button>
                                <button style='width: 42px' class="btn btn-warning js-edit-project-data-btn" value="" data-bs-toggle='modal' data-bs-target='#edit-project-data-modal' title="Редактировать проект"><i class="fas fa-edit"></i></button>
                            </div>
                        <?endif;?>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-9">

                <div class="row mb-2">
                    <div class="col">
                        <div class="mx-auto" style="width:280px;">
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <button type="button" class="btn btn-outline-primary js-project-tasks-btn active">Задачи</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col">
                        <div class="table-responsive">
                            <div class="fixed-height-table">
                                <table class="table table-bordered table-hover pl-2 pr-2 text-center js-project-table">

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
        function start_all_tooltip(){
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }
        $(document).ready(function(){
            let user_id = $("input[name='user_id']").val();
            $.ajax({
                method: 'POST',
                url: '../php/get_db_table.php',
                data: {
                    show: 'all_user_tasks',
                    user_id: user_id
                },
                success: function(response){
                    $(".js-project-table").empty().append(response);
                    start_all_tooltip();
                }
            });
        });
    </script>
</body>
</html>