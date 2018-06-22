
<div class="row">
    <h2 class="commonh2"><?php echo $siteInfo['site_name']?>様 運営状況サマリ</h2>
    <h3 class="page-header">
        <?php
        if($site_target_key == 'transactions' || $site_target_key == 'transactions_1'){
        ?>
        CV数データ
        <?php
        }elseif($site_target_key == 'transactionRevenue'){
        ?>
        売上データ
        <?php
        }elseif($site_target_key == 'pageviews'){
        ?>
        PV数データ
        <?php
        }
        ?>
    </h3>

    <?php
    if($int_current_day == 1){
    //Begin == 1 ====================================================
    ?>
    <ul class="info_report col-md-12">

    <li class="col-md-12">
    <?php echo $start_date_display;?>〜<?php echo $current_date_display;?> 実績値
    </li>

    <li class="col-md-4">
    <span class="number_report total"><?php echo number_format($data_total_right_display['target_all'])?> <?php echo $site_target_unit;?>
    </span>

    </li>
    <li class="col-md-4">
    <i class="fa fa-desktop"></i> <span class="number_report"><?php echo number_format($data_total_right_display['target_desktop'])?> <?php echo $site_target_unit;?>
    </span>
    <br>
    <span class="small_note">
    （目標比 <?php echo number_format($data_total_right_display['target_desktop_percent'],1);?>%）
    </span>
    </li>
    <li class="col-md-4">
    <i class="fa fa-mobile-phone"></i> <span class="number_report"><?php echo number_format($data_total_right_display['target_tablet_phone'])?> <?php echo $site_target_unit;?>
    </span>
    <br>
    <span class="small_note">
    （目標比 <?php echo number_format($data_total_right_display['target_tablet_phone_percent'],1);?>%）
    </span>
    </li>
    </ul>

    <?php
    //End == 1 ======================================================

    }else{

    //Bengin  != 1 ==================================================
    ?>

    <div class="mainKpiTable">
        <!-- 実績値のbox -->
        <div class="mainKpiTable__boxCell">
            <div class="mainKpiBox__termlabel">
                <?php echo $start_date_display;?>〜<?php echo $current_date_display;?>&nbsp;実績値
            </div>
            <div class="mainKpiContentsTable">
              <!-- All Device -->
              <div class="mainKpiContent allDevice">
                <div class="mainKpiContent__contents">
                  <div>
                    <span class="mainKpiContent__contents__value"><?php echo number_format($data_total_left_display['total_left_after_check_change']);?></span>
                    <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                  </div>
                    <?php
                    if($data_total_left_display['total_left_after_check_change'] != $data_total_left_display['total_left_ga']){
                    ?>
                    <p class="mainKpiContent__contents__exceptFixData">
                      ※&nbsp;補正前: <?php echo number_format($data_total_left_display['total_left_ga']);?> <?php echo $site_target_unit;?>
                    </p>
                    <?php
                    }
                    ?>
                </div>
              </div>
              <!-- PC -->
              <div class="mainKpiContent pc">
                <label class="mainKpiContent__label">
                  <i class="fa fa-desktop"></i>
                </label>
                <div class="mainKpiContent__contents">
                  <div>
                    <span class="mainKpiContent__contents__value">
                        <?php
                        echo ($view_id !="" ? number_format($data_total_left_display['desktop_total']) : '-');
                        ?>
                        </span>
                    <span class="mainKpiContent__contents__unit">
                        <?php echo $site_target_unit;?>
                        </span>
                  </div>
                </div>
              </div>
              <!-- SP -->
              <div class="mainKpiContent sp">
                <label class="mainKpiContent__label">
                  <i class="fa fa-mobile"></i>
                </label>
                <div class="mainKpiContent__contents">
                  <div>
                    <span class="mainKpiContent__contents__value">
                        <?php
                        echo ($view_id !="" ? number_format(($data_total_left_display['mobile_total'] + $data_total_left_display['tablet_total'])) : "-");
                        ?>
                        </span>
                    <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                  </div>
                </div>
              </div>
            </div>
            </div>
            <!-- divider -->
            <div class="mainKpiTable__dividerCell">
              <i class="fa fa-caret-right"></i>
            </div>
            <!-- 到着見込みのbox -->
            <div class="mainKpiTable__boxCell">
              <div class="mainKpiBox__termlabel">
                到着見込（<?php echo $start_date_display;?>〜<?php echo $last_day_of_month?>&nbsp;予測値）
              </div>
              <div class="mainKpiContentsTable">
                <div class="mainKpiContent allDevice">
                  <div class="mainKpiContent__contents">
                    <div>
                      <span class="mainKpiContent__contents__value"><?php echo number_format($data_total_right_display['target_all'])?></span>
                      <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                    </div>
                    <?php
                    if($site_target_key != 'transactions' && $target_total != 0 && $data_total_right_display['target_all_percent'] != 0){
                    ?>
                    <p class="mainKpiContent__contents__targetRate">
                      （目標比<span><?php echo number_format($data_total_right_display['target_all_percent'],1);?>%</span>）
                    </p>
                    <?php
                    }
                    ?>
                  </div>
                </div>
                <div class="mainKpiContent pc">
                  <label class="mainKpiContent__label">
                    <i class="fa fa-desktop"></i>
                  </label>
                  <div class="mainKpiContent__contents">
                    <?php
                    if($view_id != ""){
                    ?>
                    <div>
                      <span class="mainKpiContent__contents__value"><?php echo number_format($data_total_right_display['target_desktop'])?></span>
                      <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                    </div>
                    <p class="mainKpiContent__contents__targetRate">
                    （構成比<span><?php echo number_format($data_total_right_display['target_desktop_percent'],1);?>%</span>）
                    </p>
                    <?php
                    }else{
                    ?>
                    <div>
                      <span class="mainKpiContent__contents__value">-</span>
                      <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                    </div>
                    <?php
                    }
                    ?>
                  </div>
                </div>
                <div class="mainKpiContent sp">
                  <label class="mainKpiContent__label">
                    <i class="fa fa-mobile"></i>
                  </label>
                  <div class="mainKpiContent__contents">
                    <?php
                    if($view_id != ""){
                    ?>

                    <div>
                      <span class="mainKpiContent__contents__value"><?php echo number_format($data_total_right_display['target_tablet_phone'])?></span>
                      <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                    </div>
                    <p class="mainKpiContent__contents__targetRate">
                    （構成比<span><?php echo number_format($data_total_right_display['target_tablet_phone_percent'],1);?>%</span>）
                    </p>

                    <?php
                    }else{
                    ?>

                    <div>
                      <span class="mainKpiContent__contents__value">-</span>
                      <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                    </div>

                    <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
        </div>
    <?php
    //End  != 1 =============================================================
    }
    ?>

</div>

<div id="chart">
<div class="row">
        <div class="col-md-12">
            <div class="report_wrap" style="width:100%">
                <div class="button_display_chart btn-group">
                <button class="btn btn-default btn_load_chart" char-name="day">日別</button>
                <button class="btn btn-default btn_load_chart" char-name="calculate">累計</button>
                </div>
                <div id="report_div">
                    <div style="position: relative; min-height: 150px">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>

<div class="row">
    <h3 class="page-header">主要KPIの状況
        <?php 
        if($allow_action['change_site_kpi']){
        ?>
        <button type="button" class="btn btn-sm btn-default pull-right style="margin-bottom:10px" id="changeKPIBtn" data-toggle="modal" data-target="#KPIBtn">KPIを変更</button>
        <?php
        }else{
        ?>
        <button type="button" class="btn btn-sm btn-default pull-right style="margin-bottom:10px" disabled id="changeKPIBtn" data-toggle="modal" data-target="#KPIBtn">KPIを変更</button>
        <?php
        }
        ?>
    </h3>
        <!--Popup-->
        <div class="modal fade" id="KPIBtn" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"> 表示するKPIを選択してください </h4>
                    </div>
                        <div class="modal-body">
                            <div class="kpi_setting_msg" style="text-align:center"></div>
                            <form>
                                <div id="listResults">
                                <!--kpi-->
                                <?php
                                foreach($kpis_list as $kpi_item){
                                ?>

                                <div class="checkbox checkbox-blue">
                                <input class="kpi_setting" type="checkbox" <?php if(in_array($kpi_item['key'],$report_kpi_checked)) echo "checked";?> value="<?php echo $kpi_item['key'];?>" >
                                <label><?php echo $kpi_item['title']?></label>
                                </div>

                                <?php
                                }
                                ?>
                                <!--end kpi-->

                                </div>
                            </form>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn_save_kpi_setting" >更新</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    </div>
                </div>

            </div>
        </div>
        <!--Popup-->


        <p class="textFrontBox">
          ※各数値データは、計測対象期間から24時間経過後に、数値が最終確定します。<br>（計測対象期間から24時間以内は、数値データが変動する場合があります。）
        </p>


        <?php
        foreach($kpis_list as $kpi_item){
            if(in_array($kpi_item['key'],$report_kpi_checked)){
            ?>
            <!--transactionRevenue-->
            <div class="box">
            <div class="itemFrontbox">
            <strong><i class="<?php echo $kpi_item['icon']?>"></i> <?php echo $kpi_item['title']?></strong>

            <?php
            //Icon status box
            if($data_status_box[$kpi_item['key']] == 1){
            ?>
            <i class="fa success fa-check-circle-o success_icon"></i>
            <?php
            }else{
            ?>
            <i class="fa fa-exclamation-triangle error_icon"></i>
            <?php
            }
            //End icon status box
            ?>
            </div>
            <ul class="sub_box">
                <li><?php echo $start_date_display;?>〜<?php echo $current_date_display;?> 実績値</li>
                <li class="big_number">
                <?php
                $number = $this->Shishimai->displayActualKPIpageV2($kpi_item['key'],$data,$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);

                if($kpi_item['key'] == 'transactionRevenue' || $kpi_item['key'] == 'revenuePerTransaction_1' || $kpi_item['key'] == 'revenuePerTransaction'){
                    if($number != '-'){
                        echo $kpi_item['pre_char'].str_replace("¥",'',$number);
                    }else{
                        echo $number;
                    }
                }elseif(in_array($kpi_item['key'],array('topBounceRate'))){
                    if($number == "0"){
                        echo "-";
                    }else{
                        echo $number;
                    }
                }else{
                    echo $number;
                }
                ?>
                </li>
            </ul>

            <ul class="sub_box clearfix mgbt">
                <li><?php echo $start_date_display;?>〜<?php echo $last_day_of_month?> 予測値</li>
                <li class="big_number">
                <?php $number = $this->Shishimai->displayActualKPIpageV2($kpi_item['key'],$data_predict,$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);
                if($kpi_item['key'] == 'transactionRevenue' || $kpi_item['key'] == 'revenuePerTransaction_1' || $kpi_item['key'] == 'revenuePerTransaction'){
                    if($number != '-'){
                        echo $kpi_item['pre_char'].str_replace("¥",'',$number);
                    }else{
                        echo $number;
                    }
                }elseif(in_array($kpi_item['key'],array('topBounceRate'))){
                    if($number == "0"){
                        echo "-";
                    }else{
                        echo $number;
                    }
                }else{
                    echo $number;
                }
                ?>
                </li>
                <li><span class="left">目標値</span><span class="right">
                <?php echo $this->Shishimai->displayZeroDashTargetHome($data_target[$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],1);?>
                </span></li>
                <li><span class="left">目標比</span><span class="right">
                <?php echo $this->Shishimai->displayZeroDash($data_target_rate[$kpi_item['key']],"number","","%",1);?>
                </span></li>
                <li><span class="left">前月比</span><span class="right">
                <?php echo $this->Shishimai->displayZeroDash($data_prevM_compare[$kpi_item['key']],"number","","%",1);?>
                </span></li>
                <li><span class="left">前年比</span><span class="right">
                <?php echo $this->Shishimai->displayZeroDash($data_prevY_compare[$kpi_item['key']],"number","","%",1);?>
                </span></li>
            </ul>

            </div>

            <?php
            }
        }
        ?>
    </div>
<!--========= Row advice chart ===========-->
<div>
    <div class="row">
        <h3 class="page-header">６ヶ月施策表 <a class="btn btn-sm btn-default" href="<?php echo $this->Html->url('/OttKpi?site_id='.$site_id.'&auto_show_advice=1&show_list_month=1')?>" target="_blank">変更する</a></h3>
        <div class="wrap_advice_chart col-md-12 clearfix" style="width:100%;height:400px">
            <div style="position: relative; min-height: 150px;padding-top:150px">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</div>
<!--========= Row advice chart ===========-->

<!--========= Row advice chart ===========-->
<div>
    <div class="row" style="margin-bottom:20px">
        <div class="name_over">
            <h3 class="page-header">6ヶ月施策表</h3>
            <div class=" col-md-12 clearfix">
                <table class="table reportTable weeklyTable reportTable--weekly">
                    <tr>
                        <th width="20%"></th>
                        <?php
                        foreach($advice_range_data as $adv_month){
                            ?>
                            <th width="13.3%"><?php echo $adv_month['m']?>月</th>
                            <?php
                        }
                        ?>
                    </tr>


                    <?php
                    if(count($arr_advKey_map_advData)){
                        foreach($arr_advKey_map_advData as $note_key=>$key_data){
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    $str = nl2br(htmlspecialchars($advice_note_keys[$note_key]));

                                    $ex = explode('<br />',$str);
                                    if(count($ex) > 1){
                                        echo $ex[0]."<br>".$ex[1];
                                    }else{
                                        echo $ex[0];
                                    }
                                    ?>
                                </td>

                                <?php
                                foreach($advice_range_data as $adv_month){
                                    ?>
                                    <td>
                                        <?php
                                        echo nl2br(htmlspecialchars($key_data[$adv_month['m']]));
                                        ?>
                                    </td>
                                    <?php
                                }
                                ?>

                            </tr>
                            <?php
                        }
                    }else{
                        ?>
                        <tr><td colspan='7' style="text-align:center"> データがございません。 </td></tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!--========= Row advice chart ===========-->
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script>
var updateKpiReport_url     = "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateSiteKPI']);?>";
var current_url             = "<?php echo $this->request->here();?>";
var site_id                 = "<?php echo $site_id?>";
var arr_day_string          = [<?php echo $day_string;?>];
var arr_actual_value_chart_string   = [<?php echo $actual_value_chart_string;?>];
var arr_target_value_chart_string   = [<?php echo $target_value_chart_string;?>];
var arr_actual_value_chart_2_string = [<?php echo $actual_value_chart_2_string;?>];
var arr_target_value_chart_2_string = [<?php echo $target_value_chart_2_string; ?>];
var first_day_of_week               = [<?php echo $list_start_day_of_week?>];
//Advice Url
var getAdviceChart_url     = "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'getAdviceChart']);?>";
</script>
<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
<script src="<?php echo $this->Html->url('/ott')?>/js/otthome.js"></script>
<link rel="stylesheet" href="<?php echo $this->Html->url('/ott')?>/css/otthome.css">
<link rel="stylesheet" href="<?php echo $this->Html->url('/ott')?>/css/styleCheckbox.css">
