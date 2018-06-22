<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h2 class="commonh2 center">割合工数</h2>
        <?php echo $this->Form->create('SsmSettings', array('role' => 'form', 'type'=>'file', 'novalidate' => true)); ?>
            <div class="form-group">
            <?php 
                echo $this->Form->input('value', array(
                    'class'       => 'form-control',
                    'placeholder' => '割合工数',
                    'label'       => '割合工数',
                    'type'        => 'string'
                ));
            ?>
            </div>
            <div class="actions" style="text-align: center;">
              <input type="submit" name="commit" value="追加" class="btn btn-lg btn-primary">
            </div>
        <?php echo $this->Form->end() ?>
  </div>
</div>