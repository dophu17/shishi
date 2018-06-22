<div class="row">
  <div class="col-md-4 order-md-2 mb-4">
    <h4 class="d-flex justify-content-between align-items-center mb-3">
        <?php if ($user_role == 'admin' || $user_role == 'worker'): ?>
        <a class="title-to-thank-page" href="<?php echo $this->Html->url('/OttThank/send');?>" target="_blank"><span class="text-muted">アリガトウ</span></a>
        <a class="title-to-thank-page" href="<?php echo $this->Html->url('/OttThank/send');?>" target="_blank"><p>感謝の気持ちを送りましょう。</p></a>
        <?php endif; ?>
      <span class="text-muted">タスクリスト</span>
      <p style='font-size:13px'>タイトルをクリックすると「本日の作業」に表示されます。</p>
    </h4>
    <ul class="list-group mb-3" id="defined_task">
  	<?php
    	if(!empty($my_task)){
    		foreach($my_task as $task){
		?>
    		<li class="list-group-item list-group-item-left d-flex justify-content-between lh-condensed" task_id="<?php echo $task['id'];?>" task_name="<?php echo $task['name'];?>">
					<div class="wrap_check">
						<input type="checkbox" class="hide_task_checkbox" value="<?php echo $task['id'];?>" <?php if(in_array($task['id'],$arr_hide_task)) echo "checked";?>>
					</div>
					<div class="wrap_task_name">
						<h6 class="task_name task-id-<?php echo $task['id'];?>"><?php echo $task['name'];?></h6>
					</div>
					<span class="wrap_input_time">
						<input type="number" class="input_time" default-value="<?php echo $task['est'] == 0 ? "0" : ($task['est'] == intval($task['est']) ? intval($task['est']) : $task['est']);?>" value="<?php echo $task['est'] == 0 ? "0" : ($task['est'] == intval($task['est']) ? intval($task['est']) : $task['est']);?>" min='0'>
						<span class="h_title">h</span>
						<button class="btn_update_time btn btn-default btn-xs edit-actual-submit-btn">保存</button>
					</span>
	      </li>
		<?php
    		}
    	}
  	?>
    </ul>
    <button class='btn btn-default btn-xs btn_delete_task'>削除</button>
    <h4 class="text-muted">新規タスク追加</h4>
    <p style="font-size:13px">入力後「Enter」で新規追加されます。</p>
    <form class="card p-2" onSubmit="return addTaskToList();">
      	<div class="">
        		<input type="text" class="form-control" id="task_input" placeholder="新規タスクをご作成ください">
        	</div>
    </form>
  </div>
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3 text-muted">本日の作業</h4>
          <form class="needs-validation" id="form_report_task" method="post">
            <div class="row">
            	<div class="col-md-12">
	              <ul class="list-group list-group-right" id="list-task-today" style="border-top-right-radius: 4px;
                    border-top-left-radius: 4px;
                    min-height: 20px;
                    border: 1px solid #ddd;
                    border-bottom-right-radius: 4px;
                    border-bottom-left-radius: 4px;
                    padding: 0px;
                    min-height:50px
                ">
	              	<?php
		              	if(!empty($arr_task_log)){
			              	foreach($arr_task_log as $key=>$log){
			              	    echo $key;
              		?>
								    <li class="list-group-item d-flex justify-content-between lh-condensed list-group-item-right" task_id="<?php echo $log['task_id']?>">
    									<div class="col-md-1 mb-3">
    										<a class="remove_task" title="削除"> <i class="fa fa-trash"></i> </a>
    									</div>
    									<div class="col-md-3 mb-3">
    										<?php echo $my_task[$log['task_id']]['name']?>
    										<input type='hidden' class='task_id' name="task_id[<?php echo $key?>]" value='<?php echo $log['task_id'];?>'>
    									</div>
    									<div class="col-md-3 mb-3">123
    										<select class="form-control task_site" name="task_site[<?php echo $key?>]">
    											<?php echo $list_client_option?>
    										</select>
    									</div>
    									<div class="col-md-3 mb-3">456
    										<select class="form-control task_label" name="task_label[<?php echo $key?>]">
    											<?php echo $list_label_option?>
    										</select>
    									</div>
    									<div class="col-md-2 mb-3">
    										<input type="text" class="form-control task_est" value="<?php echo $log['task_time']?>" name="task_est[<?php echo $key?>]">
    										<span class="h_title_right">h</span>
    									</div>
								    </li>
    								<script>
    									$(document).ready(function(){
    										//reset value select site
    										$('select[name="task_site[<?php echo $key?>]"] > option[value=<?php echo $log['site_id']?>]').attr('selected',true);
    										$('select[name="task_label[<?php echo $key?>]"] > option[value=<?php echo $log['label_id']?>]').attr('selected',true);
    									});
    								</script>
              		<?php
			              	}
		              	}
	              	?>
	              </ul>
	            </div>
            	<div class="col-md-12 mb-3">
                <label for="username" class="text-muted">
                  <h4 class="text-muted">思ったこと・次への一言！</h4>
                </label>
	              <div class="input-group" style="width:100%">
	                <textarea class="form-control" name="task_note" style="width:100%;height:200px" id="username" placeholder=""><?php echo $task_note;?></textarea>
	              </div>
	            </div>
            </div>
            <hr class="mb-4">
            <input type="hidden" name="hide_task" value="<?php echo $hide_task;?>">
            <button class="btn btn-primary btn-lg btn-block" id="right_submit" type="button">送信</button>

              <!-- Modal -->
              <div class="modal fade" id="modalTaskLog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel">送信する前に合計時間をご確認ください。</h4>
                          </div>
                          <div class="modal-body">
                              <table id="task-log" class="table table-bordered margin-bottom-0"></table>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">いいえ</button>
                              <button type="button" class="btn btn-primary" id="btn-send-cw">はい</button>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- end Modal -->
          </form>
        </div>
      </div>
  <style>
  .form-control {
	  font-size: 13px;
  }
  </style>
	<link href="<?php echo $this->Html->url('/ott')?>/css/task.css" rel="stylesheet">
	<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
	<script src="<?php echo $this->Html->url('/ott')?>/js/task.js"></script>
	<script>
	var addTask_url 		= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'addTask']);?>";
	var removeTask_url 		= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'removeTask']);?>";
	var updateTaskEst_url 	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateTaskEst']);?>";
	var list_client_option 	= "<?php echo $list_client_option?>";
	var list_label_option  	= "<?php echo $list_label_option?>";
	var stt 				= <?php echo $stt;?>;
    var baseUrl             = "<?php echo $baseUrl ?>";
    var arrTaskId           = [];
	</script>