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
                        <?if($_SESSION['user']['status']=='10'): ?>
                            <div class="card-body">
                                <a href="#" class="btn btn-primary">Редактировать</a>
                                <button class="btn btn-danger js-delete-project-btn" value="" >Удалить проект</button>
                            </div>
                        <?endif;?>
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
            $("[data-bs-toggle='popover']").popover();

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
                    $('.js-delete-project-btn').attr('value', response.project_id);
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
                        start_all_tooltip();
                    }
                });
            }
        });


        // ВЫВОД ТАБЛИЦЫ С ЗАДАЧАМИ ПРОЕКТА
        $(".js-project-tasks-btn").on('click', function(event){

            $("#test").attr("class", "float-end add-task-btn-block");
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
                        start_all_tooltip();
                    }
            });
        });


        //ОЧИСТКА ОШИБОК ПРИ НАЖАТИИ НА КНОПКУ "ДОБАВИТЬ" НАД ТАБЛИЦЕЙ С ЗАДАЧАМИ ПРОЕКТА
        $('.js-add-project-task-btn').on('click', function(){
            $('input[name="task_name"]').removeClass("is-invalid");
            $('input[name="task_end_date"]').removeClass("is-invalid");
        });


        // ДОБАВЛЕНИЕ ЗАДАЧИ В ПРОЕКТ ПОСЛЕ НАЖАТИЯ НА КНОПКУ "ДОБАВИТЬ" В МОДАЛЬНОМ ОКНЕ
        $(".js-add-project-task-submit-btn").on('click', function(event){
            event.preventDefault();
            $('input').removeClass("is-invalid");
            $('select').removeClass("is-invalid");
            
            let project_id = $('input[name="project_id"]').val();
            let task_name = $('input[name="task_name"]').val();
            let user_id = $('select[name=task_user]').val();
            let end_date = $('input[name="task_end_date"]').val();

            $.ajax({
                method: 'POST',
                url: '../php/add_to_project.php',
                data: {
                    add: 'task',
                    project_id: project_id,
                    task_name: task_name,
                    user_id: user_id,
                    end_date: end_date
                },
                success: function(response){
                    if(response.status){
                        
                        console.log(response.message);
                        $(".js-project-tasks-btn").trigger('click');                        
                        $('input[name="task_name"]').val('');
                        $('.js-add-project-task-close-btn').trigger('click');
                    } else {
                        if(response.type === 1) {
                            response.fields.forEach(field => {
                                if(field === 'task_user'){
                                    $(`select[name="${field}"]`).addClass("is-invalid");
                                } else {
                                    $(`input[name="${field}"]`).addClass("is-invalid");
                                }
                            });
                        }
                    }

                }
            })
        });


        // ВЫВОД ТАБЛИЦЫ С ПОЛЬЗОВАТЕЛЯМИ ПРОЕКТА
        $(".js-project-users-btn").on('click', function(event){
            event.preventDefault();
            $("#test").attr("class", "float-end add-users-btn-block");
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

        $(document).on('click','.add-users-btn-block', function(){
            $('.auth-error').addClass('none')
            let project_id = $('input[name="project_id"]').val();
            $.ajax({
                method: 'POST',
                data:{
                    modal: 'add_users',
                    project_id: project_id
                },
                url:'../php/modal.php',
                success: function(response){
                    if(response.status){
                        $('.js-add-users-to-project-modal-body').empty().append(response.html);
                    } else {
                        $('.js-add-users-to-project-modal-body').empty().append(response.html);
                        $('.js-add-users-to-project-modal-footer').empty();
                    }
                    
                }
            });
        });

        $(document).on('click','.add-task-btn-block', function(){
            $("select option[value='']").prop("selected", true);
            let project_id = $('input[name="project_id"]').val();
            $.ajax({
                method: 'POST',
                data:{
                    modal: 'add_task',
                    project_id: project_id
                },
                url:'../php/modal.php',
                success: function(response){
                    
                    $('.js-add-task-modal-body').empty().append(response);
                }
            });
        });


        // ДОБАВЛЕНИЕ ПОЛЬЗОВАТЕЛЯ В ПРОЕКТ ПОСЛЕ НАЖАТИЯ НА КНОПКУ "ДОБАВИТЬ" В МОДАЛЬНОМ ОКНЕ
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
                success: function(response){
                    if(response.status){

                        $(".js-project-users-btn").trigger('click');
                        // $.ajax({
                        //     method: 'POST',
                        //     url: '../php/get_db_table.php',
                        //     data: {
                        //         show: 'project_users',
                        //         project_id: project_id
                        //     },
                        //     success: function(response){
                        //         $(".js-project-table").empty().append(response);
                        //     }
                        // });

                        $('.js-add-project-user-close-btn').trigger('click');
                    } else {
                        $('.auth-error').removeClass('none').text(response.message);
                    }
                    
                }
            });
        });

        // $('.allow-focus').on('click', function (e) {
        //     e.stopPropagation();
        // });
        

        // УДАЛЕНИЕ ЗАДАЧИ ИЗ ПРОЕКТА
        $('.js-project-table').on('click', '.js-delete-project-task-btn', function(event){
            event.preventDefault();
            let isDelete = confirm("Вы точно хотите удалить эту задачу'?");
            if(isDelete){
                let project_id = $('input[name="project_id"]').val();
                let task_id = $(this).val();

                $.ajax({
                    method: 'POST',
                    url: '../php/delete.php',
                    data: {
                        action: 'delete_project_task',
                        project_id: project_id,
                        task_id: task_id
                    },
                    success: function(response){
                        if(response.status){
                            console.log(response.msg);

                            $(".js-project-tasks-btn").trigger('click');

                                
                        } else {
                            console.log(response.msg);
                        }
                    }
                });
            }


        });


        // КНОПКА ВЫПОЛНЕНИЯ ЗАДАЧИ
        $('.js-project-table').on('click', '.js-done-project-task-btn', function(event){
            event.preventDefault();
            let project_id = $('input[name="project_id"]').val();
            let task_id = $(this).val();

            $.ajax({
                method: 'POST',
                url: '../php/edit.php',
                data: {
                    action: 'complete_project_task',
                    project_id: project_id,
                    task_id: task_id
                },
                success: function(response){
                    if(response.status){
                        $(".js-project-tasks-btn").trigger('click');
                        $('.js-project-table').remove('.js-done-project-task-btn');
                        console.log(response.msg);  
                    } else {
                        console.log(response.msg);
                    }
                }
            });
        });


        // УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ ИЗ ПРОЕКТА
        $('.js-project-table').on('click', '.js-delete-project-user-btn', function(event){
            event.preventDefault();
            let isDelete = confirm("Вы точно хотите удалить этого пользователя?");
            if(isDelete){
                let project_id = $('input[name="project_id"]').val();
                let user_id = $(this).val();

                $.ajax({
                    method: 'POST',
                    url: '../php/delete.php',
                    data: {
                        action: 'delete_project_user',
                        project_id: project_id,
                        user_id: user_id
                    },
                    success: function(response){
                        if(response.status){
                            console.log(response.msg);
                            $('.js-project-users-btn').trigger('click');
                        } else {
                            console.log(response.msg);
                        }
                    }
                });
            }
        });


        // УДАЛЕНИЕ ПРОЕКТА
        $('.js-delete-project-btn').on('click', function(event){
            event.preventDefault();
            let isDelete = confirm("Вы точно хотите удалить проект?");
            if(isDelete){
                let project_id = $(this).val();
                console.log(project_id);

                $.ajax({
                    method: 'POST',
                    url: '../php/delete.php',
                    data: {
                        action: 'delete_project',
                        project_id: project_id
                    },
                    success: function(response){
                        if(response.status){
                            console.log(response.msg);
                            document.location.href = './myProjects.php';
                            
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