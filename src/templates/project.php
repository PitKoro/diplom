<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
include '../php/connect.php';
include '../php/lib.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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

    <title>Проект</title>

</head>
<body>

<?echo"<input name='project_id' value='{$_GET['id']}' hidden>";?>
<?echo"<input name='user_id' value='{$_SESSION['user']['id']}' hidden>";?>
<?echo"<input name='user_full_name' value='{$_SESSION['user']['full_name']}' hidden>";?>
<?echo"<input name='user_login' value='{$_SESSION['user']['login']}' hidden>";?>

    <? require "blocks/navbar.php"; ?>
    <div class="container mt-5">
        <div class="row gy-0">
            <div class="col-sm-3">
                <div class="col mb-5">
                    <div class="card">
                        <img src="" class="card-img-top js-project-photo p-2">
                        <div class="card-body">
                            <h4 class="card-title js-project-name">Card title</h4>
                            <p class="card-text js-project-description">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        </div>
                        <ul class="list-group list-group-flush px-3">
                            <label for="project-start-date">Дата начала</label><li id="project-start-date" class="list-group-item js-project-start-date"><span>An item</span></li>
                            <label for="project-end-date">Дата окончания</label><li id="project-end-date" class="list-group-item js-project-end-date">A second item</li>
                        </ul>
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
                                <button type="button" class="btn btn-outline-primary js-project-users-btn">Пользователи</button>
                                <button type="button" class="btn btn-outline-primary js-project-files-btn">Файлы</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col">
                        <?if($_SESSION['user']['status']=='10'): ?>
                            <div id="test" class="float-end add-task-btn-block" style="height: 38px; width: 97px;">
                                <button class="btn btn-success js-add-project-task-btn" id="add-to-project-btn" data-bs-toggle='modal' data-bs-target='#add-project-task-modal'>Добавить</button>
                            </div>
                        <?endif;?>
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

    <!-- Modal for project task chat-->
    <div class="modal fade" id="project-task-chat-modal" tabindex="-1" aria-labelledby="project-task-chat-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="project-task-chat-modal-label">Чат задачи</h5>
                    <button type="button" id="project-task-chat-modal-close-btn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="js-project-task-chat-modal-body">
                        <div class='chat'>
                            <div class='form-body mb-3'>
                                <div class='' id='messages' style="overflow-y: scroll; height:200px;">
                                    Загрузка...
                                </div>
                            </div>
                            <div class='row g-0 justify-content-center chat-input'>
                                <div class="col-8 me-2">
                                    <input type='text' id='message-text' class='chat-form__input form-control' placeholder='Введите сообщение'>
                                    <div class="invalid-feedback">
                                        Введите сообщение
                                    </div>
                                </div>
                                <div class="col-2"><button class='chat-form__submit btn btn-primary' value=''><i class="fas fa-paper-plane"></i></button></div>
                                <input type='text' id='task-id' value='' hidden>
                                
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->


    <!-- Modal for add task -->
    <div class="modal fade" id="add-project-task-modal" tabindex="-1" aria-labelledby="add-project-task-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-project-task-modal-label">Добавление задачи</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-body js-add-task-modal-body">

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger js-add-project-task-close-btn" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success js-add-project-task-submit-btn">Добавить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal for add user -->
    <div class="modal fade" id="add-project-user-modal" tabindex="-1" aria-labelledby="add-project-user-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-project-user-modal-label">Добавление пользователя</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="auth-error mb-3 none "></div>
                    <div class="form-body js-add-users-to-project-modal-body">

                    </div>

                </div>
                <div class="modal-footer justify-content-between js-add-users-to-project-modal-footer">
                    <button type="button" class="btn btn-danger js-add-project-user-close-btn" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success js-add-project-user-submit-btn">Добавить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal for add file -->
    <div class="modal fade" id="add-project-file-modal" tabindex="-1" aria-labelledby="add-project-file-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-project-file-modal-label">Добавление файла</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-body js-add-file-to-project-modal-body">
                            <h1>тут добавление файлов</h1>
                    </div>

                </div>
                <div class="modal-footer justify-content-between js-add-file-to-project-modal-footer">
                    <button type="button" class="btn btn-danger js-add-project-file-close-btn" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success js-add-project-file-submit-btn">Добавить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal for edit project data -->
    <div class="modal fade" id="edit-project-data-modal" tabindex="-1" aria-labelledby="edit-project-data-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-project-data-modal-label">Редактирование проекта</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-body js-edit-project-data-modal-body">

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger js-edit-project-data-close-btn" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success js-edit-project-data-submit-btn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    
    <!-- Modal for edit project task -->
    <div class="modal fade" id="edit-project-task-modal" tabindex="-1" aria-labelledby="edit-project-task-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-project-task-modal-label">Редактирование задачи</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-body js-edit-project-task-modal-body">

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger js-edit-project-task-close-btn" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success js-edit-project-task-submit-btn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/popper/popper.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../src/js/chat.js"></script>
    
    <script src="../../src/js/forProject.js"></script>

</body>
</html>