<?
require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL

if($_POST['table']=='project') {
    /* Вывод таблицы из БД */
    $sql = mysqli_query($connect, "SELECT * FROM project");

    $table_data = "
    <thead>
        <tr>
        <th scope=\"col\">#</th>
        <th scope=\"col\">Имя</th>
        <th scope=\"col\">Описание</th>
        <th scope=\"col\">address</th>
        <th scope=\"col\">photo</th>
        <th scope=\"col\">start_date</th>
        <th scope=\"col\">end_date</th>
        </tr>
    </thead>
    <tbody id='project_table_body'>";

    while($result = mysqli_fetch_array($sql))#функция вывода таблицы
    {
        $table_data = $table_data."<tr>
        <td> {$result['id']} </td>
        <td> {$result['name']} </td>
        <td> {$result['description']}</td>
        <td> {$result['address']} </td>
        <td> {$result['photo']} </td>
        <td> {$result['start_date']}</td>
        <td> {$result['end_date']}</td>
        </tr>";
    }

    $table_data = $table_data."</tbody>";
    mysqli_free_result($sql);
    echo $table_data;
    die();
}

if($_POST['table']=='user') {
    /* Вывод таблицы из БД */
    $sql = mysqli_query($connect, "SELECT * FROM users");

    $table_data = "
    <thead>
        <tr>
        <th scope=\"col\">id</th>
        <th scope=\"col\">login</th>
        <th scope=\"col\">email</th>
        <th scope=\"col\">password</th>
        <th scope=\"col\">full_name</th>
        <th scope=\"col\">avatar</th>
        <th scope=\"col\">status</th>
        </tr>
    </thead>
    <tbody id='user_table_body'>";

    while($result = mysqli_fetch_array($sql))#функция вывода таблицы
    {
        $table_data = $table_data."<tr>
        <td> {$result['id']} </td>
        <td> {$result['login']} </td>
        <td> {$result['email']}</td>
        <td> {$result['password']} </td>
        <td> {$result['full_name']} </td>
        <td> {$result['avatar']}</td>
        <td> {$result['status']}</td>
        </tr>";
    }

    $table_data = $table_data."</tbody>";
    mysqli_free_result($sql);
    echo $table_data;
    die();
}


?>
?>