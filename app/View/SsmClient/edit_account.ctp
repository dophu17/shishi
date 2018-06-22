<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2 class="commonh2 center">ユーザー情報変更</h2>

      <?php echo $this->Form->create('SsmUser', array('role' => 'form','type'=>'file')); ?>
        <div class="form-group">
          <?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => 'Eメールアドレス','label' => 'Eメールアドレス', 'value' => $info_user['SsmUser']['username']));?>
        </div>

        <div class="form-group">
          <?php echo $this->Form->input('department', array('class' => 'form-control', 'placeholder' => '部署','label' => '部署', 'value' => $info_user['SsmUser']['department']));?>
        </div>

        <div class="form-group">
          <?php echo $this->Form->input('position', array('class' => 'form-control', 'placeholder' => '役職','label' => '役職', 
          'value' => $info_user['SsmUser']['position']));?>
        </div>

        <div class="form-group">
          <label>名前</label>
          <div style="display: table;width: 100%;">
            <div class="form-group-col2">
              <div class="form-group ">
                <?php echo $this->Form->input('first_name', array('class' => 'form-control', 'placeholder' => '名前','label' => false, 'value' => $info_user['SsmUser']['first_name']));?>
              </div>
            </div>
            <div class="form-group-col2">
              <div class="form-group ">
                <?php echo $this->Form->input('last_name', array('class' => 'form-control', 'placeholder' => '名前','label' =>false, 'value' => $info_user['SsmUser']['last_name']));?>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>プロフィール写真</label>
          <?php
          echo $this->Form->input('file_path',array('templates' => array(
          'inputContainer' => '{{content}}',
          $info_user['SsmUser']['avatar']
          ),
          'label' => false,'class'=>'','type'=>'file','required' => false));
          ?>
        </div>


        <div class="actions" style="text-align: center;">
          <input type="submit" name="commit" value="更新" class="btn btn-lg btn-primary">
        </div>
      <?php echo $this->Form->end() ?>
  </div>
  </div>