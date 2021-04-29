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
        <h1>Это главная страница</h1>
        <div class="row">
            <div class="col" id="chart"></div>
        </div>
       
    </div>

    <!-- Bootstrap and jQuery JS -->
    <script src="../../public/vendor/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../public/vendor/popper/popper.min.js"></script>
    <script src="../../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/vendor/apexcharts/apexcharts.js"></script>
    <script>

        var options = {
            series: [44, 55, 41, 17, 15],
            chart: {
            type: 'donut',
            },
            responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                width: 350
                },
                legend: {
                position: 'right'
                }
            }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

    </script>
</body>
</html>