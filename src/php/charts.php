<?
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL

if(isset($_POST['show'])){

    if($_POST['show']=='all_projects_charts'){
        if($_SESSION['user']['status'] == '10'){
            $completed_tasks = mysqli_query($connect, "SELECT * FROM `projects_tasks` WHERE `status`='1'");
            $not_completed_tasks = mysqli_query($connect, "SELECT * FROM `projects_tasks` WHERE `status`='0'");
    
            $completed_tasks_cnt = mysqli_num_rows($completed_tasks);
            $not_completed_tasks_cnt = mysqli_num_rows($not_completed_tasks);
    
            if(($completed_tasks_cnt == 0) && ($not_completed_tasks_cnt == 0)){
                $response["status"] = 0;
                echo json_encode($response);
                die();
            }
    
            $response = [
                "tasks_chart"=>[
                    'completed_tasks'=>$completed_tasks_cnt,
                    'not_completed_tasks'=>$not_completed_tasks_cnt
                ]
            ];
    
    
    
            $all_users_with_tasks_query = mysqli_query($connect, "SELECT U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id GROUP BY U.full_name"); // ЗАПРОС НА ПОЛУЧЕНИЕ full_name ВСЕХ users У КОТОРЫХ ЕСТЬ ХОТЯ БЫ ОДНА ЗАДАЧА
            $all_tasks_query = mysqli_query($connect, "SELECT A.*, U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id ");// WHERE A.project_id='{$project_id}' ORDER BY A.id DESC
            
            while($all_users_with_tasks = mysqli_fetch_array($all_users_with_tasks_query)){
    
                $response["users_bar"][$all_users_with_tasks["full_name"]] = [
                    "completed_tasks" => 0,
                    "completing_soon_tasks" => 0,
                    "overdue_tasks" => 0,
                    "current_tasks" => 0
                ];
            }
            
            while($all_tasks=mysqli_fetch_array($all_tasks_query)){
                $task_end_date = date("d.m.Y",strtotime($all_tasks['end_date']));
                $current_date = time();
                $days_to_finish = ceil(( strtotime($task_end_date) - $current_date ) / (60*60*24));
            
                if($all_tasks["status"] == "1"){
                    $response["users_bar"][$all_tasks["full_name"]]["completed_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["completed_tasks"] + 1;
                } else if(($days_to_finish<=5) && ($days_to_finish>=0) && ($all_tasks['status']!='1')){
                    $response["users_bar"][$all_tasks["full_name"]]["completing_soon_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["completing_soon_tasks"] + 1;
                } else if(($days_to_finish<0) && ($all_tasks['status']!='1')){
                    $response["users_bar"][$all_tasks["full_name"]]["overdue_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["overdue_tasks"] + 1;
                } else {
                    $response["users_bar"][$all_tasks["full_name"]]["current_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["current_tasks"] + 1;
                }
            }
    
            echo json_encode($response);
            die();
        } else if($_SESSION['user']['status'] == '1'){
            $user_projects_query = mysqli_query($connect, "SELECT * FROM users_in_projects WHERE user_id={$_SESSION['user']['id']}");
            
            $completed_tasks_sql_query = "SELECT * FROM `projects_tasks` WHERE `status`='1'";
            $not_completed_tasks_sql_query = "SELECT * FROM `projects_tasks` WHERE `status`='0'";

            while($user_projects = mysqli_fetch_array($user_projects_query)){
                $completed_tasks_sql_query .= " AND project_id={$user_projects['project_id']}";
                $not_completed_tasks_sql_query .= " AND project_id={$user_projects['project_id']}";
            }

            $completed_tasks = mysqli_query($connect, $completed_tasks_sql_query);
            $not_completed_tasks = mysqli_query($connect, $not_completed_tasks_sql_query);
    
            $completed_tasks_cnt = mysqli_num_rows($completed_tasks);
            $not_completed_tasks_cnt = mysqli_num_rows($not_completed_tasks);
    
            if(($completed_tasks_cnt == 0) && ($not_completed_tasks_cnt == 0)){
                $response["status"] = 0;
                echo json_encode($response);
                die();
            }
    
            $response = [
                "tasks_chart"=>[
                    'completed_tasks'=>$completed_tasks_cnt,
                    'not_completed_tasks'=>$not_completed_tasks_cnt
                ]
            ];
    
    
            

            $all_users_with_tasks_sql_query = "SELECT U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id";
            $all_tasks_sql_query = "SELECT A.*, U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id";
            
            $i = 0;
            $user_projects_query = mysqli_query($connect, "SELECT * FROM users_in_projects WHERE user_id={$_SESSION['user']['id']}");
            while($user_projects = mysqli_fetch_array($user_projects_query)){
                if($i>0){
                    $all_users_with_tasks_sql_query .= " AND A.project_id='{$user_projects['project_id']}'";
                    $all_tasks_sql_query .= " AND A.project_id='{$user_projects['project_id']}'";
                } else {
                    $all_users_with_tasks_sql_query .= " WHERE A.project_id='{$user_projects['project_id']}'";
                    $all_tasks_sql_query .= " WHERE A.project_id='{$user_projects['project_id']}'";
                }
                
                $i += 1;
            }

            $all_users_with_tasks_sql_query .= " GROUP BY U.full_name";

            $all_users_with_tasks_query = mysqli_query($connect, $all_users_with_tasks_sql_query); // ЗАПРОС НА ПОЛУЧЕНИЕ full_name ВСЕХ users У КОТОРЫХ ЕСТЬ ХОТЯ БЫ ОДНА ЗАДАЧА
            $all_tasks_query = mysqli_query($connect, $all_tasks_sql_query);// WHERE A.project_id='{$project_id}' ORDER BY A.id DESC
            
            while($all_users_with_tasks = mysqli_fetch_array($all_users_with_tasks_query)){
    
                $response["users_bar"][$all_users_with_tasks["full_name"]] = [
                    "completed_tasks" => 0,
                    "completing_soon_tasks" => 0,
                    "overdue_tasks" => 0,
                    "current_tasks" => 0
                ];
            }
            
            while($all_tasks=mysqli_fetch_array($all_tasks_query)){
                $task_end_date = date("d.m.Y",strtotime($all_tasks['end_date']));
                $current_date = time();
                $days_to_finish = ceil(( strtotime($task_end_date) - $current_date ) / (60*60*24));
            
                if($all_tasks["status"] == "1"){
                    $response["users_bar"][$all_tasks["full_name"]]["completed_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["completed_tasks"] + 1;
                } else if(($days_to_finish<=5) && ($days_to_finish>=0) && ($all_tasks['status']!='1')){
                    $response["users_bar"][$all_tasks["full_name"]]["completing_soon_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["completing_soon_tasks"] + 1;
                } else if(($days_to_finish<0) && ($all_tasks['status']!='1')){
                    $response["users_bar"][$all_tasks["full_name"]]["overdue_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["overdue_tasks"] + 1;
                } else {
                    $response["users_bar"][$all_tasks["full_name"]]["current_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["current_tasks"] + 1;
                }
            }
    
            echo json_encode($response);
            die();
        }

    }


    if($_POST['show']=='project_charts'){
        $project_id = $_POST['project_id'];
        $completed_tasks = mysqli_query($connect, "SELECT * FROM `projects_tasks` WHERE `project_id`='{$project_id}' AND `status`='1'");
        $not_completed_tasks = mysqli_query($connect, "SELECT * FROM `projects_tasks` WHERE `project_id`='{$project_id}' AND `status`='0'");

        $completed_tasks_cnt = mysqli_num_rows($completed_tasks);
        $not_completed_tasks_cnt = mysqli_num_rows($not_completed_tasks);

        if(($completed_tasks_cnt == 0) && ($not_completed_tasks_cnt == 0)){
            $response["status"] = 0;
            echo json_encode($response);
            die();
        }

        $response = [
            "tasks_chart"=>[
                'completed_tasks'=>$completed_tasks_cnt,
                'not_completed_tasks'=>$not_completed_tasks_cnt
            ]
        ];

        $all_users_with_tasks_query = mysqli_query($connect, "SELECT U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id WHERE `project_id`='{$project_id}' GROUP BY U.full_name"); // ЗАПРОС НА ПОЛУЧЕНИЕ full_name ВСЕХ users У КОТОРЫХ ЕСТЬ ХОТЯ БЫ ОДНА ЗАДАЧА
        $all_tasks_query = mysqli_query($connect, "SELECT A.*, U.full_name FROM projects_tasks A LEFT JOIN users U ON A.user_id=U.id WHERE `project_id`='{$project_id}'");// WHERE A.project_id='{$project_id}' ORDER BY A.id DESC
        
        while($all_users_with_tasks = mysqli_fetch_array($all_users_with_tasks_query)){

            $response["users_bar"][$all_users_with_tasks["full_name"]] = [
                "completed_tasks" => 0,
                "completing_soon_tasks" => 0,
                "overdue_tasks" => 0,
                "current_tasks" => 0
            ];
        }
        
        while($all_tasks=mysqli_fetch_array($all_tasks_query)){
            $task_end_date = date("d.m.Y",strtotime($all_tasks['end_date']));
            $current_date = time();
            $days_to_finish = ceil(( strtotime($task_end_date) - $current_date ) / (60*60*24));
        
            if($all_tasks["status"] == "1"){
                $response["users_bar"][$all_tasks["full_name"]]["completed_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["completed_tasks"] + 1;
            } else if(($days_to_finish<=5) && ($days_to_finish>=0) && ($all_tasks['status']!='1')){
                $response["users_bar"][$all_tasks["full_name"]]["completing_soon_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["completing_soon_tasks"] + 1;
            } else if(($days_to_finish<0) && ($all_tasks['status']!='1')){
                $response["users_bar"][$all_tasks["full_name"]]["overdue_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["overdue_tasks"] + 1;
            } else {
                $response["users_bar"][$all_tasks["full_name"]]["current_tasks"] = $response["users_bar"][$all_tasks["full_name"]]["current_tasks"] + 1;
            }
        }

        echo json_encode($response);
    }
}