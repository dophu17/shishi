//Validate contract
$( ".datepicker" ).datepicker({
    onSelect: function(dateText, inst) {
        xuly($(this).attr('stt'),$(this).attr('name'),dateText, $(this));
    },
    dateFormat: 'yy/mm/dd'
});

function xuly(row, input_name, date_string, it) {
    //start day
    if ($(it).attr("check_se") == 'start_day') {
        var start_day_cur = date_string;
        start_day_cur = return_YMD(start_day_cur);

        var end_day_cur = $(it).parents('.contractFields').find('input[check_se=end_day]').val();
        end_day_cur = return_YMD(end_day_cur);

        if(end_day_cur <= start_day_cur){
            //alert('end day bị nhỏ hơn start_day_cur 1');
             alert('終了日は開始日以降の日付を入力してください。');
            disable_button();
            $(it).val('');
            return;
        } else {
            active_button();
        }

        $('.contractFields').attr('editing', 'false');
        $(it).parents('.contractFields').attr('editing', 'true');
        var sum_contract = $('.contractFields[editing=false]').length;
        var array_contract = [];
        $('.contractFields[editing=false]').each(function() {
            var st = return_YMD($(this).find('input[check_se=start_day]').val());
            var ed = return_YMD($(this).find('input[check_se=end_day]').val());
            var contract = [st, ed];
            array_contract.push(contract);
        });

        if(start_day_cur != undefined){
            for (var i = 0; i < array_contract.length; i++) {
                if ((start_day_cur >= array_contract[i][0]) && (start_day_cur <= array_contract[i][1])) {
                    //alert('start_day trùng hoặc nằm trong hợp đồng trước đó'); ok
                    alert('開始日：契約期間が重複しています。');
                    $(it).val('');
                    disable_button();
                } else {
                    if(end_day_cur != undefined){
                        if(start_day_cur >= end_day_cur){
                            //alert('start_day bị lớn hơn end_day');
                            alert('終了日は開始日以降の日付を入力してください。');
                            disable_button();
                            $(it).val('');
                            return;
                        } else {
                            active_button();
                        }
                    }else {
                       disable_button();
                    }
                }
            }
        } else {
            disable_button();
        }

        prevent_add_contract(it);
        //end day
    } else {
        var end_day_cur = date_string;
        end_day_cur = return_YMD(end_day_cur);

        var start_day_cur = $(it).parents('.contractFields').find('input[check_se=start_day]').val();
        start_day_cur = return_YMD(start_day_cur);

        var start_day_next = $(it).parents('.contractFields').next().find('input[check_se=start_day]').val();
        start_day_next = return_YMD(start_day_next);

        if(end_day_cur <= start_day_cur){
            alert('終了日は開始日以降の日付を入力してください。');
            //alert('end day bị nhỏ hơn start_day_cur 2');ok
            disable_button();
            $(it).val('');
            return;
        } else {
            active_button();
        }

        $('.contractFields').attr('editing', 'false');
        $(it).parents('.contractFields').attr('editing', 'true');
        var sum_contract = $('.contractFields').length;
        var array_contract = [];
        $('.contractFields[editing=false]').each(function() {
            var st = return_YMD($(this).find('input[check_se=start_day]').val());
            var ed = return_YMD($(this).find('input[check_se=end_day]').val());
            var contract = [st, ed];
            array_contract.push(contract);
        });
        var next_range = "";

        if(end_day_cur != undefined){
            for (var i = 0; i < array_contract.length; i++) {
                if ((end_day_cur >= array_contract[i][0]) && (end_day_cur <= array_contract[i][1])) {
                    alert('終了日：契約期間が重複しています。');

                    //alert('end_day nằm trong khoảng hd'); ok
                    disable_button();
                    $(it).parents('.contractFields').find('input[check_se=end_day]').val('');
                    return;
                } else {
                    if (next_range == "" && array_contract[i][0] > start_day_cur) {
                        next_range = array_contract[i][0];
                    }

                    if (next_range != "" && end_day_cur >= next_range) {
                        // alert('終了日は開始日以降の日付を入力してください。'); ok
                        alert('契約期間が重複しています。');
                        //alert('không được lớn hơn hoặc bằng ngày bắt đầu của hợp đồng sau');
                        disable_button();
                        $(it).parents('.contractFields').find('input[check_se=end_day]').val('');
                        return;
                    } else {
                        if(start_day_cur != undefined){
                            if(start_day_cur < end_day_cur ){
                                // alert('sfdsf 4');
                                active_button();
                            }else{
                                disable_button();
                                $(it).val('');
                                alert('終了日は開始日以降の日付を入力してください。');
                                //alert('end day bij nhor hown start_day');
                                return;
                            }
                        }else {
                            disable_button();
                        }
                    }

                }
            }
        } else {
            disable_button();
        }

        prevent_add_contract(it);
    }
}

//prevent add contact
function prevent_add_contract(it){
    var x = $(it).parents('.table').find('#contractFieldsForWrapper').find('.contractFields:last').find('input[check_se=start_day]').val();
    var y = $(it).parents('.table').find('#contractFieldsForWrapper').find('.contractFields:last').find('input[check_se=end_day]').val();

    if(x == "" || y == ""){
        disable_button();
    }
}

//Active button add contract
function active_button() {
    allow_add = true;
}

//Disable button add contract
function disable_button() {
    allow_add = false;
}

//Return yy/mm/dd to yymmdd
function return_YMD(string) {
    if (typeof(string) == 'string' && string != undefined) {
        var res = string.split("/");
        return res = res[0] + res[1] + res[2];
    }
}

//Modal delele contract
var delete_plan_id = 0;

$("#modal-btn-si").on("click", function() {
    if (delete_plan_id != 0) {
         //Delete
        $.post(
            removePlanUrl, {
                id: delete_plan_id
            },
            function(res) {
            if (res.trim() == 'success') {
                console.log('plan deleted');
            } else {
                console.log('Error');
             }
            }
        );
    }

    $('.contractFields[deleting=true]').remove();
    $("#mi-modal").modal('hide');
    delete_plan_id = 0;
});

$("#modal-btn-no").on("click", function() {
    $("#mi-modal").modal('hide');
});

//edit plan
$('.edit_plan').click(function(){
    $(this).parents('.contractFields').find('input').removeAttr('disabled');
    $(this).parents('.contractFields').find('select').removeAttr('disabled');
}); 

//prevent input by key
$(document).on('change','.datepicker', function() {
    $(this).val('');
});

//Lock and unlock contract when submit
$('.complete').click(function(){
    $('input').removeAttr('disabled');
    $('select').removeAttr('disabled');
});

function disabled(){
    $('.disabled').find('input').attr('disabled', 'disabled');
    $('.disabled').find('select').attr('disabled', 'disabled');
}
disabled();