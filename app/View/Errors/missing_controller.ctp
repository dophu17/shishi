<div id="container_b">
<p class="error_height"></p>
<div class="form_top"></div><!-- /form_top -->
<div class="wbg mb40">
<div class="error_page_text">
<?php if (Configure::read('debug') > 0):?>
	<h2><?php echo $message; ?></h2>
	<p class="error">
	<strong><?php echo __d('cake', 'Error'); ?>: </strong>
	<?php printf(__d('cake', 'The requested address %s was not found on this server.'),
	"<strong>'{$url}'</strong>"); ?>
	</p>
<?php echo $this->element('exception_stack_trace');
else:
?>
	<p class="text">指定されたページは存在しません。</p>
	<p>もう一度URLをご確認ください。</p>
<?php endif; ?>
</div>
</div><!-- /wbg -->
<div class="table_bottom"></div>
</div><!-- /container_b -->
