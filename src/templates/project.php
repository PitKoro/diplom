<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
include '../php/connect.php';
include '../php/lib.php';
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

    <title>Проект</title>

</head>
<body>
    <?echo"<input name='project_id' value='{$_GET['id']}' hidden>";?>
    <? require "blocks/navbar.php"; ?>

    <div class="container mt-5">
        <div class="row gy-0">
            <div class="col-sm-4">
                <div class="col mb-5">
                    <div class="card">
                        <img src="" class="card-img-top js-project-photo">
                        <div class="card-body">
                            <h4 class="card-title js-project-name">Card title</h4>
                            <p class="card-text js-project-description">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <label for="project-start-date">Дата начала</label><li id="project-start-date" class="list-group-item js-project-start-date"><span>An item</span></li>
                            <label for="project-end-date">Дата окончания</label><li id="project-end-date" class="list-group-item js-project-end-date">A second item</li>
                        </ul>
                        <div class="card-body">
                            <a href="#" class="card-link">Редактировать</a>
                            <a href="#" class="card-link">Удалить проект</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-8">

                <div class="row mb-2">
                    <div class="col">
                        <div class="mx-auto" style="width:210px;">
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <button type="button" class="btn btn-outline-primary js-project-tasks-btn active">Задачи</button>
                                <button type="button" class="btn btn-outline-primary js-project-users-btn">Пользователи</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col">
                        <div class="test">
                            <button class="btn btn-success float-end js-add-project-task-btn" id="add-to-project-btn" data-bs-toggle='modal' data-bs-target='#add-project-task-modal'>Добавить</button>
                        </div>
                        
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover pl-2 pr-2 text-center js-project-table">

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    
    <!-- Modal for add task -->
    <div class="modal fade" id="add-project-task-modal" tabindex="-1" aria-labelledby="add-project-task-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-project-task-modal-label">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-body">
                        <label for="task-name-field" class="form-label">Название</label>
                        <input type="text" name="task_name" class="form-control mb-3" id="task-name-field" aria-describedby="validation_project_task_name" placeholder="Введите название задачи">
                        <div class="invalid-feedback" id="validation_project_task_name">Пожалуйста, введите название для задачи.</div>
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
                    <h5 class="modal-title" id="add-project-user-modal-label">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-body js-add-users-modal-body">
                    <?
                        // $project_id = $_GET['id'];
                        // $users_out_project = get_all_users_not_participating_in_the_project($connect, $project_id);

                        // for($i = 0; $i < count($users_out_project); $i++){
                        //     echo "
                        //     <div class='form-check'>
                        //         <input class='form-check-input' type='checkbox' name='user_id[]' value='{$users_out_project[$i]['id']}' id='check-user-{$users_out_project[$i]['id']}'>
                        //         <label class='form-check-label' for='check-user-{$users_out_project[$i]['id']}'>
                        //             {$users_out_project[$i]['login']} | {$users_out_project[$i]['full_name']} 
                        //         </label>
                        //     </div>
                        //     ";
                        // }
                    ?>


                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger js-add-project-user-close-btn" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success js-add-project-user-submit-btn">Добавить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            let project_id = $('input[name="project_id"]').val();

            $.ajax({
                method: 'POST',
                url: '../php/get_db_table.php',
                data: {
                    show: 'project_data',
                    project_id: project_id
                },
                success: function(response){
                    $('.js-project-photo').attr('src', `../../${response.project_photo}`);
                    $('.js-project-name').empty().append(response.project_name);
                    $('.js-project-description').empty().append(response.project_description==='' ? 'Описание не указано' : response.project_description);
                    $('.js-project-start-date').empty().append(response.project_start_date);
                    $('.js-project-end-date').empty().append(response.project_end_date);
                }
            });

            let tasksBtnClasses = document.querySelector(".js-project-tasks-btn").classList;
            let usersBtnClasses = document.querySelector(".js-project-users-btn").classList;

            if((tasksBtnClasses['value'].indexOf('active') != -1) && (usersBtnClasses['value'].indexOf('active') == -1)){
                $.ajax({
                    method: 'POST',
                    url: '../php/get_db_table.php',
                    data: {
                        show: 'project_tasks',
                        project_id: project_id
                    },
                    success: function(response){
                        $(".js-project-table").empty().append(response);
                    }
                });
            }
        });

        $(".js-project-tasks-btn").on('click', function(event){
            event.preventDefault();

            $('#add-to-project-btn').attr('data-bs-target','#add-project-task-modal');
            $("#add-to-project-btn").removeClass('js-add-project-user-btn').addClass('js-add-project-task-btn');
            $(".js-project-users-btn").removeClass('active');
            $(".js-project-tasks-btn").addClass('active');
            

            let project_id = $('input[name="project_id"]').val();

            $.ajax({
                    method: 'POST',
                    url: '../php/get_db_table.php',
                    data: {
                        show: 'project_tasks',
                        project_id: project_id
                    },
                    success: function(response){
                        $(".js-project-table").empty().append(response);
                    }
            });
        });

        $('.js-add-project-task-btn').on('click', function(){
            $('input[name="task_name"]').removeClass("is-invalid"); 
        });

        $(".js-add-project-task-submit-btn").on('click', function(event){
            event.preventDefault();
            
            let project_id = $('input[name="project_id"]').val();
            let task_name = $('input[name="task_name"]').val();

            $('input[name="task_name"]').val('');

            $.ajax({
                method: 'POST',
                url: '../php/add_to_project.php',
                data: {
                    add: 'task',
                    project_id: project_id,
                    task_name: task_name
                },
                success: function(response){
                    if(response.status){
                        console.log(response.message);
                        $.ajax({
                            method: 'POST',
                            url: '../php/get_db_table.php',
                            data: {
                                show: 'project_tasks',
                                project_id: project_id
                            },
                            success: function(response){
                                $(".js-project-table").empty().append(response);
                            }
                        });

                        $('.js-add-project-task-close-btn').trigger('click');
                    } else {
                        if(response.type === 1) {
                            response.fields.forEach(field => {
                                $(`input[name="${field}"]`).addClass("is-invalid");                        
                            });
                        }
                    }

                }
            })
        });

        $(".js-project-users-btn").on('click', function(event){
            event.preventDefault();
            $('#add-to-project-btn').attr('data-bs-target','#add-project-user-modal');
            let project_id = $('input[name="project_id"]').val();
            $(".js-project-tasks-btn").removeClass('active');
            $(".js-project-users-btn").addClass('active');
            $("#add-to-project-btn").removeClass('js-add-project-task-btn').addClass('js-add-project-user-btn');

            $.ajax({
                method: 'POST',
                url: '../php/get_db_table.php',
                data: {
                    show: 'project_users',
                    project_id: project_id
                },
                success: function(response){
                    $(".js-project-table").empty().append(response);
                }
            });
        });

        $('.test').on('click', function(){
            let project_id = $('input[name="project_id"]').val();
            $.ajax({
                method: 'POST',
                data:{
                    modal: 'add_users',
                    project_id: project_id
                },
                url:'../php/modal.php',
                success: function(response){
                    $('.js-add-users-modal-body').empty().append(response);
                }
            });
        });


        $('.js-add-project-user-submit-btn').on('click', function(){
            let project_id = $('input[name="project_id"]').val();

            //создаём массив для значений флажков
            var checked_users_id = [];
            $('input:checkbox:checked').each(function(){
                //добавляем значение каждого флажка в этот массив
                checked_users_id.push(this.value);
            });
            /*объединяем массив в строку с разделителем-запятой. Но лучше подобные вещи хранить в массиве. Для наглядности - вывод в консоль.*/
            checked_users_id = checked_users_id.join(',');

            $.ajax({
                method: 'POST',
                url: '../php/add_to_project.php',
                data: {
                    add: 'users',
                    users_id: checked_users_id,
                    project_id: project_id
                },
                success: function(){
                    $.ajax({
                        method: 'POST',
                        url: '../php/get_db_table.php',
                        data: {
                            show: 'project_users',
                            project_id: project_id
                        },
                        success: function(response){
                            $(".js-project-table").empty().append(response);
                        }
                    });

                    $('.js-add-project-user-close-btn').trigger('click');
                }
            });
        });

        // $('.allow-focus').on('click', function (e) {
        //     e.stopPropagation();
        // });


    </script>

</body>
</html>