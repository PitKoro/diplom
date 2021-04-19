<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
include '../php/connect.php';
include '../php/lib.php';

if(isset($_POST['modal'])){
    if($_POST['modal'] == 'add_users'){
        $project_id = $_POST['project_id'];
        $users_out_project = get_all_users_not_participating_in_the_project($connect, $project_id);

        $response = "";
        for($i = 0; $i < count($users_out_project); $i++){
            $response = $response."
            <div class='form-check'>
                <input class='form-check-input' type='checkbox' name='user_id[]' value='{$users_out_project[$i]['id']}' id='check-user-{$users_out_project[$i]['id']}'>
                <label class='form-check-label' for='check-user-{$users_out_project[$i]['id']}'>
                    {$users_out_project[$i]['login']} | {$users_out_project[$i]['full_name']} 
                </label>
            </div>
            ";
        }
        echo $response;
    }
}