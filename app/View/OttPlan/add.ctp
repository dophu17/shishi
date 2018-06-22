
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2 class="commonh2 center">プラン新規作成</h2>

      <?php echo $this->Form->create('SsmPlan', array('role' => 'form','type'=>'file','novalidate' => true)); ?>
        <div class="form-group">
          <?php echo $this->Form->input('name', array('class' => 'form-control', 'placeholder' => 'プラン名','label' => 'プラン名'));?>
        </div>

        <div class="form-group">
          <?php echo $this->Form->input('price', array('class' => 'form-control', 'placeholder' => '金額','label' => '金額'));?>
        </div>

        <div class="actions" style="text-align: center;">
          <input type="submit" name="commit" value="追加" class="btn btn-lg btn-primary">
        </div>
      <?php echo $this->Form->end() ?>
  </div>
  </div>