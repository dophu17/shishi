<div class="baseConainer">
    <div class="mainContents nofooter">
        <div class="">
            <div class="row reportTopSection">
                <div class="col-md-8 clearfix">
                    <h2 class="commonh2" style="float: left;margin-right: 20px"><?php echo $show_user['SsmUser']['first_name'].$show_user['SsmUser']['last_name']?></h2> 
                    
                </div>
                <div class="col-md-4">
                    <h2 class="commonh2" style="text-align:right">
                        <a class="show_ld" href="<?php echo $this->Html->url($prev_link);?>">
                            <i class="fa fa-caret-square-o-left"></i>
                        </a>
                        <span>
                        &nbsp;
                        <?php echo $year?>年 <?php echo $month?>月          &nbsp;
                        </span>
                        <a class="show_ld" href="<?php echo $this->Html->url($next_link);?>" style="float:right">
                            <i class="fa fa-caret-square-o-right"></i>
                        </a>
                    </h2>
                </div>
                <div class="table_wrap">
                    <table class="table reportTable clientTable table-striped">
                        <thead>
                            <tr>
                                <!--<th width="250" class="t_left">クライアント名</th>-->
                                <th width="250" class="t_left">サイト名</th>
                                <th width="100" class="t_center">一ヶ月工数</th>
                                <th width="100" class="t_center">使用工数
                                </th>
                                <?php
                                foreach($label_option as $label_id=>$label){
                                ?>
                                	<th width="100" class="t_center"><?php echo $label;?></th>
                                <?php
                                }
                                ?>
                                
                            </tr>
                        </thead>
                        <tbody id="list_content">

                                <tr>
                                    <td colspan='1'>合計</td>
                                    <td><b><?php echo number_format($total_data['total_site_hour_calculate'], 2, '.', '');?></b></td>
                                    <td><b><?php echo number_format($total_data['total_all_label'], 2, '.', '');?></b></td>
                                    <?php
                                    foreach($label_option as $label_id=>$label){
                                        ?>
                                        <td><b><?php echo ($total_data['group_by_label'][$label_id] ? number_format($total_data['group_by_label'][$label_id], 2, '.', '') : '0.00');?></b></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                        		<?php
                        		if(!empty($list)){
	                        		foreach($list as $site_task){

	                        			$task_html = "";
	                        			foreach($label_option as $label_id=>$label){
	                        				$task_html .= "<td>".($site_task['task'][$label_id] ? number_format($site_task['task'][$label_id], 2, '.', '') : '0.00')."</td>";
	                        			}

	                        			?>
	                        			<tr>
                                        <!--<td class="t_left"> <?php /*echo $site_task['users'];*/?></td>-->
	                        			<td class="t_left"> <?php echo $site_task['site_name'];?></td>

	                        			<td> <?php echo number_format($site_task['site_hour_calculate'], 2, '.', '');?></td>

	                        			<td> <?php echo number_format($site_task['task_total'], 2, '.', '');?></td>

	                        			<?php echo $task_html;?>
	                        			</tr>
	                        			<?php
	                        		}


                                    ?>
                                    <?php

	                        	}else{
	                        		?>
	                        		<tr><td colspan="<?php echo count($label_option)+4;?>"> 今月はデータがございません。 </td></tr>
	                        		<?php
	                        	}
                        		?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
@media(max-width: 1200px){
    .table_wrap{
        overflow-y: hidden;
        overflow-x: scroll;
        -webkit-overflow-x: scroll;
        -webkit-overflow-scrolling: touch;
    }
}

.t_left{
    text-align: left !important;
}
.t_right{
    text-align: right !important;
}
.t_center{
    text-align: center !important;
}
</style>

