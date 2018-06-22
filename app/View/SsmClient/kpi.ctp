<div class="baseConainer">
    <div class="mainContents nofooter">
        <div class="">
            <div class="row reportTopSection">
                <div class="col-md-8 clearfix">
                    <h2 class="commonh2" style="float: left;">クライアント一覧</h2>
                </div>
                <div class="col-md-4">
                    <h2 class="commonh2" style="text-align:right">
                        <a class="show_ld" href="<?php echo $this->Html->url($prev_link);?>">
                            <i class="fa fa-caret-square-o-left"></i>
                        </a>
                        <span>
                        &nbsp;
                        <?php echo $year?>年 <?php echo $month?>月          &nbsp;
                        </span>
                        <a class="show_ld" href="<?php echo $this->Html->url($next_link);?>" style="float:right">
                            <i class="fa fa-caret-square-o-right"></i>
                        </a>
                    </h2>
                </div>
                <div class="table_wrap">
                    <table class="table reportTable clientTable table-striped">
                        <thead>
                            <tr>
                                <th class="t_left">サイト名</th>
                                <th width="10%" class="t_center">売上</th>
                                <th width="10%" class="t_center">売上予測</th>
                                <th width="10%" class="t_center">達成率</th>
                                <th width="10%" class="t_center">UU</th>
                                <th width="10%" class="t_center">PV</th>
                                <th width="10%" class="t_center">直帰率</th>
                                <th width="10%" class="t_center">コンバージョン</th>
                                <th width="10%" class="t_center">客単価</th>
                            </tr>
                        </thead>
                        <tbody id="list_content">
                        </tbody>
                        <tfoot>
                            <tr class="block_button"><td colspan="9" style="text-align:center"><button class="load_more_data btn btn-info">もっと読み込む</button></td></tr>
                            <tr class="block_loading">
                                <td colspan="9" style="text-align:center">
                                    <div class="loading_data">
                                    <img width="200px" src="<?php echo $this->Html->url('/ott/img/loading_small.gif'); ?>">
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
@media(max-width: 1200px){
    .table_wrap{
        overflow-y: hidden;
        overflow-x: scroll;
        -webkit-overflow-x: scroll;
        -webkit-overflow-scrolling: touch;
    }
}

.t_left{
    text-align: left !important;
}
.t_right{
    text-align: right !important;
}
.t_center{
    text-align: center !important;
}
</style>

<script type="text/javascript">
var allow_load_more     = 1;
var begin_site_id       = 0;
var year                = "<?php echo $year;?>";
var month               = "<?php echo $month;?>";
var getKpiClient_url    = "<?php echo $this->Html->url('/ShishimaiApi/getKpiClient');?>";
$(document).ready(function(){
    //FirloadSite
    loadSite();
    $('.load_more_data').click(function(){
        loadSite();
    });
});

function loadSite(){
    if(allow_load_more){
        show_loading();

        if($('#list_content').find('tr').length > 0){
            var prev_stt = $('#list_content').find('tr:last').attr('stt');
        }else{
            var prev_stt = 0;
        }
        $.get(getKpiClient_url,{begin_site_id:begin_site_id,year:year,month:month,prev_stt:prev_stt},function(data){
            if(data){

            }
            $('#list_content').append(data);
            window.scrollTo(0,document.body.scrollHeight);
            var last_tr = $('#list_content').find('tr:last');

            var last_site_id = last_tr.attr('site_id');
            if(last_site_id == 'empty_data'){
                hide_all();
            }else{
                if(parseInt(last_site_id) > begin_site_id){
                    begin_site_id = parseInt(last_site_id);
                }
                hide_loading();
            }
        });
        window.scrollTo(0,document.body.scrollHeight);
    }else{
        //alert('Cant load more data !');
    }
}


function show_loading(){
    $('.block_button').css('display','none');
    $('.block_loading').css('display','');
}

function hide_loading(){
    $('.block_loading').css('display','none');
    $('.block_button').css('display','');
}

function hide_all(){
    allow_load_more = false;
    $('.block_loading').css('display','none');
    $('.block_button').css('display','none');
    $('#list_content').find('tr:last').find('td').html('');//Cant load more data
}
</script>