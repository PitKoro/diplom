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

    <title>Главная</title>

</head>
<body>

    <? require "blocks/navbar.php"; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-8">
            <?
                $all_user_projects = get_all_user_projects($connect, $_SESSION['user']['id']);
                for($i=0; $i<count($all_user_projects); $i++)
                {
                    if($all_user_projects[$i]['start_date'] == ''){
                        $all_user_projects[$i]['start_date'] = 'Не указано';
                    }
                    if($all_user_projects[$i]['end_date'] == ''){
                        $all_user_projects[$i]['end_date'] = 'Не указано';
                    }
                    echo "
                    <div class='card mb-3'>
                        <div class='row g-0'>
                            <div class='col-md-4'>
                                <img src='../../{$all_user_projects[$i]['photo']}' style='width: 100%; height:100%;'>
                            </div>
                            <div class='col-md-8'>
                                <div class='card-header text-white bg-primary'>
                                    <h5 class='card-title'>Имя: {$all_user_projects[$i]['name']} id: {$all_user_projects[$i]['id']}</h5>
                                </div>
                                <div class='card-body'>
                                    <p class='card-text'>Описание: {$all_user_projects[$i]['description']}</p>
                                    <p class='card-text'><small class='text-muted'>С {$all_user_projects[$i]['start_date']} по {$all_user_projects[$i]['end_date']}</small></p>
                                </div>
                                <div class='card-footer'></div>
                            </div>
                        </div>
                    </div>";
                }
            ?>
            </div>
        </div>
    


    </div>

    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>