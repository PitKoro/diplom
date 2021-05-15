<?
session_start();

require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL

if($_POST['show']=='project_data') {
    header('Content-Type: application/json; charset=utf-8');

    $project_id = $_POST['project_id'];
    $sql = mysqli_query($connect, "SELECT * FROM projects WHERE id=$project_id");

    $project_id = NULL;
    $project_name = NULL;
    $project_description = NULL;
    $project_photo = NULL;
    $project_start_date = NULL;
    $project_end_date = NULL;

    while($project = mysqli_fetch_array($sql))#функция вывода таблицы 
    {   
        $project_id = $project['id'];
        $project_name = $project['name'];
        $project_description = $project['description'];
        $project_photo = $project['photo'];
        $project_start_date = $project['start_date'];
        $project_end_date = $project['end_date'];
    }

    mysqli_free_result($sql);
    $project_json_data = [
        "project_id" => $project_id,
        "project_name" => $project_name,
        "project_description" => $project_description,
        "project_photo" => $project_photo,
        "project_start_date" => $project_start_date,
        "project_end_date" => $project_end_date
    ];

    echo json_encode($project_json_data);
    die();
}

if($_POST['show']=='project_tasks'){
    header('Content-Type: text/html; charset=utf-8');
    $project_id = $_POST['project_id'];
    if($_SESSION['user']['status'] == '10'){
        $sql = mysqli_query($connect, "SELECT A.*, U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id WHERE A.project_id='{$project_id}' ORDER BY A.status ");
    } else {
        $user_id = $_SESSION['user']['id'];
        $sql = mysqli_query($connect, "SELECT A.*, U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id WHERE A.project_id='{$project_id}' AND A.user_id='{$user_id}' ORDER BY A.status ");
    }

    $row_cnt = mysqli_num_rows($sql);

    if($row_cnt > 0){
        $table_data = "
        <thead>
            <tr>
            <th scope='col'>№</th>
            <th scope='col'>Название</th>
            <th scope='col'>Ответственный</th>
            <th scope='col'>Дата завершения</th>
            <th scope='col'>Статус</th>";
            // if($_SESSION['user']['status'] == '10'){
            //     $table_data = $table_data."
            //     <th scope='col'>Удалить</th>
            //     <th scope='col'>Изменить</th>";
            // }


            
            $table_data=$table_data."</tr>
                                    </thead>
                                    <tbody>";

        while($result = mysqli_fetch_array($sql))#функция вывода таблицы 
        {
            
            $task_end_date = date("d.m.Y",strtotime($result['end_date']));
            $current_date = time();
            $days_to_finish = ceil(( strtotime($task_end_date) - $current_date ) / (60*60*24));

            if($result['status']=='1'){
                $table_data = $table_data."<tr class='table-success'>";
            } else if(($days_to_finish<=5) && ($days_to_finish>=0) && ($result['status']!='1')){
                $table_data = $table_data."<tr class='table-warning'>";
            } else if(($days_to_finish<0) && ($result['status']!='1')){
                $table_data = $table_data."<tr class='table-danger'>";
            } else {
                $table_data = $table_data."<tr class='table-primary'>";
            }
            
           
            

            if($_SESSION['user']['status'] == '10'){
                $status = (($result['status']=='1') ? "Выполнено" : "Не выполнено");
                $table_data = $table_data."                
                    <td> {$result['id']} </td>
                    <td> {$result['name']}</td>
                    <td>{$result['full_name']}</td>
                    <td>{$task_end_date}</td>
                    <td>{$status}</td>";
                    if($result['comment']!=''){
                        $table_data = $table_data."<td  class='text-start'><b>Комметрарий:</b> {$result['comment']}</td>";
                    }

                    if(($result['status']=='1') && ($result['comment']=='')){
                        $table_data = $table_data."<td class='text-start'><b>Комметрарий:</b> <em>не указан</em></td>";
                    }
                    $table_data = $table_data."<td class='text-start'> 
                        <button style='width: 42px' class='js-delete-project-task-btn btn btn-danger mb-2' title='Удалить' value='{$result['id']}'><i class='fas fa-trash-alt'></i></button>
                        <button style='width: 42px' class='js-edit-project-task-btn btn btn-warning  mb-2' value='{$result['id']}' title='Изменить' data-bs-toggle='modal' data-bs-target='#edit-project-task-modal'><i class='fas fa-edit'></i></button>
                    ";
                if($result['status']=='0'){
                    $table_data= $table_data."
                    
                        <textarea style='width: 220px; height:60px;' class='form-control mb-2' type='text' name='task{$result['id']}_comment' placeholder='Комментарий (необязательно)' hidden></textarea>
                        <button style='width: 42px' class='js-add-comment-to-project-task-btn btn btn-success mb-2' id='add-comment-to-project-task-{$result['id']}' value='{$result['id']}' title='Выполнено'><i class='fas fa-check'></i></button>
                        <div class='row justify-content-between'>
                            <div class='col text-end'>
                                <button style='width: 42px' class='js-close-done-project-task-btn btn btn-danger mb-2' id='close-done-project-task-btn-{$result['id']}' value='{$result['id']}' title='Отмена' hidden><i class='fas fa-times'></i></button>
                                
                            </div>
                            <div class='col text-start'>
                                <button style='width: 42px' class='js-done-project-task-btn btn btn-success mb-2' id='done-project-task-btn-{$result['id']}' value='{$result['id']}' hidden title='Выполнено'><i class='fas fa-check'></i></button>
                            </div>
                        </div>

                    </td>";

                    if(($days_to_finish<=5) && ($days_to_finish>=0)){
                        if($days_to_finish == '-0'){ $days_to_finish=0; }
                        $table_data= $table_data."
                        <td>
                            <span class='text-center'>
                                <i class='fas fa-exclamation-triangle' data-bs-toggle='tooltip' data-bs-placement='top' title='Осталось дней: {$days_to_finish}' style='font-size: 25px; color: #ff9933;'></i>
                            </span>
                        </td>";
                    } else if(($days_to_finish<0)){
                        $days_to_finish = abs($days_to_finish);
                        $table_data= $table_data."
                        <td>
                            <span>
                                <i class='fas fa-exclamation-triangle' data-bs-toggle='tooltip' data-bs-placement='top' title='Просрочено дней: {$days_to_finish}' style='font-size: 25px; color: #ff0000;'></i>
                            </span>
                        </td>";
                    }
                } else {
                    // if($result['comment']!=''){
                    //     $table_data = $table_data."<td><b>Комметрарий:</b> {$result['comment']}</td>";
                    // }
                    
                }

                $table_data = $table_data."</tr>";
            } else {
                $status = (($result['status']=='1') ? "Выполнено" : "Не выполнено");
                $table_data = $table_data."
                <td> {$result['id']} </td>
                <td> {$result['name']}</td>
                <td>{$result['full_name']}</td>
                <td>{$result['end_date']}</td>
                <td>{$status}</td>
                ";

                if($result['comment']!=''){
                    $table_data = $table_data."<td  class='text-start'><b>Комметрарий:</b> {$result['comment']}</td>";
                }

                if(($result['status']=='1') && ($result['comment']=='')){
                    $table_data = $table_data."<td class='text-start'><b>Комметрарий:</b> <em>не указан</em></td>";
                }

                $table_data = $table_data."
                <td class='text-start'>
                    <button style='width: 42px' class='js-edit-project-task-btn btn btn-warning  mb-2' value='{$result['id']}' title='Изменить' data-bs-toggle='modal' data-bs-target='#edit-project-task-modal'><i class='fas fa-edit'></i></button>
                ";

                if($result['status']=='0'){

                    if($result['user_id'] == $_SESSION['user']['id']){
                        $table_data = $table_data."
                            <textarea style='width: 220px; height:60px;' class='form-control my-2' type='text' name='task{$result['id']}_comment' placeholder='Комментарий (необязательно)' hidden></textarea>
                            <button style='width: 42px' class=' mb-2 js-add-comment-to-project-task-btn btn btn-success' id='add-comment-to-project-task-{$result['id']}' title='Выполнено' value='{$result['id']}'><i class='fas fa-check'></i></button>
                            <button style='width: 42px' class='mb-2 js-close-done-project-task-btn btn btn-danger' id='close-done-project-task-btn-{$result['id']}' title='Отмена' value='{$result['id']}' hidden><i class='fas fa-times'></i></button>
                            <button style='width: 42px' class='mb-2 js-done-project-task-btn btn btn-success' id='done-project-task-btn-{$result['id']}' value='{$result['id']}' title='Выполнено' hidden><i class='fas fa-check'></i></button>
                        </td>";
                        if(($days_to_finish<=5) && ($days_to_finish>=0)){

                            if($days_to_finish == '-0'){ $days_to_finish=0; }
    
                            $table_data= $table_data."
                            <td>
                                <span class='text-center'>
                                    <i class='fas fa-exclamation-triangle' data-bs-toggle='tooltip' data-bs-placement='top' title='Осталось дней: {$days_to_finish}' style='font-size: 25px; color: #ff9933;'></i>
                                </span>
                            </td>";
                        } else if(($days_to_finish<0)){
                            $days_to_finish = abs($days_to_finish);
                            $table_data= $table_data."
                            <td>
                                <span>
                                    <i class='fas fa-exclamation-triangle' data-bs-toggle='tooltip' data-bs-placement='top' title='Просрочено дней: {$days_to_finish}' style='font-size: 25px; color: #ff0000;'></i>
                                </span>
                            </td>";
                        }
                    }



                } else {
                    // if($result['comment']!=''){
                    //     $table_data = $table_data."<td><b>Комметрарий:</b> {$result['comment']}</td>";
                    // }
                    $table_data = $table_data."</td>";
                    
                }



                $table_data = $table_data."</tr>";

            }
        }

        $table_data = $table_data."</tbody>";
        mysqli_free_result($sql);
        echo $table_data;
        die();
    } else {
        $response = "
            <div class='form-footer mt-3'>
                <h4>Задач в проекте пока нет.</h4>
            </div>";
        echo $response;
        die();
    }

    
}

if($_POST['show'] == 'project_users'){
    header('Content-Type: text/html; charset=utf-8');
    $project_id = $_POST['project_id'];

    $sql = mysqli_query($connect, "SELECT * FROM users A LEFT JOIN users_in_projects B ON A.id=B.user_id WHERE project_id=$project_id");

    $table_data = "
    <thead>
        <tr>
        <th scope='col'>#</th>
        <th scope='col'>Логин</th>
        <th scope='col'>ФИО</th>
        </tr>
    </thead>
    <tbody>";

    while($result = mysqli_fetch_array($sql))#функция вывода таблицы 
    {
        if($_SESSION['user']['id'] == $result['id']){
            $table_data = $table_data."
            <tr>
                <td> {$result['id']} </td>
                <td> {$result['login']} </td>
                <td> {$result['full_name']}</td>        
            </tr>";
        } else if($_SESSION['user']['status'] == '10'){
            $table_data = $table_data."
            <tr>
                <td> {$result['id']} </td>
                <td> {$result['login']} </td>
                <td> {$result['full_name']}</td>        
                <td> <button class='js-delete-project-user-btn btn btn-danger' value='{$result['id']}' title='Удалить'><i class='fas fa-trash-alt'></i></button></td>
            </tr>";
        } else {
            $table_data = $table_data."
            <tr>
                <td> {$result['id']} </td>
                <td> {$result['login']} </td>
                <td> {$result['full_name']}</td>        
            </tr>";
        }
        
    }

    $table_data = $table_data."</tbody>";
    mysqli_free_result($sql);
    echo $table_data;
    die();
}


if($_POST['show'] == 'project_files'){
    header('Content-Type: text/html; charset=utf-8');
    $project_id = $_POST['project_id'];

    $sql = mysqli_query($connect, "SELECT * FROM project_files WHERE project_id='{$project_id}'");
    $row_cnt = mysqli_num_rows($sql);
    if($row_cnt>0){
        $table_data = "
            <thead>
                <tr>
                <th scope='col'>Имя</th>
                <th scope='col'>Размер</th>
                </tr>
            </thead>
            <tbody>";

        while($result = mysqli_fetch_array($sql)){
            $size = round(floatval($result['size'])/1024, 2);
            if($_SESSION['user']['status'] == '10'){
                $table_data = $table_data."
                <tr>
                    <td> {$result['name']} </td>
                    <td> {$size} КБ</td>     
                    <td>
                        <button class='js-delete-project-file-btn btn btn-danger' title='Удалить' value='{$result['id']}'><i class='fas fa-trash-alt'></i></button>
                        <a href='../../{$result['path']}' class='btn btn-success' id='{$result['id']}' title='Скачать' download><i class='fas fa-download'></i></a>
                    </td>
                </tr>";
            } else {
                $table_data = $table_data."
                <tr>
                    <td> {$result['name']} </td>
                    <td> {$size} КБ</td>      
                    <td>
                        <a href='../../{$result['path']}' class='btn btn-success' id='{$result['id']}' title='Скачать' download><i class='fas fa-download'></i></a>
                    </td>
                </tr>";
            }
        }

        $table_data = $table_data."</tbody>";
        mysqli_free_result($sql);
        echo $table_data;
        die();
        



    } else {
        $response = "
        <div class='form-footer mt-3'>
            <h4>Файлов в проекте пока нет.</h4>
        </div>";
        echo $response;
        die();
    }

}

if($_POST['show'] == 'overdue_projects'){
    header('Content-Type: text/html; charset=utf-8');

    $current_date = date("Y-m-d");
    $overdue_projects_query = mysqli_query($connect, "SELECT * FROM projects WHERE end_date<'{$current_date}'");
    $response = "
    <div class='row mb-3'>
        <div class='col'><h5 style='font-size: 19px; font-weight: 900; fill: rgb(55, 61, 63);' '>Просроченные проекты</h5></div>
    </div>
    ";

    $project_count = 0;
    $i = 0;
    while($overdue_projects = mysqli_fetch_array($overdue_projects_query)){

        $overdue_project_tasks_query = mysqli_query($connect, "SELECT * FROM projects_tasks WHERE project_id={$overdue_projects['id']}");

        $count_completed_tasks = 0;
        $count_project_tasks = 0;
        $progress = 0;
        
        while($overdue_project_tasks = mysqli_fetch_array($overdue_project_tasks_query)){
            if($overdue_project_tasks['status'] == '1'){
                $count_completed_tasks += 1;
            }

            $count_project_tasks += 1;
        }

        if($count_project_tasks>0){
            $progress = round($count_completed_tasks/$count_project_tasks, 2) * 100;
        }

        if($progress != 100){
            $project_count += 1;
            $i+=1;
            if($i<5){
                $response = $response."
                    <div class='row mb-3'>
                        <div class='col-4'><h5>{$overdue_projects['name']}</h5></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>
                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$overdue_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>
                ";
            } else if($i==5){
                $response = $response."
                    <a class='mb-3' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOngoingProject' aria-expanded='false' aria-controls='collapseOngoingProject'>
                        Больше <i class='fas fa-angle-down'></i>
                    </a>
                ";
            
                $response = $response."
                    <div class='collapse' id='collapseOngoingProject'>
                ";
                $response = $response."
                    <div class='row mb-3'>
                        <div class='col-4'><h5>{$overdue_projects['name']}</h5></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>
                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$overdue_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>
                ";
            } else if($i>5){
                $response = $response."
                    <div class='row mb-3'>
                        <div class='col-4'><h5>{$overdue_projects['name']}</h5></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>
                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$overdue_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>
                ";
            }
        }
    }

    if($i > 4){
        $response = $response."</div>";
    }

    if($project_count == 0){
        $response = $response."
            <div class='row mb-3'>
                <div class='col'><h5>Просроченных проектов нет</h5></div>
            </div>
        ";

        echo $response;
        die();
    }

    echo $response;
    die();
}

if($_POST['show'] == 'ongoing_projects'){
    header('Content-Type: text/html; charset=utf-8');

    $current_date = date("Y-m-d");
    $ongoing_projects_query = mysqli_query($connect, "SELECT * FROM projects WHERE end_date>'{$current_date}'");
    $response = "
    <div class='row mb-3'>
        <div class='col'><h5 style='font-size: 19px; font-weight: 900; fill: rgb(55, 61, 63);' '>Текущие проекты</h5></div>
    </div>
    ";


    $project_count = 0;
    $i = 0;
    while($ongoing_projects = mysqli_fetch_array($ongoing_projects_query)){

        $ongoing_project_tasks_query = mysqli_query($connect, "SELECT * FROM projects_tasks WHERE project_id={$ongoing_projects['id']}");

        $count_completed_tasks = 0;
        $count_project_tasks = 0;
        $progress = 0;
        
        while($ongoing_project_tasks = mysqli_fetch_array($ongoing_project_tasks_query)){
            if($ongoing_project_tasks['status'] == '1'){
                $count_completed_tasks += 1;
            }

            $count_project_tasks += 1;
        }

        if($count_project_tasks>0){
            $progress = round($count_completed_tasks/$count_project_tasks, 2) * 100;
        }

        if($progress != 100){
            $project_count += 1;
            $i+=1;
            if($i<5){
                $response = $response."
                    <div class='row align-items-center mb-3'>
                        <div class='col-4'><h6>{$ongoing_projects['name']}</h6></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>
                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$ongoing_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>

                ";
            } else if($i==5){
                $response = $response."
                    <a class='mb-3' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOngoingProject' aria-expanded='false' aria-controls='collapseOngoingProject'>
                        Больше <i class='fas fa-angle-down'></i>
                    </a>
                ";
            
                $response = $response."
                    <div class='collapse' id='collapseOngoingProject'>
                ";
                $response = $response."
                    <div class='row mb-3'>
                        <div class='col-4'><h5>{$ongoing_projects['name']}</h5></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>

                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$ongoing_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>
                ";
            } else if($i>5){
                $response = $response."
                    <div class='row mb-3'>
                        <div class='col-4'><h5>{$ongoing_projects['name']}</h5></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>

                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$ongoing_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>
                ";
            }
        }
    }

    if($i > 4){
        $response = $response."</div>";
    }


    if($project_count == 0){
        $response = $response."
            <div class='row mb-3'>
                <div class='col'><h5>Текущих проектов нет</h5></div>
            </div>
        ";

        echo $response;
        die();
    }

    echo $response;
    die();
}

if($_POST['show'] == 'completed_projects'){
    header('Content-Type: text/html; charset=utf-8');

    $current_date = date("Y-m-d");
    $completed_projects_query = mysqli_query($connect, "SELECT * FROM projects");
    $response = "
    <div class='row mb-3'>
        <div class='col'><h5 style='font-size: 19px; font-weight: 900; fill: rgb(55, 61, 63);' '>Завершенные проекты</h5></div>
    </div>
    ";

    $project_count = 0;
    $i = 0;
    while($completed_projects = mysqli_fetch_array($completed_projects_query)){

        $completed_project_tasks_query = mysqli_query($connect, "SELECT * FROM projects_tasks WHERE project_id={$completed_projects['id']}");

        $count_completed_tasks = 0;
        $count_project_tasks = 0;
        $progress = 0;
        
        while($completed_project_tasks = mysqli_fetch_array($completed_project_tasks_query)){
            if($completed_project_tasks['status'] == '1'){
                $count_completed_tasks += 1;
            }

            $count_project_tasks += 1;
        }

        if($count_project_tasks>0){
            $progress = round($count_completed_tasks/$count_project_tasks, 2) * 100;
        }

        if($progress == 100){
            $project_count += 1;
            $i+=1;
            if($i<5){
                $response = $response."
                    <div class='row mb-3'>
                        <div class='col-4'><h5>{$completed_projects['name']}</h5></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>

                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$completed_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>
                ";
            } else if($i==5){
                $response = $response."
                    <a class='mb-3' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOngoingProject' aria-expanded='false' aria-controls='collapseOngoingProject'>
                        Больше <i class='fas fa-angle-down'></i>
                    </a>
                ";
            
                $response = $response."
                    <div class='collapse' id='collapseOngoingProject'>
                ";
                $response = $response."
                    <div class='row mb-3'>
                        <div class='col-4'><h5>{$completed_projects['name']}</h5></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>

                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$completed_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>
                ";
            } else if($i>5){
                $response = $response."
                    <div class='row mb-3'>
                        <div class='col-4'><h5>{$completed_projects['name']}</h5></div>
                    
                        <div class='col-6'>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: {$progress}%' aria-valuenow='{$progress}' aria-valuemin='0' aria-valuemax='100'>{$progress}%</div>
                            </div>
                        </div>

                        <div class='col-2 mx-auto'>
                            <button class='btn btn-primary js-project-charts' style='border-radius: 20px;' value='{$completed_projects['id']}' title='Статистика по проекту'><i class='fas fa-angle-right'></i></button>
                        </div>
                    </div>
                ";
            }
        }

    }
    if($i > 4){
        $response = $response."</div>";
    }

    if($project_count == 0){
        $response = $response."
            <div class='row mb-3'>
                <div class='col'><h5>Завершенных проектов нет</h5></div>
            </div>
        ";

        echo $response;
        die();
    }

    echo $response;
    die();
}

?>