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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Bootstrap CSS -->
    <link type="text/css" rel="stylesheet" href="../../public/vendor/bootstrap/css/bootstrap.min.css">

    <title>Проекты</title>

</head>
<body>

    <? require "blocks/navbar.php"; ?>
    <div class="container mt-5">
        <div class="row mb-2">

        </div>
        <div class="row justify-content-center">
            <div class="col-8">
            <?
                $all_user_projects = get_all_user_projects($connect, $_SESSION['user']['id']);
                if(count($all_user_projects)){
                    for($i=0; $i<count($all_user_projects); $i++)
                    {
                        if($all_user_projects[$i]['start_date'] == ''){
                            $all_user_projects[$i]['start_date'] = 'Не указано';
                        }
                        if($all_user_projects[$i]['end_date'] == ''){
                            $all_user_projects[$i]['end_date'] = 'Не указано';
                        }
                        if($all_user_projects[$i]['description'] == ''){
                            $all_user_projects[$i]['description'] = 'Не указано';
                        }
                        echo "
                        <div class='card mb-3'>
                            <div class='row g-0'>
                                <div class='col-xs-4 col-md-3'>
                                    <img src='../../{$all_user_projects[$i]['photo']}' class='rounded mx-auto d-block img-thumbnail' style='width: 200px; height:200px;'>
                                </div>
                                <div class='col-xs-8 col-md-9'>
                                    <div class='card-header text-white bg-primary'>
                                        <h5 class='card-title'>Имя: {$all_user_projects[$i]['name']} id: {$all_user_projects[$i]['id']}</h5>
                                    </div>
                                    <div class='card-body'>
                                        <p class='card-text'>Описание: {$all_user_projects[$i]['description']}</p>
                                        <p class='card-text'><small class='text-muted'>С {$all_user_projects[$i]['start_date']} по {$all_user_projects[$i]['end_date']}</small></p>
                                    </div>
                                    <div class='card-footer'>
                                        <div class='row'>
                                            <div class='col'>
                                                <a href='./project.php?id={$all_user_projects[$i]['id']}' class='btn btn-primary'>Подробнее</a>
                                            </div>
                                            <div class='col'>
                                                <button class='btn btn-danger js-delete-project-btn' value='{$all_user_projects[$i]['id']}'>Удалить</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    if($_SESSION['user']['status'] == '10'){
                        echo "
                            <div class='form-footer mt-3'>
                                <h4>У вас еще нет проектов</h4>
                                <h6>Вы можете его <a href='addProject.php'>создать</a>!</h6>
                            </div>";
                    } else {
                        echo "
                            <div class='form-footer mt-3'>
                                <h4>У вас еще нет проектов</h4>                                
                            </div>";
                    }
                    
                }
            ?>
            </div>
        </div>
    


    </div>

    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/popper/popper.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
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