<div class="users form">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Create user</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <?php echo $this->Form->create('SsmUser', array('role' => 'form')); ?>

            <div class="form-group">
                <?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => 'Username'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => 'Password'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('repeat_password', array('class' => 'form-control', 'placeholder' => 'Repeat_password', 'type' => 'password'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('role', array(
                    'options' => array('admin' => 'Admin', 'staff' => 'Staff'),
                    'class' => 'form-control', 'placeholder' => 'Role'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
            </div>

            <?php echo $this->Form->end() ?>

        </div><!-- end col md 12 -->
    </div><!-- end row -->
</div>
