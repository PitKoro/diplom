let interval = null;//Переменная с интервалом подгрузки сообщений
let var1 = null;
let task_id = null;

$(document).on('click', '.js-project-task-chat-btn', function(){
	$('#messages').empty().append(`<div class="text-center">
										<div class="spinner-border" role="status">
										<span class="visually-hidden">Loading...</span>
										</div>
									</div>`);
    let task_id = $(this).val();
    $('#task-id').attr('value', task_id);
    interval = setInterval(()=>update(task_id), 1000);
	
});


$('#project-task-chat-modal').on('hidden.bs.modal', function (e) {
	clearInterval(interval);
});


$(document).on('click', '.chat-form__submit', function(e){
	e.preventDefault();
	let message = $('#message-text').val();
	if(message == ''){
		$('#message-text').attr('class','chat-form__input form-control is-invalid');
	}else{
		$('#message-text').attr('class','chat-form__input form-control');
		let task_id = $('#task-id').val();
		send_request('send', task_id);
		$("#messages").scrollTop($("#messages")[0].scrollHeight);
	}
	
});

function send_request(act, task_id, login = null) {//Основная функция
	//Переменные, которые будут отправляться
	let var1 = null;

	
	if(act == 'auth') {
		//Если нужно авторизоваться, получаем логин, которыq был передан в функцию
		var1 = login;
	} else if(act == 'send') {
        //Если нужно отправить сообщение, то получаем текст из поля ввода
		var1 = $('#message-text').val(); 
	}
	
	$.ajax({
		method: 'POST',
		url: '../php/chat.php',
		data: {
			act: act,
			var1: var1,
			task_id: task_id
		},
		success: function(response){
			//Заносим в контейнер ответ от сервера
			$('#messages').empty().append(response);
			

			if(act == 'send') {
				//Если нужно было отправить сообщение, очищаем поле ввода
				$('#message-text').val('');
			}
		}
	});

}

function update(task_id) {
	send_request('load', task_id);
}
