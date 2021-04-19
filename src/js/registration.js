// Получение изображения с поля
let avatar = false;

$('input[name="avatar"]').change(e => {
    avatar = e.target.files[0];
    console.log(avatar);
});


// Регистрация
$('.register-btn').click(function(e){
    e.preventDefault();

    $('input').removeClass("is-invalid");
    $('select').removeClass("is-invalid");

    let login = $('input[name="login"]').val();
    let password = $('input[name="password"]').val();
    let full_name = $('input[name="full_name"]').val();
    let email = $('input[name="email"]').val();
    let password_confirm = $('input[name="password_confirm"]').val();
    let user_status = $('select[name="user_status"]').val();

    let formData = new FormData();
    formData.append('login', login);
    formData.append('password', password);
    formData.append('password_confirm', password_confirm);
    formData.append('full_name', full_name);
    formData.append('email', email);
    formData.append('avatar', avatar);
    formData.append('user_status', user_status);

    $.ajax({
        url: '../php/signup.php',
        type: 'POST',
        dataType: 'json',
        processData: false, // Не обрабатываем файлы
        contentType: false, // Так jQuery скажет серверу, что это строковый запрос
        cache: false,
        data: formData,
        success (data){
            if(data.status) {
                document.location.href = '../index.php';
            } else {
                if(data.type === 1) {
                    data.fields.forEach(field => {
                        if(field === 'user_status'){
                            $(`select[name="${field}"]`).addClass("is-invalid");
                        } else {
                            $(`input[name="${field}"]`).addClass("is-invalid");
                        }
                    });
                }
                $('.auth-error').removeClass('none').text(data.message);
            }
        }
    });
});