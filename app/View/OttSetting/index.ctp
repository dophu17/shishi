<div class="mainContents nofooter">
    <div class="container">
        <div class="row reportTopSection">
            <div class="col-md-10 col-md-offset-1">
                <h2 class="commonh2">チャットワークに吐き出すメッセージの管理</h2>

                <table class="table table_ottplan_index" style="table-layout: auto;">
                    <tbody>
                        <?php
                        if(!empty($list)){
                            foreach($list as $item){
                            ?>
                                <tr class="weeklyReportLine">
                                    <td>
                                        <?php echo $item['SsmSetting']['title']?>
                                    </td>
                                    <td style="text-align:right" class="btn_ottplan">
                                        <a class="btn btn-default btn-xs" href="<?php echo $this->Html->url('/OttSetting/update/setting_id:'.$item['SsmSetting']['id'])?>">
                                            <i class="fa fa-edit edit-btn"></i>編集
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<style type="text/css">

.weeklyReportLine td:nth-of-type(1) {
    padding-left: 10px !important;
}
</style>
