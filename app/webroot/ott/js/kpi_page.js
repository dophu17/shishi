function getNext6Month(month){
	if(month > 7 ){
		return (month + 5) - 12;
	}else{
		return month + 5;
	}
}

function getNextMonth(month){
	if(month == 12){
		return 1;
	}else{
		return month + 1;
	}
}

function getPrevMonth(month){
	if(month == 1){
		return 12;
	}else{
		return month - 1;
	}
}

function isNumber(text){
	var pattern_checknumber = /^\d+(\.\d+)?$/;
	// /^\d+$/
	return pattern_checknumber.test(text);
}

$(document).ready(function(){
	$(".cong").click(function() {
    	var num1 = parseInt($(".number1").text());
    	num1 = getNextMonth(num1);
        var num2 = getNext6Month(num1);
        var num3 = getNextMonth(num2);
        var num4 = getNext6Month(num3);

        $(".number1").text(num1);
        $(".number2").text(num2);
        $(".number3").text(num3);
        $(".number4").text(num4);
    });

    $(".tru").click(function() {
    	var num1 = parseInt($(".number1").text());
    	num1 = getPrevMonth(num1);
        var num2 = getNext6Month(num1);
        var num3 = getNextMonth(num2);
        var num4 = getNext6Month(num3);

        $(".number1").text(num1);
        $(".number2").text(num2);
        $(".number3").text(num3);
        $(".number4").text(num4);
    });

	$('select[name=type_report]').change(function(){
		var url = $(this).find('option[value='+$(this).val()+']').attr('link');
		$('#loading-layer').show();
		window.location.href = url;
	});

	$('.btn_show_edit').click(function(){
		var parent_td = $(this).parents('.editable');
		$('.wrap_input_update').hide();
		$('.wrap_input_current').show();
		parent_td.find('.wrap_input_current').hide();
		var input_update_wrap = parent_td.find('.wrap_input_update');
		var input_update = input_update_wrap.find('.input_update_value');
		input_update.val(input_update.attr('current_value'));
		input_update_wrap.show();
		$(this).removeClass('show_icon_edit').addClass('hide');
		input_update.focus();
	});

	$('.btn_update_value').click(function(){
		//Validate data
		var input_change = $(this).prev('.input_update_value');
		var edit_type 		= input_change.attr('edit_type');
		var kpi_key 		= input_change.attr('kpi_key');
		var current_week 	= input_change.attr('current_week');
		var current_value 	= input_change.attr('current_value');
		if(current_value ==""){
			current_value  = 0;
		}

		if(edit_type == 'actual'){
			//Call change actual value
			var new_value = input_change.val();
			//validate new value
			if(new_value == ""){
				alert('入力した値は正しくありません。');
				return false;
			}

			//call api
			$.get(updateActualValue_url,{year:year_report,month:month_report,week:current_week,kpi_key:kpi_key,cr_value:current_value,new_value:new_value,site_id:site_id},function(data){
				ajax_respone_check(data,true);
				var jsonOBJ = JSON.parse(data);
				if(jsonOBJ.status == 'success'){
					location.reload();
				}else if(jsonOBJ.status == 'error'){
					alert('更新は失敗です。再度試してください。');
				}else{
					alert('未定義のエラー');
				}
			});
		}else if(edit_type == 'target'){
			//Call change target value
			var new_value = input_change.val();
			//validate new value
			if(new_value == ""){
				alert('入力した値は正しくありません。');
				return false;
			}

			//call api
			$.get(updateTagetValue_url,{year:year_report,month:month_report,week:current_week,kpi_key:kpi_key,cr_value:current_value,new_value:new_value,site_id:site_id},function(data){
				ajax_respone_check(data,true);
				var jsonOBJ = JSON.parse(data);
				if(jsonOBJ.status == 'success'){
					location.reload();
				}else if(jsonOBJ.status == 'error'){
					alert('更新は失敗です。再度試してください。');
				}else{
					alert('未定義のエラー');
				}
			});
		}
	});

	$('.btn_update_month_note').click(function(){
		var note = $(this).prev('textarea').val();
		var year = $(this).attr('year');
		var month = $(this).attr('month');
		$.post(updateMonthNote_url,{year:year,month:month,note:note,site_id:site_id},function(data){
			var data = data.trim();
			ajax_respone_check(data,true);
			var jsonOBJ = JSON.parse(data);
			if(jsonOBJ.status == 'SUCCESS'){
				alert('更新されました。');
			}else if(jsonOBJ.status == 'ERROR'){
				alert('更新は失敗です。再度試してください。');
			}else if(jsonOBJ.status == 'ROLE_ERROR'){
				alert('更新は失敗です。再度試してください。');
			}else{
				alert('未定義のエラー');
			}
		});
	});

	$('.btn_update_advice_note').click(function(e){
		e.preventDefault();
		var input_active = $(this).prev('.advice_input');
		var note = input_active.val();
		var year = $(this).attr('year');
		var month = $(this).attr('month');
		var key_note = $(this).attr('key');

		if(key_note == 'advice_note_8' || key_note == 'advice_note_10' || 
			key_note == 'advice_sessions' || key_note == 'advice_transactionsPerSession' || key_note == 'advice_revenuePerTransaction' ){

			if(note != '' && !isNumber(note)){
				alert('数値で入力してください。');
				input_active.val(input_active.attr('original_value'));
				input_active.focus();
				return false;
			}else{
				if(key_note != 'advice_transactionsPerSession'){
					note = Math.round(note);
				}else{
					note = parseFloat(note);
				}
			}
		}

		$.post(updateMonthNote_url,{year:year,month:month,note:note,site_id:site_id,key_note:key_note},function(data){
			var data = data.trim();

			ajax_respone_check(data,true);

			var jsonOBJ = JSON.parse(data);
			if(jsonOBJ.status == 'SUCCESS'){
				if(key_note == 'advice_note_8' || key_note == 'advice_note_10'){
					//change value in view
					input_active.val(note);
					//Update original_value attr
					input_active.attr('original_value',note);
					$('.estimated_cost_display[year='+year+'][month='+month+']').text(jsonOBJ.data.estimated_cost_display);
					alert('更新されました。');
				}else if(key_note == 'advice_sessions' || key_note == 'advice_transactionsPerSession' || key_note == 'advice_revenuePerTransaction'){
					//change value in view
					input_active.val(note);
					//Update original_value attr
					input_active.attr('original_value',note);
					$('.estimated_display[year='+year+'][month='+month+']').text(jsonOBJ.data.estimated_display);
					alert('更新されました。');
				}else{
					alert('更新されました。');
				}
			}else if(jsonOBJ.status == 'ERROR'){
				alert('更新は失敗です。再度試してください。');
			}else if(jsonOBJ.status == 'ROLE_ERROR'){
				alert('更新は失敗です。再度試してください。');
			}else{
				alert('未定義のエラー');
			}
		});
	});


	$('.btn_update_advice_key_note').click(function(e){
		e.preventDefault();
		var input_active = $(this).parent('td').find('.advice_key_input');
		var value = input_active.val();
		var key = $(this).attr('key');

		$.post(updateAdviceTitle_url,{year:advice_year,start_month:advice_start_month,value:value,site_id:site_id,key:key},function(data){
			var data = data.trim();

			ajax_respone_check(data,true);

			var jsonOBJ = JSON.parse(data);
			if(jsonOBJ.status == 'SUCCESS'){
				alert('更新されました。');
			}else if(jsonOBJ.status == 'INPUT_INVALID'){
				alert('入力データは正しくありません。');
			}else if(jsonOBJ.status == 'ERROR'){
				alert('更新は失敗です。再度試してください。');
			}else if(jsonOBJ.status == 'ROLE_ERROR'){
				alert('更新は失敗です。再度試してください。');
			}else{
				alert('未定義のエラー');
			}
		});
	});

	//Hover
	$(".editable").hover(function(){
		var crr_display_status = $(this).find('.wrap_input_update').css('display');
		var btn = $(this).find('.edit-target-btn');
		if(crr_display_status == 'none'){
			btn.addClass('show_icon_edit');
		}else{
			btn.removeClass('show_icon_edit').addClass('hide');
		}
	},function(){
	    $(this).find('.edit-target-btn').removeClass('show_icon_edit');
	});

	//Change report range
	$('.btn_save_range_setting').click(function(){
		var number_1 = $('.number1').text();
		var number_2 = $('.number2').text();
		var number_3 = $('.number3').text();
		var number_4 = $('.number4').text();

		if(number_1 != "" && number_2 != "" && number_3 != "" && number_4 != ""){
			$.get(updateRangeReport_url,{number_1:number_1,number_2:number_2,number_3:number_3,number_4:number_4,site_id:site_id},function(res){
				ajax_respone_check(res,true);
				if(res.trim() =='SUCCESS'){
					//$('.range_setting_msg').html('Success').addClass('success');
					setTimeout(function(){ 
						$('#TermsBtn').modal('hide');
						$('#loading-layer').show();
						window.location.href = kpi_url; }, 2000);
				}else{
					$('.range_setting_msg').html('更新は失敗です。再度試してください。').addClass('error');
				}
			});
		}
	});

	//Change report kpi display
	$('.btn_save_kpi_setting').click(function(){
		var setting = [];
		var kpi_check = $('.kpi_setting');
		kpi_check.each(function(){
			if($(this).prop('checked') == true){
				setting.push($(this).attr('value'));
			}
		});

		$.post(updateKpiReport_url,{kpi:setting,site_id:site_id},function(res){
			ajax_respone_check(res,true);
			if(res.trim() =='SUCCESS'){
				setTimeout(function(){ 
					$('#KPIBtn').modal('hide');
					$('#loading-layer').show();
					window.location.href = current_url; }, 2000);
			}else{
				$('.kpi_setting_msg').html('更新は失敗です。再度試してください。').addClass('error');
			}
		});

	});

	$('.btn_update_site_note').click(function(){
		var site_note = $('.site_note').val();

		$.post(updateSiteNote_url,{site_note:site_note,site_id:site_id},function(res){
			ajax_respone_check(res,true);
			if(res.trim() =='SUCCESS'){
				//$('.range_setting_msg').html('Success').addClass('success');
				setTimeout(function(){ 
					$('#loading-layer').show();
					window.location.href = current_url; }, 2000);
			}else{
				$('.range_setting_msg').html('更新は失敗です。再度試してください。').addClass('error');
			}
		});
	});

	//Hide setFlash info send Chatwork
	setTimeout(function(){
	    $('.alert').fadeOut();
	}, 3000);

	//Advice Function
	var text_click_to_show = "6ヶ月施策表を開く";
	var text_click_to_close = "6ヶ月施策表を閉じる";
	$('.advice_header_text').text(text_click_to_show);
	$('.header_advice').click(function(){
		$('.advice_note').toggle(500);
		if($('.advice_header_text').text() == text_click_to_show){
			$('.advice_header_text').text(text_click_to_close);
		}else{
			$('.advice_header_text').text(text_click_to_show);
		}
	});

	//Advice Function
	$('.btn_show_copy_form').click(function(){
		$('input[name=copy_confirmed]').val(0);
		$('.copy_msg').text('');
		$('.confirm_button').hide();
		$('.normal_button').show();
		$('#copy_modal').modal('show');
	});

	$('.btn_copy_data_confirmed,.btn_copy_data').click(function(e){
		e.preventDefault();
		$.ajax( {
	      type: "POST",
	      url: copyDataMonth_url,
	      data: $('#form_copy').serialize(),
	      success: function( data ) {
	        var data = data.trim();
			ajax_respone_check(data,true);

			var jsonOBJ = JSON.parse(data);
			if(jsonOBJ.status == 'input_invalid'){
				$('.copy_msg').addClass('warning').html('入力データは正しくありません。<br>');
			}else if(jsonOBJ.status == 'from_is_to'){
				$('.copy_msg').addClass('warning').html('元サイトのデータは先サイトのデータと違いをお選びください。<br>');
			}else if(jsonOBJ.status == 'from_not_exist_data'){
				$('.copy_msg').addClass('warning').html('元サイトのデータがありません。上書きしますか？<br>');
				$('input[name=copy_confirmed]').val(1);
				$('.normal_button').hide();
				$('.confirm_button').show();
			}else if(jsonOBJ.status == 'target_exits_data'){
				$('.copy_msg').addClass('warning').html('コピー先サイトのデータが既に存在しています。上書きしますか？<br>');
				$('input[name=copy_confirmed]').val(1);
				$('.normal_button').hide();
				$('.confirm_button').show();
			}else if(jsonOBJ.status == 'success'){
				$('.copy_msg').removeClass('warning').html('コピーされました。<br>');
				$('.confirm_button').hide();
				$('.normal_button').show();
				$('input[name=copy_confirmed]').val(0);
				setTimeout(function(){
					$('.copy_msg').removeClass('warning').html('');
					$('#copy_modal').modal('hide');
					location.reload();
				}, 1000);
			}
	      }
	    });
	});

	if(auto_show_advice == '1'){
		$('.header_advice').trigger('click');
	}
});

