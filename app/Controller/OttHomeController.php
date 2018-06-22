<?php
include_once(__DIR__."/../Vendor/Google/GA.php");
App::uses('SsmAdminController', 'Controller');
/**
 * Dashboards Controller
 *
 * @property Dashboard $Dashboard
 * @property PaginatorComponent $Paginator
 */
class OttHomeController extends SsmAdminController {

    public $helpers     = array('Shishimai','Html');
    public $components  = array('Cshishimai','Session','SsmAuth','SsmGA');
    public $uses        = array('SsmKpi','SsmKpiGaWeek','SsmKpiGaMonth','SsmKpiChange','SsmSite','SsmKpiNote','SsmAdviceKey');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function beforeRender(){
        parent::beforeRender();
    }

    public function index() {
        //set up view_id,site_id for module
        $this->layout = 'ott';
        Configure::load('config_shishimai');
        //User and Site define ========================================================
        $site_id    = $this->site_id;

        $loginUser  = $this->loginUser;

        if(!$this->SsmAuth->checkContractSite($site_id) && $this->user_role !="admin"){
            $this->render('/OttError/no_contract');
            return;
        }

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }
        $this->set('siteInfo',$siteInfo);
        if($siteInfo['ga_ecommerce'] != 1){
            $kpis_list = Configure::read('kpis_1');
            $cv_key = 'transactions_1';
        }else{
             $kpis_list = Configure::read('kpis');
            $cv_key = 'transactions';
        }

        //echo $cv_key;exit;
        $this->set('kpis_list',$kpis_list);

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        $current_year  = date('Y');
        $current_month = date('m');
        $current_day   = date('d');

        $int_current_day = intval($current_day);
        $date = new DateTime('now');
        $date->modify('last day of this month');
        $last_day_number_of_month = $date->format('d');
        $int_last_day_number_of_month = intval($last_day_number_of_month);
        $rest_day_of_last_week = 36 - $int_last_day_number_of_month;

        $report_kpi_checked = $siteInfo['report_kpi'];
        $site_target_key = in_array('transactionRevenue',$report_kpi_checked) ? 'transactionRevenue' : ( (in_array('transactions_1',$report_kpi_checked) || in_array('transactions',$report_kpi_checked)) ? $cv_key : 'pageviews' );

        if($site_target_key == 'transactionRevenue'){
            $this->set('site_target_unit','円');
        }elseif($site_target_key == 'transactions'){
            $this->set('site_target_unit','CV');
        }elseif($site_target_key == 'transactions_1'){
            //Change arr kpi display
            $this->set('site_target_unit','CV');
        }elseif($site_target_key == 'pageviews'){
            //Change arr kpi display
            $this->set('site_target_unit','PV');
        }

        if($cv_key == 'transactions_1'){
            foreach ($report_kpi_checked as $key=>$value) {
                if($value == 'transactions'){
                    $report_kpi_checked[$key] = 'transactions_1';
                }elseif($value == 'transactionsPerSession'){
                    $report_kpi_checked[$key] = 'transactionsPerSession_1';
                }elseif($value == 'revenuePerTransaction'){
                    $report_kpi_checked[$key] = 'revenuePerTransaction_1';
                }
            }
        }else{
            foreach ($report_kpi_checked as $key=>$value) {
                if($value == 'transactions_1'){
                    $report_kpi_checked[$key] = 'transactions';
                }elseif($value == 'transactionsPerSession_1'){
                    $report_kpi_checked[$key] = 'transactionsPerSession';
                }elseif($value == 'revenuePerTransaction_1'){
                    $report_kpi_checked[$key] = 'revenuePerTransaction';
                }
            }
        }

        $this->set('report_kpi_checked',$report_kpi_checked);
        $this->set('site_target_key',$site_target_key);
        $this->set('int_current_day',$int_current_day);
        $this->set('last_day_number_of_month',$last_day_number_of_month);

        //End for dev

        //Default value =============================================
        //KPI Current value
        $data_current = $this->Cshishimai->gaEmptyData();
        //KPI Current value


        //KPI Predict value
        $data_predict = $this->Cshishimai->gaEmptyData();
        //End KPI Predict value

        //KPI Target value
        $data_target = $this->Cshishimai->gaEmptyData();
        //End KPI Target value

        //KPI Target rate
        $data_target_rate = $this->Cshishimai->gaEmptyData();

        //End KPI Target rate

        //KPI prev month compare
        $data_prevM_compare = $this->Cshishimai->gaEmptyData();

        //End KPI prev month compare

        //KPI prev year compare
        $data_prevY_compare = $this->Cshishimai->gaEmptyData();
        //End KPI prev year compare


        //KPI status box icon
        $data_status_box = $this->Cshishimai->gaEmptyData();
        //End KPI status box icon


        //End default value =========================================

        //Query & calculate form DB ======================================================================================================================================
        //Target KPI value
        $dataSsmKpi     = $this->SsmKpi->getDataTargetMonth($current_year,$current_month,$site_id);

        $data_target    = $dataSsmKpi['kpi_month'];

        $target_week    = $dataSsmKpi['kpi_week'];

        $target_total   = $dataSsmKpi['kpi_month'][$site_target_key];

        $this->set('target_total',$target_total);

        //Change KPI value
        $dataSsmKpiChange       = $this->SsmKpiChange->getDataChangeMonth($site_id,$current_year,$current_month);
        $change_data_in_month   = $dataSsmKpiChange['change_data_in_month'];
        $change_data_in_week    = $dataSsmKpiChange['change_data_in_week'];
        $change_data_in_day     = $dataSsmKpiChange['change_data_in_day'];

        //End Query & calculate form DB ======================================================================================================================================

        $GA = new GA($view_id);
        try{
            $result = $GA->test();
        }catch (Exception $e) {
            $msg = $e->getMessage();
            $msg_obj = json_decode($msg);
            if(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have any Google Analytics account."){
                $this->renderError('システムはこのウェブサイトのこのデータを取得出来ません');
                return;
            }elseif(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have sufficient permissions for this profile."){
                $this->renderError('閲覧権限の期限が切れたか、Google view idが異なっている可能性があります。担当者にお問い合わせください。');
                return;
            }else{
                $this->renderError('未定義のエラーです。');
                return;
            }
        }

        //Build date string============================
        $day_string = "";
        $arr_first_day_of_week = array();
        for($i = 1;$i<=$int_last_day_number_of_month;$i++)
        {
            if($i==1){
                $day_string .= "'{$current_month}月01日'";

                $arr_first_day_of_week[] = "'{$current_month}月01日'";
            }else{
                if($i == 8){
                    $day_string .= ",'{$current_month}月08日'";
                }elseif($i == 15){
                    $day_string .= ",'{$current_month}月15日'";
                }elseif($i == 22){
                    $day_string .= ",'{$current_month}月22日'";
                }elseif($i == 29){
                    $day_string .= ",'{$current_month}月29日'";
                }else{
                    if($i<10){
                        $day_string .= ",'{$current_month}月0{$i}日'";
                    }else{
                        $day_string .= ",'{$current_month}月{$i}日'";
                    }
                }

                if(($i-1)%7 == 0){
                    if($i<10){
                        $arr_first_day_of_week[] = "'{$current_month}月0{$i}日'";
                    }else{
                        $arr_first_day_of_week[] = "'{$current_month}月{$i}日'";
                    }
                }
            }
        }

        //rest day of last week string
        for($i = 1;$i <= $rest_day_of_last_week;$i++){
            $in_crr_month  = intval($current_month);
            if($in_crr_month < 9){
                $month_str = "0".($in_crr_month + 1);
            }elseif($in_crr_month == 12){
                $month_str = "01";
            }else{
                $month_str = ($in_crr_month + 1);
            }

            $day_string .= ",'{$month_str}月0{$i}日'";
            if($i ==$rest_day_of_last_week){
                $arr_first_day_of_week[] = "'{$month_str}月0{$i}日'";
            }
        }

        if(!empty($arr_first_day_of_week)){
            $list_start_day_of_week = implode(',',$arr_first_day_of_week);
        }else{
            $list_start_day_of_week = "";
        }

        $this->set('day_string',$day_string);
        $this->set('list_start_day_of_week',$list_start_day_of_week);
        //End build date string ==========================

        if(intval($current_day) != 1){
            //========================== > 1 ==================================
            //Begin top total left
            $report_begin_date  = "{$current_year}-{$current_month}-01";
            $report_end_date    = "{$current_year}-{$current_month}-".((intval($current_day) > 10) ? (intval($current_day) - 1) : "0".(intval($current_day) - 1));

            $total_data = $GA->getReport('home_total_'.$site_target_key.'_by_device_type',$report_begin_date,$report_end_date);
            $total_data_arr = $GA->arrResultMetricDimention($total_data);

            if(isset($total_data_arr['dimensions'][0])){
                foreach ($total_data_arr['dimensions'][0] as $key => $data_demention) {
                    if(isset($data_demention['value']) && $data_demention['value'] == 'desktop'){
                        $desktop_stt = $key;
                    }elseif(isset($data_demention['value']) && $data_demention['value'] == 'mobile'){
                        $mobile_stt = $key;
                    }elseif(isset($data_demention['value']) && $data_demention['value'] == 'tablet'){
                        $tablet_stt = $key;
                    }
                }

                if(isset($desktop_stt) && isset($total_data_arr['metrics'][0][0][$desktop_stt]['value'])){
                    $desktop_total = $total_data_arr['metrics'][0][0][$desktop_stt]['value'];
                }

                if(isset($mobile_stt) && isset($total_data_arr['metrics'][0][0][$mobile_stt]['value'])){
                    $mobile_total = $total_data_arr['metrics'][0][0][$mobile_stt]['value'];
                }

                if(isset($tablet_stt) && isset($total_data_arr['metrics'][0][0][$tablet_stt]['value'])){
                    $tablet_total = $total_data_arr['metrics'][0][0][$tablet_stt]['value'];
                }
            }

            $data_total_left_display = array();
            $total_day = intval(date('d'));
            if(isset($desktop_total)){
                $data_total_left_display['desktop_total'] = $desktop_total;
            }else{
                $data_total_left_display['desktop_total'] = 0;
            }

            if(isset($mobile_total)){
                $data_total_left_display['mobile_total'] = $mobile_total;
            }else{
                $data_total_left_display['mobile_total'] = 0;
            }

            if(isset($tablet_total)){
                $data_total_left_display['tablet_total'] = $tablet_total;
            }else{
                $data_total_left_display['tablet_total'] = 0;
            }

            $total_left_ga = $data_total_left_display['desktop_total'] + $data_total_left_display['mobile_total'] + $data_total_left_display['tablet_total'];

            $data_total_left_display['total_left_ga'] = $total_left_ga;

            if($change_data_in_month[$site_target_key] != 0){
                $total_left_after_check_change = $total_left_ga + $change_data_in_month[$site_target_key];
            }else{
                $total_left_after_check_change = $total_left_ga;
            }

            $data_total_left_display['total_left_after_check_change'] = $total_left_after_check_change;

            $this->set('data_total_left_display',$data_total_left_display);
            //End top total left

            //Top right
            $data_total_right_display = array();
            $target_all = (($total_left_after_check_change)/(intval($current_day) - 1)) * intval($last_day_number_of_month);
            $data_total_right_display['target_all'] = $target_all;

            $target_desktop = ($desktop_total/(intval($current_day) - 1))* intval($last_day_number_of_month);
            $data_total_right_display['target_desktop'] = $target_desktop;

            $target_tablet_phone = (($mobile_total + $tablet_total)/(intval($current_day) - 1))* intval($last_day_number_of_month);
            $data_total_right_display['target_tablet_phone'] = $target_tablet_phone;


            //Percent
            $data_total_right_display['target_all_percent'] = ($data_total_right_display['target_all'] * 100) / $target_total;
            $data_total_right_display['target_desktop_percent'] = ($data_total_right_display['target_desktop'] * 100) / ($target_desktop + $target_tablet_phone);
            $data_total_right_display['target_tablet_phone_percent'] = ($data_total_right_display['target_tablet_phone'] * 100) / ($target_desktop + $target_tablet_phone);

            $this->set('data_total_right_display',$data_total_right_display);
            //End Top right

            //Chart
            //Get real data from GA && calculate with change data
            $chart_ga_data = $GA->getReport('home_chart_'.$site_target_key.'_data',$report_begin_date,$report_end_date);

            $chart_ga_data_arr = $GA->arrResultMetricDimention($chart_ga_data);
            $actual_value           = array();
            $actual_value_chart_2   = array();
            $prev = 0;

            $total_current_week = 0;
            $start_end_day_of_crrw = $this->Cshishimai->getStartEnDateOfCurrentWeek();

            //Set default change as base value
            for($i=1;$i<=$int_last_day_number_of_month;$i++)
            {
                $actual_value[$i] = array(
                    'date'  =>(($i>9)? $i : '0'.$i),
                    'value' =>0
                );
            }

            foreach ($change_data_in_week as $week=>$week_change) {
                $avg_value = $week_change[$site_target_key]/7;
                $get_start_end_of_week = $this->Cshishimai->getStartEnDateOfWeek($current_year,$current_month,$week);
                for($i = $get_start_end_of_week['start_day'];$i <= $get_start_end_of_week['end_day'] ;$i++){
                    $actual_value[$i]['value'] = $avg_value;
                }
            }

            //Sum base value with GA value
            $last_day_show_total_chart = 0;
            foreach ($chart_ga_data_arr['dimensions'][0] as $key => $date_data) {
                $day_number_ga      = substr($date_data['value'],6);
                $int_day_number_ga  = intval($day_number_ga);

                $ga_value = $chart_ga_data_arr['metrics'][0][0][$key]['value'];

                $actual_value[$int_day_number_ga]['value'] = $actual_value[$int_day_number_ga]['value'] + $ga_value;
                if($actual_value[$int_day_number_ga]['value'] > 0){
                    $last_day_show_total_chart = $int_day_number_ga;
                }
            }

            $last_day_show_total_chart = ($last_day_show_total_chart > ($current_day-1)) ? $last_day_show_total_chart : ($current_day-1);

            for($i = 1; $i <= $last_day_show_total_chart; $i++){
                $prev = $prev + $actual_value[$i]['value'];
                $actual_value_chart_2[$i] = array(
                    'date'=>(($i >9)? $i : '0'.$i),
                    'value'=>$prev
                );
            }

            for($i = 1;$i <= $last_day_show_total_chart;$i++){
                if(!isset($actual_value_chart_2[$i])){
                    $actual_value_chart_2[$i]['value'] = 0;
                }
            }

            //End refill if actual_value_chart_2 empty
            $actual_value_chart_string   = "";
            $actual_value_chart_2_string = "";
            //Actual value
            for($i=1;$i<=$int_last_day_number_of_month;$i++)
            {
                //Chart 1
                if(isset($actual_value[$i]['value'])){
                    if($actual_value[$i]['value'] >= 0){
                        $chart_value = $actual_value[$i]['value'];
                    }else{
                        $chart_value = 0;
                    }

                    if($i==1){
                        $actual_value_chart_string .= $chart_value;
                    }else{
                        $actual_value_chart_string .= ",".$chart_value;
                    }
                }else{
                    if($i==1){
                        $actual_value_chart_string .= '0';
                    }else{
                        $actual_value_chart_string .= ",0";
                    }
                }

                //Chart 2
                if(isset($actual_value_chart_2[$i]['value'])){
                    if($actual_value_chart_2[$i]['value'] >= 0){
                        $chart_value = $actual_value_chart_2[$i]['value'];
                    }else{
                        $chart_value = 0;
                    }

                    if($i==1){
                        $actual_value_chart_2_string .= $chart_value;
                    }else{
                        $actual_value_chart_2_string .= ",".$chart_value;
                    }
                }else{
                    if($i==1){
                        $actual_value_chart_2_string .= '0';
                    }else{
                        $actual_value_chart_2_string .= ",0";
                    }
                }
            }

            for($i = 1;$i <= $rest_day_of_last_week;$i++){
                $actual_value_chart_string .= ",0";
                $actual_value_chart_2_string .= ",0";
            }

            $this->set('actual_value_chart_string',$actual_value_chart_string);
            $this->set('actual_value_chart_2_string',$actual_value_chart_2_string);

            $avg_target_transactionRevenue  = 0;
            $avg_week = array();
            if($target_total > 0){
                $avg_target_transactionRevenue = $target_total/$last_day_number_of_month;
                if(count($target_week)){
                    foreach ($target_week as $w_number => $item_week) {
                        $last_of_w = $w_number * 7;
                        if($last_of_w > $int_last_day_number_of_month){
                            $numDayLastWeek = $int_last_day_number_of_month - 28;
                            $avg_week[$w_number] = (isset($item_week[$site_target_key]) && $item_week[$site_target_key] > 0 )? ($item_week[$site_target_key] / ($numDayLastWeek)) : 0 ;
                        }else{
                            $avg_week[$w_number] = (isset($item_week[$site_target_key]) && $item_week[$site_target_key] > 0 )? ($item_week[$site_target_key] / 7) : 0 ;
                        }
                    }
                }
            }

            $target_value = array();
            for ($i = 1 ; $i <= $int_last_day_number_of_month ; $i++) {
                if($i > 0 && $i < 8){
                    $w = 1;
                }elseif($i > 7 && $i < 15){
                    $w = 2;
                }elseif($i > 14 && $i < 22){
                    $w = 3;
                }elseif($i > 21 && $i < 29){
                    $w = 4;
                }else{
                    $w = 5;
                }

                if(isset($avg_week[$w])){
                    $avg_target_transactionRevenue = $avg_week[$w];
                }else{
                    $avg_target_transactionRevenue = 0;
                }

                $target_value[$i]= array(
                    'date' =>'',
                    'value'=>$avg_target_transactionRevenue
                );
            }
            //End target value

            //Target value chart 2
            $target_value_chart_2 = $target_value;
            $total_2= 0;
            for ($i = 1 ; $i <= $last_day_number_of_month ; $i++) {
                $total_2 = $total_2 + $target_value[$i]['value'];
                $target_value_chart_2[$i]['value'] = $total_2;
            }

            $target_value_chart_string   = "";
            $target_value_chart_2_string = "";
            //Target Value
            for($i=1;$i<=$int_last_day_number_of_month;$i++)
            {
                //Chart 1
                if(isset($target_value[$i]['value'])){

                    if($target_value[$i]['value'] >= 0){
                        $chart_value = $target_value[$i]['value'];
                    }else{
                        $chart_value = 0;
                    }

                    if($i==1){
                        $target_value_chart_string .= $chart_value;
                    }else{
                        $target_value_chart_string .= ",".$chart_value;
                    }
                }else{
                    if($i==1){
                        $target_value_chart_string .= '0';
                    }else{
                        $target_value_chart_string .= ",0";
                    }
                }

                //Chart 2
                if(isset($target_value_chart_2[$i]['value'])){

                    if($target_value_chart_2[$i]['value'] >= 0){
                        $chart_value = $target_value_chart_2[$i]['value'];
                    }else{
                        $chart_value = 0;
                    }

                    if($i==1){
                        $target_value_chart_2_string .= $chart_value;
                    }else{
                        $target_value_chart_2_string .= ",".$chart_value;
                    }
                }else{
                    if($i==1){
                        $target_value_chart_2_string .= '0';
                    }else{
                        $target_value_chart_2_string .= ",0";
                    }
                }
            }

            $this->set('target_value_chart_string',$target_value_chart_string);
            $this->set('target_value_chart_2_string',$target_value_chart_2_string);
            //End target value chart 2

            //End chart

            //KPI current value
            $data_ga_crr_month = $this->SsmKpiGaMonth->getGAMonthData($GA,$site_id,$current_year,$current_month);
            $data_ga = $data_ga_crr_month['kpis'];

            $this->set('data_ga_no_change',$data_ga);
            $data_change_in_month = $this->SsmKpiChange->getDataChangeMonth($site_id,$current_year,$current_month);

            $data_current_with_change = $this->Cshishimai->gaTotal($data_ga,$data_change_in_month['change_data_in_month']);
            $data_current_with_change = $this->Cshishimai->reCalculateField($data_current_with_change);

            $this->set('data',$data_current_with_change);
            //KPI Current value

            //KPI Predict value
            $data_predict = $this->Cshishimai->gaPredict($data_current_with_change,($int_current_day - 1),$last_day_number_of_month);
            $data_predict = $this->Cshishimai->reCalculateField($data_predict);

            $this->set('data_predict',$data_predict);
            //End KPI Predict value

            //KPI Target value
            $this->set('data_target',$data_target);
            //End KPI Target value

            //KPI Target rate
            $data_target_rate = $this->Cshishimai->gaRatio($data_predict,$data_target,1);

            $this->set('data_target_rate',$data_target_rate);
            //End KPI Target rate

            //KPI prev month compare ========

            if(intval($current_month) == 1){
                $year_rp    = $current_year - 1;
                $month_rp   = 12;
            }else{
                $year_rp    = $current_year;
                $month_rp   = $current_month - 1;
            }

            $ga_data_prev_month         = $this->SsmKpiGaMonth->getGAMonthData($GA,$site_id,$year_rp,$month_rp);
            $ga_data_change_prev_month  = $this->SsmKpiChange->getDataChangeMonth($site_id,$year_rp,$month_rp);
            $total_actual_prev_month    = $this->Cshishimai->gaTotal($ga_data_prev_month['kpis'],$ga_data_change_prev_month['change_data_in_month']);
            $total_actual_prev_month    = $this->Cshishimai->reCalculateField($total_actual_prev_month);

            $total_ratio_prevMonth_month = $this->Cshishimai->gaRatio($data_predict,$total_actual_prev_month);
            $this->set('data_prevM_compare',$total_ratio_prevMonth_month);

            //KPI prev year compare ===========

            $ga_data_prev_YearM         = $this->SsmKpiGaMonth->getGAMonthData($GA,$site_id,($current_year-1),$current_month);
            $ga_data_change_prev_YearM  = $this->SsmKpiChange->getDataChangeMonth($site_id,($current_year-1),$current_month);
            $total_actual_prev_YearM    = $this->Cshishimai->reCalculateField($this->Cshishimai->gaTotal($ga_data_prev_YearM['kpis'],$ga_data_change_prev_YearM['change_data_in_month']));

            $total_ratio_prevYearM_month = $this->Cshishimai->gaRatio($data_predict,$total_actual_prev_YearM);
            $this->set('data_prevY_compare',$total_ratio_prevYearM_month);

            //End KPI prev year compare =====

            //KPI data_status_box ===========
            $data_status_box = $this->Cshishimai->iconStatus($data_predict,$data_target);

            $this->set('data_status_box',$data_status_box);
            //KPI data_status_box ===========

            $start_date_display     = intval($current_month)."/1";
            $current_date_display   = intval($current_month)."/".($current_day-1);
            $this->set('start_date_display',$start_date_display);
            $this->set('current_date_display',$current_date_display);
            $this->set('last_day_of_month',intval($current_month)."/".$last_day_number_of_month);

            //========================== > 1 ==================================
        }else{
            //start date = 1
            $this->set('data_total_left_display',array('desktop_total'=>0,'mobile_total'=>0,'tablet_total'=>0,'total_left_ga'=>0,'total_left_after_check_change'=>0));

            $this->set('data_total_right_display',array('target_all'=>0,'target_desktop'=>0,'target_tablet_phone'=>0,'target_all_percent'=>0,'target_desktop_percent'=>0,'target_tablet_phone_percent'=>0));

            $actual_value_chart_string = "";
            $actual_value_chart_2_string = "";
            for($i=1;$i<=36;$i++)
            {
                if($i==1){
                    $actual_value_chart_string .= '0';
                    $actual_value_chart_2_string .= '0';
                }else{
                    $actual_value_chart_string .= ",0";
                    $actual_value_chart_2_string .= ",0";
                }
            }

            $this->set('actual_value_chart_string',$actual_value_chart_string);
            $this->set('actual_value_chart_2_string',$actual_value_chart_2_string);

            //Target value chart 2
            $avg_target_transactionRevenue  = 0;

            $avg_week = array();
            if($target_total > 0){
                $avg_target_transactionRevenue = $target_total/$last_day_number_of_month;
                if(count($target_week)){
                    foreach ($target_week as $w_number => $item_week) {
                        $last_of_w = $w_number * 7;
                        if($last_of_w > $int_last_day_number_of_month){
                            $numDayLastWeek = $int_last_day_number_of_month - 28;
                            $avg_week[$w_number] = (isset($item_week[$site_target_key]) && $item_week[$site_target_key] > 0 )? ($item_week[$site_target_key] / ($numDayLastWeek)) : 0 ;
                        }else{
                            $avg_week[$w_number] = (isset($item_week[$site_target_key]) && $item_week[$site_target_key] > 0 )? ($item_week[$site_target_key] / 7) : 0 ;
                        }
                    }
                }
            }

            $target_value = array();
            for ($i = 1 ; $i <= $int_last_day_number_of_month ; $i++) {
                if($i > 0 && $i < 8){
                    $w = 1;
                }elseif($i > 7 && $i < 15){
                    $w = 2;
                }elseif($i > 14 && $i < 22){
                    $w = 3;
                }elseif($i > 21 && $i < 29){
                    $w = 4;
                }else{
                    $w = 5;
                }

                if(isset($avg_week[$w])){
                    $avg_target_transactionRevenue = $avg_week[$w];
                }else{
                    $avg_target_transactionRevenue = 0;
                }

                $target_value[$i]= array(
                    'date' =>'',
                    'value'=>$avg_target_transactionRevenue
                );
            }

            $target_value_chart_2 = $target_value;
            $total_2= 0;
            for ($i = 1 ; $i <= $last_day_number_of_month ; $i++) {
                $total_2 = $total_2 + $target_value[$i]['value'];
                $target_value_chart_2[$i]['value'] = $total_2;
            }

            $target_value_chart_string   = "";
            $target_value_chart_2_string = "";
            //Target Value
            for($i=1;$i<=$int_last_day_number_of_month;$i++)
            {
                //Chart 1
                if(isset($target_value[$i]['value'])){

                    if($target_value[$i]['value'] >= 0){
                        $chart_value = $target_value[$i]['value'];
                    }else{
                        $chart_value = 0;
                    }

                    if($i==1){
                        $target_value_chart_string .= $chart_value;
                    }else{
                        $target_value_chart_string .= ",".$chart_value;
                    }
                }else{
                    if($i==1){
                        $target_value_chart_string .= '0';
                    }else{
                        $target_value_chart_string .= ",0";
                    }
                }

                //Chart 2
                if(isset($target_value_chart_2[$i]['value'])){

                    if($target_value_chart_2[$i]['value'] >= 0){
                        $chart_value = $target_value_chart_2[$i]['value'];
                    }else{
                        $chart_value = 0;
                    }

                    if($i==1){
                        $target_value_chart_2_string .= $chart_value;
                    }else{
                        $target_value_chart_2_string .= ",".$chart_value;
                    }
                }else{
                    if($i==1){
                        $target_value_chart_2_string .= '0';
                    }else{
                        $target_value_chart_2_string .= ",0";
                    }
                }
            }

            $this->set('target_value_chart_string',$target_value_chart_string);
            $this->set('target_value_chart_2_string',$target_value_chart_2_string);
            //End target value chart 2

            //KPI Current value
            $this->set('data',$data_current);
            //KPI Current value

            //KPI Predict value
            $this->set('data_predict',$data_predict);
            //End KPI Predict value

            //KPI Target value
            $this->set('data_target',$data_target);
            //End KPI Target value

            //KPI Target rate
            $this->set('data_target_rate',$data_target_rate);
            //End KPI Target rate

            //KPI prev month compare
            $this->set('data_prevM_compare',$data_prevM_compare);
            //End KPI prev month compare

            //KPI prev year compare
            $this->set('data_prevY_compare',$data_prevY_compare);
            //End KPI prev year compare

            $data_status_box = $this->Cshishimai->iconStatus($data_predict,$data_target);
            $this->set('data_status_box',$data_status_box);

            $start_date_display     = $current_month."/01";
            $current_date_display   = $current_month."/".$last_day_number_of_month;
            $this->set('start_date_display',$start_date_display);
            $this->set('current_date_display',$current_date_display);
            $this->set('last_day_of_month',$current_month."/".$last_day_number_of_month);
        }


        //============================== advice table  ==============================
        $range_config = !empty($siteInfo['report_range']) ? $siteInfo['report_range'] : array(
            '1'=>'1_6',
            '2'=>'7_12');
        //Set range display on popup
        $range_1 = explode('_',$range_config['1']);
        $range_2 = explode('_',$range_config['2']);

        $int_current_month  = intval($current_month);

        $start_range_year = $current_year;//Default start range year

        //Get the input data and defined var ==============================================

        //Set range report
        $range_1 = explode('_',$range_config[1]);
        $range_2 = explode('_',$range_config[2]);

        $list_month_range_1 = $this->Cshishimai->getListmonth($range_config[1]);

        if(in_array($int_current_month,$list_month_range_1)){
            $range_report = $range_config[1];
            //Update start range year
            if($range_1[1] < $range_1[0]){
                $start_range_year--;
            }

        }else{
            $range_report = $range_config[2];
            if($range_2[1] < $range_2[0]){
                $start_range_year--;
            }
        }

        $range_data = $this->SsmGA->setRangeData($range_report,$start_range_year,true);

        //Get Month Note
        $arr_con = array();
        $arr_month_adv = array();
        foreach ($range_data as $range_item) {
            $arr_con[] = array(
                'SsmKpiNote.year'   =>$range_item['y'],
                'SsmKpiNote.month'  =>$range_item['m'],
                'SsmKpiNote.site_id'=>$site_id
            );
            $arr_month_adv[] = $range_item['m'];
        }

        $kpiNote = $this->SsmKpiNote->find('all',array(
            'conditions'=>array(
                'OR'=>$arr_con
            )
        ));

        $advice_note    = array();
        if(!empty($kpiNote)){
            foreach ($kpiNote as $note) {
                $advice_note[$note['SsmKpiNote']['month']] = $note['SsmKpiNote'];
            }
        }

        //And setup advice field
        $advice_note_keys = array('note'=>'今月の施策');
        $advice_note_keys = array_merge($advice_note_keys,Configure::read('advice_note'));

        $arr_advKey_map_advData = array();
        foreach ($advice_note_keys as $note_key => $note_title) {

            if(in_array($note_key,array('advice_note_8','advice_note_10'))){
                if(
                    intval($advice_note[$arr_month_adv[0]][$note_key]) > 0 ||
                    intval($advice_note[$arr_month_adv[1]][$note_key]) > 0 ||
                    intval($advice_note[$arr_month_adv[2]][$note_key]) > 0 ||
                    intval($advice_note[$arr_month_adv[3]][$note_key]) > 0 ||
                    intval($advice_note[$arr_month_adv[4]][$note_key]) > 0 ||
                    intval($advice_note[$arr_month_adv[5]][$note_key]) > 0
                ){
                    $arr_advKey_map_advData[$note_key] = array(
                        $arr_month_adv[0] => "¥".number_format(intval($advice_note[$arr_month_adv[0]][$note_key])),
                        $arr_month_adv[1] => "¥".number_format(intval($advice_note[$arr_month_adv[1]][$note_key])),
                        $arr_month_adv[2] => "¥".number_format(intval($advice_note[$arr_month_adv[2]][$note_key])),
                        $arr_month_adv[3] => "¥".number_format(intval($advice_note[$arr_month_adv[3]][$note_key])),
                        $arr_month_adv[4] => "¥".number_format(intval($advice_note[$arr_month_adv[4]][$note_key])),
                        $arr_month_adv[5] => "¥".number_format(intval($advice_note[$arr_month_adv[5]][$note_key])),
                    );
                }

            }else{
                if(
                    $advice_note[$arr_month_adv[0]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[1]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[2]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[3]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[4]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[5]][$note_key] != ""
                ){
                    $arr_advKey_map_advData[$note_key] = array(
                        $arr_month_adv[0] => $advice_note[$arr_month_adv[0]][$note_key],
                        $arr_month_adv[1] => $advice_note[$arr_month_adv[1]][$note_key],
                        $arr_month_adv[2] => $advice_note[$arr_month_adv[2]][$note_key],
                        $arr_month_adv[3] => $advice_note[$arr_month_adv[3]][$note_key],
                        $arr_month_adv[4] => $advice_note[$arr_month_adv[4]][$note_key],
                        $arr_month_adv[5] => $advice_note[$arr_month_adv[5]][$note_key]
                    );
                }
            }

            

            
            /*if(in_array($note_key,array('advice_note_1','advice_note_13','advice_note_14','advice_note_15'))){
                if(
                    $advice_note[$arr_month_adv[0]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[1]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[2]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[3]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[4]][$note_key] != "" ||
                    $advice_note[$arr_month_adv[5]][$note_key] != ""
                ){
                    $arr_advKey_map_advData[$note_key] = array(
                        $arr_month_adv[0] => $advice_note[$arr_month_adv[0]][$note_key],
                        $arr_month_adv[1] => $advice_note[$arr_month_adv[1]][$note_key],
                        $arr_month_adv[2] => $advice_note[$arr_month_adv[2]][$note_key],
                        $arr_month_adv[3] => $advice_note[$arr_month_adv[3]][$note_key],
                        $arr_month_adv[4] => $advice_note[$arr_month_adv[4]][$note_key],
                        $arr_month_adv[5] => $advice_note[$arr_month_adv[5]][$note_key]
                    );
                }
            }else{
                $arr_advKey_map_advData[$note_key] = array(
                    $arr_month_adv[0] => $advice_note[$arr_month_adv[0]][$note_key],
                    $arr_month_adv[1] => $advice_note[$arr_month_adv[1]][$note_key],
                    $arr_month_adv[2] => $advice_note[$arr_month_adv[2]][$note_key],
                    $arr_month_adv[3] => $advice_note[$arr_month_adv[3]][$note_key],
                    $arr_month_adv[4] => $advice_note[$arr_month_adv[4]][$note_key],
                    $arr_month_adv[5] => $advice_note[$arr_month_adv[5]][$note_key]
                );
            }
            */
        }

        //echo "<pre>";
        //print_r($arr_advKey_map_advData);exit;

        //Get custom advice title
        $db_advice_key = $this->SsmAdviceKey->find('all',array(
            'conditions'=>array(
                'year'          =>$range_data[0]['y'],
                'start_month'   =>$range_data[0]['m'],
                'site_id'       =>$this->site_id
            )
        ));

        //Overwrite default title
        if($db_advice_key){
            foreach ($db_advice_key as $adv_key_row) {
                if(in_array($adv_key_row['SsmAdviceKey']['advkey'], array_keys($advice_note_keys))){
                    $advice_note_keys[$adv_key_row['SsmAdviceKey']['advkey']] = $adv_key_row['SsmAdviceKey']['advvalue'];
                }
            }
        }

        $this->set('advice_range_data',$range_data);
        $this->set('advice_note_keys',$advice_note_keys);
        $this->set('arr_advKey_map_advData',$arr_advKey_map_advData);
        //============================== advice table =======================================


    }

    /**
     * Active site and redirect back referer location
     * @return void
     */
    public function activesite(){
        if(isset($this->request->query['site_id'])){
            $site_id = $this->request->query['site_id'];
        }elseif(isset($this->request->params['named']['site_id'])){
            $site_id = $this->request->params['named']['site_id'];
        }else{
            $site_id = $listSiteID[0];
        }
        $this->SsmAuth->setActiveSite($site_id);

        if (strpos($this->referer(),'OttKpi') !== false) {
            $this->redirect('/OttKpi?site_id='.$site_id);
        }elseif (strpos($this->referer(),'OttHome?') !== false) {
            $this->redirect('/OttHome?site_id='.$site_id);
        }elseif (strpos($this->referer(),'OttReport?') !== false) {
            $this->redirect('/OttReport?site_id='.$site_id);
        }elseif (strpos($this->referer(),'OttReport/edit_week') !== false || strpos($this->referer(),'OttReport/see_week') !== false) {
            $this->redirect('/OttReport?site_id='.$site_id);
        }else{
            return $this->redirect($this->referer());
        }
    }
}
