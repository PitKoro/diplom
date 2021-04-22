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

    $sql = mysqli_query($connect, "SELECT A.*, U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id WHERE A.project_id='{$project_id}' ORDER BY A.id DESC");
    $row_cnt = mysqli_num_rows($sql);
    if($row_cnt > 0){
        $table_data = "
        <thead>
            <tr>
            <th scope='col'>№</th>
            <th scope='col'>Название</th>
            <th scope='col'>Ответственный</th>
            <th scope='col'>Дата завершения</th>
            <th scope='col'>Удалить</th>
            <th scope='col'>Изменить</th>
            <th scope='col'>Подробнее</th>
            </tr>
        </thead>
        <tbody>";

        while($result = mysqli_fetch_array($sql))#функция вывода таблицы 
        {
            if($_SESSION['user']['status'] == '10'){
                $table_data = $table_data."<tr>
                <td> {$result['id']} </td>
                <td> {$result['name']}</td>
                <td>{$result['full_name']}</td>
                <td>{$result['end_date']}</td>
                <td> <button class='js-delete-project-task-btn btn btn-danger' value='{$result['id']}'>Удалить</button></td>
                <td><button class='editItem btn btn-primary' value='{$result['id']}'>Изменить</button></td>
                <td><button type='button' class='moreButton btn btn-primary' value='{$result['id']}'>Подробнее</button></td>
                </tr>";
            } else {
                $table_data = $table_data."<tr>
                <td> {$result['id']} </td>
                <td> {$result['name']}</td>
                <td>{$result['full_name']}</td>
                <td>{$result['end_date']}</td>
                <td>Недоступно</td>
                <td>Недоступно</td>
                <td><button type='button' class='moreButton btn btn-primary' value='{$result['id']}'>Подробнее</button></td>
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
        <th scope='col'>Удалить</th>
        <th scope='col'>Подробнее</th>
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
                <td> Недоступно</td>
                <td><button type='button' class='moreButton btn btn-primary' id='{$result['id']}'>Подробнее</button></td>
            </tr>";
        } else if($_SESSION['user']['status'] == '10'){
            $table_data = $table_data."
            <tr>
                <td> {$result['id']} </td>
                <td> {$result['login']} </td>
                <td> {$result['full_name']}</td>        
                <td> <button class='js-delete-project-user-btn btn btn-danger' value='{$result['id']}'> Удалить</button></td>
                <td><button type='button' class='moreButton btn btn-primary' id='{$result['id']}'>Подробнее</button></td>
            </tr>";
        } else {
            $table_data = $table_data."
            <tr>
                <td> {$result['id']} </td>
                <td> {$result['login']} </td>
                <td> {$result['full_name']}</td>        
                <td> Недоступно</td>
                <td><button type='button' class='moreButton btn btn-primary' id='{$result['id']}'>Подробнее</button></td>
            </tr>";
        }
        
    }

    $table_data = $table_data."</tbody>";
    mysqli_free_result($sql);
    echo $table_data;
    die();
}


?>