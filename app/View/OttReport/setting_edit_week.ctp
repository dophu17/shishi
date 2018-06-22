<div class="mainContents nofooter">
      <div class="">
  <div class="row">
    <div class="col-md-6 col-md-offset-3 setting_edit">
      <h2 class="commonh2">週次レポート共通設定</h2>
      <form class="edit_project" accept-charset="UTF-8" method="post" action="<?php echo $this->Html->url('/OttReport/edit_week/report_id:' . $report_id); ?>">
        <div class="form-group">
          <label>記入期日</label>
          <div>
            毎週 
            <select class="form-control date-select" style="margin-right: 10px; margin-bottom: 10px" name="day_in_week" id="name_day">
              <?php foreach($cf_day_in_week as $key => $day_in_week){
              ?>

                <option value="<?php echo $key ?>" <?php if($key == $date_dead_week){echo ' selected'; } ?>>
                <?php echo $day_in_week ;?>                 
                </option>

              <?php
              }?>
                     
            </select>
            <input class="form-control date-select" step="1" min="1" max="23" type="number" value="<?php echo $hour_dead_week; ?>" name="hour" id="name_hour"/> 時
          </div>
        </div>

        <div class="form-group">
          <label>担当ディレクター</label>
          <div>
            <select class="form-control position" style="width: 100%;" name="position">
              <?php foreach($data_client as $key => $client){
                ?>
                  <option value="<?php echo $client['SsmUser']['id']; ?>" 
                    <?php if($client['SsmUser']['id'] == $site_manage_user)
                    {
                      echo ' selected';
                    }; ?>
                     >
                     <?php echo $client['SsmUser']['first_name'] .' '. $client['SsmUser']['last_name']; ?></option>   
                <?php
                }; ?>
            </select>
          </div>
        </div>

        <div style="text-align: center; margin-bottom: 100px">
          <input type="submit" name="commit" value="保存" class="btn btn-lg btn-primary" style="margin-right: 20px;" />
        </div>
</form>    </div>
  </div>
</div>
    </div>
  </div>
</body>

</html>
