<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
<link href="<?php echo $this->Html->url('/ott')?>/css/styleCheckbox.css" rel="stylesheet">

<div class="container">

<div class="row">
<div class="row reportTopSection" id="kpiComment">
    <div class="col-md-2">
	  <div class="thumbnail" style="width: 125px;    margin: 0 0 5px 0;">
	    <img alt="<?php echo $manage_user['name']?>" src="<?php echo $this->Html->url($avatar_url)?>">
	  </div>
	  <div>
	    <p style="font-size: 10px;margin: 0; color: #999;">
	      <?php echo $manage_user['department']?> <?php echo $manage_user['position']?>
	    </p>
	    <p style="font-size: 10px;margin: 0; color: #999;">
	      <span></span>
	    </p>
	    <p style="font-size: 12px;margin: 0;">
	      <span><?php echo $manage_user['first_name']?><?php echo $manage_user['last_name']?></span>
	    </p>
	  </div>
	</div>
	<div class="col-md-10">
		<?php
		if($allow_action['change_site_note']){
		?>
		<textarea class="site_note well"><?php echo $siteInfo['site_note'];?></textarea>
		<button class="btn btn-xs btn-default btn_update_site_note" style="margin-bottom: 10px">保存</button>
		<?php
		}else{
		?>
		<div class="well" style="height: 150px;overflow-y: scroll;">
	      <p><?php echo nl2br($siteInfo['site_note']);?></p>
	    </div>
		<?php
		}
		?>
	</div>
</div>

<div class="row" style="padding:0px 15px">
	<div class="col-md-6">
		<h2 class="commonh2">
		    <a class="show_ld" href="<?php echo $this->Html->url($prev_link_report)?>">
		      	<i class="fa fa-caret-square-o-left"></i>
			</a>
			<span>
			&nbsp;
		    <?php echo $text_report_date_range?>
		    &nbsp;
			</span>
			<a class="show_ld" href="<?php echo $this->Html->url($next_link_report)?>">
		      	<i class="fa fa-caret-square-o-right"></i>
			</a>
		</h2>
	</div>
	<div class="col-md-6">

		<div class="pull-right btn_select">

			<button type="button" class="btn btn-sm btn-default <?php if(!$allow_action['change_site_range']) echo 'disabled error_per';?>" id="changeTermsBtn" data-toggle="modal" data-target="#TermsBtn">表示期間の設定</button>
		    <div class="modal fade" id="TermsBtn" role="dialog">
    			<div class="modal-dialog">

				    <!-- Modal content-->
				    <div class="modal-content">
				        <div class="modal-header">
				          	<button type="button" class="close" data-dismiss="modal">&times;</button>
				          	<h4 class="modal-title">表示開始日を選んでください</h4>
				        </div>
				        <div class="modal-body">
				        	<div class="range_setting_msg"></div>


				            <p><strong>月の設定</strong></p>
				            <p>半期の開始日を選択してください</p>

				            <ul>
				           		<li><strong>上半期</strong></li>
				           		<li>
				           			<div class="modifyMonth">
								        <span class="number1"><?php echo $range_config['number_1'];?></span><span>月</span>
								        <span class="cong"><i class="fa fa-caret-up" aria-hidden="true"></i></span>
								        <span class="tru"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
								    </div>
				           		</li>
				           		<li><span>~</span></li>
				           		<li><span class="number2"><?php echo $range_config['number_2'];?></span>月</li>
				            </ul>

				            <ul>
				           		<li><strong>下半期</strong></li>
				           		<li><span class="number3"><?php echo $range_config['number_3'];?></span>月</span></span></li>
				           		<li><span>~</span></li>
				           		<li><span class="number4"><?php echo $range_config['number_4'];?></span>月</li>
				            </ul>
				    		<div></div>
				            <p class="text-center"><button type="button" class="btn btn-default btn-modify btn_save_range_setting">保存する</button></p>
				        </div>
				        <div class="modal-footer">
				          <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
				        </div>
				    </div>

    			</div>
  			</div>
		    <button type="button" class="btn btn-sm btn-default <?php if(!$allow_action['change_site_kpi']) echo 'disabled error_per';?>" id="changeKPIBtn" data-toggle="modal" data-target="#KPIBtn">KPIを変更</button>
			<div class="modal fade" id="KPIBtn" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">表示するKPIを選択してください。</h4>
						</div>
							<div class="modal-body">
								<div class="kpi_setting_msg"></div>
								<form>
									<div id="listResults">
									<!--kpi-->
									<?php
									foreach($kpis_list as $kpi_item){
									?>

									<div class="checkbox checkbox-blue">
									<input class="kpi_setting" type="checkbox" <?php if(in_array($kpi_item['key'],$report_kpi_checked)) echo "checked";?> value="<?php echo $kpi_item['key'];?>" >
									<label><?php echo $kpi_item['title']?></label>
									</div>

									<?php
									}
									?>
									<!--end kpi-->

									</div>
								</form>
							</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary btn_save_kpi_setting" >更新</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
						</div>
					</div>

				</div>
			</div>

			<?php
			if($allow_action['copy_month_data']){
			?>
				<button class=" btn btn-sm btn-default btn_show_copy_form">データのコピー</button>
			<?php
			}
			?>
		</div>
	</div>
</div>



<div class="row">
	<div class="name_over">
    <div class="col-md-12 reportTableWrapper">
		<table class="table reportTable" style="margin-bottom:0px;">
		  <tbody>
		    <tr>
		      	<?php
		      	if(!$show_list_month){
		      		echo '<th colspan="2">&nbsp;</th>';
			      	foreach($range_data as $m_in_range){
			      		$m = $m_in_range['m'];
        				$y = $m_in_range['y'];
			      	?>
			      	<th class="reportTable__monthlbl <?php echo (($month_report == $m) ? "active" : "");?>">
			          <?php echo $m;?>月
			            <?php
			            if($month_report == $m){
			            ?>
			            <a class="btn btn-xs btn-default hideWeeklyBtn show_ld" data-month="<?php echo $m?>" href="<?php echo $this->Html->url($this->Shishimai->changeParamUrl($this->Shishimai->changeParamUrl($current_link_report,'month_report',$m),'show_list_month','1'))?>">閉じる</a>
			            <?php
			            }else{
			            ?>
			            <a class="btn btn-xs btn-default hideWeeklyBtn show_ld" data-month="<?php echo $month?>" href="<?php echo $this->Html->url($this->Shishimai->changeParamUrl($this->Shishimai->changeParamUrl($current_link_report,'month_report',$m),'show_list_month','0'));?>">週次</a>
			            <?php
			            }
			            ?>
			        </th>
			      	<?php
			      	}

			      	echo '<th class="reportTable__sumlbl"></th>';
		      	}
		      	?>
		    </tr>
		  </tbody>
		</table>
	<div class="weeklyTableWrapper" style="display: block;">

	

    <table class="table reportTable weeklyTable reportTable--weekly">
    <tbody>

    		<?php
    		if(!$show_list_month){
    		//Show header report (list week of month)
    		?>
    		<!-- Row list week of month title -->
    		<tr>
				<th colspan="2">
				  <select class="form-control" style="max-width: 100px;background-color: #efefef;height: 25px;padding: 0;" name="type_report">
				    <option value="by_day" link="<?php echo $this->Html->url($this->Shishimai->changeParamUrl($current_link_report,'type_report','by_day'))?>">各週</option>
				    <option <?php if($type_report == 'by_total') echo "selected";?> value="by_total" link="<?php echo $this->Html->url($this->Shishimai->changeParamUrl($current_link_report,'type_report','by_total'))?>">累計</option>
				  </select>
				</th>

				<?php
				foreach($week_title as $week_title_item)
				{
				?>
				<th class="reportTable__weeklbl5 <?php if($week_title_item['is_current_week']) echo 'current_week'?>">
			      <?php echo $week_title_item['week_title']?><br>
			      <small>
			        <?php echo ($show_total ? $week_title_item['week_des_total'] : $week_title_item['week_des'] );?>
			      </small>
			    </th>
				<?php
				}
				?>

				<th class="reportTable__sumlbl"><?php echo $month_report?>月分<br>合計</th>
			</tr>
			<!-- Row list week of month title -->
    		<?php
    		//End show header report (list week of month)
    		}else{
    		//Show header report (list month of range)
    		?>
    		<!-- Row list month title -->

    		<tr>
	      		<th class="reportTable__sumlbl" colspan="2" style="background-color:white">
	      		<select class="form-control" style="max-width: 100px;background-color: #efefef;height: 25px;padding: 0;" name="type_report">
				    <option value="by_day" link="<?php echo $this->Html->url($this->Shishimai->changeParamUrl($current_link_report,'type_report','by_day'))?>">単月</option>
				    <option <?php if($type_report == 'by_total') echo "selected";?> value="by_total" link="<?php echo $this->Html->url($this->Shishimai->changeParamUrl($current_link_report,'type_report','by_total'))?>">累計</option>
				</select>
				</th>

	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
		      	$m = $m_in_range['m'];
				$y = $m_in_range['y'];
		      	?>
		      	<th class="reportTable__monthlbl " style="background-color:white">
		          <?php echo $m;?>月
		            <a class="btn btn-xs btn-default hideWeeklyBtn show_ld" data-month="<?php echo $m?>" href="<?php echo $this->Html->url($this->Shishimai->changeParamUrl($this->Shishimai->changeParamUrl($current_link_report,'month_report',$m),'show_list_month',0))?>">
		            <?php echo "週次";?>
		            </a>

		            <?php if($show_advice && $allow_action['send_cw_month_data']){?>
		            <a class="btn btn-sm btn-default send_all_info_month" href="<?php echo $this->Html->url($this->Shishimai->changeParamUrl($this->Shishimai->changeParamUrl($current_link_report,'month_report',$m),'show_list_month',1)) ?>&send_info_month=1&number=<?php echo $i?>&month='<?php echo $m?>'&year='<?php echo $y?>'">ChatWorkに送信</a>
		            <?php }?>
		        </th>
		      	<?php
		      		$i++;
		      	}
		      	?>
		      	<th class="reportTable__sumlbl" style="background-color:white">6ヶ月合計</th>
		    </tr>



		    <!-- ======================================================= ADVICE ==============================================-->
<?php if($show_advice){?>

		    <!-- Row list month title -->
		    <tr style="cursor: pointer;" class="header_advice">
		    	<th class="reportTable__monthlbl advice_header_text" style="border-top:none" colspan="2">
		    		6ヶ月施策表を開く
				</th>
	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
		      	$m = $m_in_range['m'];
				$y = $m_in_range['y'];
		      	?>
		      	<th class="reportTable__monthlbl">
		          <?php echo $m;?>月
		        </th>
		      	<?php
		      		$i++;
		      	}
		      	?>
		      	<th style="border-top:none">
				</th>
		    </tr>

		    <!-- Row note of list month title -->
    		<tr class="advice_note">
		      <td colspan="2">今月の施策</td>
		      	<?php
		      	if($show_note_month){
				    $i = 1;
			      	foreach($range_data as $m_in_range){
			      		$m = $m_in_range['m'];
        				$y = $m_in_range['y'];
				    ?>
			        <td>
			            <?php
			            if($allow_action['edit_advice_note']){
			            ?>
			            <textarea style="width:110px"><?php echo (isset($month_note[$m]) ? $month_note[$m] : ""); ?></textarea><button class="btn btn-xs btn-default btn_update_month_note" month="<?php echo $m;?>" year="<?php echo $y;?>">保存</button>
			            <?php
			        	}else{
			        	?>
			        	<p><?php echo (isset($month_note[$m]) ? $month_note[$m] : ""); ?></p>
			        	<?php
			        	}
			            ?>
			        </td>
			        <?php
			        $i++;
					}
				}else{
					?>
					<td></td><td></td><td></td><td></td><td></td><td></td>
					<?php
				}
				?>
		      	<td></td>
		    </tr>

		    <?php
		    foreach($advice_note_key_title as $key_note=>$title_note){
		    ?>
		    <!-- Row advice note-->
		    <tr class="advice_note">
	      		<td colspan="2">
	      			<?php
	      			if($allow_action['edit_advice_title'] && in_array($key_note,array('advice_note_13','advice_note_14','advice_note_15'))){
	      			?>
	      			<textarea style="width:100%" class="advice_key_input"><?php echo $title_note;?></textarea><br>
	      			<button class="btn btn-xs btn-default btn_update_advice_key_note pull-right" key="<?php echo $key_note;?>" month="<?php echo $advice_start_month;?>" year="<?php echo $advice_year;?>">保存</button>
	      			<?php
	      			}else{
	      				echo $title_note;
	      			}

	      			?>
				</td>

	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
		      	$m = $m_in_range['m'];
				$y = $m_in_range['y'];
		      	?>
		      	<td class="reportTable__monthlbl" style="
		      	<?php
		      	if($key_note == 'advice_note_8' || $key_note == 'advice_note_10'){
		      		echo 'text-align:right;';
		      	}
		      	?>">
		      	<?php
		      		if($allow_action['edit_advice_note']){
		      			//===================== allow edit ===============
		      			if($key_note == 'advice_note_8' || $key_note == 'advice_note_10'){
			      			?>¥ <input class="advice_input number" type="text"
			      			original_value="<?php if(!empty($advice_note[$m][$key_note])) echo $advice_note[$m][$key_note];?>" 
			      			value="<?php if(!empty($advice_note[$m][$key_note])) echo $advice_note[$m][$key_note];?>"
			      			style="width:84%"
			      			>
			      			<?php
			      		}else{
			      			?>
			      			<textarea class="advice_input"><?php if(!empty($advice_note[$m][$key_note])) echo $advice_note[$m][$key_note];?></textarea>
			      			<?php
			      		}
			      		?>
			      		<button class="btn btn-xs btn-default btn_update_advice_note" key="<?php echo $key_note;?>" month="<?php echo $m;?>" year="<?php echo $y;?>">保存</button>
			      		<?php
		      			//===================== allow edit ===============
		      		}else{
		      			//===================== only see ==============
		      			if($key_note == 'advice_note_8' || $key_note == 'advice_note_10'){
			      			if(!empty($advice_note[$m][$key_note])) 
			      				echo "¥".number_format($advice_note[$m][$key_note]);
			      			else
			      				echo "¥0";
			      		}else{
			      			if(!empty($advice_note[$m][$key_note])) echo nl2br($advice_note[$m][$key_note]);
			      		}
		      			//===================== only see ==============
		      		}
		      		?>
		        </td>
		      	<?php
		      		$i++;
		      	}
		      	?>
		      	<td>

				</td>
		    </tr>
		    <!-- Row advice note-->
		    <?php
			}
		    ?>

		    <!--Advice header サイト -->
		    <tr class="advice_note" style="background-color:#f7f7f7;">
		    	<td >
	      			サイト
				</td>

				<td class="reportTable__sumlbl"></td>
	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
		      	$m = $m_in_range['m'];
				$y = $m_in_range['y'];
		      	?>
		      	<td class="reportTable__monthlbl" style="text-align:center">
		          <?php echo $m;?>月
		        </td>
		      	<?php
		      		$i++;
		      	}
		      	?>
		      	<td>
				</td>
		    </tr>
		    <!--Advice header サイト -->

		    <!--Advice header サイト - Content -->
		    <?php
		    foreach($advice_keys as $advice_key=>$advice_title){
		    ?>
		    <tr class="advice_note">
	      		<td >
	      			<?php echo $advice_title?>
				</td>

				<td class="reportTable__sumlbl"></td>
	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
		      	$m = $m_in_range['m'];
				$y = $m_in_range['y'];
				$advice_value_input = $advice_by_month[$m][$advice_key];
				$advice_value_display = $advice_by_month_display[$m][$advice_key];
				$pre_char_input = "";
				$sub_char_input = "";
				$input_style 	= "";
				if($advice_key == 'advice_transactionsPerSession'){
      				$sub_char_input = '%';
      				$pre_char_input = '';
      				$input_style 	= "style='width:84%'";
      			}elseif($advice_key == 'advice_revenuePerTransaction'){
      				$pre_char_input = '¥';
      				$sub_char_input = '';
      				$input_style 	= "style='width:84%'";
      			}else{
      				$pre_char_input = "";
					$sub_char_input = "";
      			}
		      	?>
		      	<td class="reportTable__monthlbl" style="text-align:right">
		      		<?php
		      		if($allow_action['edit_advice_note']){
		      			echo $pre_char_input;
		      			?>
		      			<input class="advice_input number" type="text" <?php echo $input_style;?>
		      			original_value="<?php if(!empty($advice_value_input)) echo $advice_value_input;?>" 
		      			value="<?php if(!empty($advice_value_input)) echo $advice_value_input;?>">
		      			<?php
		      			echo $sub_char_input;
		      			?>
		      			<button class="btn btn-xs btn-default btn_update_advice_note" key="<?php echo $advice_key;?>" month="<?php echo $m;?>" year="<?php echo $y;?>">保存</button>
		      			<?php
		      		}else{
		      			echo htmlspecialchars($advice_value_display);
		      		}
		      		?>
		        </td>
		      	<?php
		      		$i++;
		      	}
		      	?>
		      	<td>

				</td>
		    </tr>
		    <?php
			}
		    ?>

		    <tr class="advice_note">
	      		<td>
	      			売上予測
				</td>

				<td class="reportTable__sumlbl" style="text-align:right"></td>
	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
			      	$m = $m_in_range['m'];
					$y = $m_in_range['y'];

					$estimated = $advice_by_month_display[$m]['estimated'];
			      	?>
			      	<td class="reportTable__monthlbl" style="text-align:right">
			          <span class="estimated_display" month='<?php echo $m?>' year='<?php echo $y?>'><?php echo $estimated;?></span>
			        </td>
			      	<?php
			      		$i++;
		      	}
		      	?>
		      	<td>

				</td>
		    </tr>
		    <!--Advice header サイト - Content -->


		    <!--Advice header サイト合計-->
		    <tr class="advice_note" style="background-color:#f7f7f7;">
		    	<td >
	      			サイト合計
				</td>

				<td class="reportTable__sumlbl"></td>
	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
		      	$m = $m_in_range['m'];
				$y = $m_in_range['y'];
		      	?>
		      	<td class="reportTable__monthlbl" style="text-align:center">
		          <?php echo $m;?>月
		        </td>
		      	<?php
		      		$i++;
		      	}
		      	?>
		      	<td>
				</td>
		    </tr>
		    <!--Advice header サイト合計-->

		    <!--Advice header サイト合計 - Content-->

		    <tr class="advice_note">
	      		<td >
	      			売上予測
				</td>

				<td class="reportTable__sumlbl" ></th>
	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
			      	$m = $m_in_range['m'];
					$y = $m_in_range['y'];

					$estimated = $advice_by_month_display[$m]['estimated'];
			      	?>
			      	<td class="reportTable__monthlbl" style="text-align:right">
			          <span class="estimated_display" month='<?php echo $m?>' year='<?php echo $y?>'><?php echo $estimated;?></span>
			        </td>
			      	<?php
			      		$i++;
		      	}
		      	?>
		      	<td>

				</td>
		    </tr>

		    <tr class="advice_note">
	      		<td >
	      			コスト予測
				</td>

				<td class="reportTable__sumlbl" style="text-align:right"></td>
	      		<?php
	      		$i = 1;
		      	foreach($range_data as $m_in_range){
			      	$m = $m_in_range['m'];
					$y = $m_in_range['y'];

					$estimated_cost = $advice_by_month_display[$m]['estimated_cost'];
			      	?>
			      	<td class="reportTable__monthlbl" style="text-align:right">
			          <span class='estimated_cost_display' month='<?php echo $m?>' year='<?php echo $y?>'><?php echo $estimated_cost;?></span>
			        </td>
			      	<?php
			      		$i++;
		      	}
		      	?>
		      	<td style="background-color:white">

				</td>
		    </tr>
		    <!--Advice header サイト合 - Content-->

		    <!-- End row advice -->
<?php }?>
		    <!-- ======================================================= END ADVICE ==============================================-->


    		<?php
    		//End show header report (list month of range)
    		}

    		?>

    		<!-- Row note of list month title -->

			<!-- =========================================Loop KPI List ==========================================-->
			<?php
			$kpi_stt = 1;
			foreach($kpis_list as $kpi_item){
				if($kpi_item['prospective'] == 1 && $show_prospective){
					$row_span = 3;
				}else{
					$row_span = 2;
				}
				if(in_array($kpi_item['key'],$report_kpi_checked)){
					if(!$show_list_month){
					?>
					<!-- =========================================BEGIN DISPLAY KPI (WEEK OF MONTH)=====================================================-->
					<!--Target Row <?php echo $kpi_item['key'];?>-->

					<tr class="kpi_line_revenues targetLine">
						<td rowspan="<?php echo $row_span?>" class="width10">
						<?php echo $kpi_stt.". ".$kpi_item['title']?>
						</td>
						<td>目標値</td>
						<?php
						for($week = 1;$week <= $count_week_report;$week++){
						?>

						<td class="editable">
							<span class="mainNumber wrap_input_current">
							<?php echo $this->Shishimai->displayValueTarget($target_week[$week][$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
							</span>
							<!--Btn change target value-->
							<?php
							if($type_report == 'by_day'){

								$cr_value = $target_week[$week][$kpi_item['key']];

								if('avgSessionDuration' == $kpi_item['key']){
									$cr_value = gmdate("i:s",$target_week[$week][$kpi_item['key']]);
									$input_type = 'text';
								}else{
									$input_type = 'number';
								}
							?>
								<span class="wrap_input_update">
								<input
								type="<?php echo $input_type?>"
								class="input_update_value"
								value="<?php echo $cr_value;?>"
								edit_type="target"
								kpi_key="<?php echo $kpi_item['key']?>"
								kpi_title="<?php echo $kpi_item['title']?>"
								current_week="<?php echo $week;?>"
								current_value="<?php echo $cr_value;?>"
								>
								<button class="btn_update_value btn btn-default btn-xs edit-actual-submit-btn">保存</button>
								</span>
								<i class="fa fa-edit edit-target-btn <?php echo (($allow_action['edit_week_target'])? 'btn_show_edit' : 'error_per');?>"></i>

							<?php
							}
							?>

							<!--Btn change target value-->
						</td>

						<?php
						}
						?>
						<td>
							<span class="mainNumber">
							<?php echo $this->Shishimai->displayValueTarget($total_target_month[$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
							</span>
						</td>
					</tr>

					<!--End Target Row-->

					<!--Actual & Ratio-->
					<tr>
				  		<td class="width10">実績値</td>
					  	<?php
						for($week = 1;$week <= $count_week_report;$week++){

						$actual_display = $this->Shishimai->displayActualKPIpageV2($kpi_item['key'],$actual_value[$week],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);

						if($actual_display != '-'){
							if($show_total){
								$minus_class = ($ratio_with_target[$week][$kpi_item['key']] < 100 && $target_week[$week][$kpi_item['key']] != 0) ? 'minus' :'';
							}else{
								$minus_class = ( in_array($week_title[$week]['week_in'],array('past','present')) && $ratio_with_target[$week][$kpi_item['key']] < 100 && $target_week[$week][$kpi_item['key']] != 0) ? 'minus' :'';
							}
						}else{
							$minus_class = '';
						}
						?>

				    	<td class="editable">
							<span class="mainNumber <?php echo $minus_class;?> wrap_input_current">
							<?php
							echo $actual_display;
							?>
							</span>
							<!--Btn change actual value-->
							<?php
							if($kpi_item['allow_change_actual'] == 1 && $type_report == 'by_day'){

								$cr_value = $actual_value[$week][$kpi_item['key']];

								if('avgSessionDuration' == $kpi_item['key']){
									$cr_value = gmdate("i:s",$cr_value);
									$input_type = 'text';
								}else{
									$input_type = 'number';
								}
							?>
							<span class="wrap_input_update">
								<input
								type="<?php echo $input_type;?>"
								class="input_update_value"
								value="<?php echo $actual_value[$week][$kpi_item['key']];?>"
								edit_type="actual"
								kpi_key="<?php echo $kpi_item['key']?>"
								kpi_title="<?php echo $kpi_item['title']?>"
								current_week="<?php echo $week;?>"
								current_value="<?php echo $cr_value;?>"
								>
								<button class="btn_update_value btn btn-default btn-xs edit-actual-submit-btn">保存</button>
							</span>
							<i class="fa fa-edit edit-target-btn <?php echo (($allow_action['edit_week_actual'])? 'btn_show_edit' : 'error_per');?>"></i>
							<?php
							}
							?>
							<!--Btn change actual value-->
							<br>
							<small class="subNumber <?php echo $minus_class;?>">
							<span>目標比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($ratio_with_target[$week][$kpi_item['key']],'number','','%',1);?>
							</small>
							<small class="subNumber">
							<span>前週比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($ratio_with_prev_week[$week][$kpi_item['key']],'number','','%',1);?>
							</small>
							<small class="subNumber">
							<span>前年比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($ratio_with_prev_yearW[$week][$kpi_item['key']],'number','','%',1);?>
							</small>
						</td>

			    		<?php
						}
						?>

						<?php
							$actual_display = $this->Shishimai->displayActualKPIpageV2($kpi_item['key'],$total_actual_month,$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);

							if($actual_display != '-'){
								$minus_class = (($total_ratio_target_month[$kpi_item['key']] < 100 && $total_target_month[$kpi_item['key']] != 0) ? 'minus' :'');
							}else{
								$minus_class = '';
							}
						?>
						<td>
							<span class="mainNumber <?php echo $minus_class?>">
							<?php
							echo $actual_display;
							?>
							</span>
							<small class="subNumber <?php echo $minus_class?>">
							<span>目標比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($total_ratio_target_month[$kpi_item['key']],'number','','%',1);?>
							</small>
							<small class="subNumber">
							<span>前月比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($total_ratio_prevMonth_month[$kpi_item['key']],'number','','%',1);?>
							</small>
							<small class="subNumber">
							<span>前年比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($total_ratio_prevYearM_month[$kpi_item['key']],'number','','%',1);?>
							</small>
						</td>
					</tr>

					<!--End Actual & ratio-->

					<?php
					if($kpi_item['prospective'] == 1 && $show_prospective){
					//Begin kpi prospective
					?>
					<tr class="reportTable__estimateRow">
					    <td>実績値<br>&nbsp;&nbsp;&nbsp;&nbsp;+<br>到着見込</td>
					    <?php
						for($week = 1;$week <= $count_week_report;$week++){
							if(isset($actual_prospective_value[$week][$kpi_item['key']])){
						?>
							<td>
								<span class="mainNumber ">
								<?php echo $this->Shishimai->displayValue($actual_prospective_value[$week][$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
								</span>
								<br>
								<small class="subNumber">
								<span>目標比&nbsp;</span>
								<?php echo $this->Shishimai->displayValue($ratio_prospective_with_target[$week][$kpi_item['key']],'number','','%',1);?>
								</small>
								<small class="subNumber">
								<span>前週比&nbsp;</span>
								<?php echo $this->Shishimai->displayValue($ratio_prospective_with_prev_week[$week][$kpi_item['key']],'number','','%',1);?>
								</small>
								<small class="subNumber">
								<span>前年比&nbsp;</span>
								<?php echo $this->Shishimai->displayValue($ratio_prospective_with_prev_yearW[$week][$kpi_item['key']],'number','','%',1);?>
								</small>
					      	</td>
						<?php
							}else{
						?>
							<td><div style="text-align: center;">-</div></td>
						<?php
							}
						}
						?>

					    <td>
						    <span class="mainNumber ">
						    <?php echo $this->Shishimai->displayValue($total_actual_prospective_month[$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
						    </span>
						    <small class="subNumber">
						      <span>目標比&nbsp;</span>
						      <?php echo $this->Shishimai->displayValue($total_ratio_prospective_target_month[$kpi_item['key']],'number','','%',1);?>
						    </small>
						    <small class="subNumber">
						      <span>前月比&nbsp;</span>
						      <?php echo $this->Shishimai->displayValue($total_ratio_prospective_prevMonth_month[$kpi_item['key']],'number','','%',1);?>
						    </small>
						    <small class="subNumber">
						      <span>前年比&nbsp;</span>
						      <?php echo $this->Shishimai->displayValue($total_ratio_prospective_prevYearM_month[$kpi_item['key']],'number','','%',1);?>
						    </small>
					    </td>
					</tr>
					<tr></tr>

					<?php
					//End kpi prospective
					}
					?>
					<!-- ========================================END DISPLAY KPI (WEEK OF MONTH)================================================ -->
					<?php
					}else{
					?>
					<!-- ========================================BEGIN DISPLAY KPI (MONTH OF RANGE )============================================ -->
					<!--Target Row <?php echo $kpi_item['key'];?>-->

					<tr class="kpi_line_revenues targetLine">
						<td rowspan="<?php echo $row_span?>"><?php echo $kpi_stt.". ".$kpi_item['title']?></td>
						<td class="width10">目標値</td>
						<?php
						$i = 1;
				      	foreach($range_data as $m_in_range){
				      	$m = $m_in_range['m'];
	        			$y = $m_in_range['y'];

						?>

						<td class="editable reportTable__monthlbl">
							<span class="mainNumber wrap_input_current">
							<?php echo $this->Shishimai->displayValueTarget($target_week[$i][$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
							</span>
						</td>

						<?php
						$i++;
						}
						?>
						<td>
							<span class="mainNumber">
							<?php echo $this->Shishimai->displayValueTarget($total_target_month[$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
							</span>
						</td>
					</tr>

					<!--End Target Row-->

					<!--Actual & Ratio-->
					<tr>
				  		<td class="width10">実績値</td>
					  	<?php
						$i = 1;
				      	foreach($range_data as $m_in_range){
				      	$m = $m_in_range['m'];
	        			$y = $m_in_range['y'];

	        			$actual_display = $this->Shishimai->displayActualKPIpageV2($kpi_item['key'],$actual_value[$i],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);

	        			if($actual_display != '-'){
		        			if($show_total){
		        				$minus_class = ((is_numeric($ratio_with_target[$i][$kpi_item['key']])) &&($ratio_with_target[$i][$kpi_item['key']] < 100) && ($target_week[$i][$kpi_item['key']] != 0)) ? "minus":'';
		        			}else{
		        				$minus_class = ((is_numeric($ratio_with_target[$i][$kpi_item['key']])) &&($ratio_with_target[$i][$kpi_item['key']] < 100) && ($target_week[$i][$kpi_item['key']] != 0)) ? "minus":'';
		        			}
	        			}else{
	        				$minus_class = '';
	        			}
						?>

				    	<td class="editable">
							<span class="mainNumber <?php echo $minus_class;?> wrap_input_current">
							<?php
								echo $actual_display;
							?>
							</span>
							<br>
							<small class="subNumber <?php echo $minus_class;?>">
							<span>目標比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($ratio_with_target[$i][$kpi_item['key']],'number','','%',2);?>
							</small>
							<small class="subNumber">
							<span>前月比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($ratio_with_prev_week[$i][$kpi_item['key']],'number','','%',2);?>
							</small>
							<small class="subNumber">
							<span>前年比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($ratio_with_prev_yearW[$i][$kpi_item['key']],'number','','%',2);?>
							</small>
						</td>

			    		<?php
			    		$i++;
						}
						?>


						<?php

						$actual_display = $this->Shishimai->displayActualKPIpageV2($kpi_item['key'],$total_actual_month,$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);

						if($actual_display != '-'){
							if($kpi_item['prospective'] == 1 && $show_prospective){
								$minus_class = (($total_ratio_prospective_target_month[$kpi_item['key']] < 100 && $total_target_month[$kpi_item['key']] != 0 && $total_target_month[$kpi_item['key']] != '-') ? 'minus':'');
							}else{
								$minus_class = (($total_ratio_target_month[$kpi_item['key']] < 100 && $total_target_month[$kpi_item['key']] != 0 && $total_target_month[$kpi_item['key']] != '-') ? 'minus':'');
							}
						}else{
							$minus_class = '';
						}
						?>
						<td>
							<span class="mainNumber 
							<?php echo $minus_class;?>">
							<?php echo $actual_display;?>
							</span>
							<small class="subNumber <?php echo $minus_class;?>">
							<span>目標比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($total_ratio_target_month[$kpi_item['key']],'number','','%',2);?>
							</small>
							<small class="subNumber">
							<span>前月比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($total_ratio_prevMonth_month[$kpi_item['key']],'number','','%',2);?>
							</small>
							<small class="subNumber">
							<span>前年比&nbsp;</span>
							<?php echo $this->Shishimai->displayValue($total_ratio_prevYearM_month[$kpi_item['key']],'number','','%',2);?>
							</small>
						</td>
					</tr>

					<!--End Actual & ratio-->

					<!-- prospective -->
					<?php
					if($kpi_item['prospective'] == 1 && $show_prospective){
					?>
					<tr class="reportTable__estimateRow">
					    <td>実績値<br>&nbsp;&nbsp;&nbsp;&nbsp;+<br>到着見込</td>
					    <?php
						$i = 1;
				      	foreach($range_data as $m_in_range){
					      	$m = $m_in_range['m'];
		        			$y = $m_in_range['y'];
							if(isset($actual_prospective_value[$i][$kpi_item['key']])){
						?>
							<td>
								<span class="mainNumber">
								<?php echo $this->Shishimai->displayValue($actual_prospective_value[$i][$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
								</span>
								<br>
								<small class="subNumber">
								<span>目標比&nbsp;</span>
								<?php echo $this->Shishimai->displayValue($ratio_prospective_with_target[$i][$kpi_item['key']],'number','','%',2);?>
								</small>
								<small class="subNumber">
								<span>前月比&nbsp;</span>
								<?php echo $this->Shishimai->displayValue($ratio_prospective_with_prev_week[$i][$kpi_item['key']],'number','','%',2);?>
								</small>
								<small class="subNumber">
								<span>前年比&nbsp;</span>
								<?php echo $this->Shishimai->displayValue($ratio_prospective_with_prev_yearW[$i][$kpi_item['key']],'number','','%',2);?>
								</small>
					      	</td>
						<?php
							}else{
						?>
							<td><div style="text-align: center;">-</div></td>
						<?php
							}
							$i++;
						}
						?>

					    <td>
						    <span class="mainNumber ">
						    <?php echo $this->Shishimai->displayValue($total_actual_prospective_month[$kpi_item['key']],$kpi_item['type_data'],$kpi_item['pre_char'],$kpi_item['sub_char'],$kpi_item['decimal']);?>
						    </span>
						    <small class="subNumber ">
						      <span>目標比&nbsp;</span>
						      <?php echo $this->Shishimai->displayValue($total_ratio_prospective_target_month[$kpi_item['key']],'number','','%',2);?>
						    </small>
						    <small class="subNumber">
						      <span>前月比&nbsp;</span>
						      <?php echo $this->Shishimai->displayValue($total_ratio_prospective_prevMonth_month[$kpi_item['key']],'number','','%',2);?>
						    </small>
						    <small class="subNumber">
						      <span>前年比&nbsp;</span>
						      <?php echo $this->Shishimai->displayValue($total_ratio_prospective_prevYearM_month[$kpi_item['key']],'number','','%',2);?>
						    </small>
					    </td>
					</tr>
					<tr></tr>

					<?php
					}
					?>
					<!-- prospective -->

					<!-- ========================================END DISPLAY KPI (MONTH OF RANGE )============================================== -->
					<?php
					}
					$kpi_stt++;
				}
			//End loop kpi
			}
			?>
			<!--========================================= End Loop KPI List ==========================================-->

    </tbody>
  </table>
</div>
	<!--div style="padding: 30px;">
	  <p style="float: left">
	    ※各数値データは、計測対象期間から24時間経過後に、数値が最終確定します。<br>（計測対象期間から24時間以内は、数値データが変動する場合があります。）
	  </p>
	  <a class="btn btn-default" style="float: right;" href="/projects/1/export_csv.csv?month=8&amp;report_type=weekly&amp;year=2017">CSV出力</a>
	</div-->
</div>
</div>
</div>
</div>


<!--Modal copy month data-->
<?php if($allow_action['copy_month_data']){?>
<div class="modal fade" id="copy_modal">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">データのコピー</h4>
			</div>
				<div class="modal-body">
					<form class="form-horizontal" id="form_copy">
						<div class="row" >
							<div class="form-group">
							    <label class="col-sm-3 control-label">データの種類</label>
							    <div class="col-sm-9">
							      	<select class="form-control" name="copy_data_type">
								  	<option value="note_data">6ヶ月施策</option>
								  	<option value="target_data">目標値</option>
									</select>
							    </div>
							</div>
						</div>

						<div class="row" style="margin-top:20px">
							<div class="form-group">
							    <label class="col-sm-3 control-label">コピー元サイト</label>
							    <div class="col-sm-9">
							      	<select class="form-control" name="copy_data_from_site">
								  	<?php echo $this->Shishimai->buildOptionSite($listSiteIDInfo,$site_id);?>
									</select>
							    </div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
							    <label class="col-sm-3 control-label">時間</label>
							    <div class="col-sm-4">
								    <select class="form-control" name="copy_data_from_year">
									  	<?php echo $this->Shishimai->getOptionYear();?>
									</select>
							    </div>
							    <div class="col-sm-5">
								    <select class="form-control" name="copy_data_from_month">
									  	<?php echo $this->Shishimai->getOptionMonth();?>
									</select>
							    </div>
							</div>
						</div>

						<div class="row" style="margin-top:20px">
							<div class="form-group">
							    <label class="col-sm-3 control-label">コピー先サイト</label>
							    <div class="col-sm-9">
							      <select class="form-control" name="copy_data_to_site">
								  	<?php echo $this->Shishimai->buildOptionSite($listSiteIDInfo,$site_id);?>
								</select>
							    </div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
							    <label class="col-sm-3 control-label">時間</label>
							    <div class="col-sm-4">
								    <select class="form-control" name="copy_data_to_year">
									  	<?php echo $this->Shishimai->getOptionYear();?>
									</select>
							    </div>
							    <div class="col-sm-5">
								    <select class="form-control" name="copy_data_to_month">
									  	<?php echo $this->Shishimai->getOptionMonth();?>
									</select>
							    </div>
							</div>
						</div>
						<input type="hidden" name="copy_confirmed" value="0">
					</form>
				</div>
			<div class="modal-footer">

				<span class="copy_msg warning clearfix" style="margin-bottom: 10px;"></span>
				<div class="confirm_button" style="display:none">
					<button type="button" class="btn btn-primary btn_copy_data_confirmed" >はい</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">いいえ</button>
				</div>
				<div class="normal_button">
					<button type="button" class="btn btn-primary btn_copy_data" >コピーする</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<!--End modal copy month data-->

<!--download file csv-->
<div style="margin-bottom: 100px">
	<div class="col-md-8">
		<p>※各数値データは、計測対象期間から24時間経過後に、数値が最終確定します。<br/>
		（計測対象期間から24時間以内は、数値データが変動する場合があります。）</p>
	</div>
	<div class="col-md-4">
		<p class="pull-right">
		<?php
		if($allow_action['import_csv'])
		echo $this->Html->link("入力", array("controller" => "OttImport", "action" => "index"), array("class" => "btn btn-default",'style="margin-right:2px"'));
		if($allow_action['export_csv'])
		echo $this->Html->link("CSV出力", array("controller" => "OttKpi", "action" => "index", "?".$uri), array("class" => "btn btn-default"));
		?>
		</p>
	</div>
</div>
<!--end download file csv-->
</div>
<link rel="stylesheet" href="<?php echo $this->Html->url('/ott')?>/css/kpi_page.css">
<script type="text/javascript">
var current_url             = "<?php echo $this->request->here();?>";
var kpi_url             	= "<?php echo Router::url(['controller' => 'OttKpi', 'action' => 'index']);?>";
var year_report 			= '<?php echo $year_update_db;?>';
var month_report 			= '<?php echo $month_report;?>';
var updateActualValue_url 	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateActualValue']);?>";
var updateTagetValue_url 	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateTagetValue']);?>";
var updateRangeReport_url 	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateSiteRange']);?>";
var updateKpiReport_url 	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateSiteKPI']);?>";
var updateSiteNote_url 		= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateSiteNote']);?>";
var updateMonthNote_url 	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateMonthNote']);?>";
var copyDataMonth_url		= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'copyDataMonth']);?>";
var updateAdviceTitle_url	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'updateAdviceTitle']);?>";
var site_id 				= "<?php echo $site_id;?>";
var auto_show_advice		= "<?php echo $auto_show_advice;?>";

var advice_year 			= "<?php echo $advice_year;?>";
var advice_start_month 		= "<?php echo $advice_start_month;?>";
</script>
<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
<script src="<?php echo $this->Html->url('/ott')?>/js/kpi_page.js"></script>
