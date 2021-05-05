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

                      <h1 id='overdue_projects'></h1>

                    </div>
                  </div>
                </div>

                <div class="col-sm-12 mb-3">
                  <div class="card h-md-100">
                    <div class="card-body">

                      <h1>Тут будут текущие проекты</h1>

                    </div>
                  </div>
                </div>

                <div class="col-sm-12 mb-3">
                  <div class="card h-md-100">
                    <div class="card-body">

                      <h1>Тут будут проекты в работе</h1>

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

                                <div id="task-chart-donut"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xl-10 col-xxl-9 mb-3 pe-md-2">
                        <div class="card h-md-100">
                            <div class="card-body">

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


          // ВЫВОД ДИАГРАММЫ ДЛЯ ЗАДАЧ
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
                            text: 'Все задачи',
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

        var options = {
          series: [{
          name: 'Сделано',
          data: [44, 55, 41]
        }, {
          name: 'Просрочено',
          data: [53, 32, 33]
        }, {
          name: 'Осталось менее 5 дней',
          data: [12, 17, 11]
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
        title: {
          text: 'Пользователи'
        },
        xaxis: {
          categories: ["Иванов", "Сидоров", "Петров"],
          labels: {
            formatter: function (val) {
              return val + "задач"
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
          opacity: 1
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#users-chart-bar"), options);
        chart.render();
    </script>

    <script>
    


    </script>
</body>
</html>