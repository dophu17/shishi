$(document).ready(function(){
    $('.success').hide();
    $('.error').hide();
	//Submit send to chatwork info
    $('.get_chatwork').click(function(e){  
    	e.preventDefault();
        $('.edit_project').attr('action',chatworkUrl);
        $('input').removeAttr('disabled');
        $('select').removeAttr('disabled');
        $('.edit_project').submit();
    });

    //style calender
    $( ".datepicker" ).datepicker();

    //Set price for contract
    // $('.price').val($('.contractFields__plan').find(":selected").attr("price"));
    $(document).on('change','select.contractFields__plan',function(){
	    var x = $(this).find(":selected").attr("price");
	    $(this).parents('.contractFields').find('.price').val(x);
    });

    // popover
    $('[data-toggle="popover"]').popover();

   	//delete contract 
   	$(document).on('click', ".btn-confirm", function(){
   		$("#mi-modal").modal('show');
        $('.contractFields').attr('deleting','false');
        $(this).parents('.contractFields').attr('deleting','true');
        delete_plan_id = $(this).attr('plan_id');
        allow_add = true;
        //Check last contract
		// prevent_add_contract($(this));
   	});
   
	//check GA
	$('.confirmSettingBtn').click(function(){
		var ga_view_id = $('input[name=ga_view_id]').val();
		$.post(
			checkGA,
			{
				ga_view_id: ga_view_id
			},
			function(res){
                ajax_respone_check(res,true);
				if(res.trim() == 'INVALID_GA_VIEW_ID' || res.trim() == 'VIEW_ID_EMPTY'){
                    $('.error').text('Google Analyticsとの連携が失敗しました。再確認してください。').fadeIn();
                    setTimeout(function(){
                        $('.error').fadeOut();
                    }, 2500);
				} else if(res.trim() == 'SUCCESS'){
                    $('.success').text('Google Analyticsとの連携が成功しました。').fadeIn();
                    setTimeout(function(){
                        $('.success').fadeOut();
                    }, 2500);
				}
			}
			).fail(function(xhr) {
				$('.error').text('Google Analyticsとの連携が失敗しました。再確認してください。').fadeIn();
                setTimeout(function(){
                    $('.error').fadeOut();
                }, 2500);
			});;
	});


    //calculate site price
    $('.site_hour_calculate').val(calculateSitePrice($('input[name=site_price]').val(),$('input[name=site_price_per_hour]').val()));


    $('input[name=site_price] ,input[name=site_price_per_hour]').change(function(){
        var cr_val = $(this).val();
        if(!isInteger(cr_val)){
            $('.site_price_per_hour').text('数値で入力してください。');
        }else{
            $('.site_price_per_hour').text('');
            $('.site_hour_calculate').val(calculateSitePrice($('input[name=site_price]').val(),$('input[name=site_price_per_hour]').val()));
        }        
    });
});

//show id room chatwork
$(document).on('change', 'input[type=radio]', function(){
    var x = $(this).val();
    $('input[name=chatwork_id]').val(x);
    $('.cw_room_admin').html(x);
});

//Get calculate string
function calculateSitePrice(price,price_per_hour){
    if(price == 0 || price == "" || price_per_hour == 0 || price_per_hour == ""){
        return '';
    }

    return number_format((price * manHoursRate) / price_per_hour,2);
}
