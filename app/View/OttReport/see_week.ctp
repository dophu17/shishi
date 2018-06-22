<div class="mainContents nofooter">
    <div class="">
        <div class="row reportTopSection">
            <div class="col-md-12">
                <div class="reportWrapper">
                    <h3>
          <?php echo $month; ?>月<?php echo $start_day; ?>日週&nbsp;（<?php echo $this->Shishimai->add_zero_before_day($month); ?>月<?php echo $this->Shishimai->add_zero_before_day($start_day); ?>日〜<?php echo $this->Shishimai->add_zero_before_day($month); ?>月<?php echo $this->Shishimai->add_zero_before_day($end_day); ?>日）
          週次レポート
        </h3>

                    <section class="projectShowSection">
                        <h4>
            <a target="_blank" style="color:#555;" href="<?php echo $site_url; ?>">
              <?php echo $site_name; ?>
              <span style="font-size: 70%;"><?php echo $site_url; ?></span>
</a>          </h4>
                        <h4 class="projectShowh4">主要KPIの状況</h4>
                        <div class="load_box_week" style="position: relative; min-height: 150px">
                            <!--Load box week-->
                            <div id="loader"></div>
                        </div>
                    </section>

                    <section class="projectShowSection">
                        <h4 class="projectShowh4">
            戦略
            <?php if($user_role == 'admin'){ 
                echo $this->Html->link("編集", array("action" => "edit_week", 'report_id' => $report_id) ,array('class'=>'btn btn-default btn-sm edit'));
            }?>
          </h4>
                        <div class="projectShowContents">
                            <div class="reportMessageOutputBox">
                                <label><?php echo $info_client['SsmUser']['position']; ?></label>
                                <div class="reportMessageOutputBox__repBox">
                                    <img src="<?php echo $this->Html->url('/uploads/ott/user/' . $info_client['SsmUser']['avatar'])?>">
                                    <span><?php echo $info_client['SsmUser']['department']; ?></span>
                                    <span></span>
                                    <span><?php echo $info_client['SsmUser']['first_name'] . ' ' . $info_client['SsmUser']['last_name']; ?></span>
                                </div>
                                <textarea class="reportMessageOutputBox__message" readonly="true" style="width:100%;min-height:1200px" ><?php echo trim($content);?></textarea>
                            </div>
                        </div>
                    </section>

                    <!-- <section class="projectShowSection">
                        <h4 class="projectShowh4">タスク進捗状況</h4>
                        <div style="padding: 0 20px;">
                            <div class="zeroDataCaution" style="text-align: center;">
                                該当期間が期限のタスクはありません
                            </div> -->
                            <!-- todo merge -->
                            <!-- <div class="modal fade" id="taskDetailModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close">&times;</button>
                                            <h4 class="modal-title taskDetailModal__title"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="taskDetailModal__body"></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default">閉じる</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section> -->

                </div>
            </div>
        </div>
    </div>
</div>
</div>

</body>
<style type="text/css">
body {
    background: white !important;
}
.edit {
    float: right;
    margin-top: -10px;
}
</style>
<link rel="stylesheet" type="text/css" href='<?php echo $this->Html->url('/ott/')?>css/style_ottreport.css'>
<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
<script type="text/javascript">
var getkpiboxweekUrl = "<?php echo $this->Html->url('/ShishimaiApi/getkpiboxweek'); ?>";
var month = "<?php echo $month; ?>";
var year = "<?php echo $year; ?>";
var week = "<?php echo $week; ?>";
var site_id = "<?php echo $site_id; ?>";
var cv_key = "<?php echo $info_time['SsmReport']['cv_key']?>";
var site_target_key = "<?php echo $info_time['SsmReport']['site_target_key']?>";
var kpi_list = "<?php echo $info_time['SsmReport']['kpi_list']?>";

$(document).ready(function(){
     //Load chart, box
    function showLoading() {
      $("#loader").show();
      $("body").css("cursor", "wait");
    }
    function hideLoading() {
      $("#loader").fadeOut(100);
      $("body").css("cursor", "default");
    }
    showLoading();
    $.ajax({
        url: getkpiboxweekUrl,
        type: "GET",
        data: {
            year: year,
            month: month,
            week: week,
            site_id: site_id,
            cv_key: cv_key,
            kpi_list : kpi_list
        },
        success: function(res){
            $('.load_box_week').html(res);
        },
        error: function(){
            $('.load_box_week').text("Loading Box Error!");
        },
        complete: function(){
            hideLoading();
        }
    });
});

</script>

</html>
