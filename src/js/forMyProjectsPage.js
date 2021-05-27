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