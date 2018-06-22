
<div class="row">
    <div class="col-md-6 col-md-offset-3">

      <?php echo $this->Form->create('SsmSetting', array('role' => 'form','type'=>'file','novalidate' => true)); ?>

        <!--<?php echo $setting['SsmSetting']['key']?>-->
        <?php
        if($setting['SsmSetting']['type'] == 'textarea'){
        ?>
        <div class="form-group">
          <?php echo $this->Form->input('value', array('class' => 'form-control', 'placeholder' =>$setting['SsmSetting']['title'],'label' => $setting['SsmSetting']['title'],'rows'=>30));?>
        </div>
        <?php
        }else{
        ?>
        <div class="form-group">
          <?php echo $this->Form->input('value', array('class' => 'form-control', 'placeholder' =>$setting['SsmSetting']['title'],'label' => $setting['SsmSetting']['title']));?>
        </div>
        <?php
        }
        ?>
        <div class="actions" style="text-align: center;">
          <input type="submit" name="commit" value="保存" class="btn btn-lg btn-primary">
        </div>
      <?php echo $this->Form->end()?>
  </div>
  </div>