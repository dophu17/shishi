<div id="advice_div" style="height:400px"></div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
function loadChart(){
    Highcharts.chart('advice_div', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: '6ヶ月計画表'
	    },
	    xAxis: {
	        categories: [
	            <?php echo $m_string;?>
	        ],
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: ''
	        },
	        labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[0]
                },
                formatter:function(){
                    return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            }
	    },
	    tooltip: {
	    	shared: true
	    },
	    legend: {
            layout: 'vertical',
            align: 'left',
            x: 1030,
            verticalAlign: 'top',
            y: 50,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
	    plotOptions: {
	        column: {
	            pointPadding: 0.01,
	            borderWidth: 0
	        }
	    },
	    series: [{
	        name: '売上予測', //estimated
	        data: [<?php echo $estimated_string;?>],
	        color : '#3366CC'

	    }, {
	        name: 'コスト予測', //estimated cost
	        data: [<?php echo $estimated_cost_string;?>],
	        color : '#DC3912'

	    }]
	});
}

$(document).ready(function(){
    loadChart();
    $('.highcharts-credits').remove();
});
</script>
<style type="text/css">
.highcharts-background{
    border: 1px solid #999999 !important;
}
</style>