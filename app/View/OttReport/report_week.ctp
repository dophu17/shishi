<div class="mainContents nofooter">
    <div class="">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="text-align: center;">
                <h2 style="margin-bottom: 50px;">週次レポート新規作成</h2>
                <form class="new_weekly_report" id="new_weekly_report" accept-charset="UTF-8" action="<?php echo $this->Html->url('/OttReport/report_week/site_id:' . $site_id); ?>" method="post">
                    <div class="form-group">
                        <label style="margin-right: 15px;">年</label>
                        <select class="form-control date-select" style="width: 300px;" name="year">
                            <?php
                            foreach($year_list as $item_y){
                                $active = ($item_y == $active_year)? 'selected': "";
                            ?>
                            <option value="<?php echo $item_y;?>" <?php echo $active;?> ><?php echo $item_y;?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="margin-right: 15px;">月</label>
                        <select class="form-control date-select" style="width: 300px;" name="month">
                            <?php
                            foreach($month_list as $item_m){
                                $active = ($item_m == $active_month)? 'selected': "";
                            ?>
                            <option value="<?php echo $item_m;?>" <?php echo $active;?> ><?php echo ($item_m < 10 ? "0".$item_m : $item_m);?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="margin-right: 15px;">週</label>
                        <!--label style="margin-right: 15px;">期間</label-->
                        <select class="form-control date-select" style="width: 300px;" name="info_week">
                            <option value="">期間を選択</option>
                            <?php 
                            foreach($arr_last_day as $time_info){
                                //Build array start day
                                if($time_info['last_day'] > 28){
                                    $arr_begin_day = [1,8,15,22,29];
                                } else {
                                    $arr_begin_day = [1,8,15,22];
                                }

                                //Loop array start day
                                foreach($arr_begin_day as $begin_day){

                                switch($begin_day){
                                case 1:
                                $week_current = 1;
                                break;
                                case 8:
                                $week_current = 2;
                                break;
                                case 15:
                                $week_current = 3;
                                break;
                                case 22:
                                $week_current = 4;
                                break;
                                default:
                                $week_current = 5;
                                }

                                ?>
                                <option value="<?php echo $time_info['year'] .'-' . $time_info['month'] .'-' . $week_current . '-'. $time_info['last_day']  ?>" >
                                    <?php echo $time_info['month']; ?>月<?php echo $this->Shishimai->add_zero_before_day($begin_day); ?>日週
                                </option>
                                <?php

                                }
                            }
                            ?>
                      </select>
                    </div>
                    <button type="submit" name="" class="btn btn-lg btn-primary submit show_ld" style="margin: 50px auto 100px; width: 70px" />作成</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</body>

</html>

<script type="text/javascript">
var base_url = "<?php echo $this->Html->url('/OttReport/report_week')?>";
$(document).ready(function(){

    $('select[name=year]').change(function(){
        var new_url = base_url + "?year="+$(this).val();
        window.location.href = new_url;
    });

    $('select[name=month]').change(function(){
        var new_url = base_url + "?year="+$('select[name=year]').val()+"&month="+$(this).val();
        window.location.href = new_url;
    });


});
</script>

<style type="text/css">
    @media(max-width: 414px){
        .date-select {
            width: 100% !important;
        }
    }
</style>