<?php
include_once(__DIR__."/../../Vendor/Google/GA.php");
App::uses('Component', 'Controller');

class SsmGAComponent extends Component {

	public $site_id 			= 0;
	public $ga_view_id 			= 0;
	public $report_type;	//List month or list week of month (month|week)
    public $temp_var = array();

	public $kpi = array(
        'transactionRevenue',
        'pageviews',
        'pageviewsPerSession',
        'sessions',
        'avgSessionDuration',
        'uniqueUsers',
        'transactions',
        'transactionsPerSession',
        'revenuePerTransaction',

        'transactions_1',
        'transactionsPerSession_1',
        'revenuePerTransaction_1',

        'bounceRate',
        'topBounceRate',
        'percentNewSessions'
    );
    //list kpi will using to display report
	public $year_report;	//Year report (input)
	public $range_report;	//Range report (input)
	public $month_report;	//Month report (input)

	public $query_year; //Real year use for query
	public $query_month;//Real month use for query

	public $target 				= array();
	public $total_target 		= array();
    public $target_defined      = array();
    public $target_defined_by_col = array();

    public $dataChange          = array();
	public $actual_change 		= array();
	public $actual 				= array();//Actual GA + actual change
	public $total_actual 		= array();

	//Acutal prev
	public $actual_prev 				= array();
	public $total_actual_prev 			= array();
    public $actual_prev_7day            = array();
	//Actual prev
	public $actual_prevY 				= array();
	public $total_actual_prevY 			= array();

	//Prospective
	public $prospective 		= array();
	public $total_prospective 	= array();

	//Prospective Prev
	public $prospective_prev 		= array();
	public $total_prospective_prev 	= array();

	//Prospective prevY
	public $prospective_prevY 			= array();
	public $total_prospective_prevY 	= array();

	//Phần tram của từng cột riêng lẻ
	public $ratio_actual_target 			= array();
	public $ratio_actual_prev 				= array();
	public $ratio_actual_prevY 				= array();
	public $ratio_prospective_target 		= array();
	public $ratio_prospective_prev 			= array();
	public $ratio_prospective_prevY 		= array();

	public $total_ratio_actual_target   	= array();
	public $total_ratio_actual_prev 		= array();
	public $total_ratio_actual_prevY 		= array();
	public $total_ratio_prospective_target  = array();
	public $total_ratio_prospective_prev 	= array();
	public $total_ratio_prospective_prevY 	= array();

	//Column total display
	public $col_target 			= array();
	public $col_actual 			= array();
	public $col_prev 			= array();
	public $col_prevY 			= array();
	public $col_prospective 	= array();

	//Column total display (ratio)
	public $col_ratio_actual_target 		= array();
	public $col_ratio_actual_prev 			= array();
	public $col_ratio_actual_prevY 			= array();

	public $col_ratio_prospective_target 	= array();
	public $col_ratio_prospective_prev 		= array();
	public $col_ratio_prospective_prevY 	= array();
    public $col_icon_status = array();


    public $in_view = "kpi_list";

	public function initCom($site_id,$ga_view_id){
		$this->site_id 		= $site_id;
		$this->ga_view_id 	= $ga_view_id;
		$this->ga 			= new GA($this->ga_view_id);

        try {
            $result = $this->ga->test();
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $msg_obj = json_decode($msg);
            if(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have any Google Analytics account."){
                return "ERROR_GA_PERMISSION";
            }elseif(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have sufficient permissions for this profile."){
                return "ERROR_GA_KEY";
            }else{
                return "ERROR_GA_UNKNOW";
            }
        }

        $prev_date = date('Y-m-d', strtotime(' -1 day'));
        $ex = explode('-',$prev_date);
        $this->crr_year     = intval($ex[0]);
        $this->crr_month    = intval($ex[1]);
        $this->crr_day      = intval($ex[2]);

        /*$this->crr_year     = intval(date('Y'));
        $this->crr_month    = intval(date('m'));
		$this->crr_day		= intval(date('d'));*/

		$this->SsmKpi 		= ClassRegistry::init('SsmKpi');
		$this->SsmKpiGaWeek = ClassRegistry::init('SsmKpiGaWeek');
		$this->SsmKpiGaMonth 	= ClassRegistry::init('SsmKpiGaMonth');
		$this->SsmKpiChange = ClassRegistry::init('SsmKpiChange');
		$this->SsmKpiNote 	= ClassRegistry::init('SsmKpiNote');
	}

    //Clear all result in class attribute
    function resetResult(){
        $this->target              = array();
        $this->total_target        = array();
        $this->target_defined      = array();
        $this->target_defined_by_col = array();

        $this->dataChange          = array();
        $this->actual_change       = array();
        $this->actual              = array();//Actual GA + actual change
        $this->total_actual        = array();

        //Acutal prev
        $this->actual_prev                 = array();
        $this->total_actual_prev           = array();

        //Actual prev
        $this->actual_prevY                = array();
        $this->total_actual_prevY          = array();

        //Prospective
        $this->prospective         = array();
        $this->total_prospective   = array();

        //Prospective Prev
        $this->prospective_prev        = array();
        $this->total_prospective_prev  = array();

        //Prospective prevY
        $this->prospective_prevY           = array();
        $this->total_prospective_prevY     = array();

        //Phần tram của từng cột riêng lẻ
        $this->ratio_actual_target             = array();
        $this->ratio_actual_prev               = array();
        $this->ratio_actual_prevY              = array();
        $this->ratio_prospective_target        = array();
        $this->ratio_prospective_prev          = array();
        $this->ratio_prospective_prevY         = array();

        $this->total_ratio_actual_target       = array();
        $this->total_ratio_actual_prev         = array();
        $this->total_ratio_actual_prevY        = array();
        $this->total_ratio_prospective_target  = array();
        $this->total_ratio_prospective_prev    = array();
        $this->total_ratio_prospective_prevY   = array();

        //Column total display
        $this->col_target          = array();
        $this->col_actual          = array();
        $this->col_prev            = array();
        $this->col_prevY           = array();
        $this->col_prospective     = array();

        //Column total display (ratio)
        $this->col_ratio_actual_target         = array();
        $this->col_ratio_actual_prev           = array();
        $this->col_ratio_actual_prevY          = array();

        $this->col_ratio_prospective_target    = array();
        $this->col_ratio_prospective_prev      = array();
        $this->col_ratio_prospective_prevY     = array();
        $this->col_icon_status = array();
    }

	//Get array data calculated
	public function getReport($year,$month,$range,$type){
		//Set type
		if(!$this->setType($type)){
			echo "Type invalid";exit;
		}

		//Set range
		$this->setRangeData($range,$year);

		//Set month and year query
		if(!$this->checkMonthInRange($month)){
			echo "Month invalid";exit;
		}

		if($type == 'month'){
			//Get list month report of range
			$this->getListMonthReport();
		}else{
			//Get list week report of month
			$this->getListWeekReport();
		}
	}

	function getListMonthReport(){

		$begin_date  = date($this->query_year.'-'.($this->query_month > 9 ? $this->query_month : '0'.intval($this->query_month)).'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));

        $end_date_ex = explode('-',$end_date);
        $end_day_number = $end_date_ex[2];

        $week_title = $this->getWeekTitle($this->query_year,$this->query_month,$end_day_number);
        $count_week = count($week_title);

        $crr_month_index = 1;
        //Target And Total target
        $w = 1;
        foreach($this->range_data as $m_in_range){
        	$m = $m_in_range['m'];
        	$y = $m_in_range['y'];

            if($m == $this->crr_month){
                $crr_month_index = $w;
            }

        	$dataSsmKpi             = $this->SsmKpi->getDataTargetAllWeekInMonth($y,$m,$this->site_id);
			$this->target[$w]     	= $dataSsmKpi['kpi_month'];

            $this->target_defined_by_col[$w] = $this->increaseTarget($this->target[$w]);

        	if($i == 1){
        		$this->total_target[$w] = $this->target[$w];
        	}else{
        		$this->total_target[$w] = $this->gaTotal($this->total_target[($w-1)],$this->target[$w]);
        	}

        	//Col target
        	$this->col_target = $this->total_target[$w];
        	$w++;
        }

        //Actual And Total actual

        $w = 1;
        foreach($this->range_data as $m_in_range){
        	$m = $m_in_range['m'];
        	$y = $m_in_range['y'];

        	//Get data GA actual
            $dataSsmKpiGaMonth = $this->SsmKpiGaMonth->getGAMonthData($this->ga,$this->site_id,$y,$m);
            $dataKpiMonth = $dataSsmKpiGaMonth['kpis'];

            //Get data GA change
            $dataSsmKpiChange = $this->SsmKpiChange->getDataChangeMonth($this->site_id,$y,$m);
            $dataKpiChange = $dataSsmKpiChange['change_data_in_month'];

            $this->actual[$w] =  $this->reCalculateField($this->gaTotal($dataKpiMonth,$dataKpiChange));

            if($w == 1){
        		$this->total_actual[$w] = $this->actual[$w];
        	}else{
        		$this->total_actual[$w] = $this->reCalculateField($this->gaTotal($this->total_actual[($w-1)],$this->actual[$w]));
        	}

        	$this->col_actual =  $this->gaTotal($this->col_actual,$this->actual[$w]);

        	$w++;
        }

        //Actual Prev And Total actual Prev
        $w = 1;
        foreach($this->range_data as $m_in_range){
        	$m = $m_in_range['m'];
        	$y = $m_in_range['y'];

        	if($w == 1){
        		if($m > 1){
	                $prev_month = $m - 1;
	                $prev_year  = $y;
	            }else{
	                $prev_month = 12;
	                $prev_year  = $y - 1;
	            }
	            $prev_ga 				= $this->SsmKpiGaMonth->getGAMonthData($this->ga,$this->site_id,$prev_year,$prev_month);
                $prev_change 			= $this->SsmKpiChange->getDataChangeMonth($this->site_id,$prev_year,$prev_month);
                $this->actual_prev[$w]	= $this->reCalculateField($this->gaTotal($prev_ga['kpis'],$prev_change['change_data_in_month']));
        	}else{
        		$this->actual_prev[$w] = $this->actual[($w-1)];
        	}

        	if($w == 1){
        		$this->total_actual_prev[$w] = $this->actual_prev[$w];
        	}else{
        		$this->total_actual_prev[$w] = $this->reCalculateField($this->gaTotal($this->actual_prev[($w-1)],$this->actual_prev[$w]));
        	}
        	$this->col_prev = $this->total_actual_prev[$w];
        	$w++;
        }

        //Actual PrevY And Total actual Prev
        $w = 1;
        foreach($this->range_data as $m_in_range){
        	$m = $m_in_range['m'];
        	$y = $m_in_range['y'];

        	$prev_ga 				= $this->SsmKpiGaMonth->getGAMonthData($this->ga,$this->site_id,($y - 1),$m);
            $prev_change 			= $this->SsmKpiChange->getDataChangeMonth($this->site_id,($y - 1),$m);
            $this->actual_prevY[$w]	= $this->reCalculateField($this->gaTotal($prev_ga['kpis'],$prev_change['change_data_in_month']));

            if($w == 1){
        		$this->total_actual_prevY[$w] = $this->actual_prevY[$w];
        	}else{
        		$this->total_actual_prevY[$w] = $this->reCalculateField($this->gaTotal($this->actual_prevY[($w-1)],$this->actual_prevY[$w]));
        	}
        	$this->col_prevY = $this->total_actual_prevY[$w];
        	$w++;
        }

        //Prospective And  Total prospective
        if($this->crr_year == $this->query_year &&  in_array($this->crr_month,$this->getListMonth())){
        	//Current week
        	$w = 1;
        	foreach($this->range_data as $m_in_range){
	        	$m = $m_in_range['m'];
	        	$y = $m_in_range['y'];

        		//prospective
        		if($y < $this->crr_year){
                    $this->prospective[$w] = $this->actual[$w];
                }elseif($y == $this->crr_year){
                    if($m == $this->crr_month){
                        $this->prospective[$w] = $this->reCalculateField($this->gaProspective($this->actual[$w],intval($this->crr_day),$end_day_number));
                    }elseif($m < $this->crr_month){
                        $this->prospective[$w] = $this->actual[$w];
                    }else{
                        $this->prospective[$w] = array();
                    }
                }else{
                    $this->prospective[$w]	= array();
                }

        		//Total prospective
        		if($w == 1){
	        		$this->total_prospective[$w] = $this->prospective[$w];
	        	}else{
	        		$this->total_prospective[$w] = $this->reCalculateField($this->gaTotal($this->total_prospective[$w-1],$this->prospective[$w]));
	        	}
	        	$this->col_prospective = $this->total_prospective[$w];
	        	$w++;
        	}
        }else{
        	$this->prospective 			= array();
        	$this->total_prospective 	= array();
        }

        //Update value before get ratio
        if($this->crr_year == $this->query_year && in_array($this->crr_month,$this->getListMonth())){
            $w = 1;
            foreach($this->range_data as $m_in_range){
                $m = $m_in_range['m'];
                $this->total_actual[$w]         = $this->getAVGofPercentNumber($this->total_actual[$w],$w);
                $this->total_target[$w]         = $this->getAVGofTarget($this->total_target[$w],$this->target_defined_by_col[$w]);
                $this->total_actual_prev[$w]    = $this->getAVGofPercentNumber($this->total_actual_prev[$w],$w);
                $this->total_actual_prevY[$w]   = $this->getAVGofPercentNumber($this->total_actual_prevY[$w],$w);
                $this->total_prospective[$w]    = $this->getAVGofPercentNumber($this->total_prospective[$w],$w);
                $w++;
            }

            $this->col_actual   = $this->reCalculateField($this->getAVGofPercentNumber($this->col_actual,$crr_month_index));
            $this->col_target   = $this->getAVGofTarget($this->col_target,$this->target_defined);
            $this->col_prev     = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prev,$crr_month_index));
            $this->col_prevY    = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prevY,$crr_month_index));
            $this->col_prospective = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prospective,$crr_month_index));
        }else{
            $this->col_actual   = $this->reCalculateField($this->getAVGofPercentNumber($this->col_actual,6));
            $this->col_target   = $this->getAVGofTarget($this->col_target,$this->target_defined);
            $this->col_prev     = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prev,6));
            $this->col_prevY    = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prevY,6));
            $this->col_prospective = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prospective,6));
        }

        //Ratio ==========================

        //Ratio (prospective/prevY)
        $w = 1;
        foreach($this->range_data as $m_in_range){
        	//Ratio (actual/target)
        	$this->ratio_actual_target[$w] 			= $this->gaRatio($this->actual[$w],$this->target[$w],1);
        	$this->total_ratio_actual_target[$w] 	= $this->gaRatio($this->total_actual[$w],$this->total_target[$w],1);

        	//Ratio (actual/prev)
        	$this->ratio_actual_prev[$w] 			= $this->gaRatio($this->actual[$w],$this->actual_prev[$w],2);
        	$this->total_ratio_actual_prev[$w] 		= $this->gaRatio($this->total_actual[$w],$this->total_actual_prev[$w],2);

        	//Ratio (actual/prevY)
        	$this->ratio_actual_prevY[$w]			= $this->gaRatio($this->actual[$w],$this->actual_prevY[$w],3);
        	$this->total_ratio_actual_prevY[$w] 	= $this->gaRatio($this->total_actual[$w],$this->total_actual_prevY[$w],3);

        	//Ratio (prospective/target)
        	$this->ratio_prospective_target[$w] 	= $this->gaRatio($this->prospective[$w],$this->target[$w],1);
        	$this->total_ratio_prospective_target[$w] 	= $this->gaRatio($this->total_prospective[$w],$this->total_target[$w],1);

        	//Ratio (prospective/prev)
        	$this->ratio_prospective_prev[$w] 		= $this->gaRatio($this->prospective[$w],$this->actual_prev[$w],2);
        	$this->total_ratio_prospective_prev[$w] = $this->gaRatio($this->total_prospective[$w],$this->total_actual_prev[$w],2);

        	//Ratio (prospective/prev)
        	$this->ratio_prospective_prevY[$w] 		= $this->gaRatio($this->prospective[$w],$this->actual_prevY[$w],3);
        	$this->total_ratio_prospective_prevY[$w]= $this->gaRatio($this->total_prospective[$w],$this->total_actual_prevY[$w],3);
        	$w++;
        }
        //End Ratio ======================

        $this->week_title = $week_title;
        $this->count_week = $count_week;

        //Ratio column total
        $this->col_ratio_actual_target 	= $this->gaRatio($this->col_actual,$this->col_target,1);
        $this->col_ratio_actual_prev 	= $this->gaRatio($this->col_actual,$this->col_prev,2);
        $this->col_ratio_actual_prevY 	= $this->gaRatio($this->col_actual,$this->col_prevY,3);

        $this->col_ratio_prospective_target = $this->gaRatio($this->col_prospective,$this->col_target,1);
        $this->col_ratio_prospective_prev = $this->gaRatio($this->col_prospective,$this->col_prev,2);
        $this->col_ratio_prospective_prevY = $this->gaRatio($this->col_prospective,$this->col_prevY,3);

        //Hide data in view
        if($this->crr_year == $this->query_year && in_array($this->crr_month,$this->getListMonth())){
            $w = 1;
            for($w = ($crr_month_index + 1); $w <= 6;$w++){
                unset($this->total_actual[$w]);
                unset($this->total_ratio_actual_target[$w]);
                unset($this->total_ratio_actual_prev[$w]);
                unset($this->total_ratio_actual_prevY[$w]);

                unset($this->total_prospective[$w]);
                unset($this->total_ratio_prospective_target[$w]);
                unset($this->total_ratio_prospective_prev[$w]);
                unset($this->total_ratio_prospective_prevY[$w]);

                /*$this->total_actual[$w]         = $this->getAVGofPercentNumber($this->total_actual[$w],$w);
                $this->total_target[$w]         = $this->getAVGofPercentNumber($this->total_target[$w],$w);
                $this->total_actual_prev[$w]    = $this->getAVGofPercentNumber($this->total_actual_prev[$w],$w);
                $this->total_actual_prevY[$w]   = $this->getAVGofPercentNumber($this->total_actual_prevY[$w],$w);
                $this->total_prospective[$w]    = $this->getAVGofPercentNumber($this->total_prospective[$w],$w);*/
            }
        }

	}

	function getListWeekReport(){
		$begin_date  = date($this->query_year.'-'.($this->query_month > 9 ? $this->query_month : '0'.intval($this->query_month)).'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));

        $this->temp['begin_date']   = $begin_date;
        $this->temp['end_date']     = $end_date;


        $end_date_ex    = explode('-',$end_date);
        $end_day_number = $end_date_ex[2];
        $this->temp_var['end_day_number']     = $end_day_number;

        $week_title = $this->getWeekTitle($this->query_year,$this->query_month,$end_day_number);

        $this->temp_var['week_title'] = $week_title;

        $count_week = count($week_title);

        $dataSsmKpi = $this->SsmKpi->getDataTargetAllWeekInMonth($this->query_year,$this->query_month,$this->site_id);


        if($this->crr_day < 8){
            $current_week = 1;
            $total_day_in_crr_week = 7;
        }elseif($this->crr_day < 15){
            $current_week = 2;
            $total_day_in_crr_week = 7;
        }elseif($this->crr_day < 22){
            $current_week = 3;
            $total_day_in_crr_week = 7;
        }elseif($this->crr_day < 29){
            $current_week = 4;
            if($end_day_number < 29){
                $total_day_in_crr_week = $end_day_number - 21;
            }else{
                $total_day_in_crr_week = 7;
            }
        }else{
            $current_week = 5;
            $total_day_in_crr_week = $end_day_number - 28;
        }

        //Target And Total target
        $this->col_target = $dataSsmKpi['kpi_month'];
        $this->target 		= $dataSsmKpi['kpi_week'];

        for($w = 1; $w <= $count_week; $w++){
        	if($w == 1){
        		$this->total_target[$w] = $this->target[$w];
        	}else{
        		$this->total_target[$w] = $this->gaTotal($this->total_target[($w-1)],$this->target[$w]);
        	}
            $this->target_defined_by_col[$w] = $this->increaseTarget($this->target[$w]);
        	//Col target
        	$this->col_target = $this->total_target[$w];
        }

        //Actual And Total actual
        $dataSsmKpiChange       = $this->SsmKpiChange->getDataChangeMonth($this->site_id,$this->query_year,$this->query_month);

        $change_data_in_month   = $dataSsmKpiChange['change_data_in_month'];
        $this->actual_change    = $dataSsmKpiChange['change_data_in_week'];
        for($w = 1; $w <= $count_week; $w++){
        	if(!isset($this->actual_change[$w])){
                $this->actual_change[$w] = $this->SsmKpiChange->default_zero;
            }

            $ga_week_db[$w]     = $this->SsmKpiGaWeek->getGAWeekData($this->ga,$this->site_id,$this->query_year,$this->query_month,$w);
            $total = $this->gaTotal($ga_week_db[$w],$this->actual_change[$w]);
            $this->actual[$w]   = $this->reCalculateField($total);

            //Fix for last week of month report
            if($w == $count_week){
                $last_week_info = $this->SsmKpiGaWeek->getWeekInfo($this->query_year,$this->query_month,'last');
                $data_actual_last_week_7_day = $this->reCalculateField($this->gaPredict($this->actual[$week],$last_week_info['total_day_in_week'],7));
            }

            if($w == 1){
        		$this->total_actual[$w] = $this->actual[$w];
        	}else{
        		$this->total_actual[$w] = $this->reCalculateField($this->gaTotal($this->total_actual[($w-1)],$this->actual[$w]));
        	}

        	$this->col_actual =  $this->reCalculateField($this->gaTotal($this->col_actual,$this->actual[$w]));
        }

        $crrM = $this->SsmKpiGaMonth->getGAMonthData($this->ga,$this->site_id,$this->query_year,$this->query_month);
        $this->col_actual = $this->reCalculateField($this->gaTotal($crrM['kpis'],$change_data_in_month));

        //Actual Prev And Total actual Prev
        for($w = 1; $w <= $count_week; $w++){
        	if($w == 1){
        		//Get Prev Week Ga Data
                if($this->query_month > 1){
                    $y_prev = $this->query_year;
                    $m_prev  = $this->query_month - 1;
                }else{
                    $y_prev = $this->query_year - 1;
                    $m_prev  = 12;
                }

                $prev_ga  		= $this->SsmKpiGaWeek->getGAWeekData($this->ga,$this->site_id,$y_prev,$m_prev,'last');

                $prev_change    = $this->SsmKpiChange->getDataWeekChange($this->site_id,$y_prev,$m_prev,'last');
                $prev_week_info = $this->SsmKpiGaWeek->getWeekInfo($y_prev,$m_prev,'last');
                $this->actual_prev[$w] = $this->reCalculateField($this->gaTotal($prev_ga,$prev_change));
                $this->actual_prev_7day[$w] = $this->reCalculateField($this->gaPredict($this->gaTotal($prev_ga,$prev_change),$prev_week_info['total_day_in_week'],7));
        	}else{
        		$this->actual_prev[$w] = $this->actual[($w-1)];
                $this->actual_prev_7day[$w] = $this->actual[($w-1)];
        	}

        	if($w == 1){
        		$this->total_actual_prev[$w] = $this->actual_prev[$w];
        	}else{
        		$this->total_actual_prev[$w] = $this->reCalculateField($this->gaTotal($this->actual_prev[($w-1)],$this->actual_prev[$w]));
        	}
        	$this->col_prev = $this->total_actual_prev[$w];
        }

        //Prev month data
        //Get data GA actual ===================
        if($this->query_month > 1){
            $py = $this->query_year;
            $pm = $this->query_month-1;
        }else{
            $py = $this->query_year-1;
            $pm = 12;
        }

        $dataSsmKpiGaMonth = $this->SsmKpiGaMonth->getGAMonthData($this->ga,$this->site_id,$py,$pm);
        $dataKpiMonth = $dataSsmKpiGaMonth['kpis'];
        //Get data GA change

        $dataSsmKpiChange = $this->SsmKpiChange->getDataChangeMonth($this->site_id,$py,$pm);
        $dataKpiChange = $dataSsmKpiChange['change_data_in_month'];

        $this->col_prev =  $this->reCalculateField($this->gaTotal($dataKpiMonth,$dataKpiChange));
        //End prev month ========================

        //Actual PrevY And Total actual Prev
        for($w = 1; $w <= $count_week; $w++){
        	$ga_prevY = $this->SsmKpiGaWeek->getGAWeekData($this->ga,$this->site_id,($this->query_year-1),$this->query_month,($w == $count_week ? 'last':$w));
            $prevY_change    = $this->SsmKpiChange->getDataWeekChange($this->site_id,($this->query_year-1),$this->query_month,($w == $count_week ? 'last':$w ));
            $this->actual_prevY[$w] = $this->reCalculateField($this->gaTotal($ga_prevY,$prevY_change));
            if($w == 1){
        		$this->total_actual_prevY[$w] = $this->actual_prevY[$w];
        	}else{
        		$this->total_actual_prevY[$w] = $this->reCalculateField($this->gaTotal($this->total_actual_prevY[($w-1)],$this->actual_prevY[$w]));
        	}
        	$this->col_prevY = $this->total_actual_prevY[$w];
        }

        //Prospective And  Total prospective
        if($this->crr_year == $this->query_year && $this->crr_month == $this->query_month){
        	//Current week

            $lst_w_have_data = 1;
        	for($w = 1; $w <= $count_week; $w++){

        		//prospective
        		if($w < $current_week){
        			$this->prospective[$w] = $this->actual[$w];
        		}elseif($w == $current_week){
        			$this->prospective[$w] = $this->reCalculateField($this->gaProspective($this->actual[$w],($this->crr_day - $week_title[$w]['week_start_day']) + 1,$total_day_in_crr_week));
        		}

        		//Total prospective
        		if($w == 1 || $w < $current_week){
	        		$this->total_prospective[$w] = $this->total_actual[$w];
                    $lst_w_have_data = $w;
	        	}elseif($w == $current_week){
	        		//$this->total_prospective[$w] = $this->gaPredict($this->total_actual[$w],$this->crr_day + 1,$week_title[$w]['week_end_day']);
                    $this->total_prospective[$w] = $this->reCalculateField($this->gaTotal($this->total_prospective[$w-1],$this->prospective[$w]));
                    $lst_w_have_data = $w;
	        	}elseif($w > $current_week){
	        		//$pros = $this->gaProspective($this->total_prospective[($w-1)],$week_title[($w-1)]['week_end_day'],($week_title[$w]['week_end_day'] - $week_title[($w-1)]['week_end_day']));
	        		//$this->total_prospective[$w] = $this->gaTotal($this->total_prospective[$w-1],$pros);
                    $this->total_prospective[$w] = $this->total_prospective[$lst_w_have_data];
	        	}

	        	$this->col_prospective = $this->total_prospective[$w];
        	}

        }else{
        	$this->prospective 			= array();
        	$this->total_prospective 	= array();
        }

        //Update value before get ratio
        for($w = 1; $w <= $count_week; $w++){
            $this->total_actual[$w]         = $this->getAVGofPercentNumber($this->total_actual[$w],$w);
            $this->total_target[$w]         = $this->getAVGofTarget($this->total_target[$w],$this->target_defined_by_col[$w]);
            $this->total_actual_prev[$w]    = $this->getAVGofPercentNumber($this->total_actual_prev[$w],$w);
            $this->total_actual_prevY[$w]   = $this->getAVGofPercentNumber($this->total_actual_prevY[$w],$w);
            $this->total_prospective[$w]    = $this->getAVGofPercentNumber($this->total_prospective[$w],$w);
        }

        if($this->crr_year == $this->query_year && $this->crr_month == $this->query_month){

            //$this->col_actual   = $this->reCalculateField($this->getAVGofPercentNumber($this->col_actual,$current_week));
            $this->col_target   = $this->getAVGofTarget($this->col_target,$this->target_defined);
            $this->col_prevY    = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prevY,$current_week));
            $this->col_prospective = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prospective,$current_week));
        }else{

            //$this->col_actual   = $this->reCalculateField($this->getAVGofPercentNumber($this->col_actual,$count_week));
            $this->col_target   = $this->getAVGofTarget($this->col_target,$this->target_defined);
            $this->col_prevY    = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prevY,$count_week));
            $this->col_prospective = $this->reCalculateField($this->getAVGofPercentNumber($this->col_prospective,$count_week));
        }

        //End update value before get ratio
        //Ratio ==========================
        //Ratio (prospective/prevY)
        for($w = 1; $w <= $count_week; $w++){
        	//Ratio (actual/target)
        	$this->ratio_actual_target[$w] 			= $this->gaRatio($this->actual[$w],$this->target[$w],1);
        	$this->total_ratio_actual_target[$w] 	= $this->gaRatio($this->total_actual[$w],$this->total_target[$w],1);

        	//Ratio (actual/prev)
            if($w == $count_week){
                $actual_7_day = $this->reCalculateField($this->gaPredict($this->actual[$w],(intval($week_title[$count_week]['week_end_day']) - intval($week_title[$count_week]['week_start_day']) + 1),7));
            }else{
                $actual_7_day = $this->actual[$w];
            }

        	$this->ratio_actual_prev[$w] 			= $this->gaRatio($actual_7_day,$this->actual_prev_7day[$w],2);
        	$this->total_ratio_actual_prev[$w] 		= $this->gaRatio($this->total_actual[$w],$this->total_actual_prev[$w],2);

        	//Ratio (actual/prevY)
        	$this->ratio_actual_prevY[$w]			= $this->gaRatio($this->actual[$w],$this->actual_prevY[$w],3);
        	$this->total_ratio_actual_prevY[$w] 	= $this->gaRatio($this->total_actual[$w],$this->total_actual_prevY[$w],3);

        	//Ratio (prospective/target)
        	$this->ratio_prospective_target[$w] 	= $this->gaRatio($this->prospective[$w],$this->target[$w],1);
        	$this->total_ratio_prospective_target[$w] 	= $this->gaRatio($this->total_prospective[$w],$this->total_target[$w],1);


        	//Ratio (prospective/prev)
        	$this->ratio_prospective_prev[$w] 		= $this->gaRatio($this->prospective[$w],$this->actual_prev_7day[$w],2);
        	$this->total_ratio_prospective_prev[$w] = $this->gaRatio($this->total_prospective[$w],$this->total_actual_prev[$w],2);

        	//Ratio (prospective/prev)
        	$this->ratio_prospective_prevY[$w] 		= $this->gaRatio($this->prospective[$w],$this->actual_prevY[$w],3);
        	$this->total_ratio_prospective_prevY[$w]= $this->gaRatio($this->total_prospective[$w],$this->total_actual_prevY[$w],3);
        }
        //End Ratio ======================

        $this->week_title = $week_title;
        $this->count_week = $count_week;

        //Ratio column total
        $this->col_ratio_actual_target 	= $this->gaRatio($this->col_actual,$this->col_target,1);
        $this->col_ratio_actual_prev 	= $this->gaRatio($this->col_actual,$this->col_prev,2);
        $this->col_ratio_actual_prevY 	= $this->gaRatio($this->col_actual,$this->col_prevY,3);

        $this->col_ratio_prospective_target = $this->gaRatio($this->col_prospective,$this->col_target,1);
        $this->col_ratio_prospective_prev = $this->gaRatio($this->col_prospective,$this->col_prev,2);
        $this->col_ratio_prospective_prevY = $this->gaRatio($this->col_prospective,$this->col_prevY,3);

        //Unset prospective 
        if($this->crr_year == $this->query_year && $this->crr_month == $this->query_month){
            for($w = ($current_week+1); $w <= $count_week; $w++){
                unset($this->prospective[$w]);
                unset($this->ratio_prospective_prev[$w]);
                unset($this->ratio_prospective_prevY[$w]);

                unset($this->total_prospective[$w]);
                unset($this->total_ratio_prospective_prev[$w]);
                unset($this->total_ratio_prospective_prevY[$w]);
            }
        }

        $this->col_icon_status = $this->iconStatus($this->col_actual,$this->col_target);
	}

    //Function for report controller from monday to sunday (pending)
    function getListWeekReportNoChange(){
        $begin_date  = date($this->query_year.'-'.($this->query_month > 9 ? $this->query_month : '0'.intval($this->query_month)).'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));

        $this->temp['begin_date']   = $begin_date;
        $this->temp['end_date']     = $end_date;


        $end_date_ex    = explode('-',$end_date);
        $end_day_number = $end_date_ex[2];
        $this->temp_var['end_day_number']     = $end_day_number;

        $week_title = $this->getWeekTitle($this->query_year,$this->query_month,$end_day_number);

        $count_week = count($week_title);

        $dataSsmKpi = $this->SsmKpi->getDataTargetAllWeekInMonth($this->query_year,$this->query_month,$this->site_id);

        if($this->crr_day < 8){
            $current_week = 1;
            $total_day_in_crr_week = 7;
        }elseif($this->crr_day < 15){
            $current_week = 2;
            $total_day_in_crr_week = 7;
        }elseif($this->crr_day < 22){
            $current_week = 3;
            $total_day_in_crr_week = 7;
        }elseif($this->crr_day < 29){
            $current_week = 4;
            if($end_day_number < 29){
                $total_day_in_crr_week = $end_day_number - 21;
            }else{
                $total_day_in_crr_week = 7;
            }
        }else{
            $current_week = 5;
            $total_day_in_crr_week = $end_day_number - 28;
        }

        //Target And Total target
        $this->col_target = $dataSsmKpi['kpi_month'];
        $this->target       = $dataSsmKpi['kpi_week'];
        for($w = 1; $w <= $count_week; $w++){
            if($w == 1){
                $this->total_target[$w] = $this->target[$w];
            }else{
                $this->total_target[$w] = $this->gaTotal($this->total_target[($w-1)],$this->target[$w]);
            }
            $this->target_defined_by_col[$w] = $this->increaseTarget($this->target[$w]);
            //Col target
            $this->col_target = $this->total_target[$w];
        }

        //Actual And Total actual
        $dataSsmKpiChange       = $this->SsmKpiChange->getDataChangeMonth($this->site_id,$this->query_year,$this->query_month);
        $this->dataChange[$this->query_month] = $dataSsmKpiChange;


        $change_data_in_month   = $dataSsmKpiChange['change_data_in_month'];
        $this->actual_change    = $dataSsmKpiChange['change_data_in_week'];
        for($w = 1; $w <= $count_week; $w++){
            if(!isset($this->actual_change[$w])){
                $this->actual_change[$w] = $this->SsmKpiChange->default_zero;
            }

            $ga_week_db[$w]     = $this->SsmKpiGaWeek->getGAWeekData($this->ga,$this->site_id,$this->query_year,$this->query_month,$w);
            //$this->actual[$w]   = $this->gaTotal($ga_week_db[$w],$this->actual_change[$w]);
            $this->actual[$w]   = $ga_week_db[$w];

            //Fix for last week of month report
            if($w == $count_week){
                $last_week_info = $this->SsmKpiGaWeek->getWeekInfo($this->query_year,$this->query_month,'last');
                $data_actual_last_week_7_day = $this->gaPredict($this->actual[$week],$last_week_info['total_day_in_week'],7);
            }

            if($w == 1){
                $this->total_actual[$w] = $this->actual[$w];
            }else{
                $this->total_actual[$w] = $this->gaTotal($this->total_actual[($w-1)],$this->actual[$w]);
            }

            $this->col_actual =  $this->gaTotal($this->col_actual,$this->actual[$w]);
        }

        //Actual Prev And Total actual Prev
        for($w = 1; $w <= $count_week; $w++){
            if($w == 1){
                //Get Prev Week Ga Data
                if($this->query_month > 1){
                    $y_prev = $this->query_year;
                    $m_prev  = $this->query_month - 1;
                }else{
                    $y_prev = $this->query_year - 1;
                    $m_prev  = 12;
                }

                $prev_ga        = $this->SsmKpiGaWeek->getGAWeekData($this->ga,$this->site_id,$y_prev,$m_prev,'last');
                $prev_change    = $this->SsmKpiChange->getDataWeekChange($this->site_id,$y_prev,$m_prev,'last');
                $prev_week_info = $this->SsmKpiGaWeek->getWeekInfo($y_prev,$m_prev,'last');

                //$this->actual_prev[$w] = $this->gaTotal($prev_ga,$prev_change);
                $this->actual_prev[$w] = $prev_ga;
                $this->actual_prev_7day[$w] = $this->gaPredict($prev_ga,$last_week_info['total_day_in_week'],7);
            }else{
                $this->actual_prev[$w] = $this->actual[($w-1)];
                $this->actual_prev_7day[$w] = $this->actual[($w-1)];
            }

            if($w == 1){
                $this->total_actual_prev[$w] = $this->actual_prev[$w];
            }else{
                $this->total_actual_prev[$w] = $this->gaTotal($this->actual_prev[($w-1)],$this->actual_prev[$w]);
            }
            $this->col_prev = $this->total_actual_prev[$w];
        }

        //Prev month data
        //Get data GA actual ===================
        if($this->query_month > 1){
            $py = $this->query_year;
            $pm = $this->query_month-1;
        }else{
            $py = $this->query_year-1;
            $pm = 12;
        }
        $dataSsmKpiGaMonth = $this->SsmKpiGaMonth->getGAMonthData($this->ga,$this->site_id,$py,$pm);
        $dataKpiMonth = $dataSsmKpiGaMonth['kpis'];

        //Get data GA change
        $dataSsmKpiChange = $this->SsmKpiChange->getDataChangeMonth($this->site_id,$py,$pm);
        $dataKpiChange = $dataSsmKpiChange['change_data_in_month'];
        $this->dataChange[$pm] = $dataSsmKpiChange;

        //$this->col_prev =  $this->gaTotal($dataKpiMonth,$dataKpiChange);
        $this->col_prev =  $dataKpiMonth;
        //End prev month ========================

        //Actual PrevY And Total actual Prev
        for($w = 1; $w <= $count_week; $w++){
            $ga_prevY = $this->SsmKpiGaWeek->getGAWeekData($this->ga,$this->site_id,($this->query_year-1),$this->query_month,($w == $count_week ? 'last':$w));
            $prevY_change    = $this->SsmKpiChange->getDataWeekChange($this->site_id,($this->query_year-1),$this->query_month,($w == $count_week ? 'last':$w ));
            //$this->actual_prevY[$w] = $this->gaTotal($ga_prevY,$prevY_change);

            $this->actual_prevY[$w] = $ga_prevY;
            if($w == 1){
                $this->total_actual_prevY[$w] = $this->actual_prevY[$w];
            }else{
                $this->total_actual_prevY[$w] = $this->gaTotal($this->total_actual_prevY[($w-1)],$this->actual_prevY[$w]);
            }
            $this->col_prevY = $this->total_actual_prevY[$w];
        }

        //Prospective And  Total prospective
        if($this->crr_year == $this->query_year && $this->crr_month == $this->query_month){
            //Current week

            $lst_w_have_data = 1;
            for($w = 1; $w <= $count_week; $w++){

                //prospective
                if($w < $current_week){
                    $this->prospective[$w] = $this->actual[$w];
                }elseif($w == $current_week){
                    $this->prospective[$w] = $this->gaProspective($this->actual[$w],($this->crr_day - $week_title[$w]['week_start_day']) + 1,$total_day_in_crr_week);
                }

                //Total prospective
                if($w == 1 || $w < $current_week){
                    $this->total_prospective[$w] = $this->total_actual[$w];
                    $lst_w_have_data = $w;
                }elseif($w == $current_week){
                    //$this->total_prospective[$w] = $this->gaPredict($this->total_actual[$w],$this->crr_day + 1,$week_title[$w]['week_end_day']);
                    $this->total_prospective[$w] = $this->gaTotal($this->total_prospective[$w-1],$this->prospective[$w]);
                    $lst_w_have_data = $w;
                }elseif($w > $current_week){
                    //$pros = $this->gaProspective($this->total_prospective[($w-1)],$week_title[($w-1)]['week_end_day'],($week_title[$w]['week_end_day'] - $week_title[($w-1)]['week_end_day']));
                    //$this->total_prospective[$w] = $this->gaTotal($this->total_prospective[$w-1],$pros);
                    $this->total_prospective[$w] = $this->total_prospective[$lst_w_have_data];
                }

                $this->col_prospective = $this->total_prospective[$w];
            }

        }else{
            $this->prospective          = array();
            $this->total_prospective    = array();
        }

        //Update value before get ratio
        for($w = 1; $w <= $count_week; $w++){
            $this->total_actual[$w]         = $this->getAVGofPercentNumber($this->total_actual[$w],$w);
            $this->total_target[$w]         = $this->getAVGofTarget($this->total_target[$w],$this->target_defined_by_col[$w]);
            $this->total_actual_prev[$w]    = $this->getAVGofPercentNumber($this->total_actual_prev[$w],$w);
            $this->total_actual_prevY[$w]   = $this->getAVGofPercentNumber($this->total_actual_prevY[$w],$w);
            $this->total_prospective[$w]    = $this->getAVGofPercentNumber($this->total_prospective[$w],$w);
        }

        if($this->crr_year == $this->query_year && $this->crr_month == $this->query_month){

            $this->col_actual   = $this->getAVGofPercentNumber($this->col_actual,$current_week);
            $this->col_target   = $this->getAVGofTarget($this->col_target,$this->target_defined);
            $this->col_prevY    = $this->getAVGofPercentNumber($this->col_prevY,$current_week);
            $this->col_prospective = $this->getAVGofPercentNumber($this->col_prospective,$current_week);
        }else{

            $this->col_actual   = $this->getAVGofPercentNumber($this->col_actual,$count_week);
            $this->col_target   = $this->getAVGofTarget($this->col_target,$this->target_defined);
            $this->col_prevY    = $this->getAVGofPercentNumber($this->col_prevY,$count_week);
            $this->col_prospective = $this->getAVGofPercentNumber($this->col_prospective,$count_week);
        }

        //End update value before get ratio
        //Ratio ==========================
        //Ratio (prospective/prevY)
        for($w = 1; $w <= $count_week; $w++){
            //Ratio (actual/target)
            $this->ratio_actual_target[$w]          = $this->gaRatio($this->actual[$w],$this->target[$w],1);
            $this->total_ratio_actual_target[$w]    = $this->gaRatio($this->total_actual[$w],$this->total_target[$w],1);

            //Ratio (actual/prev)
            if($w == $count_week){
                $actual_7_day = $this->gaPredict($this->actual[$w],(intval($week_title[$count_week]['week_end_day']) - intval($week_title[$count_week]['week_start_day']) + 1),7);
            }else{
                $actual_7_day = $this->actual[$w];
            }

            $this->ratio_actual_prev[$w]            = $this->gaRatio($actual_7_day,$this->actual_prev_7day[$w],2);
            $this->total_ratio_actual_prev[$w]      = $this->gaRatio($this->total_actual[$w],$this->total_actual_prev[$w],2);

            //Ratio (actual/prevY)
            $this->ratio_actual_prevY[$w]           = $this->gaRatio($this->actual[$w],$this->actual_prevY[$w],3);
            $this->total_ratio_actual_prevY[$w]     = $this->gaRatio($this->total_actual[$w],$this->total_actual_prevY[$w],3);

            //Ratio (prospective/target)
            $this->ratio_prospective_target[$w]     = $this->gaRatio($this->prospective[$w],$this->target[$w],1);
            $this->total_ratio_prospective_target[$w]   = $this->gaRatio($this->total_prospective[$w],$this->total_target[$w],1);


            //Ratio (prospective/prev)
            $this->ratio_prospective_prev[$w]       = $this->gaRatio($this->prospective[$w],$this->actual_prev[$w],2);
            $this->total_ratio_prospective_prev[$w] = $this->gaRatio($this->total_prospective[$w],$this->total_actual_prev[$w],2);

            //Ratio (prospective/prev)
            $this->ratio_prospective_prevY[$w]      = $this->gaRatio($this->prospective[$w],$this->actual_prevY[$w],3);
            $this->total_ratio_prospective_prevY[$w]= $this->gaRatio($this->total_prospective[$w],$this->total_actual_prevY[$w],3);
        }
        //End Ratio ======================

        $this->week_title = $week_title;
        $this->count_week = $count_week;

        //Ratio column total
        $this->col_ratio_actual_target  = $this->gaRatio($this->col_actual,$this->col_target,1);
        $this->col_ratio_actual_prev    = $this->gaRatio($this->col_actual,$this->col_prev,2);
        $this->col_ratio_actual_prevY   = $this->gaRatio($this->col_actual,$this->col_prevY,3);

        $this->col_ratio_prospective_target = $this->gaRatio($this->col_prospective,$this->col_target,1);
        $this->col_ratio_prospective_prev = $this->gaRatio($this->col_prospective,$this->col_prev,2);
        $this->col_ratio_prospective_prevY = $this->gaRatio($this->col_prospective,$this->col_prevY,3);

        //Unset prospective 
        if($this->crr_year == $this->query_year && $this->crr_month == $this->query_month){
            for($w = ($current_week+1); $w <= $count_week; $w++){
                unset($this->prospective[$w]);
                unset($this->ratio_prospective_prev[$w]);
                unset($this->ratio_prospective_prevY[$w]);

                unset($this->total_prospective[$w]);
                unset($this->total_ratio_prospective_prev[$w]);
                unset($this->total_ratio_prospective_prevY[$w]);
            }
        }

        $this->col_icon_status = $this->iconStatus($this->col_actual,$this->col_target);
    }

    function getReportMonth($year,$month){
        //Set type
        $this->type = 'week';
        //Set range
        //$this->setRangeData($range,$year);

        //Set month and year query
        /*if(!$this->checkMonthInRange($month)){
            echo "Month invalid";exit;
        }*/
        $this->query_year = $year;
        $this->query_month = $month;

        /*if($type == 'month'){
            //Get list month report of range
            $this->getListMonthReport();
        }else{
            //Get list week report of month*/
            $this->getListWeekReport();
        //}
    }

    //Function for report from monday to sunday (pending)
    function getReportMonthNoChange($year,$month){
        //Set type
        $this->type = 'week';
        //Set range
        //$this->setRangeData($range,$year);

        //Set month and year query
        /*if(!$this->checkMonthInRange($month)){
            echo "Month invalid";exit;
        }*/
        $this->query_year = $year;
        $this->query_month = $month;

        /*if($type == 'month'){
            //Get list month report of range
            $this->getListMonthReport();
        }else{
            //Get list week report of month*/
            $this->getListWeekReportNoChange();
        //}
    }


	function setRangeData($range,$year,$return_range = false){

		$range_ex = explode('_',$range);
		$range_data = array();
		if($range_ex[0] > $range_ex[1]){
			for($i = $range_ex[0];$i< 13;$i++) {
				$range_data[] = array(
					'm'=>$i,
					'y'=>$year
				);
			}
			$next_y = $year + 1;
			for ($i = 1; $i <= $range_ex[1]; $i++) {
				$range_data[] = array(
					'm'=>$i,
					'y'=>$next_y
				);
			}
		}else{
			for($i = $range_ex[0];$i<= $range_ex[1];$i++) {
				$range_data[] = array(
					'm'=>$i,
					'y'=>$year
				);
			}
		}
        
        if($return_range){
            return $range_data;
        }

		$this->range_data = $range_data;
	}

	function setType($type){
		if(in_array($type,array('week','month'))){
			$this->type = $type;
			return true;
		}else{
			return false;
		}
	}

	function checkMonthInRange($month){
		$exits = 0;
		foreach ($this->range_data as $month_item) {
			if($month_item['m'] == $month){
				$this->query_year = $month_item['y'];
				$this->query_month = $month_item['m'];
				return true;
			}
		}
		return false;
	}

	function getListMonth(){
		$r = array();
		foreach ($this->range_data as $month_item) {
			$r[] = $month_item['m'];
		}
		return $r;
	}

	function getWeekTitle($year,$month,$end_day){
		$month = intval($month) > 9 ? $month: "0".intval($month);
		$start_day_title_of_week    = $this->getDateOfWeek($year.'-'.$month.'-01');
        $end_day_title_of_week      = $this->getDateOfWeek($year.'-'.$month.'-07');
        $end_day_title_of_last_day  = $this->getDateOfWeek($year.'-'.$month.'-'.$end_day);

        $week_title = array();
        if($end_day > 28){
            $week_title = array(
                1=>array(
                    'week_title'    =>'第1週',
                    'week_des'      =>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/7('.$end_day_title_of_week.')',
                    'week_des2'      =>$month.'/01('.$start_day_title_of_week.') 〜 '.$month.'/07('.$end_day_title_of_week.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/7('.$end_day_title_of_week.')',
                    'week_start'    =>$year."-".$month."-01",
                    'week_start_day'=>1,
                    'week_end'      =>$year."-".$month."-07",
                    'week_end_day'  =>7,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=1 && $this->crr_day <=7)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,1,7)
                ),
                2=>array(
                    'week_title'    =>'第2週',
                    'week_des'      =>$month.'/8('.$start_day_title_of_week.') 〜 '.$month.'/14('.$end_day_title_of_week.')',
                    'week_des2'      =>$month.'/08('.$start_day_title_of_week.') 〜 '.$month.'/14('.$end_day_title_of_week.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/14('.$end_day_title_of_week.')',
                    'week_start'    =>$year."-".$month."-08",
                    'week_start_day'=>8,
                    'week_end'      =>$year."-".$month."-14",
                    'week_end_day'  =>14,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=8 && $this->crr_day <=14)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,8,14)
                ),
                3=>array(
                    'week_title'    =>'第3週',
                    'week_des'      =>$month.'/15('.$start_day_title_of_week.') 〜 '.$month.'/21('.$end_day_title_of_week.')',
                    'week_des2'      =>$month.'/15('.$start_day_title_of_week.') 〜 '.$month.'/21('.$end_day_title_of_week.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/21('.$end_day_title_of_week.')',
                    'week_start'    =>$year."-".$month."-15",
                    'week_start_day'=>15,
                    'week_end'      =>$year."-".$month."-21",
                    'week_end_day'  =>21,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=15 && $this->crr_day <=21)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,15,21)
                ),
                4=>array(
                    'week_title'    =>'第4週',
                    'week_des'      =>$month.'/22('.$start_day_title_of_week.') 〜 '.$month.'/28('.$end_day_title_of_week.')',
                    'week_des2'      =>$month.'/22('.$start_day_title_of_week.') 〜 '.$month.'/28('.$end_day_title_of_week.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/28('.$end_day_title_of_week.')',
                    'week_start'    =>$year."-".$month."-22",
                    'week_start_day'=>22,
                    'week_end'      =>$year."-".$month."-28",
                    'week_end_day'  =>28,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=22 && $this->crr_day <=28)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,22,28)
                ),
                5=>array(
                    'week_title'    =>'第5週',
                    'week_des'      =>$month.'/29('.$start_day_title_of_week.') 〜 '.$month.'/'.$end_day.'('.$end_day_title_of_last_day.')',
                    'week_des2'      =>$month.'/29('.$start_day_title_of_week.') 〜 '.$month.'/'.$end_day.'('.$end_day_title_of_last_day.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/'.$end_day.'('.$end_day_title_of_last_day.')',
                    'week_start'    =>$year."-".$month."-29",
                    'week_start_day'=>29,
                    'week_end'      =>$year."-".$month."-".$end_day,
                    'week_end_day'  =>$end_day,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=29 && $this->crr_day <=$end_day)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,29,$end_day)
                )
            );

        }else{
            $week_title = array(
                1=>array(
                    'week_title'    =>'第1週',
                    'week_des'      =>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/7('.$end_day_title_of_week.')',
                    'week_des2'      =>$month.'/01('.$start_day_title_of_week.') 〜 '.$month.'/07('.$end_day_title_of_week.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/7('.$end_day_title_of_week.')',
                    'week_start'    =>$year."-".$month."-01",
                    'week_start_day'=>1,
                    'week_end'      =>$year."-".$month."-07",
                    'week_end_day'  =>7,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=1 && $this->crr_day <=7)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,1,7)
                ),
                2=>array(
                    'week_title'    =>'第2週',
                    'week_des'      =>$month.'/8('.$start_day_title_of_week.') 〜 '.$month.'/14('.$end_day_title_of_week.')',
                    'week_des2'      =>$month.'/08('.$start_day_title_of_week.') 〜 '.$month.'/14('.$end_day_title_of_week.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/14('.$end_day_title_of_week.')',
                    'week_start'    =>$year."-".$month."-08",
                    'week_start_day'=>8,
                    'week_end'      =>$year."-".$month."-14",
                    'week_end_day'  =>14,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=8 && $this->crr_day <=14)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,8,14)
                ),
                3=>array(
                    'week_title'    =>'第3週',
                    'week_des'      =>$month.'/15('.$start_day_title_of_week.') 〜 '.$month.'/21('.$end_day_title_of_week.')',
                    'week_des2'      =>$month.'/15('.$start_day_title_of_week.') 〜 '.$month.'/21('.$end_day_title_of_week.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/21('.$end_day_title_of_week.')',
                    'week_start'    =>$year."-".$month."-15",
                    'week_start_day'=>15,
                    'week_end'      =>$year."-".$month."-21",
                    'week_end_day'  =>21,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=15 && $this->crr_day <=21)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,15,21)
                ),
                4=>array(
                    'week_title'    =>'第4週',
                    'week_des'      =>$month.'/22('.$start_day_title_of_week.') 〜 '.$month.'/'.$end_day.'('.$end_day_title_of_last_day.')',
                    'week_des2'      =>$month.'/22('.$start_day_title_of_week.') 〜 '.$month.'/'.$end_day.'('.$end_day_title_of_last_day.')',
                    'week_des_total'=>$month.'/1('.$start_day_title_of_week.') 〜 '.$month.'/'.$end_day.'('.$end_day_title_of_week.')',
                    'week_start'    =>$year."-".$month ."-22",
                    'week_start_day'=>22,
                    'week_end'      =>$year."-".$month ."-".$end_day,
                    'week_end_day'  =>$end_day,
                    'is_current_week'=>(($year == $this->crr_year && $month == $this->crr_month && ($this->crr_day >=22 && $this->crr_day <=$end_day)) ? true : false),
                    'week_in'=>$this->getWeekIn($year,$month,22,$end_day)
                )
            );
        }

        return $week_title;
	}

	function getDateOfWeek($date_time_string,$lang = 'ja'){
    	$date = date('w',strtotime($date_time_string));
    	switch ($date) {
    		case 0:
    			# sunday
    			return ($lang == 'ja') ? '日' : 'Sunday';
    			break;
    		case 1:
    			# monday
    			return ($lang == 'ja') ? '月' : 'Monday';
    			break;
    		case 2:
    			# code...
    			return ($lang == 'ja') ? '火' : 'Tuesday';
    			break;
    		case 3:
    			# code...
    			return ($lang == 'ja') ? '水' : 'Wednesday';
    			break;
    		case 4:
    			# code...
    			return ($lang == 'ja') ? '木' : 'Thursday';
    			break;
    		case 5:
    			# code...
    			return ($lang == 'ja') ? '金' : 'Friday';
    			break;
    		case 6:
    			# code...
    			return ($lang == 'ja') ? '土' : 'Saturday';
    			break;

    		default:
    			# code...
    			break;
    	}
    }


    function getWeekIn($year,$month,$start_day,$end_day){
        $year = intval($year);
        $month = intval($month);

        $crr_year = intval($this->crr_year);
        $crr_month = intval($this->crr_month);
        $crr_day = intval($this->crr_day);

        if($year < $crr_year){
            return 'past';
        }elseif($year == $crr_year){
            if($month < $crr_month){
                return 'past';
            }elseif($month == $crr_month){
                if($start_day <= $crr_day  && $crr_day <= $end_day){
                    return 'present';
                }elseif($end_day < $crr_day){
                    return 'past';
                }else{
                    return 'future';
                }
            }else{
                return 'future';
            }
        }else{
            return 'future';
        }
    }

    function getWeekIn2($year,$month,$week){
        $year   = intval($year);
        $month  = intval($month);

        $begin_date  = date($year.'-'.($month > 9 ? $month : '0'.$month).'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));
        $end_ex = explode('-',$end_day);
        $start_day = ($week * 7) - 6;
        if($week == 5){
            $end_day = $end_ex[2];
        }else{
            $end_day = $start_day + 6;
        }

        $crr_year   = intval($this->crr_year);
        $crr_month  = intval($this->crr_month);
        $crr_day    = intval($this->crr_day);

        if($year < $crr_year){
            return 'past';
        }elseif($year == $crr_year){
            if($month < $crr_month){
                return 'past';
            }elseif($month == $crr_month){
                if($start_day <= $crr_day  && $crr_day <= $end_day){
                    return 'present';
                }elseif($end_day < $crr_day){
                    return 'past';
                }else{
                    return 'future';
                }
            }else{
                return 'future';
            }
        }else{
            return 'future';
        }
    }

    function gaTotal($kpiAArr,$kpiBArr){
        $sessions               = $kpiAArr['sessions']              + $kpiBArr['sessions'];
        $avgSessionDuration     = $kpiAArr['avgSessionDuration']    + $kpiBArr['avgSessionDuration'];
        $pageviews              = $kpiAArr['pageviews']             + $kpiBArr['pageviews'];
        $transactionRevenue     = $kpiAArr['transactionRevenue']    + $kpiBArr['transactionRevenue'];
        $bounceRate             = $kpiAArr['bounceRate']            + $kpiBArr['bounceRate'];
        $transactions           = $kpiAArr['transactions']          + $kpiBArr['transactions'];

        $transactions_1           = $kpiAArr['transactions_1']          + $kpiBArr['transactions_1'];

        $uniqueUsers            = $kpiAArr['uniqueUsers']           + $kpiBArr['uniqueUsers'];
        $topBounceRate          = $kpiAArr['topBounceRate']         + $kpiBArr['topBounceRate'];

        $transactionsPerSession = $kpiAArr['transactionsPerSession'] + $kpiBArr['transactionsPerSession'];
        $revenuePerTransaction  = $kpiAArr['revenuePerTransaction']  + $kpiBArr['revenuePerTransaction'];
        $transactionsPerSession_1 = $kpiAArr['transactionsPerSession_1'] + $kpiBArr['transactionsPerSession_1'];
        $revenuePerTransaction_1  = $kpiAArr['revenuePerTransaction_1']  + $kpiBArr['revenuePerTransaction_1'];

        $pageviewsPerSession    = $kpiAArr['pageviewsPerSession']    + $kpiBArr['pageviewsPerSession'];
        $percentNewSessions    = $kpiAArr['percentNewSessions']    + $kpiBArr['percentNewSessions'];
        return array(
            'sessions'          =>$sessions,
            'avgSessionDuration'=>$avgSessionDuration,
            'pageviews'         =>$pageviews,
            'transactionRevenue'=>$transactionRevenue,
            'bounceRate'        =>$bounceRate,
            'transactions'      =>$transactions,
            'transactions_1'      =>$transactions_1,
            'uniqueUsers'       =>$uniqueUsers,
            'topBounceRate'     =>$topBounceRate,
            'transactionsPerSession'    =>$transactionsPerSession,
            'revenuePerTransaction'     =>$revenuePerTransaction,
            'transactionsPerSession_1'    =>$transactionsPerSession_1,
            'revenuePerTransaction_1'     =>$revenuePerTransaction_1,

            'pageviewsPerSession'       =>$pageviewsPerSession,
            'percentNewSessions'        =>$percentNewSessions
        );
    }

    //get the ratio of array ga A with array ga B
    //$row = "1" //row 1
    //$row = "2" //row 2
    //$row = "3" //row 3
    function gaRatio($ssmKpiArray,$targetArray,$row = 1){ 
    	/*return array(
    		'sessions'                => ($targetArray['sessions'] != 0 && $ssmKpiArray['sessions'] != 0) ? ($ssmKpiArray['sessions'] * 100) / $targetArray['sessions'] : "-" ,
    		'avgSessionDuration'      => ($targetArray['avgSessionDuration'] != 0 && $ssmKpiArray['avgSessionDuration'] != 0) ? ($ssmKpiArray['avgSessionDuration'] * 100) / $targetArray['avgSessionDuration']: "-" ,
    		'pageviews'               => ($targetArray['pageviews'] != 0 && $ssmKpiArray['pageviews'] != 0) ? ($ssmKpiArray['pageviews'] * 100) / $targetArray['pageviews']: "-" ,
    		'transactionRevenue'      => ($targetArray['transactionRevenue'] != 0 && $ssmKpiArray['transactionRevenue'] != 0) ? ($ssmKpiArray['transactionRevenue'] * 100) / $targetArray['transactionRevenue']: "-" ,

    		'revenuePerTransaction'   => ($targetArray['revenuePerTransaction'] != 0 && $ssmKpiArray['revenuePerTransaction'] != 0) ? ($ssmKpiArray['revenuePerTransaction'] * 100) / $targetArray['revenuePerTransaction']: "-" ,
            'revenuePerTransaction_1'   => ($targetArray['revenuePerTransaction_1'] != 0 && $ssmKpiArray['revenuePerTransaction_1'] != 0) ? ($ssmKpiArray['revenuePerTransaction_1'] * 100) / $targetArray['revenuePerTransaction_1']: "-" ,

    		'bounceRate'              => ($targetArray['bounceRate'] != 0 && $ssmKpiArray['bounceRate'] != 0) ? ($ssmKpiArray['bounceRate'] * 100) / $targetArray['bounceRate']: "-" ,
    		'transactions'            => ($targetArray['transactions'] != 0 && $ssmKpiArray['transactions'] != 0) ? ($ssmKpiArray['transactions'] * 100) / $targetArray['transactions']: "-" ,
            'transactions_1'            => ($targetArray['transactions_1'] != 0 && $ssmKpiArray['transactions_1'] != 0) ? ($ssmKpiArray['transactions_1'] * 100) / $targetArray['transactions_1']: "-" ,
    		'transactionsPerSession'  => ($targetArray['transactionsPerSession'] != 0 && $ssmKpiArray['transactionsPerSession'] != 0) ? ($ssmKpiArray['transactionsPerSession'] * 100) / $targetArray['transactionsPerSession']: "-" ,
            'transactionsPerSession_1'  => ($targetArray['transactionsPerSession_1'] != 0 && $ssmKpiArray['transactionsPerSession_1'] != 0) ? ($ssmKpiArray['transactionsPerSession_1'] * 100) / $targetArray['transactionsPerSession_1']: "-" ,

    		'uniqueUsers'             => ($targetArray['uniqueUsers'] != 0 && $ssmKpiArray['uniqueUsers'] != 0) ? ($ssmKpiArray['uniqueUsers'] * 100) / $targetArray['uniqueUsers']: "-",
    		'topBounceRate'           => ($targetArray['topBounceRate'] != 0 && $ssmKpiArray['topBounceRate'] != 0) ? ($ssmKpiArray['topBounceRate'] * 100) / $targetArray['topBounceRate']: "-" ,
    		'pageviewsPerSession'     => ($targetArray['pageviewsPerSession'] != 0 && $ssmKpiArray['pageviewsPerSession'] != 0) ? ($ssmKpiArray['pageviewsPerSession'] * 100) / $targetArray['pageviewsPerSession']: "-" ,
            'percentNewSessions'     => ($targetArray['percentNewSessions'] != 0 && $ssmKpiArray['percentNewSessions'] != 0) ? ($ssmKpiArray['percentNewSessions'] * 100) / $targetArray['percentNewSessions']: "-" ,
    	);*/

        if($this->in_view == 'kpi_list'){

            if($row == 1){
                $transactionRevenue = $this->ct($ssmKpiArray['transactionRevenue'],$targetArray['transactionRevenue']);
                $pageviews = $this->ct($ssmKpiArray['pageviews'],$targetArray['pageviews']);
                $pageviewsPerSession = $this->ct($ssmKpiArray['pageviewsPerSession'],$targetArray['pageviewsPerSession']);
                $avgSessionDuration = $this->ct($ssmKpiArray['avgSessionDuration'],$targetArray['avgSessionDuration']);
                $revenuePerTransaction = $this->ct($ssmKpiArray['revenuePerTransaction'],$targetArray['revenuePerTransaction']);
                $bounceRate = $this->ct($ssmKpiArray['bounceRate'],$targetArray['bounceRate'],1);
                $transactions = $this->ct($ssmKpiArray['transactions'],$targetArray['transactions']);
                $transactions_1 = $this->ct($ssmKpiArray['transactions_1'],$targetArray['transactions_1']);
                $transactionsPerSession = $this->ct($ssmKpiArray['transactionsPerSession'],$targetArray['transactionsPerSession']);
                $transactionsPerSession_1 = $this->ct($ssmKpiArray['transactionsPerSession_1'],$targetArray['transactionsPerSession_1']);
                $revenuePerTransaction_1 = $this->ct($ssmKpiArray['revenuePerTransaction_1'],$targetArray['revenuePerTransaction_1']);
                $revenuePerTransaction_1 = $this->ct($ssmKpiArray['revenuePerTransaction_1'],$targetArray['revenuePerTransaction_1']);
                $uniqueUsers = $this->ct($ssmKpiArray['uniqueUsers'],$targetArray['uniqueUsers']);
                $topBounceRate = $this->ct($ssmKpiArray['topBounceRate'],$targetArray['topBounceRate'],1);
                $percentNewSessions = $this->ct($ssmKpiArray['percentNewSessions'],$targetArray['percentNewSessions']);
                $sessions = $this->ct($ssmKpiArray['sessions'],$targetArray['sessions']);

            }elseif($row == 2){
                $transactionRevenue = $this->ct($ssmKpiArray['transactionRevenue'],$targetArray['transactionRevenue']);
                $pageviews = $this->ct($ssmKpiArray['pageviews'],$targetArray['pageviews']);
                $pageviewsPerSession = $this->ct($ssmKpiArray['pageviewsPerSession'],$targetArray['pageviewsPerSession']);
                $avgSessionDuration = $this->ct($ssmKpiArray['avgSessionDuration'],$targetArray['avgSessionDuration']);
                $revenuePerTransaction = $this->ct($ssmKpiArray['revenuePerTransaction'],$targetArray['revenuePerTransaction']);
                $bounceRate = $this->ct($ssmKpiArray['bounceRate'],$targetArray['bounceRate']);
                $transactions = $this->ct($ssmKpiArray['transactions'],$targetArray['transactions']);
                $transactions_1 = $this->ct($ssmKpiArray['transactions_1'],$targetArray['transactions_1']);
                $transactionsPerSession = $this->ct($ssmKpiArray['transactionsPerSession'],$targetArray['transactionsPerSession']);
                $transactionsPerSession_1 = $this->ct($ssmKpiArray['transactionsPerSession_1'],$targetArray['transactionsPerSession_1']);
                $revenuePerTransaction_1 = $this->ct($ssmKpiArray['revenuePerTransaction_1'],$targetArray['revenuePerTransaction_1']);
                $revenuePerTransaction_1 = $this->ct($ssmKpiArray['revenuePerTransaction_1'],$targetArray['revenuePerTransaction_1']);
                $uniqueUsers = $this->ct($ssmKpiArray['uniqueUsers'],$targetArray['uniqueUsers']);
                $topBounceRate = $this->ct($ssmKpiArray['topBounceRate'],$targetArray['topBounceRate']);
                $percentNewSessions = $this->ct($ssmKpiArray['percentNewSessions'],$targetArray['percentNewSessions']);
                $sessions = $this->ct($ssmKpiArray['sessions'],$targetArray['sessions']);

            }elseif($row == 3){
                $transactionRevenue = $this->ct1($ssmKpiArray['transactionRevenue'],$targetArray['transactionRevenue']);
                $pageviews = $this->ct1($ssmKpiArray['pageviews'],$targetArray['pageviews']);
                $pageviewsPerSession = $this->ct1($ssmKpiArray['pageviewsPerSession'],$targetArray['pageviewsPerSession']);
                $avgSessionDuration = $this->ct1($ssmKpiArray['avgSessionDuration'],$targetArray['avgSessionDuration']);
                $revenuePerTransaction = $this->ct1($ssmKpiArray['revenuePerTransaction'],$targetArray['revenuePerTransaction']);
                $bounceRate = $this->ct1($ssmKpiArray['bounceRate'],$targetArray['bounceRate']);
                $transactions = $this->ct1($ssmKpiArray['transactions'],$targetArray['transactions']);
                $transactions_1 = $this->ct1($ssmKpiArray['transactions_1'],$targetArray['transactions_1']);
                $transactionsPerSession = $this->ct1($ssmKpiArray['transactionsPerSession'],$targetArray['transactionsPerSession']);
                $transactionsPerSession_1 = $this->ct1($ssmKpiArray['transactionsPerSession_1'],$targetArray['transactionsPerSession_1']);
                $revenuePerTransaction_1 = $this->ct1($ssmKpiArray['revenuePerTransaction_1'],$targetArray['revenuePerTransaction_1']);
                $uniqueUsers = $this->ct1($ssmKpiArray['uniqueUsers'],$targetArray['uniqueUsers']);
                $topBounceRate = $this->ct1($ssmKpiArray['topBounceRate'],$targetArray['topBounceRate']);
                $percentNewSessions = $this->ct1($ssmKpiArray['percentNewSessions'],$targetArray['percentNewSessions']);
                $sessions = $this->ct1($ssmKpiArray['sessions'],$targetArray['sessions']);
            }

        }else{
            if($targetArray['sessions'] != 0 && $ssmKpiArray['sessions'] == 0){
                $sessions = 0;
            }elseif($targetArray['sessions'] == "-" || $targetArray['sessions'] == 0){
                $sessions = "-";
            }else{
                $sessions = ($ssmKpiArray['sessions'] * 100) / $targetArray['sessions'];
            }

            if($targetArray['avgSessionDuration'] != 0 && $ssmKpiArray['avgSessionDuration'] == 0){
                $avgSessionDuration = 0;
            }elseif($targetArray['avgSessionDuration'] == "-" || $targetArray['avgSessionDuration'] == 0){
                $avgSessionDuration = "-";
            }else{
                $avgSessionDuration = ($ssmKpiArray['avgSessionDuration'] * 100) / $targetArray['avgSessionDuration'];
            }

            if($targetArray['pageviews'] != 0 && $ssmKpiArray['pageviews'] == 0){
                $pageviews = 0;
            }elseif($targetArray['pageviews'] == "-" || $targetArray['pageviews'] == 0){
                $pageviews = "-";
            }else{
                $pageviews = ($ssmKpiArray['pageviews'] * 100) / $targetArray['pageviews'];
            }

            if($targetArray['revenuePerTransaction'] != 0 && $ssmKpiArray['revenuePerTransaction'] == 0){
                $revenuePerTransaction = 0;
            }elseif($targetArray['transactionRevenue'] == "-" || $targetArray['transactionRevenue'] == 0){
                $transactionRevenue = "-";
            }else{
                $transactionRevenue = ($ssmKpiArray['transactionRevenue'] * 100) / $targetArray['transactionRevenue'];
            }

            if($targetArray['revenuePerTransaction'] != 0 && $ssmKpiArray['revenuePerTransaction'] == 0){
                $revenuePerTransaction = 0;
            }elseif($targetArray['revenuePerTransaction'] == "-" || $targetArray['revenuePerTransaction'] == 0){
                $revenuePerTransaction = "-";
            }else{
                $revenuePerTransaction = ($ssmKpiArray['revenuePerTransaction'] * 100) / $targetArray['revenuePerTransaction'];
            }

            if($targetArray['bounceRate'] != 0 && $ssmKpiArray['bounceRate'] == 0){
                $bounceRate = 0;
            }elseif($targetArray['bounceRate'] == "-" || $targetArray['bounceRate'] == 0){
                $bounceRate = "-";
            }else{
                $bounceRate = ($ssmKpiArray['bounceRate'] * 100) / $targetArray['bounceRate'];
            }

            if($targetArray['transactions'] != 0 && $ssmKpiArray['transactions'] == 0){
                $transactions = 0;
            }elseif($targetArray['transactions'] == "-" || $targetArray['transactions'] == 0){
                $transactions = "-";
            }else{
                $transactions = ($ssmKpiArray['transactions'] * 100) / $targetArray['transactions'];
            }

            if($targetArray['transactions_1'] != 0 && $ssmKpiArray['transactions_1'] == 0){
                $transactions_1 = 0;
            }elseif($targetArray['transactions_1'] == "-" || $targetArray['transactions_1'] == 0){
                $transactions_1 = "-";
            }else{
                $transactions_1 = ($ssmKpiArray['transactions_1'] * 100) / $targetArray['transactions_1'];
            }

            if($targetArray['transactionsPerSession'] != 0 && $ssmKpiArray['transactionsPerSession'] == 0){
                $transactionsPerSession = 0;
            }elseif($targetArray['transactionsPerSession'] == "-" || $targetArray['transactionsPerSession'] == 0){
                $transactionsPerSession = "-";
            }else{
                $transactionsPerSession = ($ssmKpiArray['transactionsPerSession'] * 100) / $targetArray['transactionsPerSession'];
            }

            if($targetArray['transactionsPerSession_1'] != 0 && $ssmKpiArray['transactionsPerSession_1'] == 0){
                $transactionsPerSession_1 = 0;
            }elseif($targetArray['transactionsPerSession_1'] == "-" || $targetArray['transactionsPerSession_1'] == 0){
                $transactionsPerSession_1 = "-";
            }else{
                $transactionsPerSession_1 = ($ssmKpiArray['transactionsPerSession_1'] * 100) / $targetArray['transactionsPerSession_1'];
            }

            if($targetArray['revenuePerTransaction_1'] != 0 && $ssmKpiArray['revenuePerTransaction_1'] == 0){
                $revenuePerTransaction_1 = 0;
            }elseif($targetArray['revenuePerTransaction_1'] == "-" || $targetArray['revenuePerTransaction_1'] == 0){
                $revenuePerTransaction_1 = "-";
            }else{
                $revenuePerTransaction_1 = ($ssmKpiArray['revenuePerTransaction_1'] * 100) / $targetArray['revenuePerTransaction_1'];
            }

            if($targetArray['uniqueUsers'] != 0 && $ssmKpiArray['uniqueUsers'] == 0){
                $uniqueUsers = 0;
            }elseif($targetArray['uniqueUsers'] == "-" || $targetArray['uniqueUsers'] == 0){
                $uniqueUsers = "-";
            }else{
                $uniqueUsers = ($ssmKpiArray['uniqueUsers'] * 100) / $targetArray['uniqueUsers'];
            }

            if($targetArray['topBounceRate'] != 0 && $ssmKpiArray['topBounceRate'] == 0){
                $topBounceRate = 0;
            }elseif($targetArray['topBounceRate'] == "-" || $targetArray['topBounceRate'] == 0){
                $topBounceRate = "-";
            }else{
                $topBounceRate = ($ssmKpiArray['topBounceRate'] * 100) / $targetArray['topBounceRate'];
            }

            if($targetArray['pageviewsPerSession'] != 0 && $ssmKpiArray['pageviewsPerSession'] == 0){
                $pageviewsPerSession = 0;
            }elseif($targetArray['pageviewsPerSession'] == "-" || $targetArray['pageviewsPerSession'] == 0){
                $pageviewsPerSession = "-";
            }else{
                $pageviewsPerSession = ($ssmKpiArray['pageviewsPerSession'] * 100) / $targetArray['pageviewsPerSession'];
            }

            if($targetArray['percentNewSessions'] != 0 && $ssmKpiArray['percentNewSessions'] == 0){
                $percentNewSessions = 0;
            }elseif($targetArray['percentNewSessions'] == "-" || $targetArray['percentNewSessions'] == 0){
                $percentNewSessions = "-";
            }else{
                $percentNewSessions = ($ssmKpiArray['percentNewSessions'] * 100) / $targetArray['percentNewSessions'];
            }
        }

        $return = array(
            'sessions'                => $sessions,
            'avgSessionDuration'      => $avgSessionDuration,
            'pageviews'               => $pageviews,
            'transactionRevenue'      => $transactionRevenue,
            'revenuePerTransaction'   => $revenuePerTransaction,
            'bounceRate'              => $bounceRate ,
            'transactions'            => $transactions,
            'transactions_1'          => $transactions_1,
            'transactionsPerSession'  => $transactionsPerSession,
            'transactionsPerSession_1'=> $transactionsPerSession_1,
            'revenuePerTransaction_1' => $revenuePerTransaction_1,
            'uniqueUsers'             => $uniqueUsers,
            'topBounceRate'           => $topBounceRate,
            'pageviewsPerSession'     => $pageviewsPerSession,
            'percentNewSessions'      => $percentNewSessions
        );

        return $return;
    }

    function gaPredict($ssmKpiArray,$number_day_get_data,$number_day_in_month){
    	$number_day_get_data = intval($number_day_get_data);
    	$number_day_in_month = intval($number_day_in_month);
    	return array(
    		'sessions'                =>(($ssmKpiArray['sessions']/$number_day_get_data) * $number_day_in_month),
    		'avgSessionDuration'      =>$ssmKpiArray['avgSessionDuration'],
    		'pageviews'               =>(($ssmKpiArray['pageviews']/$number_day_get_data) * $number_day_in_month),
    		'transactionRevenue'      =>(($ssmKpiArray['transactionRevenue']/$number_day_get_data) * $number_day_in_month),
    		'revenuePerTransaction'   =>$ssmKpiArray['revenuePerTransaction'],
            'revenuePerTransaction_1'   =>$ssmKpiArray['revenuePerTransaction_1'],
    		'bounceRate'              =>$ssmKpiArray['bounceRate'],
    		'transactions'            =>(($ssmKpiArray['transactions']/$number_day_get_data) * $number_day_in_month),
    		'transactionsPerSession'  =>$ssmKpiArray['transactionsPerSession'],
            'transactions_1'            =>(($ssmKpiArray['transactions_1']/$number_day_get_data) * $number_day_in_month),
            'transactionsPerSession_1'  =>$ssmKpiArray['transactionsPerSession_1'],
    		'uniqueUsers'             =>(($ssmKpiArray['uniqueUsers']/$number_day_get_data) * $number_day_in_month),
    		'topBounceRate'           =>$ssmKpiArray['topBounceRate'],
    		'pageviewsPerSession'     =>$ssmKpiArray['pageviewsPerSession'],
            'percentNewSessions'      =>$ssmKpiArray['percentNewSessions'],
    	);
    }

    function gaProspective($ssmKpiArray,$number_day_get_data,$total_day){
    	$number_day_get_data = intval($number_day_get_data);
    	$total_day = intval($total_day);
    	return array(
    		'sessions'                =>(($ssmKpiArray['sessions']/$number_day_get_data) * $total_day),
    		'avgSessionDuration'      =>(($ssmKpiArray['avgSessionDuration']/$number_day_get_data) * $total_day),
    		'pageviews'               =>(($ssmKpiArray['pageviews']/$number_day_get_data) * $total_day),
    		'transactionRevenue'      =>(($ssmKpiArray['transactionRevenue']/$number_day_get_data) * $total_day),
    		'revenuePerTransaction'   =>$ssmKpiArray['revenuePerTransaction'],
    		'bounceRate'              =>$ssmKpiArray['bounceRate'],
    		'transactions'            =>(($ssmKpiArray['transactions']/$number_day_get_data) * $total_day),
    		'transactionsPerSession'  =>$ssmKpiArray['transactionsPerSession'],
    		'uniqueUsers'             =>(($ssmKpiArray['uniqueUsers']/$number_day_get_data) * $total_day),
    		'topBounceRate'           =>(($ssmKpiArray['topBounceRate']/$number_day_get_data) * $total_day),
    		'pageviewsPerSession'     =>(($ssmKpiArray['pageviews']/$number_day_get_data) * $total_day) / (($ssmKpiArray['sessions']/$number_day_get_data) * $total_day),
            'percentNewSessions'      =>$ssmKpiArray['percentNewSessions'],
            'revenuePerTransaction_1'   =>$ssmKpiArray['revenuePerTransaction_1'],
            'transactions_1'            =>(($ssmKpiArray['transactions_1']/$number_day_get_data) * $total_day),
            'transactionsPerSession_1'  =>$ssmKpiArray['transactionsPerSession_1'],
    	);
    }

    function getAVGofPercentNumber($total_data,$avg_number){

        //$total_data['revenuePerTransaction'] = $total_data['revenuePerTransaction']/$avg_number;
        //$total_data['revenuePerTransaction_1'] = $total_data['revenuePerTransaction_1']/$avg_number;

        //pageviewsPerSession
        $total_data['pageviewsPerSession'] = $total_data['pageviewsPerSession']/$avg_number;
        //avgSessionDuration
        $total_data['avgSessionDuration'] = $total_data['avgSessionDuration']/$avg_number;
        //transactionsPerSession
        //$total_data['transactionsPerSession'] = $total_data['transactionsPerSession']/$avg_number;
        //$total_data['transactionsPerSession_1'] = $total_data['transactionsPerSession_1']/$avg_number;

        //bounceRate
        $total_data['bounceRate'] = $total_data['bounceRate']/$avg_number;
        //topBounceRate
        $total_data['topBounceRate'] = $total_data['topBounceRate']/$avg_number;
        //percentNewSessions
        $total_data['percentNewSessions'] = $total_data['percentNewSessions']/$avg_number;

        return $total_data;
    }


    function getAVGofTarget($total_data,$arr_col_defined){

        $total_data['revenuePerTransaction'] = ($total_data['revenuePerTransaction'] > 0 && $arr_col_defined['revenuePerTransaction'] >0 ) ? ($total_data['revenuePerTransaction']/$arr_col_defined['revenuePerTransaction']) : 0;
        //pageviewsPerSession
        $total_data['pageviewsPerSession'] = ($total_data['pageviewsPerSession'] > 0 && $arr_col_defined['pageviewsPerSession'] >0 ) ? ($total_data['pageviewsPerSession']/$arr_col_defined['pageviewsPerSession']) : 0;
        //avgSessionDuration
        $total_data['avgSessionDuration'] = ($total_data['avgSessionDuration'] > 0 && $arr_col_defined['avgSessionDuration'] >0 ) ? ($total_data['avgSessionDuration']/$arr_col_defined['avgSessionDuration']) : 0;
        //transactionsPerSession
        $total_data['transactionsPerSession'] = ($total_data['transactionsPerSession'] > 0 && $arr_col_defined['transactionsPerSession'] >0 ) ? ($total_data['transactionsPerSession']/$arr_col_defined['transactionsPerSession']) : 0;
        //bounceRate
        $total_data['bounceRate'] = ($total_data['bounceRate'] > 0 && $arr_col_defined['bounceRate'] >0 ) ? ($total_data['bounceRate']/$arr_col_defined['bounceRate']) : 0;
        //topBounceRate
        $total_data['topBounceRate'] = ($total_data['topBounceRate'] > 0 && $arr_col_defined['topBounceRate'] >0 ) ? ($total_data['topBounceRate']/$arr_col_defined['topBounceRate']) : 0;
        //percentNewSessions
        $total_data['percentNewSessions'] = ($total_data['percentNewSessions'] > 0 && $arr_col_defined['percentNewSessions'] >0 ) ? ($total_data['percentNewSessions']/$arr_col_defined['percentNewSessions']) : 0;

        $total_data['revenuePerTransaction_1'] = ($total_data['revenuePerTransaction_1'] > 0 && $arr_col_defined['revenuePerTransaction_1'] >0 ) ? ($total_data['revenuePerTransaction_1']/$arr_col_defined['revenuePerTransaction_1']) : 0;
        $total_data['transactionsPerSession_1'] = ($total_data['transactionsPerSession_1'] > 0 && $arr_col_defined['transactionsPerSession_1'] >0 ) ? ($total_data['transactionsPerSession_1']/$arr_col_defined['transactionsPerSession_1']) : 0;

        return $total_data;
    }

    function showProspective(){
        if($this->type == 'week'){
            if($this->crr_year == $this->query_year && $this->crr_month == $this->query_month){
                return true;
            }else{
                return false;
            }
        }else{
            if($this->crr_year == $this->query_year){
                foreach($this->range_data as $m_in_range){
                    if($m_in_range['m'] == $this->crr_month && $m_in_range['y'] == $this->crr_year){
                        return true;
                    }
                }
                return false;
            }
        }
    }

    //Increase the number of columns that have been set by the target
    function increaseTarget($target_data_column){

        if($target_data_column['pageviewsPerSession'] > 0){
            $this->target_defined['pageviewsPerSession']++;
        }
        if($target_data_column['revenuePerTransaction'] > 0){
            $this->target_defined['revenuePerTransaction']++;
        }
        if($target_data_column['revenuePerTransaction_1'] > 0){
            $this->target_defined['revenuePerTransaction_1']++;
        }
        if($target_data_column['transactionsPerSession'] > 0){
            $this->target_defined['transactionsPerSession']++;
        }
        if($target_data_column['transactionsPerSession_1'] > 0){
            $this->target_defined['transactionsPerSession_1']++;
        }
        if($target_data_column['bounceRate'] > 0){
            $this->target_defined['bounceRate']++;
        }
        if($target_data_column['topBounceRate'] > 0){
            $this->target_defined['topBounceRate']++;
        }
        if($target_data_column['avgSessionDuration'] > 0){
            $this->target_defined['avgSessionDuration']++;
        }
        if($target_data_column['percentNewSessions'] > 0){
            $this->target_defined['percentNewSessions']++;
        }
        return $this->target_defined;
    }

    //Set status icon after compare actual value with target value
    function iconStatus($actual,$target){
        $return = array();
        foreach ($this->kpi as $kpi_key) {
            if($kpi_key == 'bounceRate' || $kpi_key == "topBounceRate"){
                if($target[$kpi_key] == 0 || $target[$kpi_key] >= $actual[$kpi_key]){
                    $return[$kpi_key] = 1;
                }else{
                    $return[$kpi_key] = 0;
                }
            }else{
                if($target[$kpi_key] == 0 || $target[$kpi_key] <= $actual[$kpi_key]){
                    $return[$kpi_key] = 1;
                }else{
                    $return[$kpi_key] = 0;
                }
            }
        }
        return $return;
    }

    function displayValue($value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        if($value == "-" || $value == "0" || $value == 0){
            return "-";
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }

    public function displayActualKPIpage($key_api,$value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        $value = trim($value);

        if(in_array($key_api,array('pageviewsPerSession','avgSessionDuration','transactionsPerSession_1','transactionsPerSession','revenuePerTransaction_1','revenuePerTransaction','bounceRate','topBounceRate','percentNewSessions'))){
            if($value == 0){
                return "-";
            }
        }

        if($value == "-"){
            return "-";
        }elseif($value == 0){
            if($type == 'number'){
                return $pre_char.number_format(0,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",0).$sub_char;
            }else{
                return $pre_char."0".$sub_char;
            }
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }

    //Function display actual value of revenuePerTransaction_1,revenuePerTransaction ,transactionsPerSession_1 ,transactionsPerSession
    public function displayActualKPIpageV2($key_kpi,$data,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        if($key_kpi == 'transactionsPerSession_1'){
            if($data['sessions'] == 0 || $data['sessions'] == '0'){
                return '-';
            }else{
                if($data['transactions_1'] == 0 || $data['transactions_1'] == '0'){
                    return number_format(0,$decimal_number).$sub_char;
                }else{
                    return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
                }
            }
        }elseif($key_kpi == 'transactionsPerSession'){
            if($data['sessions'] == 0 || $data['sessions'] == '0'){
                return '-';
            }else{
                if($data['transactions'] == 0 || $data['transactions'] == '0'){
                    return number_format(0,$decimal_number).$sub_char;
                }else{
                    return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
                }
            }
        }elseif($key_kpi == 'revenuePerTransaction_1'){
            if($data['transactions_1'] == 0 || $data['transactions_1'] == '0'){
                return '-';
            }else{
                if($data['transactionRevenue'] == 0 || $data['transactionRevenue'] == '0'){
                    return number_format(0,$decimal_number).$sub_char;
                }else{
                    return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
                }
            }
        }elseif($key_kpi == 'revenuePerTransaction'){
            if($data['transactions'] == 0 || $data['transactions'] == '0'){
                return '-';
            }else{
                if($data['transactionRevenue'] == 0 || $data['transactionRevenue'] == '0'){
                    return number_format(0,$decimal_number).$sub_char;
                }else{
                    return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
                }
            }
        }else{
            return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
        }
    }

    function reCalculateField($data){
        if($data['transactionRevenue'] != 0 &&$data['transactions'] != 0){
            $data['revenuePerTransaction'] = $data['transactionRevenue']/$data['transactions'];
        }else{
            $data['revenuePerTransaction'] = 0;
        }

        if($data['transactionRevenue'] != 0 && $data['transactions'] !=0){
            $data['revenuePerTransaction'] = $data['transactionRevenue']/$data['transactions'];
        }else{
            $data['revenuePerTransaction'] = 0;
        }

        if($data['transactions'] != 0 && $data['sessions'] !=0){
            $data['transactionsPerSession'] = ($data['transactions']/$data['sessions'])*100;
        }else{
            $data['transactionsPerSession'] = 0;
        }

        if($data['transactionRevenue'] != 0 && $data['transactions_1'] !=0){
            $data['revenuePerTransaction_1'] = $data['transactionRevenue']/$data['transactions_1'];
        }else{
            $data['revenuePerTransaction_1'] = 0;
        }

        if($data['transactions_1'] != 0 && $data['sessions'] !=0){
            $data['transactionsPerSession_1'] = ($data['transactions_1']/$data['sessions'])*100;
        }else{
            $data['transactionsPerSession_1'] = 0;
        }
        return $data;
    }


    function ct($cur,$tar,$reverse = false){
        if(!$reverse){
            if($cur == 0){
                $re = "-";
            }else{
                if($tar == 0){
                    $re = "-";
                }else{
                    $re = ($cur * 100) / $tar;
                }
            }
        }else{
            if($cur == 0){
                $re = "-";
            }else{
                if($tar == 0){
                    $re = "-";
                }else{
                    $re = ($tar * 100) / $cur;
                }
            }
        }
        return $re;
    }

    function ct1($cur,$tar,$reverse = false){
        //CT1
        if(!$reverse){
            if($cur == 0){
                if($tar == 0){
                    $re = "-";
                }else{
                    $re = 0;
                }
            }else{
                if($tar == 0){
                    $re = "-";
                }else{
                    $re = ($cur * 100) / $tar;
                }
            }
        }else{
            if($cur == 0){
                if($tar == 0){
                    $re = "-";
                }else{
                    $re = 0;
                }
            }else{
                if($tar == 0){
                    $re = "-";
                }else{
                    $re = ($tar * 100) / $cur;
                }
            }
        }
        return $re;
    }

}