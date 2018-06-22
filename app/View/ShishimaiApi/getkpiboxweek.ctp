<div class="row">

        <?php
        foreach($kpis_list as $kpi_item){
            if(in_array($kpi_item['key'],$report_kpi_checked)){
        ?>

        <div class="boxS">
        <div class="itemFrontbox">
        <strong><i class="<?php echo $kpi_item['icon'];?>"></i> <?php echo $kpi_item['title'];?></strong>

        <?php
        //Icon status box
        if($data_status_box[$kpi_item['key']]){
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

        <?php
        if($week_in == 'present'){
        ?>
        <ul class="sub_box">
            <li><?php echo $start_date_display;?>〜<?php echo $current_date_display;?> 実績値</li>
            <li class="big_number">
                <?php
                $number = $this->Shishimai->displayActualKPIpageV2($kpi_item['key'],$actual,$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);

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
        <?php
        }
        ?>

        <ul class="sub_box clearfix mgbt">
            <li><?php echo $start_date_display;?>〜<?php echo $last_day_of_month?> 予測値</li>
            <li class="big_number">
                <?php
                $number = $this->Shishimai->displayActualKPIpageV2($kpi_item['key'],$data_predict,$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);

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
                <?php echo $this->Shishimai->displayZeroDashTargetReport($data_target[$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
            </span></li>
            <li><span class="left">目標比</span><span class="right"><?php echo $this->Shishimai->displayZeroDash($data_target_rate[$kpi_item['key']],'number','','%',$kpi_item['decimal']);?></span></li>
            <li><span class="left">前月比</span><span class="right"><?php echo $this->Shishimai->displayZeroDash($data_prevM_compare[$kpi_item['key']],'number','','%',$kpi_item['decimal']);?>
            </span></li>
            <li><span class="left">前年比</span><span class="right"><?php echo $this->Shishimai->displayZeroDash($data_prevY_compare[$kpi_item['key']],'number','','%',$kpi_item['decimal']);?></span></li>
        </ul>
        </div>
        <?php
            }
        }
        ?>
</div>