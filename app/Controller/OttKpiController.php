<?php
include_once(__DIR__."/../Vendor/Google/GA.php");
App::uses('SsmAdminController', 'Controller');
/**
 * Dashboards Controller
 *
 * @property Dashboard $Dashboard
 * @property PaginatorComponent $Paginator
 */
class OttKpiController extends SsmAdminController {

    public $helpers     = array('Shishimai','Html');
    public $components  = array('Cshishimai','Session','SsmAuth','SsmGA');
    public $uses        = array('SsmKpi','SsmKpiGaWeek','SsmKpiGaMonth','SsmKpiChange','SsmSite','SsmUser','SsmKpiNote','SsmAdviceKey');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function beforeRender(){
        parent::beforeRender();
    }

    public function index(){
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
        $this->set('siteInfo',$siteInfo);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        if($siteInfo['ga_ecommerce'] != 1){
            $kpis_list = Configure::read('kpis_1');
            $cv_key = 'transactions_1';
        }else{
             $kpis_list = Configure::read('kpis');
            $cv_key = 'transactions';
        }

        $this->set('kpis_list',$kpis_list);

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================
        $this->set('loginUser',$loginUser);


        //Range config
        $range_config = !empty($siteInfo['report_range']) ? $siteInfo['report_range'] : array(
            '1'=>'1_6',
            '2'=>'7_12');
        //Set range display on popup
        $range_1 = explode('_',$range_config['1']);
        $range_2 = explode('_',$range_config['2']);
        $this->set('range_config',array(
            'number_1'=>$range_1[0] > 0 ? $range_1[0] : 1,
            'number_2'=>$range_1[1] > 0 ? $range_1[1] : 6,
            'number_3'=>$range_2[0] > 0 ? $range_2[0] : 7,
            'number_4'=>$range_2[1] > 0 ? $range_2[1] : 12,
        ));

        //End set range display on popup

        //Report kpi checked
        $report_kpi_checked = $siteInfo['report_kpi'];
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
        //End report kpi checked

        $current_year  = date('Y');
        $current_month = date('m');
        $current_day   = date('d');
        $int_current_month  = intval($current_month);
        $int_current_day    = intval($current_day);
        $date = new DateTime('now');
        $date->modify('last day of this month');
        $last_day_number_of_month       = $date->format('d');
        $int_last_day_number_of_month   = intval($last_day_number_of_month);
        $rest_day_of_last_week          = 36 - $int_last_day_number_of_month;

        //Get the input data and defined var ==============================================
        //Display image manage user
        $manage_user = $this->SsmUser->getUserInfo($siteInfo['site_manage_user']);
        $this->set('manage_user',$manage_user);
        $upload_dir = Configure::read('upload_dir');
        $this->set('upload_dir',$upload_dir);
        $this->set('avatar_url',$upload_dir['user'].'/'.$manage_user['avatar']);

        $show_prospective = false;//set default show_prospective
        //Set report view
        if(isset($this->request->query['show_list_month']) && $this->request->query['show_list_month'] == 1){
            $show_list_month    = 1;
            $report_view        = 'month';
        }else{
            $show_list_month    = 0;
            $report_view        = 'week';
        }

        //Set range report
        $range_1 = explode('_',$range_config[1]);
        $range_2 = explode('_',$range_config[2]);
        if(isset($this->request->query['range_report']) && $this->request->query['range_report'] !=""){
            $range_report = $this->request->query['range_report'];
        }else{
            $list_month_range_1 = $this->Cshishimai->getListmonth($range_config[1]);
            if(in_array($int_current_month,$list_month_range_1)){
                $range_report = $range_config[1];
            }else{
                $range_report = $range_config[2];
            }
        }

        $range_report_ex = explode('_',$range_report);
        //Set year range of report
        if(isset($this->request->query['year_report']) && $this->request->query['year_report'] !=""){
            $year_report = intval($this->request->query['year_report']);
        }else{
            if($range_report_ex[1] < $range_report_ex[0]){
                $year_report = $current_year - 1;
            }else{
                $year_report = $current_year;
            }
        }
        $this->set('year_report',$year_report);

        //Set type report (total or single week/month)
        if(isset($this->request->query['type_report']) && in_array($this->request->query['type_report'],array('by_day','by_total'))){
            $type_report = $this->request->query['type_report'];
            if($type_report == 'by_day'){
                $show_total = 0;
            }else{
                $show_total = 1;
            }
        }else{
            $type_report = 'by_day';
            $show_total = 0;
        }


        $this->set('type_report',$type_report);

        //Set month report
        if(isset($this->request->query['month_report']) && $this->request->query['month_report'] != ''){
            $month_report = $this->request->query['month_report'];

            if(!$this->Cshishimai->checkMonthInRange($month_report,$range_report)){
                $this->renderError('週レポートは間違います。再度確認してください。');
                return;
            }

        }else{

            if($range_report_ex[1] < $range_report_ex[0]){
                if($current_year == $year_report + 1){
                    if($this->Cshishimai->checkMonthInRange($current_month,$range_report)){
                        $show_prospective = true;
                        $month_report = $current_month;
                    }else{
                        $month_report = $range_report_ex[0];
                    }
                }else{
                    $month_report = $range_report_ex[0];
                }
            }else{
                if($year_report == $current_year){
                    if($this->Cshishimai->checkMonthInRange($current_month,$range_report)){
                        $show_prospective = true;
                        $month_report = $current_month;
                    }else{
                        $month_report = $range_report_ex[0];
                    }
                }else{
                    $month_report = $range_report_ex[0];
                }
            }
        }

        if($month_report < $range_report_ex[0]){
            $year_update_db = $year_report + 1;
        }else{
            $year_update_db = $year_report;
        }
        $this->set('year_update_db',$year_update_db);

        $this->set('month_report',$month_report);

        //End define var =================================================================

        //Current text display
        $text_report_date_range = $year_report."年半期（".$range_report_ex[0]."月〜".$range_report_ex[1]."月）";
        $this->set('text_report_date_range',$text_report_date_range);

        $base_link = "/OttKpi?";
        //Prev link
        $arr_prev_param = array();
        $arr_prev_param = $this->Cshishimai->getPrevRangeANDYear($range_report,$year_report);

        $arr_prev_param['type_report']  = $type_report;
        $arr_prev_param['show_list_month']  = $show_list_month;
        $prev_link_report = $base_link.http_build_query($arr_prev_param);
        $this->set('prev_link_report',$prev_link_report);
        //Next link
        $arr_next_param = array();
        $arr_next_param = $this->Cshishimai->getNextRangeANDYear($range_report,$year_report);

        $arr_next_param['type_report']  = $type_report;
        $arr_next_param['show_list_month']  = $show_list_month;
        $next_link_report = $base_link.http_build_query($arr_next_param);
        $this->set('next_link_report',$next_link_report);

        //Url Current
        $arr_current_param = array(
            'site_id'       => $site_id,
            'range_report'  => $range_report,
            'year_report'   => $year_report,
            'month_report'  => $month_report,
            'type_report'   => $type_report,
            'show_list_month'=>$show_list_month
        );
        $current_link_report = $base_link.http_build_query($arr_current_param);
        $this->set('current_link_report',$current_link_report);
        //User infomation

        //Init GA and check valid GA obj
        $status_ga = $this->SsmGA->initCom($site_id,$view_id);
        if($status_ga == "ERROR_GA_PERMISSION"){
            $this->renderError('システムはこのウェブサイトのこのデータを取得出来ません');
            return;
        }elseif($status_ga == "ERROR_GA_KEY"){
            $this->renderError('閲覧権限の期限が切れたか、Google view idが異なっている可能性があります。担当者にお問い合わせください。');
            return;
        }elseif($status_ga == "ERROR_GA_UNKNOW"){
            $this->renderError('未定義のエラーです。');
            return;
        }



        //Calculate KPI
        $this->SsmGA->getReport($year_report,$month_report,$range_report,$report_view);
        $this->set('show_list_month',$show_list_month);

        if($show_total){
            $this->set('show_total',true);
            $this->set('count_week_report',$this->SsmGA->count_week);
            $this->set('range_data',$this->SsmGA->range_data);
            $this->set('week_title',$this->SsmGA->week_title);

            $this->set('target_week',$this->SsmGA->total_target);
            $this->set('actual_value',$this->SsmGA->total_actual);
            $this->set('ratio_with_target',$this->SsmGA->total_ratio_actual_target);
            $this->set('ratio_with_prev_week',$this->SsmGA->total_ratio_actual_prev);
            $this->set('ratio_with_prev_yearW',$this->SsmGA->total_ratio_actual_prevY);

            $this->set('actual_prospective_value',$this->SsmGA->total_prospective);
            $this->set('ratio_prospective_with_target',$this->SsmGA->total_ratio_prospective_target);
            $this->set('ratio_prospective_with_prev_week',$this->SsmGA->total_ratio_prospective_prev);
            $this->set('ratio_prospective_with_prev_yearW',$this->SsmGA->total_ratio_prospective_prevY);

            $show_advice = false;
            //set varaible for send info to Cw
            $target_week = $this->SsmGA->total_target;
            $actual_value = $this->SsmGA->total_actual;
            $ratio_with_target = $this->SsmGA->total_ratio_actual_target;
            $ratio_with_prev_week = $this->SsmGA->total_ratio_actual_prev;
            $ratio_with_prev_yearW = $this->SsmGA->total_ratio_actual_prevY;

            $actual_prospective_value = $this->SsmGA->total_prospective;
            $ratio_prospective_with_target = $this->SsmGA->total_ratio_prospective_target;
            $ratio_prospective_with_prev_week = $this->SsmGA->total_ratio_prospective_prev;
            $ratio_prospective_with_prev_yearW = $this->SsmGA->total_ratio_prospective_prevY;

        }else{

            $this->set('show_total',false);
            $this->set('count_week_report',$this->SsmGA->count_week);
            $this->set('range_data',$this->SsmGA->range_data);
            $this->set('week_title',$this->SsmGA->week_title);
            $this->set('target_week',$this->SsmGA->target);
            $this->set('actual_value',$this->SsmGA->actual);
            $this->set('ratio_with_target',$this->SsmGA->ratio_actual_target);
            $this->set('ratio_with_prev_week',$this->SsmGA->ratio_actual_prev);
            $this->set('ratio_with_prev_yearW',$this->SsmGA->ratio_actual_prevY);

            $this->set('actual_prospective_value',$this->SsmGA->prospective);
            $this->set('ratio_prospective_with_target',$this->SsmGA->ratio_prospective_target);
            $this->set('ratio_prospective_with_prev_week',$this->SsmGA->ratio_prospective_prev);
            $this->set('ratio_prospective_with_prev_yearW',$this->SsmGA->ratio_prospective_prevY);
            $show_advice = true;
            //set varaible for send info to Cw
            $target_week = $this->SsmGA->target;
            $actual_value = $this->SsmGA->actual;
            $ratio_with_target = $this->SsmGA->ratio_actual_target;
            $ratio_with_prev_week = $this->SsmGA->ratio_actual_prev;
            $ratio_with_prev_yearW = $this->SsmGA->ratio_actual_prevY;

            $actual_prospective_value = $this->SsmGA->prospective;
            $ratio_prospective_with_target = $this->SsmGA->ratio_prospective_target;
            $ratio_prospective_with_prev_week = $this->SsmGA->ratio_prospective_prev;
            $ratio_prospective_with_prev_yearW = $this->SsmGA->ratio_prospective_prevY;
        }

        //Show total column
        $this->set('total_target_month',$this->SsmGA->col_target);
        $this->set('total_actual_month',$this->SsmGA->col_actual);
        $this->set('total_actual_prospective_month',$this->SsmGA->col_prospective);

        $this->set('total_ratio_target_month',$this->SsmGA->col_ratio_actual_target);
        $this->set('total_ratio_prevMonth_month',$this->SsmGA->col_ratio_actual_prev);
        $this->set('total_ratio_prevYearM_month',$this->SsmGA->col_ratio_actual_prevY);

        $this->set('total_ratio_prospective_target_month',$this->SsmGA->col_ratio_prospective_target);
        $this->set('total_ratio_prospective_prevMonth_month',$this->SsmGA->col_ratio_prospective_prev);
        $this->set('total_ratio_prospective_prevYearM_month',$this->SsmGA->col_ratio_prospective_prevY);

        $this->set('show_advice',$show_advice);



        //Show prospective
        $show_prospective = $this->SsmGA->showProspective();
        $this->set('show_prospective',$show_prospective);

        $show_note_month = true;
        $this->set('show_note_month',$show_note_month);

        //Check auto show advice onload
        if(isset($this->request->query['auto_show_advice']) && $this->request->query['auto_show_advice'] !=""){
            $this->set('auto_show_advice',1);
            $this->request->query['auto_show_advice'] = 0;
        }else{
            $this->set('auto_show_advice',0);
        }

        if($show_list_month){
            //Get Month Note
            $arr_con = array();
            foreach ($this->SsmGA->range_data as $range_item) {
                $arr_con[] = array(
                    'SsmKpiNote.year'   =>$range_item['y'],
                    'SsmKpiNote.month'  =>$range_item['m'],
                    'SsmKpiNote.site_id'=>$site_id
                );
            }

            $kpiNote = $this->SsmKpiNote->find('all',array(
                'conditions'=>array(
                    'OR'=>$arr_con
                )
            ));

            $month_note     = array();
            $advice_note    = array();
            if(!empty($kpiNote)){
                foreach ($kpiNote as $note) {
                    $month_note[$note['SsmKpiNote']['month']] = $note['SsmKpiNote']['note'];
                    $advice_note[$note['SsmKpiNote']['month']] = $note['SsmKpiNote'];
                }
            }

            $this->set('month_note',$month_note);

            if($show_advice){
                //And setup advice field
                $advice_keys = Configure::read('advice_note_caculate');
                $advice_note_key_title = Configure::read('advice_note');

                //Get custom advice title
                $db_advice_key = $this->SsmAdviceKey->find('all',array(
                    'conditions'=>array(
                        'year'          =>$this->SsmGA->range_data[0]['y'],
                        'start_month'   =>$this->SsmGA->range_data[0]['m'],
                        'site_id'       =>$this->site_id
                    )
                ));

                //Overwrite default title
                if($db_advice_key){
                    foreach ($db_advice_key as $adv_key_row) {
                        if(in_array($adv_key_row['SsmAdviceKey']['advkey'], array_keys($advice_note_key_title))){
                            $advice_note_key_title[$adv_key_row['SsmAdviceKey']['advkey']] = $adv_key_row['SsmAdviceKey']['advvalue'];
                        }
                    }
                }

                //build kpi list array map by key
                $kpis_list_map_by_key = array();
                foreach ($kpis_list as $value) {
                    $kpis_list_map_by_key[$value['key']] = $value;
                }

                //Advice Calculate
                $i = 1;
                $advice_by_month = array();
                foreach ($this->SsmGA->range_data as $range_item) {
                    $m = $range_item['m'];
                    $y = $range_item['y'];
                    foreach ($advice_keys as $a_key=>$a_title) {
                        if($a_key == 'advice_transactionsPerSession'){
                            $decimal = 2;
                        }else{
                            $decimal = 0;
                        }
                        $advice_by_month[$m][$a_key] = round($advice_note[$m][$a_key],$decimal);
                    }
                    $i++;

                    //Estimated
                    $a_keys = array_keys($advice_keys);
                    $advice_by_month[$m]['estimated'] = ($advice_by_month[$m][$a_keys[0]] * $advice_by_month[$m][$a_keys[1]] * $advice_by_month[$m][$a_keys[2]])/100;
                    //Estimated cost
                    $advice_by_month[$m]['estimated_cost'] = intval($advice_note[$m]['advice_note_10']) + intval($advice_note[$m]['advice_note_8']);
                }

                //Advice Convert display
                $advice_by_month_display = array();
                foreach ($advice_by_month as $m=>$a_data) {
                    foreach ($a_data as $a_key=>$a_value) {
                        if($a_key == 'advice_sessions'){
                            $advice_by_month_display[$m][$a_key] = number_format($a_value);
                        }elseif($a_key == 'advice_transactionsPerSession'){
                            $advice_by_month_display[$m][$a_key] = number_format($a_value,2)."%";
                        }elseif($a_key == 'advice_revenuePerTransaction'){
                            $advice_by_month_display[$m][$a_key] = "¥".number_format($a_value);
                        }elseif($a_key == 'estimated'){
                            $advice_by_month_display[$m][$a_key] = "¥".number_format($a_value);
                        }elseif($a_key == 'estimated_cost'){
                            $advice_by_month_display[$m][$a_key] = "¥".number_format($a_value);
                        }
                    }
                }

                $this->set('advice_year',$this->SsmGA->range_data[0]['y']);
                $this->set('advice_start_month',$this->SsmGA->range_data[0]['m']);
                $this->set('advice_note_key_title',$advice_note_key_title);
                $this->set('advice_keys',$advice_keys);
                $this->set('kpis_list_map_by_key',$kpis_list_map_by_key);
                $this->set('advice_note',$advice_note);
                $this->set('advice_by_month',$advice_by_month);
                $this->set('advice_by_month_display',$advice_by_month_display);
            }
        }

        //send info of month to CW
        if(isset($this->request->query['send_info_month']) && $this->request->query['send_info_month'] == 1 && $this->loginUser['SsmUser']['role'] == 'admin'){
            //Get data
            $number = $_GET['number'];
            $month = str_replace("'", "",$_GET['month']);
            $time =  '【' . str_replace("'", "", $_GET['month'] .'月') . '】';
            $target = $target_week[$number];
            $actual = $actual_value[$number];
            $ratio_1 = $ratio_with_target[$number];
            $ratio_2 = $ratio_with_prev_week[$number];
            $ratio_3 = $ratio_with_prev_yearW[$number];
            $prospective = $actual_prospective_value[$number];
            $ratio_4 = $ratio_prospective_with_target[$number];
            $ratio_5 = $ratio_prospective_with_prev_week[$number];
            $ratio_6 = $ratio_prospective_with_prev_yearW[$number];

            $arr_name_note = Configure::read('advice_note');
            //Note
            $arr_note = array();

            foreach($advice_note as $key => $val){
                if($key == $month){
                    $arr_note = array(
                        '主な施策 ' ."<br/>".  $val['note'],
                        $arr_name_note['advice_note_1'] ."<br/>". $val['advice_note_1'],
                        $arr_name_note['advice_note_2'] ."<br/>". $val['advice_note_2'],
                        $arr_name_note['advice_note_3'] ."<br/>". $val['advice_note_3'],
                        $arr_name_note['advice_note_4'] ."<br/>". $val['advice_note_4'],
                        $arr_name_note['advice_note_5'] ."<br/>". $val['advice_note_5'],
                        $arr_name_note['advice_note_6'] ."<br/>". $val['advice_note_6'],
                        $arr_name_note['advice_note_7'] ."<br/>". $val['advice_note_7'],
                        $arr_name_note['advice_note_8'] ."<br/>¥". $val['advice_note_8'],
                        $arr_name_note['advice_note_9'] ."<br/>". $val['advice_note_9'],
                        $arr_name_note['advice_note_10'] ."<br/>¥". $val['advice_note_10'],
                        $arr_name_note['advice_note_11'] ."<br/>". $val['advice_note_11'],
                        $arr_name_note['advice_note_12'] ."<br/>". $val['advice_note_12'],
                        'セッション数' ."<br/>". $val['advice_sessions'],
                        'CVR' ."<br/>". $val['advice_transactionsPerSession'] .'%',
                        '客単価' ."<br/>¥". round($val['advice_revenuePerTransaction'],0),
                        '売上予測' ."<br/>¥".  round(($val['advice_sessions']*$val['advice_transactionsPerSession']*$val['advice_revenuePerTransaction'])/100, 0),
                        'コスト予測' ."<br/>¥". ( intval($val['advice_note_8']) + intval($val['advice_note_10']) ),
                    );
                }
            }

            $note_string;
            foreach($arr_note as $key => $val){
                $note_string .= '◎'.$val. '<br/><br/>';
            }

            //data GA
            // $arr_Kpi_show = $siteInfo['report_kpi'];
            // $data_month = array();

            // foreach($kpis_list as $key => $kpi){

            //     foreach($arr_Kpi_show as $key_s => $val_s){
            //         if( $kpi['key'] == $val_s ){
            //             $data_month[$kpi['title']] =  $data = array(
            //                 '目標値 ' . $this->SsmGA->displayActualKPIpage($kpi['key'],$target[$kpi['key']], $kpi['type_data'],$kpi['pre_char'], $kpi['sub_char'],$kpi['decimal']),

            //                 '実績値 ' . $this->SsmGA->displayActualKPIpage($kpi['key'],$actual[$kpi['key']], $kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']),

            //                 '   目標比 ' . $this->SsmGA->displayValue($ratio_1[$kpi['key']],'number','','%',2),
            //                 '   前月比 ' . $this->SsmGA->displayValue($ratio_2[$kpi['key']],'number','','%',2),
            //                 '   前年比 ' . $this->SsmGA->displayValue($ratio_3[$kpi['key']],'number','','%',2),
            //                 '実績値 + 到着見込 ' . $this->SsmGA->displayActualKPIpage($kpi['key'],$prospective[$kpi['key']], $kpi['type_data'],$kpi['pre_char'], $kpi['sub_char'],$kpi['decimal']),

            //                 '   目標比 ' . $this->SsmGA->displayValue($ratio_4[$kpi['key']],'number','','%',2),
            //                 '   前月比 ' . $this->SsmGA->displayValue($ratio_5[$kpi['key']],'number','','%',2),
            //                 '   前年比 ' . $this->SsmGA->displayValue($ratio_6[$kpi['key']],'number','','%',2),
            //             );
            //         }
            //     }
            // }

            // $data_month_string;
            // foreach($data_month as $key => $val){
            //     $data_month_string .= $key .'<br/>'. implode("<br/>" ,$val) .'<br/><br/>';
            // }

            $chatwork_api = $siteInfo['chatwork_api'];
            $chatwork_id = $siteInfo['chatwork_id'];

// $data_month_string
            $msg = <<<MSG
$time
$note_string

MSG;
$msg = trim(str_replace("<br/>", "\n", $msg));

            header( "Content-type: text/html; charset=utf-8" );
                $data = array(
                  'body' => $msg
                );

                $opt = array(
                  CURLOPT_URL => "https://api.chatwork.com/v2/rooms/{$chatwork_id}/messages",
                  CURLOPT_HTTPHEADER => array( 'X-ChatWorkToken: ' . $chatwork_api ),
                  CURLOPT_RETURNTRANSFER => TRUE,
                  CURLOPT_SSL_VERIFYPEER => FALSE,
                  CURLOPT_POST => TRUE,
                  CURLOPT_POSTFIELDS => http_build_query( $data, '', '&' )
                );

                $ch = curl_init();
                curl_setopt_array( $ch, $opt );
                $res = curl_exec( $ch );
                curl_close( $ch );

                $res_k = json_decode($res);

                if(isset($res_k->message_id) && !empty($res_k->message_id) ){
                    $info = "データはチャットワークにアップロードされました。";
                    $this->Session->setFlash($info, 'success');
                } else if( isset($res_k->errors) && $res_k->errors[0] == "Invalid API Token"){
                    $info = "Chatworkとの連携と失敗しました。APIトークンをご確認ください。";
                    $this->Session->setFlash($info, 'warning');
                } else if( isset($res_k->errors) && $res_k->errors[0] == "You don't have permission to send/edit message." ){
                    $info = "ルームIDが正しくありません。";
                    $this->Session->setFlash($info, 'warning');
                } else if( isset($res_k->errors) && $res_k->errors[0] == "Invalid Endpoint or HTTP method" ){
                    $info = "ルームIDが正しくありません。";
                    $this->Session->setFlash($info, 'warning');
                }

                $this->redirect(array(
                    'controller' => 'OttKpi', 
                    'action' => 'index', 
                    '?' => array(
                        'site_id' => $_GET['site_id'], 
                        'range_report' => $_GET['range_report'],
                        'year_report' => $_GET['year_report'],
                        'type_report' => $_GET['type_report'],
                        'show_list_month' => $_GET['show_list_month']
                    )
                ));
        }
        //end send info of month to CW

        //download file csv
        $uri_arr = $this->request->query;
        $uri_arr['export'] = true;
        $uri = http_build_query($uri_arr);
        $this->set('uri',$uri);

        if(isset($this->request->query['export'])){
            $this->response->download("export.csv");
            $this->layout = 'ajax';
            $this->render('export');
        }
        // end download file csv
    }
}
