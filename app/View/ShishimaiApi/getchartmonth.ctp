<div class="row">

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
    （目標比 <?php echo number_format($data_total_right_display['target_desktop_percent'],2);?>%）
    </span>
    </li>
    <li class="col-md-4">
    <i class="fa fa-mobile-phone"></i> <span class="number_report"><?php echo number_format($data_total_right_display['target_tablet_phone'])?> <?php echo $site_target_unit;?>
    </span>
    <br>
    <span class="small_note">
    （目標比 <?php echo number_format($data_total_right_display['target_tablet_phone_percent'],2);?>%）
    </span>
    </li>
    </ul>

    <?php
    //End == 1 ======================================================

    }else{

    //Bengin  != 1 ==================================================
    ?>

    <div class="mainKpiTable">

        <?php
        if($now_show_current_month){
        ?>
        <!-- ==================================================Show cr month========================================= -->
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
                    <span class="mainKpiContent__contents__value"><?php echo number_format($data_total_left_display['desktop_total']);?></span>
                    <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
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
                    <span class="mainKpiContent__contents__value"><?php echo number_format(($data_total_left_display['mobile_total'] + $data_total_left_display['tablet_total']));?></span>
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
                if($target_total != 0 && $data_total_right_display['target_all_percent'] != 0){
                ?>
                <p class="mainKpiContent__contents__targetRate">
                  （目標比<span><?php echo number_format($data_total_right_display['target_all_percent'],2);?>%</span>）
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
                <div>
                  <span class="mainKpiContent__contents__value"><?php echo number_format($data_total_right_display['target_desktop'])?></span>
                  <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                </div>
                <p class="mainKpiContent__contents__targetRate">
                （構成比<span><?php echo number_format($data_total_right_display['target_desktop_percent'],2);?>%</span>）
                </p>
              </div>
            </div>
            <div class="mainKpiContent sp">
              <label class="mainKpiContent__label">
                <i class="fa fa-mobile"></i>
              </label>
              <div class="mainKpiContent__contents">
                <div>
                  <span class="mainKpiContent__contents__value"><?php echo number_format($data_total_right_display['target_tablet_phone'])?></span>
                  <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                </div>
                <p class="mainKpiContent__contents__targetRate">
                （構成比<span><?php echo number_format($data_total_right_display['target_tablet_phone_percent'],2);?>%</span>）
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- ==================================================Show cr month========================================= -->
        <?php
        }else{
        ?>
        <!-- ==================================================Show other month========================================= -->
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
                    if($ratio_with_target != 0){
                    ?>
                    <p class="mainKpiContent__contents__targetRate">
                    (目標比<span><?php echo number_format($ratio_with_target,2);?>%</span>）
                    </p>
                    <?php
                    }
                    ?>

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
                    <span class="mainKpiContent__contents__value"><?php echo number_format($data_total_left_display['desktop_total']);?></span>
                    <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                  </div>
                  <p class="mainKpiContent__contents__targetRate">
                    （構成比<span><?php echo number_format($data_total_right_display['target_desktop_percent'],2);?>%</span>）
                  </p>
                </div>
              </div>
              <!-- SP -->
              <div class="mainKpiContent sp">
                <label class="mainKpiContent__label">
                  <i class="fa fa-mobile"></i>
                </label>
                <div class="mainKpiContent__contents">
                  <div>
                    <span class="mainKpiContent__contents__value"><?php echo number_format(($data_total_left_display['mobile_total'] + $data_total_left_display['tablet_total']));?></span>
                    <span class="mainKpiContent__contents__unit"><?php echo $site_target_unit;?></span>
                  </div>
                  <p class="mainKpiContent__contents__targetRate">
                    （構成比<span><?php echo number_format($data_total_right_display['target_tablet_phone_percent'],2);?>%</span>）
                    </p>
                </div>
              </div>
            </div>
        </div>

        <!-- ==================================================Show other month========================================= -->
        <?php
        }
        ?>
        <!-- 実績値のbox -->
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

                </div>
            </div>
        </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script>

function load_day(){
    Highcharts.chart('report_div', {
        chart: {
            //zoomType: 'xy'
            events: {
                load:function(event) {
                    callback_loadchart();
                },
                redraw: function(event) {
                    callback_loadchart();
                }
            }
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: [{
            categories: [
            <?php
            echo $day_string;
            ?>
            ],
            crosshair: true,
            plotLines: [
            {
                color: '#eee', // Color value
                //dashStyle: 'solid', // Style of the plot line. Default to solid
                value: 7, // Value of where the line will appear
                width: 2 // Width of the line    
            },
            {
                color: '#eee', // Color value
                //dashStyle: 'solid', // Style of the plot line. Default to solid
                value: 14, // Value of where the line will appear
                width: 2 // Width of the line    
            },
            {
                color: '#eee', // Color value
                //dashStyle: 'solid', // Style of the plot line. Default to solid
                value: 21, // Value of where the line will appear
                width: 2 // Width of the line    
            },
            {
                color: '#eee', // Color value
                //dashStyle: 'solid', // Style of the plot line. Default to solid
                value: 28, // Value of where the line will appear
                width: 2 // Width of the line    
            }
            ]
        }],
        yAxis: [
            { // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: '',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                visible :false
            },
            { // Secondary yAxis
                title: {
                    text: '',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: false,
            }
        ],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 950,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [
        {
            name: '実績値',
            type: 'column',
            yAxis: 1,
            data: [<?php echo $actual_value_chart_string;?>],
            tooltip: {
                valueSuffix: '',
                valueDecimals: 0
            },
            color : '#4BB2C5'

        }, {
            name: '目標値',
            yAxis: 1,
            type: 'spline',
            data: [<?php echo $target_value_chart_string;?>],
            tooltip: {
                valueSuffix: '',
                valueDecimals: 0
            },
            color : '#EAA228'
        }]
    });
    $('.btn_load_chart[char-name=day]').addClass('active');
    $('.btn_load_chart[char-name=calculate]').removeClass('active');
    callback_loadchart();
}

function load_calculate(){
    Highcharts.chart('report_div', {
        chart: {
            events: {
                load:function(event) {
                    callback_loadchart();
                },
                redraw: function(event) {
                    callback_loadchart();
                }
            }
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: [
        {
            categories: [
            <?php
            echo $day_string;
            ?>
            ],
            crosshair: true,
            plotLines: [
            {
                color: '#eee', // Color value
                //dashStyle: 'solid', // Style of the plot line. Default to solid
                value: 7, // Value of where the line will appear
                width: 2 // Width of the line    
            },
            {
                color: '#eee', // Color value
                //dashStyle: 'solid', // Style of the plot line. Default to solid
                value: 14, // Value of where the line will appear
                width: 2 // Width of the line    
            },
            {
                color: '#eee', // Color value
                //dashStyle: 'solid', // Style of the plot line. Default to solid
                value: 21, // Value of where the line will appear
                width: 2 // Width of the line    
            },
            {
                color: '#eee', // Color value
                //dashStyle: 'solid', // Style of the plot line. Default to solid
                value: 28, // Value of where the line will appear
                width: 2 // Width of the line    
            }
            ]
        }


        ],
        yAxis: [
            { // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: '',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                visible :false
            },
            { // Secondary yAxis
                title: {
                    text: '',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: false,

            }
        ],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 950,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [
        {
            name: '実績値',
            type: 'column',
            yAxis: 1,
            data: [<?php echo $actual_value_chart_2_string;?>],
            tooltip: {
                valueSuffix: '',
                valueDecimals: 0
            },
            color : '#4BB2C5'

        }, {
            name: '目標値',
            yAxis: 1,
            type: 'spline',
            data: [<?php echo $target_value_chart_2_string; ?>],
            tooltip: {
                valueSuffix: '',
                valueDecimals: 0
            },
            color : '#EAA228'
        }

        ]
    });

    $('.btn_load_chart[char-name=calculate]').addClass('active');
    $('.btn_load_chart[char-name=day]').removeClass('active');
    callback_loadchart();
}

function callback_loadchart(){
    var first_day_of_week = [<?php echo $list_start_day_of_week?>];

    var label_date = $('.highcharts-xaxis-labels').find('text');
    label_date.each(function(){
        var label_date = $(this).find('tspan');
        if(first_day_of_week.indexOf(label_date.text()) >= 0){
            //no remove
        }else{
            //remove
            label_date.remove();
        }

    });

    $('.highcharts-plot-background').attr('fill','#FFFDF6');
}

$(document).ready(function(){
    load_calculate();

    $('.btn_load_chart').click(function(){
        if($(this).attr('char-name') == 'day'){
            load_day();
            $('.highcharts-credits').remove();
        }else if($(this).attr('char-name') == 'calculate'){
            load_calculate();
            $('.highcharts-credits').remove();
        }
    });
    $('.highcharts-credits').remove();
});

</script>