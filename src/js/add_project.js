// Создание нового проекта
// Получение изображения с поля
let project_photo = false;

$('input[name="photo"]').change(e => {
    project_photo = e.target.files[0];
    console.log(project_photo);
});


$('.add-project-btn').click(function(e){
    e.preventDefault();

    $('input').removeClass("is-invalid");

    let name = $('input[name="name"]').val();
    let description = $('input[name="description"]').val();
    let address = $('input[name="address"]').val();
    let start_date = $('input[name="start_date"]').val();
    let end_date = $('input[name="end_date"]').val();

    let formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
    formData.append('address', address);
    formData.append('start_date', start_date);
    formData.append('project_photo', project_photo);
    formData.append('end_date', end_date);

    $.ajax({
        url: '../php/newProject.php',
        type: 'POST',
        dataType: 'json',
        processData: false, // Не обрабатываем файлы
        contentType: false, // Так jQuery скажет серверу, что это строковый запрос
        cache: false,
        data: formData,
        success (response){
            // console.log(`Новый проект: ${response}`);
            if(response.status) {
                document.location.href = './myProjects.php';
                console.log(response);
            } else {
                if(response.type === 1) {
                    response.fields.forEach(field => {
                        $(`input[name="${field}"]`).addClass("is-invalid");                        
                    });
                }
            }
        }
    });
});