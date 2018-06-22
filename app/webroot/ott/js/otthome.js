//Load chart single day
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
            categories: arr_day_string,
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
                    },
                    formatter:function(){
                        return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
                    },
                    formatter:function(){
                        return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
            x: 1030,
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
            data: arr_actual_value_chart_string,
            tooltip: {
                valueSuffix: '',
                valueDecimals: 0

            },
            color : '#4BB2C5'

        }, {
            name: '目標値',
            yAxis: 1,
            type: 'spline',
            data: arr_target_value_chart_string,
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


//Load chart total
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
            categories: arr_day_string,
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
                    },
                    formatter:function(){
                        return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
                    },
                    formatter:function(){
                        return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
            x: 1030,
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
            data: arr_actual_value_chart_2_string,
            tooltip: {
                valueSuffix: '',
                valueDecimals: 0
            },
            color : '#4BB2C5'

        }, {
            name: '目標値',
            yAxis: 1,
            type: 'spline',
            data: arr_target_value_chart_2_string,
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

//Call back function after load chart
function callback_loadchart(){
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

    //Change chart view
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


    //Change report kpi display
    $('.btn_save_kpi_setting').click(function(){
        var setting = [];
        var kpi_check = $('.kpi_setting');
        kpi_check.each(function(){
            if($(this).prop('checked') == true){
                setting.push($(this).attr('value'));
            }
        });

        $.post(updateKpiReport_url,{kpi:setting,site_id:site_id},function(res){//view:view
            ajax_respone_check(res,true);
            if(res.trim() =='SUCCESS'){
                window.location.href = current_url;
                setTimeout(function(){ 
                    //$('#KPIBtn').modal('hide');
                    //$('#loading-layer').show();
                    }, 2000);
            }else{
                $('.kpi_setting_msg').html('更新は失敗です。再度試してください。').addClass('error');
            }
        });
    });

    //Load advice chart
    $.get(getAdviceChart_url,{site_id:site_id},function(advice_html){
        ajax_respone_check(advice_html,true);
        $('.wrap_advice_chart').html(advice_html);
    });
});