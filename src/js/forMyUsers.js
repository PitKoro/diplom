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