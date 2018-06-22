<div class="mainContents nofooter">
    <div class="">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="clearfix">
                    <h2 class="commonh2" style="float: left;">クライアント情報編集</h2>
                </div>
                <form class="edit_project" id="fromEdit" action="<?php echo $this->Html->url('/SsmClient/edit/site_id:' . $site_id); ?>" accept-charset="UTF-8" method="post">
                    <h4 class="projectShowh4">企業情報</h4>
                    <div class="projectShowContents">
                        <div class="form-group">
                            <label class="projectFieldsLabel">サイト名</label>
                            <div class="projectFieldWrapper">
                                <div class="form-group ">
                                    <input class="form-control" style="width: 300px;" type="text" name="site_name" value="<?php echo $this->shishimai->show_data(site_name,$info_site['SsmSite']['site_name'])?>" />  
                                    <p class="ruleForm"><?php echo $error_site_name; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="projectFieldsLabel">サイトアドレス</label>
                            <div class="projectFieldWrapper">
                                <div class="form-group ">
                                    <input class="form-control" style="width: 300px;" type="text" name="site_url" value="<?php echo $this->shishimai->show_data(site_url,$info_site['SsmSite']['site_url'])?>"/>
                                    <p class="ruleForm"><?php echo $error_site_url; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <label class="projectFieldsLabel">企業名</label>
                                <div class="projectFieldWrapper">
                                    <div class="form-group ">
                                        <input class="form-control" style="width: 300px;" type="text" name="site_description" value="<?php echo $this->Shishimai->show_data('site_description', $info_site['SsmSite']['site_description'])?>"/>
                                        <p class="ruleForm"><?php echo $error_site_description; ?></p> 
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="projectShowContents">
                        <div class="form-group" id="projectOwnerFormGroup">
                            <label class="projectFieldsLabel">
                                ウェブお手伝いさん主担当者
                                <a data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-trigger="hover" title="主担当者とは？" data-content="
        ①担当クライアント様の主担当者として、Otetsudai画面上の様々なところに、顔写真や名前が表示されます<br /><br />
        ②Otetsudai上の情報を、チャットワークに自動連携することが可能ですが（任意）、その際には主担当者の方がコメントする形でチャットワークに情報が表示されます
      " style="    margin-left: 15px;font-weight: normal;">
                                    <i class="fa fa-question-circle"></i>&nbsp;主担当者とは?
                                </a>
                            </label>
                            <div class="projectFieldWrapper">
                                <div class="form-group ">
                                    <select class="form-control" style="width: 300px;margin: 0;" name="user_id" >
                                        <?php foreach($info_user as $user){
                                        ?>
                                            <option value="<?php echo $user['SsmUser']['id'] ;?>" id_cw_admin="<?php echo $user['SsmUser']['chatwork_api']?>" <?php if($this->shishimai->show_data('user_id',$default_user_id) == $user['SsmUser']['id']){ echo 'selected';}?>>
                                            <?php echo $user['SsmUser']['first_name']; ?> <?php echo $user['SsmUser']['last_name']; ?></option>
                                        <?php
                                        }?>
                                    </select>
                                </div>
                                <p class="ruleForm"><?php echo $error_site_manage_user; ?></p>
                            </div>
                        </div>
                    </div>

                    <h4 class="projectShowh4">契約情報</h4>
                    <p class="ruleForm"><?php echo $error_contract; ?></p>
                    <div class="name_over">
                        <div class="projectShowContents">
                        <table class="table" style="table-layout: auto;">
                            <tbody id="contractFieldsForWrapper">
                                <tr>
                                    <th>期間</th>
                                    <th style="width: 270px;">プラン</th>
                                    <th style="width: 150px;">月額利用料金</th>
                                    <th style="width: 200px;"></th>
                                </tr>
                                <!--Chatwork send to-->

                                <?php 
                                if( isset($validate_contract) || isset($validate_site) || isset($check_contract) || isset($validate_chatwork)){
                                    foreach($arr_contract as $id_contract => $contract){
                                ?>
                                <tr class='fields contractFields'>
                                    <td>
                                        <div class='form-group form-group-inline'>
                                        <input class='form-control datepicker' autocomplete="off" style='display: inline-block;' type='text' name='start_day[<?php echo $id_contract?>]' check_se='start_day' value='<?php echo $contract[start_day] ;?>' disabled="disabled" required/><p class="ruleForm"><?php echo $error_start_day; ?></p></div>〜<div class='form-group form-group-inline'>
                                        <input class='form-control datepicker' autocomplete="off" style='display: inline-block;' type='text' name='end_day[<?php echo $id_contract?>]' check_se='end_day' value='<?php echo $contract[end_day]?>' disabled="disabled" required/><p class="ruleForm"><?php echo $error_end_day; ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='form-group'>
                                            <select class='form-control contractFields__plan' name='plan[<?php echo $id_contract?>]' disabled="disabled">

                                                <?php foreach($plan as $id => $item){
                                                  ?>
                                                    <option value="<?php echo $item['SsmPlan']['id']; ?>" 
                                                        <?php if($contract[plan_id] == $item['SsmPlan']['id'])
                                                        {
                                                        echo 'selected';
                                                        } ;?> 
                                                        price="<?php echo $item['SsmPlan']['price']; ?>" class="name" >
                                                        <?php echo $item['SsmPlan']['name']; ?>
                                                    </option>
                                                  <?php
                                                  }?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='form-group'>
                                            <input class="form-control contractFields__monthlyFee price" check_dis type="number" name="price[<?php echo $id_contract?>]" value="<?php echo $contract[price] ?>" disabled="disabled"/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='form-group form_edit_plan'>
                                            <span class="edit_plan" style="cursor:pointer">編集</span><span> / </span>
                                            <span class="fa remove-item remove_plan btn-confirm" style="cursor: pointer" plan_id="<?php echo $contract['SsmContract']['id']?>">削除</span>

                                            </div>
                                    </td>
                                </tr>

                                <?php
                                    }
                                } else {
                                foreach($info_contract as $key => $contract){
                                ?>

                                <tr class="fields contractFields disabled">
                                    <td>
                                        <div class='form-group form-group-inline'>
                                            <input class='form-control datepicker' autocomplete="off" style='display: inline-block;' type='text' name='start_day[<?php echo $key?>]' value='<?php echo $this->shishimai->show_date($contract['start_day']); ?>' check_se="start_day" required/>
                                            <p class="ruleForm"><?php echo $error_start_day; ?></p>
                                        </div>
                                         〜
                                        <div class='form-group form-group-inline'>
                                            <input class='form-control datepicker' autocomplete="off" style='display: inline-block;' type='text' name="end_day[<?php echo $key?>]" value='<?php echo $this->shishimai->show_date($contract['end_day']); ?>' check_se="end_day" required/>
                                            <p class="ruleForm"><?php echo $error_end_day; ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='form-group'>
                                            <input type="hidden" name="SsmContract_id[<?php echo $key?>]" value="<?php echo $contract['id']?>">
                                            <select class='form-control contractFields__plan' name='plan[<?php echo $key?>]' >
                                                <?php foreach($plan as $id_plan => $name_plan){
                                                ?>

                                                <option
                                                <?php
                                                if($name_plan['SsmPlan']['id'] == $contract['plan_id']){
                                                    echo "selected" ;
                                                }
                                                ?>
                                                value="<?php echo $name_plan['SsmPlan']['id']?>"
                                                price="<?php echo $name_plan['SsmPlan']['price']?>" >
                                                <?php echo $name_plan['SsmPlan']['name'] ?>
                                                </option>
                                                <?php
                                                }?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='form-group'>
                                            <input class='form-control contractFields__monthlyFee price contractFields__plan' check_dis type='number' name='price[<?php echo $key?>]'
                                            value='<?php echo $contract['price']?>' />
                                        </div>

                                    </td>
                                    <td>
                                            <div class='form-group form_edit_plan'>
                                            <span class="edit_plan" style="cursor:pointer">編集</span><span> / </span>
                                            <span class="fa remove-item remove_plan btn-confirm" style="cursor: pointer" plan_id="<?php echo $contract['id']?>">削除</span>
                                            </div>
                                    </td>
                                </tr>
                                <?php
                                }
                                }?>
                                </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="100%" style="text-align: center;">
                                        <p class="btn btn-primary" id='addContractBtn' style="width: 150px;margin-top: 15px;"><i class="fa fa-plus-square-o"></i> 追加</p>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                    </div>

                    <h4 class="projectShowh4">工数</h4>
                    
                    <div class="name_over" style="clear:both;min-height:100px">
                        <div class="projectShowContents">
                            <div class="projectFieldWrapper">
                                <div class="form-group">
                                    <input class="form-control" style="width: 260px;float:left;margin-left :10px" type='number' step="1" min='0' placeholder="月の金額" name="site_price" value="<?php echo $this->Shishimai->show_data('site_price',$defaut_site_price)?>">                                    
                                    <input class="form-control" style="width: 260px;float:left;margin-left :10px" type='number' step="1" min='0' placeholder="1時間当たりの工数" name="site_price_per_hour" value="<?php echo $this->Shishimai->show_data('site_price_per_hour',$defaut_site_price_per_hour)?>">                                    
                                    <input class="form-control site_hour_calculate" style="width: 260px;float:left;margin-left :10px" disabled>
                                </div>
                                <p style="clear:both;margin-left :10px" class="ruleForm site_price_per_hour"><?php echo $error_site_price; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="projectShowh4">外部連携情報</h4>
                    <div class="projectShowContents">
                        <div class="analyticsToolInfo__ga">
                            <div class="form-group">
                                <label>Google Analytics 連携ユーザを追加</label>
                                <div class="projectFieldWrapper">
                                    <div class="well">
                                        Google Analyticsにログインし、「アナリティクス設定」&nbsp;>&nbsp;ビューの「ユーザー管理」&nbsp;>&nbsp;「権限を付与するユーザー」に下記メールアドレスを入力して追加を行ってください。
                                        <br/>
                                        <code><?php echo $cf_ga_email_service; ?></code>
                                        <br/> ※&nbsp;OtetsudaiからAPIを通してアクセスするユーザーです。 ※&nbsp;「表示と分析」の権限を付与してください。
                                    </div>
                                </div>
                                <label id="input_room_id" class="projectFieldsLabel">Google Analytics ビューID</label>
                                <div class="projectFieldWrapper">
                                    <div class="form-group ">
                                        <input class="form-control" style="width: 300px;" value="<?php echo $this->shishimai->show_data(ga_view_id, $info_site['SsmSite']['ga_view_id']) ;?>" name="ga_view_id" onchange="$(this).val($(this).val().trim());" />
                                        <p class="ruleForm"><?php echo $error_site_ga_view_id; ?></p> 
                                    </div>
                                    <p>
                                        <small>※&nbsp;「アナリティクス設定」&nbsp;>&nbsp;ビューの「ビュー設定」&nbsp;>&nbsp;「ビューID」から確認できます。</small>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="projectFieldWrapper">
                                    <label for="project_is_ecommerce">
                                        <input type="checkbox" value="1" name="ecommerce" 
                                        <?php echo $checked_ecommerce;?> 
                                        /> e-commerce設定あり
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="projectFieldWrapper">
                                    <a class="btn btn-sm btn-default confirmSettingBtn" data-type="ga">連携を確認</a>
                                </div>
                            </div>
                        </div>

                        <i id="adwordsFormGroupAnchor" class="anchor"></i>
                        <div class="form-group">
                            <label class="projectFieldsLabel">Google&nbsp;Adwords&nbsp;お客様ID</label>
                            <div class="projectFieldWrapper">
                                <div class="form-group">
                                    <input class="form-control" style="width: 300px;" type="text" value="<?php echo $this->shishimai->show_data(ga_adword, $info_site['SsmSite']['ga_adword']) ;?>" name="ga_adword"  onchange="$(this).val($(this).val().trim());" />
                                </div>

                                <p>※&nbsp;ハイフン(-)を省き、半角数字のみでご入力ください。</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="projectFieldsLabel">ChatWork&nbsp;連携先ルームID</label>
                            <div class="projectFieldWrapper">
                                <div class="cw_room_admin form-group">
                                    <input type="" name='room' class="form-control" style="width:300px" onchange="$(this).val($(this).val().trim());" value="<?php 
                                    if( isset($room_id_inputed) ){
                                        echo $room_id_inputed; 
                                    }else {
                                        echo $info_site['SsmSite']['chatwork_id'];
                                    } ?>"/> <!-- <span class="ruleForm"><?php echo $error_validate_chatwork; ?></span> -->
                                    <div id="goto"></div>
                                </div>
                                <div class="well">
                                    <p style="margin-bottom: 0;">
                                        ※&nbsp;​ ルームIDを設定すると、連携先のチャットルームに「ウェブお手伝いさん主担当者」の情報で自動投稿されます。
                                        <br/> ChatWork連携を設定するには、下記３点の設定をする必要があります。
                                    </p>
                                    <ul>
                                        <li style="list-style:none">１. 選択した主担当者がChatWork連携を設定する<small style="margin-left: 30px;"></small></li>
                                        <li style="list-style:none">２. 主担当者名を選択する<small style="margin-left: 30px;"></small></li>
                                        <li style="list-style:none">３. 保存をする。</li>
                                        <li style="list-style:none">※&nbsp;​ ChatWork連携がしていない、ルームIDが間違っている、ルームに担当者が存在しない場合は保存できません。</li>
                                    </ul>
                                </div>

                                <input type="hidden" name="fromEdit" value="1">

                                <div class="modal fade modal_cw" id="myModal">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">チャットワークグループの選択</h4>
                                        </div>
                                        <div class="modal-body" id="cwRoomsWrapper">

                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <!--CW from CW-->
                                <input type="hidden" name="cw_api" value="<?php 
                                if( isset($check_cw_api) ){
                                    echo $check_cw_api; 
                                } else { 
                                    foreach($info_user as $id => $user){
                                        if($user['SsmUser']['id'] == $info_site['SsmSite']['site_manage_user']){
                                            echo $user['SsmUser']['chatwork_api'];
                                        }
                                    }
                                }?>">
                                <input type="hidden" name="chatwork_id" value="<?php echo $this->Shishimai->show_data('chatwork_id', $chatwork_id); ?>">

                                <script type="text/javascript">
                                var getRoomCwUrl = "<?php echo $this->Html->url('/ShishimaiApi/getRoomCw'); ?>";
                                var cw_api = "<?php echo $this->Shishimai->show_data('cw_api', $cw_api); ?>"; 
                                var chatwork_id = "<?php echo $this->Shishimai->show_data('chatwork_id', $chatwork_id); ?>";

                                $(document).ready(function(){
                                    $('select[name=user_id]').on('change', function(){
                                        id_cw_admin = $(this).find('option:selected').attr('id_cw_admin');
                                        $('input[name=cw_api]').val(id_cw_admin);
                                    });

                                    $('.error').hide();
                                    $('#CwRoomsBtn').click(function(){
                                    $('#cwRoomsWrapper').find('p').remove();
                                        var cw_api = $('input[name=cw_api]').val();
                                        $.post(getRoomCwUrl,
                                            {
                                                cw_api:cw_api
                                            }
                                        ).done(
                                            function(res){
                                                if(res.trim() != "not_found_key" && res.trim() != "method_wrong"){
                                                    var x = JSON.parse(res);
                                                    var check_room = '';
                                                    for(var i = 1; i < x.length; i++){
                                                        var room_id = $('input[name=room]').val();
                                                        if(room_id == x[i].room_id){
                                                            setTimeout(function(){
                                                            $('.success').text('キーチャットワークは保存されました。').fadeIn();
                                                            }, 100);
                                                            setTimeout(function(){
                                                                $('.success').fadeOut();
                                                            }, 2500);
                                                        }
                                                    }

                                                    if($('#cwRoomsWrapper input').hasClass('room')){

                                                    } else {
                                                        setTimeout(function(){
                                                            $('.error').text('キーは無効です。').fadeIn();
                                                        }, 100);
                                                        setTimeout(function(){
                                                            $('.error').fadeOut();
                                                        }, 2500);
                                                    }

                                                }
                                                else if(res.trim() == "not_found_key"){
                                                        setTimeout(function(){
                                                            $('.error').text('キーは見つかりません。').fadeIn();
                                                        }, 100);
                                                        setTimeout(function(){
                                                            $('.error').fadeOut();
                                                        }, 2500);
                                                } else {
                                                    setTimeout(function(){
                                                            $('.error').text('方法は間違っています。').fadeIn();
                                                        }, 100);
                                                        setTimeout(function(){
                                                            $('.error').fadeOut();
                                                        }, 2500);
                                                }
                                            }
                                        );
                                    });
                                });


                                </script>
                            </div>
                            <div class="form-group">
                                <div class="projectFieldWrapper">
                                    <label for="project_is_auto_send_cw">
                                        <input type="checkbox" value="1" name="auto_send_cw" 
                                        <?php echo $checked_auto_send_cw;?> 
                                        /> チャットワークへの営業時間のお知らせあり
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="projectFieldWrapper">
                                    <label for="project_is_show_on_menu">
                                        <input type="checkbox" value="1" name="show_on_menu" 
                                        <?php echo $checked_show_on_menu;?> 
                                        /> クライアント一覧に表示する
                                    </label>
                                </div>
                            </div>

                        </div>
                        <!-- todo AdwordsとスポンサードサーチのAPI連携設定情報 -->
                    </div>
                    <h4 class="projectShowh4">その他</h4>
                    <div class="projectShowContents">
                        <input type="hidden" name="satisfaction" value="1">
                        <div class="form-group" style="margin-top: 30px;">
                            <label class="projectFieldsLabel">その他メモ</label>
                            <div class="projectFieldWrapper">
                                <div class="form-group ">
                                    <textarea class="form-control" rows="5" name="remarks"><?php echo $this->shishimai->show_data( 'remarks',$info_site['SsmSite']['site_note']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-bottom: 100px">
                        <input type="submit" name="commit" value="保存" class="btn btn-lg btn-primary complete" />
                    </div>
                    <input type="hidden" name="confirm_change_view_id" value="0">
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<!--Popup delete-->
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="mi-modal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-body">
        <p style="text-align:center">本当に削除しますか？この処理は戻せません。</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="modal-btn-si">はい</button>
        <button type="button" class="btn btn-default" id="modal-btn-no">キャンセル</button>
      </div>
    </div>
  </div>
</div>
<!--Popup delete-->

<script type="text/javascript">
var chatworkUrl = "<?php echo $this->Html->url('/SsmClient/edit_chatwork/site_id:' . $site_id); ?>";
var checkGA = "<?php echo $this->Html->url('/ShishimaiApi/checkGaKey'); ?>";
var removePlanUrl = "<?php echo $this->Html->url('/SsmClient/remove_plan'); ?>";
</script>

<p class="error"></p>
<p class="success"></p>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    // script_ssmclient.js will use this
    var manHoursRate = <?php echo $manHoursRate ?: 0.8 ?>;
</script>
<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
<script src="<?php echo $this->Html->url('/ott/js/script_ssmclient.js'); ?>"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->Html->url('/ott/css/style_ssmclient.css'); ?>">

<script type="text/javascript">
    //addContractBtn
    var allow_add = true;
    var total_contract = $('.contractFields').length;
    var num = total_contract + 1;

    $('#addContractBtn').click(function(){

        var n = $(this).parents('.table').find('#contractFieldsForWrapper').find('.contractFields').find('input[check_se]');

        allow_add = true;
        if(n.length > 0) {
            for (i = 0; i < n.length; i++) {
                if (n[i].value == "") {
                    disable_button();
                }
                if(n[i].value != ""){
                    n[i].setAttribute("disabled", "disabled");
                    var p =   n[i].parentNode;
                    var p1 = p.parentNode.nextElementSibling;
                    var p2 = p1.getElementsByTagName('select')[0];
                    p2.setAttribute("disabled", "disabled");
                    var s = p1.nextElementSibling;
                    var s1 = s.getElementsByTagName('input')[0];
                    s1.setAttribute("disabled", "disabled");
                }
            }
        }

        if(!allow_add){
             return false;
        }

        var x = "<tr class='fields contractFields'><td><div class='form-group form-group-inline'><input class='form-control datepicker' style='display: inline-block;' type='text' autocomplete='off' name='start_day["+num+"]' stt="+num+" check_se='start_day' required/></div>〜<div class='form-group form-group-inline'><input class='form-control datepicker' style='display: inline-block;' type='text' autocomplete='off' name='end_day["+num+"]' stt="+num+" check_se='end_day' required/></div></td><td><div class='form-group'><select class='form-control contractFields__plan' name='plan["+num+"]' >";

        <?php 
        foreach($plan as $id => $item)
        {
            $name = $item['SsmPlan']['name'];
            $price = $item['SsmPlan']['price'];

            ?>
            x +="<option value='<?php echo $item['SsmPlan']['id']; ?>' price='<?php echo $price;?>' class='name'><?php echo $name;?></option>";
        <?php
        }

        $first_price = $plan[0]['SsmPlan']['price'];
        ?>

        x +="</td><td><div class='form-group'><input class='form-control contractFields__monthlyFee price' type='number' check_dis name='price["+num+"]' value='<?php echo $first_price;?>' selected /></div></td>";
        x +="<td><div class='form_edit_plan'><span class='edit_plan' style='cursor:pointer'>編集</span><span> / </span><span style='cursor:pointer' class='fa remove-item btn-confirm'>削除</span></div></td></tr>";

        $('#contractFieldsForWrapper tr').last().after($(x));

        $( ".datepicker" ).datepicker({
            onSelect: function(dateText, inst) {
                xuly($(this).attr('stt'),$(this).attr('name'),dateText, $(this));
            },
            dateFormat: 'yy/mm/dd'
        });

        allow_add = false;
        num++;

        $('.edit_plan').click(function(){
            $(this).parents('.contractFields').find('input').removeAttr('disabled');
            $(this).parents('.contractFields').find('select').removeAttr('disabled');
        });
    });

    $(document).ready(function(){
        //Set default cw
        var df_user_id = $('select[name=user_id]').val();
        if(df_user_id > 0){
            $('input[name=cw_api]').val($('select[name=user_id] > option:selected').attr('id_cw_admin'));
        }
    });

</script>
</body>
</html>
<script src="<?php echo $this->Html->url('/ott/js/validate_contract.js'); ?>"></script>

