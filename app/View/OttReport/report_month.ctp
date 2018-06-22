<div class="mainContents nofooter">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="text-align: center;">
                <h2 class="commonh2 center">月次レポート新規作成</h2>
                <form action="<?php echo $this->Html->url('/OttReport/report_month/site_id:' . $site_id); ?>" accept-charset="UTF-8" method="post">
                    <input name="utf8" type="hidden" value="&#x2713;" />
                    <input type="hidden" name="authenticity_token" value="" />
                    <div class="form-group">
                        <label style="margin-right: 15px;">期間</label>
                        <select name="year" class="form-control date-select">
                            <?php for($i = ($cur_year -2); $i < ($cur_year + 3); $i++){
                            ?>

                                <option value="<?php echo $i ?>" <?php if($i == $cur_year){ echo ' selected'; }; ?>
                                >
                                <?php echo $i ?>
                                </option>

                            <?php
                            }?>
                        </select>
                        年
                        <select name="month" class="form-control date-select">
                            <?php for($i = 1; $i < 13; $i++){
                            ?>

                                <option value="<?php echo $i ?>" <?php if($i == $cur_month){ echo ' selected'; }; ?>
                                >
                                <?php echo $i ?>
                                </option>

                            <?php
                            }?>
                        </select>
                        <input type="hidden" name="" value="1" /> 月
                    </div>
                    <input type="submit" name="commit" value="作成" class="btn btn-lg btn-primary show_ld" style="margin: 50px auto 100px;" />
                </form>
            </div>
        </div>
    </div>
</div>
</div>

</body>

</html>