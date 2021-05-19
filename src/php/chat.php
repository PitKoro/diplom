<?

session_start();//Подключение должно быть на первой строчке в коде, иначе появится ошибка
require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL


# для дебага
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


function auth($connect,$user_id, $task_id) {

	if($_SESSION['user']['status']=='10'){
		return true;
	}
	//Находим совпадение в базе данных
	$result = mysqli_query($connect,"SELECT * FROM projects_tasks WHERE user_id={$user_id} AND id={$task_id}");
	if($result) {
		if(mysqli_num_rows($result) == 1) {//Проверяем, одно ли совпадение
			return true; //Возвращаем true, потому что авторизация успешна
		} else {
			return false;
		}
	} else {
		return false; //Возвращаем ложь, если произошла ошибка
	}
}

function load($connect, $task_id) {
	$response = "";
	if(auth($connect,$_SESSION['user']['id'], $task_id)) {//Проверяем успешность авторизации
		$result = mysqli_query($connect,"SELECT * FROM chat WHERE task_id={$task_id}"); //Запрашиваем сообщения из базы
		if($result) {
			if(mysqli_num_rows($result) >= 1) {
				while($array = mysqli_fetch_array($result)) {//Выводим их с помощью цикла
					// $user_result = mysqli_query($connect,"SELECT * FROM users WHERE id='$array[user_id]'");//Получаем данные об авторе сообщения
					// $user = mysqli_fetch_array($user_result);
					$response .= "<div class='mb-2 chat__message chat__message_$array[user_login]'><b>$array[user_full_name]:</b> $array[message]</div>"; //Добавляем сообщения в переменную $echo

				}
			} else {
				$response = "<div id='js-empty-chat'>Нет сообщений!</div>";//В базе ноль записей
			}
		}
	} else {
		$response = "Проблема авторизации task_id={$task_id}";//Авторизация не удалась
	}
	return $response;//Возвращаем результат работы функции
}


//Получаем переменные из супермассива $_POST
//Тут же их можно проверить на наличие инъекций
if(isset($_POST['act'])) {$act = $_POST['act'];}
if(isset($_POST['var1'])) {$var1 = $_POST['var1'];}
if(isset($_POST['task_id'])) {$task_id = $_POST['task_id'];}

switch($_POST['act']) {//В зависимости от значения act вызываем разные функции
	case 'auth': 
		if(isset($task_id)) {//Авторизуемся
			$user_id = $_SESSION['user']['id'];
			if(auth($connect,$user_id,$task_id)) {
				$echo = load($connect, $task_id);
			}
		}
	break;

	case 'get_user_id':
		$echo = $_SESSION['user']['id'];
	break;
}

echo $echo;//Выводим результат работы кода

?>