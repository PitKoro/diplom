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
            if(response.status == '0'){
                $('#task-chart-donut').empty().append("<h6>Задач нет</h6>");
                $('#users-chart-bar').empty().append("<h6>Задач нет</h6>");
            }else {
                show_project_charts(response);
            }
            
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
            if(response.status == '0'){
                $('#task-chart-donut').empty().append("<h6>Задач нет</h6>");
                $('#users-chart-bar').empty().append("<h6>Задач нет</h6>");
            }else {
                show_project_charts(response);
            }
            //show_project_charts(response);
        }            
    });

});