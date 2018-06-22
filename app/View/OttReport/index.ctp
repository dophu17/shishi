<div class="mainContents nofooter">
    <div class="">
        <div class="row reportTopSection">
            <div class="col-md-10 col-md-offset-1">
                <h2 class="commonh2">レポート一覧</h2>
                <div style="text-align: right; margin-bottom: 5px;" class="name_btn">
                    <?php if($allow_action['create_report_month']){
                        echo $this->Html->link("月次レポート新規作成",array("action" => "report_month"), array("class" => "btn btn-default show_ld")); 
                    } else {
                        echo $this->Html->link("月次レポート新規作成",array("action" => "report_month"), array("class" => "btn btn-default disabled"));
                    }
                    ?>
                    <?php if($allow_action['create_report_week']){
                        echo $this->Html->link("週次レポート新規作成",array("action" => "report_week"), array("class" => "btn btn-default show_ld")); 
                    } else {
                        echo $this->Html->link("週次レポート新規作成",array("action" => "report_week"), array("class" => "btn btn-default disabled"));
                    }
                    ?>
                </div>
                <table class="table" style="table-layout: auto;">
                    <tbody>
                        <tr class="name_head">
                            <th>レポート名</th>
                            <th style="width: 200px; ">作成日</th>
                            <th style="width: 100px;">ステータス
                            </th><th style="width: 170px;">&nbsp;
                            </th>
                        </tr>
                        <?php
                        if(!empty($list)){
                            foreach($list as $year=>$year_data){
                                foreach($year_data as $month=>$month_data){
                                ?>



                                    <!-- Month block -->
                                    <tr class="keyDateLine">
                                        <td colspan="100%">
                                            <?php echo $year?>年<?php echo $month?>月
                                        </td>
                                    </tr>

                                    <?php
                                    foreach($month_data['week'] as $week_record){
                                        ?>
                                        <!-- Week -->
                                        <tr class="weeklyReportLine">
                                            <td><?php
                                            switch($week_record['week']){
                                                case 1:
                                                $start_day = 1;
                                                break;
                                                case 2:
                                                $start_day = 8;
                                                break;
                                                case 3:
                                                $start_day = 15;
                                                break;
                                                case 4:
                                                $start_day = 22;
                                                break;
                                                default:
                                                $start_day = 29;
                                            }
                                            ?>
                                                <?php echo $week_record['month']?>月<?php echo ($start_day); ?>日週&nbsp; 週次レポート
                                            </td>
                                            <td><?php echo $this->Shishimai->showTime($week_record['created_at'])?></td>
                                            <td>
                                                <?php echo $cf_report_status[$week_record['status']]?>
                                            </td>
                                            <td class="name_btn">
                                                <?php
                                                if($allow_action['view_report_week']){
                                                ?>
                                                <a class="btn btn-default btn-xs" href="<?php echo $this->Html->url('/OttReport/see_week/report_id:'.$week_record['id'])?>">
                                                    <i class="fa fa-sticky-note-o"></i>見る
                                                </a>
                                                <?php } ?>

                                                <?php
                                                if($allow_action['edit_report_week']){
                                                ?>

                                                    <a class="btn btn-default btn-xs" href="<?php echo $this->Html->url('/OttReport/edit_week/report_id:'.$week_record['id'])?>">
                                                        <i class="fa fa-edit edit-btn"></i>編集
                                                    </a>
                                                <?php } ?>

                                                <?php
                                                if($allow_action['delete_report_week']){
                                                ?>
                                                    <a class="btn btn-default btn-xs" onClick="return confirm('削除しても良いですか？')" href="<?php echo $this->Html->url('/OttReport/delete_report/report_id:'.$week_record['id'])?>">
                                                        <i class="fa fa-trash"></i>削除
                                                    </a>

                                                <?php }; ?>
                                            </td>
                                        </tr>
                                        <!-- Week -->
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    foreach($month_data['month'] as $month_record){
                                        ?>
                                        <!-- Month -->
                                        <tr class="monthlyReportLine">
                                            <td style="padding-left: 30px;"><?php echo $month?>月&nbsp;月次レポート</td>
                                            <td><?php echo $this->Shishimai->showTime($month_record['created_at'])?></td>
                                            <td>
                                                <?php echo $cf_report_status[$month_record['status']]?>
                                            </td>
                                            <td class="name_btn">
                                                <?php
                                                if($allow_action['view_report_month']){
                                                ?>
                                                <a class="btn btn-default btn-xs" href="<?php echo $this->Html->url('/OttReport/see_month/report_id:'.$month_record['id']);?>">
                                                    <i class="fa fa-sticky-note-o"></i>見る
                                                </a>
                                                <?php }; ?>

                                                <?php
                                                if($allow_action['edit_report_month']){
                                                ?>

                                                    <a class="btn btn-default btn-xs" href="<?php echo $this->Html->url('/OttReport/edit_month/report_id:'.$month_record['id'])?>">
                                                        <i class="fa fa-edit edit-btn"></i>編集
                                                    </a>
                                                <?php }; ?>

                                                <?php
                                                if($allow_action['delete_report_month']){
                                                ?>
                                                    <a class="btn btn-default btn-xs" onClick="return confirm('削除しても良いですか？')" href="<?php echo $this->Html->url('/OttReport/delete_report/report_id:'.$month_record['id'])?>">
                                                        <i class="fa fa-trash"></i>削除
                                                    </a>

                                                <?php }?>
                                            </td>
                                        </tr>
                                        <!--Month-->
                                        <?php
                                    }
                                    ?>
                                    <!--End month Block-->

                                <?php
                                }
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
    @media(max-width: 414px){
       /* .container {
            padding-left: 5px;
            padding-right: 5px;
        }*/
        .name_head th {
            width: 25%;
        }
        .weeklyReportLine td {
            width: 36%;
        }
        .name_btn {
            text-align: right;
        }
        .name_btn a{
            margin-bottom: 5px;
        }
        .table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {
            padding: 4px;
        }
        .weeklyReportLine td:nth-of-type(1) {
            padding-left: 0;
        }
        td {
            padding-left: 0 !important;
        }
        body {
            padding-top: 45px;
        }
        .table {
             word-break: inherit; 
        }
    }
</style>