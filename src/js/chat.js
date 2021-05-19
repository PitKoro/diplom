let task_id = null;
let user_id = null;
let socket = null; // ws


$(document).on('click', '.js-project-task-chat-btn', function(){
	$('#messages').empty().append(`<div class="text-center">
										<div class="spinner-border" role="status">
										<span class="visually-hidden">Loading...</span>
										</div>
									</div>`);
    task_id = $(this).val();
    $('#task-id').attr('value', task_id);
    
	// ЗАПРОС НА АВТОРИЗАЦИЮ В ЧАТ ЗАДАЧИ
	$.ajax({
		method: 'POST',
		url: '../php/chat.php',
		data: {
			act: 'auth',
			task_id: task_id
		},
		success: function(response){
			//Заносим в контейнер ответ от сервера
			$('#messages').empty().append(response);
		}
	});

	//ОТКРЫВАЕМ СОЕДИНЕНИЕ ws c python
	socket = new WebSocket("ws://localhost:7500");
	socket.onopen = function(){
		console.log('Соединение установлено');
	};

	socket.onerror = function(){
		console.log('Ошибка при подключении');
	};

	socket.onmessage = function(response){
		$('#js-empty-chat').empty();
		response = JSON.parse(response.data);
		const div = document.createElement('div');
		div.className = "message mb-2";
		div.innerHTML=`<b>${response.user_full_name}: </b>${response.message}`

		document.getElementById('messages').appendChild(div);
		document.getElementById('messages').lastChild.scrollIntoView(false)
	};
});


$(document).on('click', '.chat-form__submit', function(e){
	e.preventDefault();
	let message = $('#message-text').val();
	if(message == ''){
		$('#message-text').attr('class','chat-form__input form-control is-invalid');
	}else{
		$('#message-text').attr('class','chat-form__input form-control');
		task_id = $('#task-id').val();
		user_id = $('input[name="user_id"]').val();
		user_full_name = $('input[name="user_full_name"]').val();
		user_login = $('input[name="user_login"]').val();
		let msg = {
			message: message,
			task_id: task_id,
			user_full_name: user_full_name,
			user_login: user_login
		};

		socket.send(JSON.stringify(msg));

		$('#message-text').val('');
	}
	
});


$('#project-task-chat-modal').on('hidden.bs.modal', function (e) {
	socket.close();
});