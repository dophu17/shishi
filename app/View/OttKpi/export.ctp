<?php

 $output =""; 

 $output .= ",";

if(!$show_list_month){
	for($i = 1; $i <= count($week_title); $i++){
		 $output .= "," /*. $week_title[$i]['week_title']*/ . $week_title[$i]['week_des'];
	}
	$output .= "," . "8月分合計" ."\n";
}else{
	foreach($range_data as $mInRange){
		$month = $mInRange['m'];
		$output .= ",". $month."月";
	}
	$output .= "," . "6ヶ月合計" ."\n";
}


$kpi_stt = 1;

if(!$show_list_month){
	foreach($kpis_list as $kpi_item){
	if(in_array($kpi_item['key'],$report_kpi_checked)){
		//One kpi
		//row1
		 $output .= $kpi_stt.". ".$kpi_item['title'] . "," . "目標値";

		for($week = 1;$week <= $count_week_report;$week++){
			 $output .= "," . '"' . $this->Shishimai->displayValueExport($target_week[$week][$kpi_item['key']], $kpi_item['type_data'], "", $kpi_item['sub_char']) .'"' ;
		}
		 $output .=  "," . '"' . $this->Shishimai->displayValueExport($total_target_month[$kpi_item['key']],$kpi_item['type_data'], "" ,$kpi_item['sub_char']) . '"';
		 $output .= "\n";

		//row2
		 $output .= "," . "実績値";

		for($week = 1;$week <= $count_week_report;$week++){
		 $output .=  "," . '"' . $this->Shishimai->displayValueExport($actual_value[$week][$kpi_item['key']],$kpi_item['type_data'],"",$kpi_item['sub_char']) . '"';	
		}
		 $output .= "," . '"' . $this->Shishimai->displayValueExport($total_actual_month[$kpi_item['key']],$kpi_item['type_data'],"",$kpi_item['sub_char']) . '"';
		 $output .= "\n";

		 $output .= "," . "目標比";
		for($week = 1;$week <= $count_week_report;$week++){
		 $output .= ',"' . $this->Shishimai->displayValue($ratio_with_target[$week][$kpi_item['key']],'number','','%') . '"' ;
		}
		 $output .= ',"' . $this->Shishimai->displayValue($total_ratio_target_month[$kpi_item['key']],'number','','%') . '"';
		 $output .= "\n";

		 $output .= "," . "前週比";
		for($week = 1;$week <= $count_week_report;$week++){
		 $output .=  "," . '"' . $this->Shishimai->displayValue($ratio_with_prev_week[$week][$kpi_item['key']],'number','','%') . '"';
		}
		 $output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prevMonth_month[$kpi_item['key']],'number','','%') . '"';
		 $output .= "\n";

		 $output .= "," . "前年比";
		for($week = 1;$week <= $count_week_report;$week++){
		 $output .=  "," . '"' . $this->Shishimai->displayValue($ratio_with_prev_yearW[$week][$kpi_item['key']],'number','','%') . '"';
		}
		 $output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prevYearM_month[$kpi_item['key']],'number','','%') . '"';
		 $output .= "\n";

		//row3
		/*
		 $output .= ",実績値 到着見込";
		for($week = 1;$week <= $count_week_report;$week++){
			 $output .= "," . '"' . $this->Shishimai->displayValue($actual_prospective_value[$week][$kpi_item['key']],$kpi_item['type_data'],"",$kpi_item['sub_char']) . '"';
		}
		 $output .= "," . '"' . $this->Shishimai->displayValue($total_actual_prospective_month[$kpi_item['key']],$kpi_item['type_data'],"",$kpi_item['sub_char'])  . '"';
		 $output .= "\n";

		 $output .= ',';
		for($week = 1;$week <= $count_week_report;$week++){
			 $output .= "," . '"' . $this->Shishimai->displayValue($ratio_prospective_with_target[$week][$kpi_item['key']],'number','','%') . '"';
		}
		 $output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prospective_target_month[$kpi_item['key']],'number','','%') . '"';
		 $output .= "\n";

		 $output .= ',';
		for($week = 1;$week <= $count_week_report;$week++){
			 $output .= "," . '"' . $this->Shishimai->displayValue($ratio_prospective_with_prev_week[$week][$kpi_item['key']],'number','','%') . '"';
		}
		 $output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prospective_prevMonth_month[$kpi_item['key']],'number','','%') . '"';
		 $output .= "\n";

		 $output .= ',';
		for($week = 1;$week <= $count_week_report;$week++){
			 $output .= "," . '"' . $this->Shishimai->displayValue($ratio_prospective_with_prev_yearW[$week][$kpi_item['key']],'number','','%') . '"';
		}
		 $output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prospective_prevYearM_month[$kpi_item['key']],'number','','%') . '"';
		 $output .= "\n";
		*/


		//End one kpi
		$kpi_stt++;
		}
		
	}
}
else
{
	foreach($kpis_list as $kpi_item){
		if(in_array($kpi_item['key'],$report_kpi_checked)){
			//One kpi
			//row1
			$output .= $kpi_stt.". ".$kpi_item['title'] . "," . "目標値";

			$w=1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
				$output .= "," . '"' . $this->Shishimai->displayValueExport($target_week[$w][$kpi_item['key']], $kpi_item['type_data'], "", $kpi_item['sub_char']) .'"' ;
				$w++;
			}
			$output .=  "," . '"' . $this->Shishimai->displayValueExport($total_target_month[$kpi_item['key']],$kpi_item['type_data'], "" ,$kpi_item['sub_char']) . '"';
			$output .= "\n";

			//row2
			$output .= "," . "実績値";
			$w = 1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
			 	$output .=  "," . '"' . $this->Shishimai->displayValueExport($actual_value[$w][$kpi_item['key']],$kpi_item['type_data'],"",$kpi_item['sub_char']) . '"';
			 	$w++;
			}
			$output .= "," . '"' . $this->Shishimai->displayValueExport($total_actual_month[$kpi_item['key']],$kpi_item['type_data'],"",$kpi_item['sub_char']) . '"';
			$output .= "\n";

			$output .= "," . "目標比";
			$w = 1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
			 	$output .= ',"' . $this->Shishimai->displayValue($ratio_with_target[$w][$kpi_item['key']],'number','','%') . '"' ;
			 	$w++;
			}
			$output .= ',"' . $this->Shishimai->displayValue($total_ratio_target_month[$kpi_item['key']],'number','','%') . '"';
			$output .= "\n";

			$output .= "," . "前週比";
			$w = 1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
			 	$output .=  "," . '"' . $this->Shishimai->displayValue($ratio_with_prev_week[$w][$kpi_item['key']],'number','','%') . '"';
			 	$w++;
			}
			$output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prevMonth_month[$kpi_item['key']],'number','','%') . '"';
			$output .= "\n";

			$output .= "," . "前年比";
			$w = 1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
			 	$output .=  "," . '"' . $this->Shishimai->displayValue($ratio_with_prev_yearW[$w][$kpi_item['key']],'number','','%') . '"';
			 	$w++;
			}
			$output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prevYearM_month[$kpi_item['key']],'number','','%') . '"';
			$output .= "\n";

			//row3
			/*
			$output .= ",実績値 到着見込";
			$w = 1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
				$output .= "," . '"' . $this->Shishimai->displayValue($actual_prospective_value[$w][$kpi_item['key']],$kpi_item['type_data'],"",$kpi_item['sub_char']) . '"';
				$w++;
			}
			$output .= "," . '"' . $this->Shishimai->displayValue($total_actual_prospective_month[$kpi_item['key']],$kpi_item['type_data'],"",$kpi_item['sub_char'])  . '"';
			$output .= "\n";

			$output .= ',';
			$w = 1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
				$output .= "," . '"' . $this->Shishimai->displayValue($ratio_prospective_with_target[$w][$kpi_item['key']],'number','','%') . '"';
				$w++;
			}
			$output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prospective_target_month[$kpi_item['key']],'number','','%') . '"';
			$output .= "\n";

			$output .= ',';
			$w = 1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
				$output .= "," . '"' . $this->Shishimai->displayValue($ratio_prospective_with_prev_week[$w][$kpi_item['key']],'number','','%') . '"';
				$w++;
			}
			$output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prospective_prevMonth_month[$kpi_item['key']],'number','','%') . '"';
			$output .= "\n";

			$output .= ',';
			$w = 1;
			foreach($range_data as $mInRange){
				$month = $mInRange['m'];
				$output .= "," . '"' . $this->Shishimai->displayValue($ratio_prospective_with_prev_yearW[$w][$kpi_item['key']],'number','','%') . '"';
				$w++;
			}
			$output .= "," . '"' . $this->Shishimai->displayValue($total_ratio_prospective_prevYearM_month[$kpi_item['key']],'number','','%') . '"';
			$output .= "\n";
			*/


			//End one kpi
			$kpi_stt++;
		}
		
	}
}


echo $output;

?>



