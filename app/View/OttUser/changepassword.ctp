<div class="row">
    <div class="col-md-4 col-md-offset-4">
      <h2 class="commonh2 center">パスワード変更</h2>
      <?php echo $this->Form->create('SsmUser', array('role' => 'form','type'=>'file','novalidate' => true)); ?>
        <div class="fields">
          <div class="form-group">
          <label>パスワード</label>
          <div class="form-group "><?php echo $this->Form->input('password', array('type'=>'password','class' => 'form-control', 'placeholder' => 'パスワード','label' =>false,'value'=>''));?></div>

          <label>パスワード確認</label>
          <div class="form-group "><?php echo $this->Form->input('repeat_password', array('type'=>'password','class' => 'form-control', 'placeholder' => 'パスワード確認','label' =>false,'value'=>''));?></div>
        </div>
        <div class="actions" style="text-align: center; padding: 50px 0;">
          <input type="submit" name="commit" value="更新" data-disable-with="更新中" class="btn btn-lg btn-primary">
        </div>
    </div><?php echo $this->Form->end() ?>
  </div>
</div>