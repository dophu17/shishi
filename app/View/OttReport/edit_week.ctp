<div class="mainContents nofooter">
  <p class="error">Error, Not found data</p>
  <div class="">
    <div class="row reportTopSection">
      <div class="col-md-10 col-md-offset-1">
        <h3 style="line-height: 1.5;" class="clearfix">
          週次レポート&nbsp;管理者用編集画面
          <a class="btn btn-default setting_edit_week "
             href="<?php echo $this->Html->url('/OttReport/setting_edit_week/report_id:' . $report_id); ?>"
             style="float: right;right: 5px;bottom: 5px;">繰り返し条件変更</a>

        </h3>
        <!-- レビュー -->
        <form class="edit_weekly_report" accept-charset="UTF-8" method="post">

          <h4 class="projectShowh4">
              <?php echo $month; ?>月<?php echo($start_day); ?>
            日週&nbsp;（<?php echo $this->Shishimai->add_zero_before_day($month); ?>
            月<?php echo $this->Shishimai->add_zero_before_day($start_day); ?>
            日〜<?php echo $this->Shishimai->add_zero_before_day($month); ?>
            月<?php echo $this->Shishimai->add_zero_before_day($end_day); ?>日）
            <div class="reportMessageLimitTimeBox">
              <span>記入期限</span>

              <select id="month" name="month" class="form-control date-select">
                  <?php for ($i = 1; $i < 13; $i++) {
                      ?>
                    <option value="<?php echo $i; ?>"
                        <?php
                        if ($i == $month_deadline) {
                            echo ' selected';
                        }
                        ?>
                    ><?php echo $i; ?></option>
                      <?php
                  }; ?>
              </select>
              /
              <select id="day" name="day" class="form-control date-select">
                  <?php for ($i = 1; $i < ($last_day_of_month + 1); $i++) {
                      ?>
                    <option value="<?php echo $i; ?>"
                        <?php
                        if ($i == (!isset($dead_day) ? $day_deadline : $dead_day)) {
                            echo ' selected';
                        }
                        ?>
                    ><?php echo $i ?></option>
                      <?php
                  }; ?>
              </select>
              &mdash; <select name="hour" class="form-control date-select" id='hour'>
                    <?php for ($i = 1; $i < 24; $i++) {
                        ?>
                      <option value="<?php echo $i; ?>"
                          <?php if ($i == (!isset($hour) ? $hour_deadline : $hour)) {
                              echo ' selected';
                          }; ?>
                      >
                          <?php echo $i; ?></option>
                        <?php
                    }; ?>
              </select>
              時
              <span>&nbsp;&nbsp;
              <a class="btn btn-default edit_deadline"
                 href="#"
                 style="float: right;right: 5px;bottom: 5px;">期限を変更する</a>
              </span>
            </div>
          </h4>
          <div class="projectShowContents">
            <div class="reportMessageBox">
              <label>担当ディレクターより</label>
              <div class="reportMessageBox__repBox">
                <select class="form-control" name="fromUser" id="fromUser" disabled="disabled">
                    <?php foreach ($data_client as $key => $client) {
                        ?>
                      <option value="<?php echo $client['SsmUser']['id']; ?>"
                          <?php if ($client['SsmUser']['id'] == $this->Shishimai->show_data('position', $user_id)) {
                              echo ' selected';
                          }; ?>
                      ><?php echo $client['SsmUser']['first_name'] . ' ' . $client['SsmUser']['last_name']; ?></option>
                        <?php
                    }; ?>
                </select>
                  <?php
                  if ($status == 1) {
                      ?>
                    <span class="reportMessageBox__statusLbl b_going" style="background-color: #3276b1;">記入済み</span>
                      <?php
                  } else {
                      ?>
                    <span class="reportMessageBox__statusLbl b_going">記入中</span>
                      <?php
                  }
                  ?>
              </div>
              <textarea rows="15" class="form-control content" name="content"><?php echo $content; ?></textarea>
              <div class="reportMessageBox__btns">
                <p class="btn btn-default doing"/>一時保存</p>
                <p class="btn btn-primary done"/>完了</p>
              </div>
            </div>

            <div style="text-align: center; padding: 50px 0;" class="p10">
              <p class="btn btn-default" id="sendReportToCwBtn">ChatWorkに送信</p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
<p class="note"></p>
</div>

<style type="text/css">
  .note {
    background-color: #dff0d8;
    border-color: #d6e9c6;
    color: #3c763d;
    border-radius: 4px;
    padding: 15px;
  }

  .error {
    background-color: #fcf8e3;
    border-color: #faebcc;
    color: #8a6d3b;
    border-radius: 4px;
    padding: 15px;
  }

  @media (max-width: 414px) {
    .projectShowContents {
      padding: 0;
    }

    .reportMessageLimitTimeBox {
      position: initial;
      margin-top: 10px;
    }

    .p10 {
      padding: 10px 0 !important;
    }
  }

  @media (max-width: 375px) {
    .setting_edit_week {
      margin-top: 20px;
    }

    .reportMessageLimitTimeBox span {
      display: block;
    }
  }

  @media (max-width: 320px) {
    /*  .container {
        padding-left: 5px;
        padding-right: 5px;
      }*/
    .reportMessageBox .reportMessageBox__repBox select.form-control {
      width: 170px !important;
    }
  }
</style>
<script type="text/javascript">
  var doingUrl = "<?php echo $this->Html->url('/ShishimaiApi/doingEditWeek');?>";
  var doneUrl = "<?php echo $this->Html->url('/ShishimaiApi/doneEditWeek');?>";
  var chatworkUrl = "<?php echo $this->Html->url('/OttReport/postChatwork/report_id:' . $report_id); ?>";
  var editDeadline = "<?php echo $this->Html->url('/OttReport/editDeadline'); ?>";
  var editDeadlineUrl = "<?php echo $this->Html->url('/ShishimaiApi/updateReportDeadline'); ?>";
  var report_id = "<?php echo $report_id?>";


  setTimeout(function () {
    $('.alert-success').fadeOut();
  }, 2000);
</script>
<script src="<?php echo $this->Html->url('/ott') ?>/js/ott.js"></script>
<script src="<?php echo $this->Html->url('/ott') ?>/js/ajaxEditWeek.js"></script>
</body>

</html>