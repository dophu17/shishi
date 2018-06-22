//window.onbeforeunload = function() {
//   return "Data will be lost if you reload the page, Are you sure you want to continue?";
//};

//Add task from left list to right list
$(document).on('click','.task_name',function(){
	var item = $(this).parents('.list-group-item-left');
    var task_id = item.attr('task_id');
    var task_name = item.attr('task_name');
    //Check task exits in right
    var list_task_right = $('.list-group-right').find('li');

    var task_est = item.find('.input_time').val();

    stt = stt + 1;

    //Append task to right
    var html_append = "";
    html_append += '<li class="list-group-item d-flex justify-content-between lh-condensed list-group-item-right" task_id="'+task_id+'">';
    html_append += '    <div class="col-md-1 mb-3">';
    html_append += '        <a class="remove_task" title="削除"> <i class="fa fa-trash"></i> </a>';
    html_append += '    </div>';
    html_append += '    <div class="col-md-3 mb-3">';
    html_append += '<span class="task-name-edit" data-name="name" data-type="text" data-pk="' + stt + '">' + task_name + '</span>';
    html_append += '<input type="hidden" class="task_name task-name-' + stt + '" name="task_name['+stt+']" value="'+task_name+'" >';
    html_append += '<input type="hidden" class="task_id" name="task_id['+stt+']" value="'+task_id+'" >';

    html_append += '    </div>';
    html_append += '    <div class="col-md-3 mb-3">';
    html_append += '        <select class="form-control task_site select2" name="task_site['+stt+']">';
    html_append += list_client_option;
    html_append += '        </select>';

    html_append += '    </div>';
    html_append += '    <div class="col-md-3 mb-3">';
    html_append += '        <select class="form-control task_label" name="task_label['+stt+']">';
    html_append += list_label_option;
    html_append += '        </select>';
    html_append += '    </div>';
    html_append += '    <div class="col-md-2 mb-3">';
    html_append += '        <input type="number" class="form-control task_est" value="'+task_est+'" name="task_est['+stt+']">';
    html_append += '        <span class="h_title_right">h</span>';
    html_append += '    </div>';
    html_append += '</li>';

    $('.list-group-right').append(html_append);

    //active select2
    $('.select2').select2({
        language: {
            noResults: function (params) {
                return "データがございません";
            }
        }
    });

    //x editable
    $('.task-name-edit').editable({
        clear: false,
        success: function(response, newValue) {
            var current_pk = $(this).data('pk');
            $('.task-name-' + current_pk).val(newValue);
        }
    });
});


$(document).on('click','.remove_task',function(){
    var task_id = $(this).parents('li').attr('task_id');
    $(this).parents('li').remove();
    return false;
})

//update task estimation (done)
$(document).on('click','.btn_update_time',function(){
    var time = $(this).parents('.wrap_input_time').find('.input_time').val();
	var item = $(this).parents('.list-group-item-left');
	var est  = item.find('.input_time').val();
	var task_id = item.attr('task_id');
    var defaultTime = $(this).parents('.wrap_input_time').find('.input_time').attr('default-value');
    var objTime = $(this).parents('.wrap_input_time').find('.input_time');

    $.post(updateTaskEst_url,{task_id:task_id,est:est},function(data){
        var data = data.trim();
        ajax_respone_check(data,true);
        var jsonOBJ = JSON.parse(data);

        if(jsonOBJ.status == 'success'){
            alert('更新されました。');
        }else{
            alert('更新されませんでした。');
            objTime.val(defaultTime);
        }
    });
});


$(document).on('click','.hide_task_checkbox',function(){
    hideTaskLeft();
});

$(document).on('click','#right_submit',function(){

    var check_list =  [];
    var err_time = 0;
    var err_dup  = 0;
    var err_empty = 0;
    var err_site_title = 0;
    if($('.list-group-item-right').length){
        $('.list-group-item-right').each(function(){
            var task_id    = $(this).find('.task_id').val();
            var task_site  = $(this).find('.task_site').val();
            var task_label = $(this).find('.task_label').val();
            var task_est   = $(this).find('.task_est').val();

            var key_check = task_id+"_"+task_site+"_"+task_label;

            if(task_site == 0 || task_label == 0){
                //err_site_title++;
            }

            if(check_list.indexOf(key_check) > -1){
                err_dup++;
            }

            check_list.push(key_check);
        });
    }else{
        err_empty++;
    }

    if(err_time > 0){
        alert('作業時間が入力されていないタスクがあります。');
    }else if(err_empty > 0){
        alert('タスクをご選択ください。');
    }else if(err_site_title > 0){
        alert('Please select site and task type');
    } else if (err_dup > 0) {
        if (confirm('既に同じタスクを追加しましたがまた追加しますか？')) {
            showTaskToday();
        }
    }

    if(err_time == 0 && err_dup == 0 && err_empty == 0 && err_site_title == 0){
        showTaskToday();
    }    
});

//Delete left task
$(document).on('click','.btn_delete_task',function(){
    var right_task_id_arr = getRightTaskArr();

    if($('.hide_task_checkbox:checked').length > 0){
        var exit_in_right = 0 ;
        //Check exits in right
        var remove_task = [];
        $('.hide_task_checkbox:checked').each(function(){
            var cr_task_id = parseInt($(this).attr('value'));
            /*if(right_task_id_arr.indexOf(cr_task_id) > -1){
                exit_in_right++;
            }else{*/
                $(this).parents('.list-group-item-left').remove();
                remove_task.push(cr_task_id);
            // }
        });

        if(remove_task.length){
            $.post(removeTask_url,{task_id:remove_task.join()});
        }

        hideTaskLeft();
        /*if(exit_in_right){
            alert('このタスクは利用されているため、削除できません');
        }*/
    }
});

$(document).on('click','#btn-send-cw',function(){
    $('#modalTaskLog').modal('hide');
    $('#form_report_task').submit();
});

//Add task to left list (done)
function addTaskToList(){
	var task_name = $('#task_input').val();

    if(task_name.trim() == ""){
        alert('入力データは正しくありません。');
        return false;
    }

	$.post(addTask_url,{task_name:task_name},function(data){
		var data = data.trim();
		ajax_respone_check(data,true);
		var jsonOBJ = JSON.parse(data);
        if(jsonOBJ.status == 'success'){
            $('#defined_task').append(jsonOBJ.data);
            $('#task_input').val(''); 
        }else{
            alert('入力データは正しくありません。');
        }		
	});
	
	return false;
}


function getRightTaskArr(){
    var data = [];
    if($('.list-group-item-right').length){
        $('.list-group-item-right').each(function(){
            var task_id     = parseInt($(this).find('.task_id').val());
            data.push(task_id);            
        });
    }
    return data;
}

function hideTaskLeft(){
    var checked = "";
    $('.hide_task_checkbox:checked').each(function(){
        if(checked == ""){
            checked += $(this).attr('value');
        }else{
            checked += ","+ $(this).attr('value');
        }
    });

    $('input[name=hide_task]').val(checked);
}

function showTaskToday() {
    $('#task-log').html('<thead><tr>' +
      '<th class="task-name">タスク名</th>' +
      '<th class="task-time">時間（h）</th>' +
      '</tr></thead>');
    var totalTime = 0;
    $('#form_report_task .list-group-item').each(function (index, value) {
        var taskName = $(this).find('.task-name-edit').html();
        var taskTime = $(this).find('.task_est').val();
        totalTime += Number(taskTime);

        $('#task-log').append('<tr>' +
          '<td>' + taskName + '</td>' +
          '<td>' + taskTime + '</td>' +
          '</tr>');
    });
    $('#task-log').append('<thead><tr>' +
      '<th>合計</th>' +
      '<th>' + totalTime + '</th>' +
      '</tr></thead>');

    $('#modalTaskLog').modal('show');
}