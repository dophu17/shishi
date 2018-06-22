<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2 class="commonh2 center">管理者ユーザー追加</h2>
      <?php echo $this->Form->create('SsmUser', array('role' => 'form','type'=>'file','novalidate' => true)); ?>
        <div class="form-group">
            <?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => 'メールアドレス','label' => 'メールアドレス'));?>
        </div>
        <div style="padding: 50px; text-align: center;">
          <input type="submit" name="commit" value="追加" class="btn btn-primary">
        </div>
      <?php echo $this->Form->end() ?>
    </div>
  </div>