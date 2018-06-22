<div class="mainContents nofooter">
    <div class="container">
        <div class="row reportTopSection">
            <div class="col-md-10 col-md-offset-1">
                <h2 class="commonh2">タスクジャンル一覧</h2>
                <div style="text-align: right; margin-bottom: 5px;">
                    <?php
                    echo $this->Html->link("新規",array("action" => "add"), array("class" => "btn btn-default"));
                    ?>
                </div>
                <table class="table table_ottplan_index" style="table-layout: auto;">
                    <tbody>
                        <tr>
                            <th style="padding-left: 10px !important;">タスクジャンル名</th>
                            </th><th style="width: 170px;">&nbsp;
                            </th>
                        </tr>
                        <?php
                        if(!empty($list)){
                            foreach($list as $item){
                            ?>
                                <tr class="weeklyReportLine">
                                    <td>
                                        <?php echo $item['SsmTaskLabel']['name']?>
                                    </td>
                                    <td style="text-align:right" class="btn_ottplan">
                                        <a class="btn btn-default btn-xs" href="<?php echo $this->Html->url('/OttTaskLabel/update/label_id:'.$item['SsmTaskLabel']['id'])?>">
                                            <i class="fa fa-edit edit-btn"></i>編集
                                        </a>
                                        <a class="btn btn-default btn-xs" onClick="return confirm('削除しても良いですか？')" href="<?php echo $this->Html->url('/OttTaskLabel/delete/label_id:'.$item['SsmTaskLabel']['id'])?>">
                                            <i class="fa fa-trash"></i>削除
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
