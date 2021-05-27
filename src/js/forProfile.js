function start_all_tooltip(){
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
}

$(document).ready(function(){
    let user_id = $("input[name='user_id']").val();
    $.ajax({
        method: 'POST',
        url: '../php/get_db_table.php',
        data: {
            show: 'all_user_tasks',
            user_id: user_id
        },
        success: function(response){
            $(".js-project-table").empty().append(response);
            start_all_tooltip();
        }
    });
});