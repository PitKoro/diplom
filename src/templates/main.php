<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
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

    <title>Главная</title>

</head>
<body>

    <? require "blocks/navbar.php"; ?>


    <div class="container mt-5">
        <div class="row no-gutters">
            <div class="col-sm-12 col-md-6 mb-3">
              <div class="row no-gutters">
                <div class="col-sm-12 mb-3">
                  <div class="card h-md-100">
                    <div class="card-body">

                      <div id='overdue_projects'></div>

                    </div>
                  </div>
                </div>

                <div class="col-sm-12 mb-3">
                  <div class="card h-md-100">
                    <div class="card-body">

                      <div id='ongoing_projects'></div>

                    </div>
                  </div>
                </div>

                <div class="col-sm-12 mb-3">
                  <div class="card h-md-100">
                    <div class="card-body">

                    <div id='completed_projects'></div>

                    </div>
                  </div>
                </div>

              </div>
            </div>


            <div class="col-sm-12 col-md-6">
                <div class="row no-gutters">
                    <div class="col-md-12 col-xl-10 col-xxl-9 mb-3 pe-md-2">
                        <div class="card h-md-100">
                            <div class="card-body">
                                <p class="js-project-tasks-chart-title" style="font-size: 16px; font-weight: 900; fill: rgb(55, 61, 63);">Задачи всех проектов</p>
                                <div id="task-chart-donut"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xl-10 col-xxl-9 mb-3 pe-md-2">
                        <div class="card h-md-100">
                            <div class="card-body">
                                <p class="js-project-users-bar-title" style="font-size: 16px; font-weight: 900; fill: rgb(55, 61, 63);">Пользователи всех проектов</p>
                                <div id="users-chart-bar"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        
        
        
        </div>

       
    </div>

    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/popper/popper.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/vendor/apexcharts/apexcharts.js"></script>
    
    <script src="../../src/js/forMainPage.js"></script>

</body>
</html>