
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2 class="commonh2 center">ユーザー情報変更</h2>

      <?php echo $this->Form->create('SsmUser', array('role' => 'form','type'=>'file','novalidate' => true)); ?>
        <div class="form-group">
          <?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => 'Eメールアドレス','label' => 'Eメールアドレス'));?>
        </div>

        <div class="form-group">
          <?php echo $this->Form->input('department', array('class' => 'form-control', 'placeholder' => '部署','label' => '部署'));?>
        </div>

        <div class="form-group">
          <?php echo $this->Form->input('position', array('class' => 'form-control', 'placeholder' => '役職','label' => '役職'));?>
        </div>

        <div class="form-group">
          <label>名前</label>
          <div style="display: table;width: 100%;">
            <div class="form-group-col2">
              <div class="form-group ">
                <?php echo $this->Form->input('first_name', array('class' => 'form-control', 'placeholder' => '名前','label' => false));?>
              </div>
            </div>
            <div class="form-group-col2">
              <div class="form-group ">
                <?php echo $this->Form->input('last_name', array('class' => 'form-control', 'placeholder' => '名前','label' =>false));?>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>プロフィール写真</label>
          <?php
          echo $this->Form->input('file_path',array('templates' => array(
          'inputContainer' => '{{content}}'
          ),
          'label' => false,'class'=>'','type'=>'file','required' => false));
          ?>
        </div>

        <div class="form-group">
          <?php echo $this->Form->input('password', array('type' => 'password','class' => 'form-control', 'placeholder' => 'パスワード','label' => 'パスワード'));?>
        </div>

        <div class="form-group">
          <?php echo $this->Form->input('repeat_password', array('type' => 'password','class' => 'form-control', 'placeholder' => 'パスワード確認','label' => 'パスワード確認'));?>
        </div>


        <div class="actions" style="text-align: center;">
          <input type="submit" name="commit" value="更新" class="btn btn-lg btn-primary">
        </div>
      <?php echo $this->Form->end() ?>
  </div>
  </div>