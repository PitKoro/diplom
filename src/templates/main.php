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
    <script>
        function show_project_charts(response){
            $('#task-chart-donut').empty();
            $('#users-chart-bar').empty();

            console.log(response);
            let complited = Number(response.tasks_chart.completed_tasks);
            let notComplited = Number(response.tasks_chart.not_completed_tasks);
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
                // title: {
                //     text: 'Задачи всех проектов',
                //     style: {
                //     fontSize: '16px'
                //     }
                // },
                series: [complited, notComplited],
                labels: ['Выполнено','Не выполнено'],
                colors:['#00e396', '#ff4560']
                
            };

            var tasksChart = new ApexCharts(document.querySelector("#task-chart-donut"), options);
            tasksChart.render();


            let users = Object.keys(response.users_bar);
            let completed_tasks_cnt = [];
            let completing_soon_tasks_cnt = [];
            let current_tasks_cnt = [];
            let overdue_tasks_cnt = [];

            for(let key in response.users_bar){
                completed_tasks_cnt.push(response['users_bar'][key]['completed_tasks']);
                completing_soon_tasks_cnt.push(response['users_bar'][key]['completing_soon_tasks']);
                current_tasks_cnt.push(response['users_bar'][key]['current_tasks']);
                overdue_tasks_cnt.push(response['users_bar'][key]['overdue_tasks']);
            }

            options = {
                series: [{
                    name: 'Текущие',
                    data: current_tasks_cnt
                }, {
                    name: 'Сделано',
                    data: completed_tasks_cnt
                }, {
                    name: 'Осталось менее 5 дней',
                    data: completing_soon_tasks_cnt
                }, {
                    name: 'Просроченные',
                    data: overdue_tasks_cnt
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    },
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                // title: {
                //     text: 'Пользователи всех проектов'
                // },
                xaxis: {
                categories: users,
                    labels: {
                        formatter: function (val) {
                        return val
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: undefined
                    },
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                        return val;
                        }
                    }
                },
                fill: {
                    
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            var usersChart = new ApexCharts(document.querySelector("#users-chart-bar"), options);
            usersChart.render();
        }

        $(document).ready(function(){

            // ВЫВОД ПРОСРОЧЕННЫХ ПРОЕКТОВ
            $.ajax({
                method: 'POST',
                url: '../php/get_db_table.php',
                data: {
                    show: 'overdue_projects'
                },
                success(response){
                    $('#overdue_projects').append(response);
                }
            });

            // ВЫВОД ТЕКУЩИХ ПРОЕКТОВ
            $.ajax({
                method: 'POST',
                url: '../php/get_db_table.php',
                data: {
                    show: 'ongoing_projects'
                },
                success(response){
                    $('#ongoing_projects').append(response);
                }
            });

            // ВЫВОД ЗАВЕРШЕННЫХ ПРОЕКТОВ
            $.ajax({
                method: 'POST',
                url: '../php/get_db_table.php',
                data: {
                    show: 'completed_projects'
                },
                success(response){
                    $('#completed_projects').append(response);
                }
            });


            // ВЫВОД ДИАГРАММ ДЛЯ ВСЕХ ПРОЕКТОВ
            $.ajax({
                method: 'POST',
                url: '../php/charts.php',
                data: {
                    show: 'all_projects_charts'
                },
                success: function(response){
                    show_project_charts(response);
                }
            });

        });

        $(document).on('click','.js-project-charts', function(){
            $('.js-project-tasks-chart-title').empty().append("Задачи");
            $('.js-project-users-bar-title').empty().append("Пользователи");
            let project_id = $(this).val();
            $.ajax({
                method: 'POST',
                url: '../php/charts.php',
                data: {
                    show: 'project_charts',
                    project_id: project_id
                },
                success: function(response){
                    show_project_charts(response);
                }            
            });

        });

    </script>

</body>
</html>