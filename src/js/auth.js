
// Авторизация
$('.login-btn').click(function(e){
    e.preventDefault();

    $('input').removeClass("is-invalid");

    let login = $('input[name="login"]').val();
    let password = $('input[name="password"]').val();

    $.ajax({
        url: 'php/signin.php',
        type: 'POST',
        dataType: 'json',
        data: {
            login: login,
            password: password
        },
        success (data){
            if(data.status) {
                document.location.href = 'templates/main.php';
            } else {

                if(data.type === 1) {
                    data.fields.forEach(field => {
                        $(`input[name="${field}"]`).addClass("is-invalid");
                    });
                }

                $('.auth-error').removeClass('none').text(data.message);
            }
            
        }
    });
});


