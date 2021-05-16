<?
session_start();//Подключение должно быть на первой строчке в коде, иначе появится ошибка
require_once 'connect.php'; # Подключаем скрипт connect.php, таким образом устанавливаем соединение с сервером MySQL



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
					$user_result = mysqli_query($connect,"SELECT * FROM users WHERE id='$array[user_id]'");//Получаем данные об авторе сообщения
					if(mysqli_num_rows($user_result) == 1) {
						$user = mysqli_fetch_array($user_result);
						$response .= "<div class='mb-2 chat__message chat__message_$user[login]'><b>$user[full_name]:</b> $array[message]</div>"; //Добавляем сообщения в переменную $echo
					}
				}
			
			} else {
				$response = "Нет сообщений!";//В базе ноль записей
			}
		}
	} else {
		$response = "Проблема авторизации task_id={$task_id}";//Авторизация не удалась
	}
	$response .= "</br>";
	return $response;//Возвращаем результат работы функции
}


function send($connect,$message, $task_id) {
	if(auth($connect,$_SESSION['user']['id'], $task_id)) {//Если авторизация удачна
		$message = htmlspecialchars($message);//Заменяем символы ‘<’ и ‘>’на ASCII-код
		$message = trim($message); //Удаляем лишние пробелы
		$message = addslashes($message); //Экранируем запрещенные символы

		$user_id = $_SESSION['user']['id'];
        $current_date = date("Y-m-d");
		
		$result = mysqli_query($connect,"INSERT INTO chat (`user_id`, `task_id`, `message`, `date`, `id`) VALUES ('$user_id', '$task_id','$message', '$current_date', NULL)");//Заносим сообщение в базу данных
	}
	return load($connect, $task_id); //Вызываем функцию загрузки сообщений
}

//Получаем переменные из супермассива $_POST
//Тут же их можно проверить на наличие инъекций
if(isset($_POST['act'])) {$act = $_POST['act'];}
if(isset($_POST['var1'])) {$var1 = $_POST['var1'];}
if(isset($_POST['task_id'])) {$task_id = $_POST['task_id'];}

switch($_POST['act']) {//В зависимости от значения act вызываем разные функции
	case 'load': 
		$echo = load($connect, $task_id); //Загружаем сообщения
	break;
	
	case 'send': 
		if(isset($var1)) {
			$echo = send($connect, $var1, $task_id); //Отправляем сообщение
		}
	break;
	
	case 'auth': 
		if(isset($var1) && isset($var2)) {//Авторизуемся
			if(auth($connect,$var1,$var2)) {
				$echo = load($connect);
			}
		}
	break;
}

echo $echo;//Выводим результат работы кода

?>