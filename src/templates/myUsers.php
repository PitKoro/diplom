<?php
session_start();
if (!$_SESSION['user']) {
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
 
    <script src="../../src/js/forMyUsers.js"></script>
    
</body>
</html>