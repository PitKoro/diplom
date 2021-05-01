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
            <div class="col-md-6 col-xl-4 col-xxl-3 mb-3 pe-md-2">
                <div class="card h-md-100">
                    <div class="card-body">

                        <div id="task-chart-donut"></div>

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
    <script>
        $(document).ready(function(){
            $.ajax({
                method: 'POST',
                url: '../php/charts.php',
                data: {
                    show: 'tasks'
                },
                success: function(response){
                    let complited = Number(response.completed_tasks);
                    let notComplited = Number(response.not_completed_tasks);
                    let options = {
                        chart: {
                            type: 'donut',
                            width: '100%',
                            height: 200
                        },
                        dataLabels: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '75%',
                                    labels: {
                                        show: true,
                                        name: {
                                            show: false
                                        },
                                        value: {
                                            show: true
                                        },
                                        total: {
                                            show: true,
                                            fontSize: '20px',
                                            fontWeight: 600,
                                            label: 'Всего'
                                        }
                                    }
                                }
                            }
                        },
                        title: {
                            text: 'Задачи',
                            style: {
                            fontSize: '18px'
                            }
                        },
                        series: [complited, notComplited],
                        labels: ['Выполнено','Не выполнено'],
                        colors:['#32CD32', '#B22222']
                        
                    };

                    var chart = new ApexCharts(document.querySelector("#task-chart-donut"), options);
                    chart.render();
                }
            });
        });
    </script>

    <script>
    


    </script>
</body>
</html>