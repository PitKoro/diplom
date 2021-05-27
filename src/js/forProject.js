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
            $('.js-edit-project-btn').attr('value', response.project_id);
        }
    });



    let tasksBtnClasses = document.querySelector(".js-project-tasks-btn").classList;
    let usersBtnClasses = document.querySelector(".js-project-users-btn").classList;
    let filesBtnClasses = document.querySelector(".js-project-files-btn").classList;

    if((tasksBtnClasses['value'].indexOf('active') != -1) && (usersBtnClasses['value'].indexOf('active') == -1) && (filesBtnClasses['value'].indexOf('active') == -1)){
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
    $("#add-to-project-btn").attr('class', 'btn btn-success js-add-project-task-btn');
    $(".js-project-users-btn").removeClass('active');
    $(".js-project-tasks-btn").addClass('active');
    $(".js-project-files-btn").removeClass('active');
    
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


// ВЫВОД ТАБЛИЦЫ С ПОЛЬЗОВАТЕЛЯМИ ПРОЕКТА
$(".js-project-users-btn").on('click', function(event){
    event.preventDefault();
    $("#test").attr("class", "float-end add-users-btn-block");
    $('#add-to-project-btn').attr('data-bs-target','#add-project-user-modal');
    let project_id = $('input[name="project_id"]').val();
    $(".js-project-tasks-btn").removeClass('active');
    $(".js-project-files-btn").removeClass('active');
    $(".js-project-users-btn").addClass('active');
    $("#add-to-project-btn").attr('class', 'btn btn-success js-add-project-user-btn');

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

// ВЫВОД ТАБЛИЦЫ С ФАЙЛАМИ ПРОЕКТА
$(".js-project-files-btn").on('click', function(event){
    event.preventDefault();
    let project_id = $('input[name="project_id"]').val();

    $("#test").attr("class", "float-end add-file-btn-block");
    $('#add-to-project-btn').attr('data-bs-target','#add-project-file-modal');
    $(".js-project-tasks-btn").removeClass('active');
    $(".js-project-users-btn").removeClass('active');
    $(".js-project-files-btn").addClass('active');
    $("#add-to-project-btn").attr('class','btn btn-success js-add-project-file-btn');

    $.ajax({
        method: 'POST',
        url: '../php/get_db_table.php',
        data: {
            show: 'project_files',
            project_id: project_id
        },
        success: function(response){
            $(".js-project-table").empty().append(response);
        }
    });
});


// ДОБАЛЕНИЕ ПОЛЕЙ В МОДАЛЬНОЕ ОКНО ДЛЯ ДОБАВЛЕНИЯ ПОЛЬЗОВАТЕЛЕЙ В ПРОЕКТ
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


// ДОБАЛЕНИЕ ПОЛЕЙ В МОДАЛЬНОЕ ОКНО ДЛЯ ДОБАВЛЕНИЯ ЗАДАЧИ В ПРОЕКТ
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


// ДОБАЛЕНИЕ ПОЛЕЙ В МОДАЛЬНОЕ ОКНО ДЛЯ ДОБАВЛЕНИЯ ФАЙЛА В ПРОЕКТ
$(document).on('click','.add-file-btn-block', function(){
    let project_id = $('input[name="project_id"]').val();
    $.ajax({
        method: 'POST',
        data:{
            modal: 'add_file',
            project_id: project_id
        },
        url:'../php/modal.php',
        success: function(response){
            
            $('.js-add-file-to-project-modal-body').empty().append(response);
        }
    });
});


// ДОБАЛЕНИЕ ПОЛЕЙ В МОДАЛЬНОЕ ОКНО ДЛЯ РЕДАКТИРОВАНИЯ ИНФОРМАЦИИ О ПРОЕКТЕ
$('.js-edit-project-data-btn').on('click', function(event){
    let project_id = $('input[name="project_id"]').val();
    event.preventDefault();
    $.ajax({
        method: 'POST',
        url: '../php/modal.php',
        data: {
            modal: 'edit_project_data',
            project_id: project_id
        },
        success: function(response){
            $('.js-edit-project-data-modal-body').empty().append(response);
        }
    });

});

// ДОБАЛЕНИЕ ПОЛЕЙ В МОДАЛЬНОЕ ОКНО ДЛЯ РЕДАКТИРОВАНИЯ ЗАДАЧИ В ПРОЕКТЕ
$(document).on('click', '.js-edit-project-task-btn', function(event){
    let project_id = $('input[name="project_id"]').val();
    let task_id = $(this).val();

    $.ajax({
        method: 'POST',
        url: '../php/modal.php',
        data: {
            modal: 'edit_project_task',
            task_id: task_id,
            project_id: project_id
        },
        success: function(response){
            //console.log(response);
            $('.js-edit-project-task-modal-body').empty().append(response);
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
                $('.js-add-project-user-close-btn').trigger('click');

            } else {
                $('.auth-error').removeClass('none').text(response.message);
            }
            
        }
    });
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


// ДОБАВЛЕНИЕ ФАЙЛА В ПРОЕКТ ПОСЛЕ НАЖАТИЯ НА КНОПКУ "ДОБАВИТЬ" В МОДАЛЬНОМ ОКНЕ
// Получение файла с поля
let project_file = false;

$(document).on('change', 'input[name="project_file"]', function(e){
    project_file = e.target.files[0];
    console.log(project_file);
});

$(".js-add-project-file-submit-btn").on('click', function(event){
    event.preventDefault();
    let project_id = $('input[name="project_id"]').val();

    let formData = new FormData();
    formData.append('add', 'project_file');
    formData.append('project_file', project_file);
    formData.append('project_id', project_id);

    $.ajax({
        url: '../php/add_to_project.php',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        success: function(response){
            if(response.status){
                //console.log(response.msg);
                $('.js-add-project-file-close-btn').trigger('click');
                $('.js-project-files-btn').trigger('click');
            } else {
                console.log(response.msg);
            }
        }
    });
});


// УДАЛЕНИЕ ЗАДАЧИ ИЗ ПРОЕКТА
$('.js-project-table').on('click', '.js-delete-project-task-btn', function(event){
    event.preventDefault();
    let isDelete = confirm("Вы точно хотите удалить эту задачу?");
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
                    //console.log(response.msg);
                    $(".js-project-tasks-btn").trigger('click');                                
                } else {
                    console.log(response.msg);
                }
            }
        });
    }


});

// ПЕРВАЯ КНОПКА ВЫПОЛНЕНИЯ ЗАДАЧИ
$('.js-project-table').on('click', '.js-add-comment-to-project-task-btn', function(){
    let task_id = $(this).val();
    $(`#add-comment-to-project-task-${task_id}`).attr('hidden', true);
    $(`#done-project-task-btn-${task_id}`).removeAttr('hidden');
    $(`#close-done-project-task-btn-${task_id}`).removeAttr('hidden');
    $(`textarea[name="task${task_id}_comment"]`).removeAttr('hidden');
});


// ВТОРАЯ КНОПКА ВЫПОЛНЕНИЯ ЗАДАЧИ
$('.js-project-table').on('click', '.js-done-project-task-btn', function(event){
    event.preventDefault();
    let project_id = $('input[name="project_id"]').val();
    let task_id = $(this).val();
    let task_comment = $(`textarea[name="task${task_id}_comment"]`).val();

    $.ajax({
        method: 'POST',
        url: '../php/edit.php',
        data: {
            action: 'complete_project_task',
            project_id: project_id,
            task_id: task_id,
            task_comment: task_comment
        },
        success: function(response){
            if(response.status){
                $(".js-project-tasks-btn").trigger('click');
                $('.js-project-table').remove('.js-done-project-task-btn');
                //console.log(response.msg);  
            } else {
                console.log(response.msg);
            }
        }
    });
});

// КНОПКА ЗАКРЫВАЮЩАЯ КНОПКУ ВЫПОЛНЕНИЯ ЗАДАЧИ
$('.js-project-table').on('click', '.js-close-done-project-task-btn', function(event){
    let task_id = $(this).val();
    $(`#add-comment-to-project-task-${task_id}`).removeAttr('hidden');
    $(`#close-done-project-task-btn-${task_id}`).attr('hidden', true);
    $(`#done-project-task-btn-${task_id}`).attr('hidden', true);
    $(`textarea[name="task${task_id}_comment"]`).attr('hidden', true);
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
                    //console.log(response.msg);
                    $('.js-project-users-btn').trigger('click');
                } else {
                    console.log(response.msg);
                }
            }
        });
    }
});


// УДАЛЕНИЕ ФАЙЛА ИЗ ПРОЕКТА
$('.js-project-table').on('click', '.js-delete-project-file-btn', function(event){
    event.preventDefault();
    let isDelete = confirm("Вы точно хотите удалить этот файл из проекта?");
    if(isDelete){
        //let project_id = $('input[name="project_id"]').val();
        let file_id = $(this).val();

        $.ajax({
            method: 'POST',
            url: '../php/delete.php',
            data: {
                action: 'delete_project_file',
                //project_id: project_id,
                file_id: file_id
            },
            success: function(response){
                if(response.status){
                    //console.log(response.msg);
                    $('.js-project-files-btn').trigger('click');
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
                    //console.log(response.msg);
                    document.location.href = './myProjects.php';                            
                } else {
                    console.log(response.msg);
                }
                
            }
        });
    }
    
});


// РЕДАКТИРОВАНИЕ ИНФОРМАЦИИ О ПРОЕКТЕ
// Получение изображения с поля
let project_photo = false;
$(document).on('change', 'input[name="project_photo"]', function(e){
    project_photo = e.target.files[0];
    console.log(project_photo);
});

$('.js-edit-project-data-submit-btn').on('click', function(event){
    event.preventDefault();



    let project_id = $("input[name='project_id']").val();
    let project_name = $("input[name='project_name']").val();
    let project_description = $("input[name='project_description']").val();
    let project_address = $("input[name='project_address']").val();
    let project_start_date = $("input[name='project_start_date']").val();
    let project_end_date = $("input[name='project_end_date']").val();

    
    let formData = new FormData();
    formData.append('action', 'edit_project_data');
    formData.append('project_id', project_id);
    formData.append('project_name', project_name);
    formData.append('project_description', project_description);
    formData.append('project_address', project_address);
    formData.append('project_photo', project_photo);
    formData.append('project_start_date', project_start_date);
    formData.append('project_end_date', project_end_date);

    if(project_photo){
        formData.append('is_change_photo', 'true');
    } else if(!project_photo){
        formData.append('is_change_photo', 'false');
    }

    console.log(project_photo);

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
                $('.js-edit-project-data-close-btn').trigger('click');
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
                        $('.js-edit-project-btn').attr('value', response.project_id);
                    }
                });
                
            } else {
                console.log(response.msg);
            }
            
        }
    });
    
    
});

//РЕДАКТИРОВАНИЕ ЗАДАЧИ В ПРОЕКТЕ
$('.js-edit-project-task-submit-btn').on('click', function(event){
    let task_id = $('input[name="edit_task_id"]').val();
    let task_name = $('input[name="edit_task_name"]').val();
    let task_status = $('select[name="edit_task_status"]').val();
    let task_end_date = $('input[name="edit_task_end_date"]').val();
    let task_user = $('select[name="edit_task_user"]').val();

    $.ajax({
        method: 'POST',
        url: '../php/edit.php',
        data: {
            action: 'edit_project_task',
            task_id: task_id,
            task_name: task_name,
            task_status: task_status,
            task_end_date: task_end_date,
            task_user: task_user
        },
        success: function(response){
            if(response.status){
                //console.log(response.msg);
                $('.js-edit-project-task-close-btn').trigger('click');
                $('.js-project-tasks-btn').trigger('click');
            }
        }
        
    });
});