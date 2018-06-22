<?php
include_once(__DIR__."/../Vendor/Google/GA.php");
App::uses('SsmAdminController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Dashboards Controller
 *
 * @property Dashboard $Dashboard
 * @property PaginatorComponent $Paginator
 */
class OttReportController extends SsmAdminController {

    public $helpers     = array('Shishimai','Html');
    public $components  = array('Cshishimai','Session','SsmAuth', 'OttClient', 'SsmGA', 'SsmReportRevision');
    public $uses        = array('SsmKpi','SsmKpiGaWeek','SsmKpiGaMonth','SsmKpiChange','SsmSite');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function beforeRender(){
        parent::beforeRender();
    }

    //List report of user
    public function index(){
        $this->layout = 'ott';

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

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        Configure::load('config_shishimai');
        $cf_type_option     = Configure::read('type_option');
        $cf_report_status   = Configure::read('report_status');
        $this->set('cf_report_status',$cf_report_status);
        $this->loadModel('SsmReport');
        if($this->user_role == 'admin'){ //admin
            $reports = $this->SsmReport->find('all',
                array(
                    'conditions'=>array(
                        'site_id'       =>$site_id,
                    ),
                    'order' => array('year DESC', 'month DESC','week DESC')
                )
            );
        } else if($this->user_role == 'client'){ //Client OR partner
            $reports = $this->SsmReport->find('all',
                array(
                    'conditions'=>array(
                        'site_id' =>$site_id,
                        'status' => 1,
                    ),
                    'order' => array('year DESC', 'month DESC','week DESC')
                )
            );
        }else{
            //partner
            $reports = $this->SsmReport->find('all',
                array(
                    'conditions'=>array(
                        'OR'=>array(
                            array(
                                'site_id'   =>$site_id,
                                'type'      =>'type_week',
                                'status'    => 1
                            ),
                            array(
                                'site_id'   =>$site_id,
                                'type'      =>'type_month'
                            )
                        )
                    ),
                    'order' => array('year DESC', 'month DESC','week DESC')
                )
            );
        }

        $list = array();
        if($reports){
            foreach ($reports as $rp) {
                $year           = $rp['SsmReport']['year'];
                $month          = $rp['SsmReport']['month'];
                $type_report    = $rp['SsmReport']['type'];
                $type           = ($type_report == $cf_type_option['m']) ? 'month' : 'week';
                if(!isset($list[$year][$month])){
                    $list[$year][$month] = array(
                        'month' =>array(),
                        'week'  =>array()
                    );
                }
                $list[$year][$month][$type][] = $rp['SsmReport'];
            }
        }

        $this->set('list',$list);
    }

    public function report_month(){
        $this->layout = 'ott';
        $this->loadModel("SsmReport");
        $this->loadModel("SsmReportSlide");
        $this->loadModel('SsmSite');

        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }
        $cv_key = ($siteInfo['ga_ecommerce'] != 1) ? 'transactions_1' : 'transactions';
        $report_kpi_checked = $siteInfo['report_kpi'];

        if($site_target_key == 'transactionRevenue'){
            $this->set('site_target_unit','円');
        }elseif($site_target_key == 'transactions'){
            $this->set('site_target_unit','CV');
        }elseif($site_target_key == 'transactions_1'){
            //Change arr kpi display
            $this->set('site_target_unit','CV');
        }

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

        $report_kpi_checked = array_unique ($report_kpi_checked);
        $site_target_key = in_array('transactionRevenue',$report_kpi_checked) ? 'transactionRevenue' : ( (in_array('transactions_1',$report_kpi_checked) || in_array('transactions',$report_kpi_checked)) ? $cv_key : 'pageviews' );

        $this->set('report_kpi_checked',$report_kpi_checked);

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        Configure::load('config_shishimai');
        $type_option  = Configure::read('type_option');
        $slide_option = Configure::read('slide_option');

        // Check Ecomer
        if($siteInfo['ga_ecommerce'] != 1){
            $kpis = Configure::read('kpis_1');
        }else{
            $kpis = Configure::read('kpis');
        }

        $month = $this->request->data["month"];
        $year = $this->request->data["year"];

        //Get current month, and current year
        $cur_month = intval(date('m'));
        $cur_year = date('Y');
        $this->set('cur_month', $cur_month);
        $this->set('cur_year', $cur_year);

        $report_in_db = $this->SsmReport->find('first',array(
        'conditions'=> array(
            //'created_user' => $this->loginUser['SsmUser']['id'], 
            'site_id' => $site_id,
            'type' => $type_option["m"],
            'year' => $year,
            'month' => $month
        )
        ));

        if($this->request->is("post")){

            // Create report month
            if(!$report_in_db){
                $this->SsmReport->create();

                $kpi_list_string = (count($report_kpi_checked) ? implode(',',$report_kpi_checked) : '');

                $data = array(
                    'year'    => $year,
                    'month'   => $month,
                    "site_id" =>$site_id,
                    "created_user" => $this->loginUser['SsmUser']['id'],
                    "type" => $type_option["m"],
                    'cv_key'=>$cv_key,
                    'site_target_key'  => $site_target_key,
                    'kpi_list' => $kpi_list_string,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->SsmReport->save($data);
                $report_id = $this->SsmReport->getLastInsertId();

                //Get info site
                /*$info_site = $this->SsmSite->find('first', array(
                    'conditions' => array(
                        'SsmSite.id' => $site_id
                    ),
                    'fields' => array(
                        'SsmSite.site_name',
                        'SsmSite.site_description',
                    )
                ));*/

                $title_title_slide = $siteInfo['site_description'].'様'.'<br/>'. $siteInfo['site_name'] .'<br/>'. $year.'年'.$month.'月レポート';

                $title_title_slide = PREG_REPLACE('#<br\s*?/?>#i', "\n", $title_title_slide);
                $title_chart_slide = $month.'月の結果報告';
                $title_kpi1_slide = '主要KPIの状況';

                //Load KPI>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

                $input = $this->request->query;
                $this->loadModel('SsmKpi');
                $this->loadModel('SsmKpiChange');
                $this->loadModel('SsmKpiGaMonth');
                // $this->loadModel('SsmSite');

                $this->autoLayout = false;

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

                $this->SsmGA->getReportMonth($year,$month);
                //show on different device
                $start_date_display   = intval($input['month'])."/1";
                $end_date_display     = intval($input['month'])."/".$this->SsmGA->temp_var['end_day_number'];


                $GA = $this->SsmGA->ga;
                $month = intval($month) > 9 ? intval($month) : '0'.intval($month);
                $report_begin_date  = "{$year}-{$month}-01";
                $report_end_date    = "{$year}-{$month}-".$this->SsmGA->temp_var['end_day_number'];

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

                //Result
                //Giá trị mục tiêu
                $this->SsmGA->col_target[$site_target_key];
                //Giá trị đạt được
                $this->SsmGA->col_actual[$site_target_key];

                //Tỷ lệ so với mục tiêu
                $this->SsmGA->col_ratio_actual_target[$site_target_key];
                //tỷ lệ so với tháng trước
                $this->SsmGA->col_ratio_actual_prev[$site_target_key];
                //tỷ lệ so với cùng kỳ năm trước
                $this->SsmGA->col_ratio_actual_prevY[$site_target_key];

                //Danh sách mục tiêu đạt được
                if($this->SsmGA->crr_year == $this->SsmGA->query_year && $this->SsmGA->crr_month == $this->SsmGA->query_month){

                    $begin_date  = date($input['year'].'-'.(intval($input['month']) > 9 ? $input['month'] : '0'.intval($input['month'])).'-01');
                    $end_date    = date('Y-m-t', strtotime($begin_date));

                    $end_date_ex = explode('-',$end_date);
                    $end_day_number = $end_date_ex[2];

                    $data_ga_crr_month = $this->SsmKpiGaMonth->getGAMonthData($this->SsmGA->ga,$site_id,$this->SsmGA->query_year,$this->SsmGA->query_month);
                    $data_ga = $data_ga_crr_month['kpis'];
                    $this->set('data_ga_no_change',$data_ga);
                    $data_change_in_month = $this->SsmKpiChange->getDataChangeMonth($site_id,$this->SsmGA->query_year,$this->SsmGA->query_month);
                    $data_current_with_change = $this->Cshishimai->gaTotal($data_ga,$data_change_in_month['change_data_in_month']);

                    $actual = $this->Cshishimai->reCalculateField($data_current_with_change);
                    $target = $this->SsmGA->col_target;

                    $data_predict = $this->Cshishimai->gaPredict($data_current_with_change,$this->SsmGA->crr_day,$this->SsmGA->temp_var['end_day_number']);
                    $predict      = $this->Cshishimai->reCalculateField($data_predict);
                    $this->SsmGA->col_icon_status = $this->Cshishimai->iconStatus($predict,$target);
                }else{
                    $actual   = $this->SsmGA->col_actual;
                    $target = $this->SsmGA->col_target;
                    $this->SsmGA->col_icon_status = $this->Cshishimai->iconStatus($actual,$target);
                }

                //end Load KPI>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

                //KPI 1
                $arr_good = array();
                $arr_bad = array();
                $arr_status = $this->SsmGA->col_icon_status;

                if(count($report_kpi_checked)){
                    foreach ($arr_status as $status_key=>$status_value) {
                        if(in_array($status_key,$report_kpi_checked)){

                        }else{
                            unset($arr_status[$status_key]);
                        }
                    }
                }else{
                    $arr_status = array();
                }

                if($cv_key == 'transactions'){
                    unset($arr_status['transactions_1']);
                    unset($arr_status['transactionsPerSession_1']);
                    unset($arr_status['revenuePerTransaction_1']);
                }else{
                    unset($arr_status['transactions']);
                    unset($arr_status['transactionsPerSession']);
                    unset($arr_status['revenuePerTransaction']);
                }

                foreach($arr_status as $key => $val){
                    if($val == 1){
                        $arr_good[] = $key;
                    }
                    else {
                        $arr_bad[] = $key;
                    }
                }
                $count_total = count($arr_status);
                $count_good = count($arr_good);
                $count_bad = count($arr_bad);

                //Get list
                $list_title = array();
                if($count_bad != 0){
                    foreach($arr_bad as $key_bad => $val_bad){
                        foreach($kpis as $i_kpis => $v_kpis){
                            if($val_bad == $v_kpis['key']){
                                $list_title[] = $v_kpis['title'];
                            }
                        }
                    }
                }
                $show_arr_bad = $count_bad == 0 ? implode('」, 「',$list_title)  : '「'. implode('」, 「',$list_title) .'」';

                //KPI 2
                if($site_target_key == 'transactionRevenue'){
                    $kpi2 = '¥' . number_format($this->SsmGA->col_actual[$site_target_key], 0);
                } else {
                    $kpi2 = number_format($this->SsmGA->col_actual[$site_target_key], 0) . '件'; 
                }
                //KPI 3
                $kpi3 = number_format($this->SsmGA->col_ratio_actual_prev[$site_target_key], 2);
                //KPI 4
                $kpi4 = number_format($this->SsmGA->col_ratio_actual_prevY[$site_target_key], 2);
                //KPI 5
                $kpi5 = number_format($this->SsmGA->col_ratio_actual_target[$site_target_key], 2);
                //KPI 6, KPI 7
                $total_left_after_check_change = $data_total_left_display['total_left_after_check_change'];

                $desktop_total = $data_total_left_display['desktop_total'];
                $mobile_total = $data_total_left_display['mobile_total'];
                $tablet_total = $data_total_left_display['tablet_total'];
                $kpi6 = number_format($desktop_total*100/$total_left_after_check_change, 2);
                $kpi7 = number_format(($tablet_total+$mobile_total)*100/$total_left_after_check_change, 2);

                $des_chart_slide = $year.'年'.$month.'月の成果は' .$kpi2. '<br/>・目標比率'.$kpi5.'％・前月比'.$kpi3.'％・前年同月比'.$kpi4.'％<br>・デバイス別に見ると、パソコン経由'.$kpi6.'％・スマートフォン経'.$kpi7.'％';
                $des_chart_slide = PREG_REPLACE('#<br\s*?/?>#i', "\n", $des_chart_slide);

                //Case KPI
                if($count_good == $count_total){
                    $des_kpi1_slide = '設定した'.count($arr_status).'種類のKPIのうち、'. $count_good .'種類で目標達成<br/>・目標未達なのは0種類のKPI' ;
                } else if($count_bad == $count_total){
                    $des_kpi1_slide = '設定した'.count($arr_status).'種類のKPIのうち、'. $count_good .'種類で目標達成<br/>・目標未達なのは、 '. $show_arr_bad .' の'.$count_bad.'種類のKPI' ;
                } else {
                    $des_kpi1_slide = '設定した'.count($arr_status).'種類のKPIのうち、'. $count_good .'種類で目標達成<br/>・目標未達なのは、 '. $show_arr_bad .' の'.$count_bad.'種類のKPI' ;
                }
                $des_kpi1_slide = PREG_REPLACE('#<br\s*?/?>#i', "\n", $des_kpi1_slide);
                // create slide default
                foreach($slide_option as $i => $option_data){
                    if(!in_array($option_data,array('image_slide','image_slide_title'))){

                        $this->SsmReportSlide->create();
                        if($option_data == 'title_slide'){
                            $title = $title_title_slide;
                        } else if($option_data == 'chart_slide'){
                            $title = $title_chart_slide;
                            $description = $des_chart_slide;
                        } else{
                            $title = $title_kpi1_slide;
                            $description = $des_kpi1_slide;
                        }
                        $slide_info_default = array(
                            "title"     =>  $title,
                            "report_id" =>  $report_id,
                            "options"   =>  $option_data,
                            "description" => $description,
                            "order_num" => $i
                        );

                        $this->SsmReportSlide->save($slide_info_default);
                    }
                }
                $this->SsmReportRevision->save_report_revision($report_id,1);
                $this->redirect(array("action" => "edit_month", 'report_id' => $report_id));

            } else {
                $this->Session->setFlash('レポートはすでに存在します！', 'warning');
            }
        }
    }

    public function report_week(){

        Configure::load('config_shishimai');
        $this->loadModel('SsmKpiGaWeekReport');
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

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

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        $report_kpi_checked = $siteInfo['report_kpi'];

        if($site_target_key == 'transactionRevenue'){
            $this->set('site_target_unit','円');
        }elseif($site_target_key == 'transactions'){
            $this->set('site_target_unit','CV');
        }elseif($site_target_key == 'transactions_1'){
            //Change arr kpi display
            $this->set('site_target_unit','CV');
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

        $report_kpi_checked = array_unique($report_kpi_checked);
        $site_target_key = in_array('transactionRevenue',$report_kpi_checked) ? 'transactionRevenue' : ( (in_array('transactions_1',$report_kpi_checked) || in_array('transactions',$report_kpi_checked)) ? $cv_key : 'pageviews' );

        $this->layout = 'ott';
        $this->loadModel("SsmReport");
        $this->loadModel("SsmModel");
        $this->loadModel("SsmSite");
        $this->loadModel("SsmReportWeek");

        $type_option = Configure::read('type_option');

        if($this->request->is('post')){
            if(!empty($this->request->data)){

                $info_week = $this->request->data['info_week'];
                if(!empty($info_week)){
                    $year   = substr($info_week, 0, 4);
                    $month  = intval(substr($info_week, 5, 2));
                    $week   = intval(substr($info_week, 8, 1));
                    $last_day_of_month  = intval(substr($info_week, 10, 2));

                    $report_week = $this->SsmReport->find("first", array(
                        'conditions' => array(
                            'type' => $type_option['w'],
                            'year' => $year,
                            'month' => $month,
                            'week' => $week,
                            'last_day_of_month' => $last_day_of_month,
                            'site_id' => $site_id
                            )
                    ));

                    if(!$report_week){
                        $this->SsmReport->create();
                        $kpi_list_string = (count($report_kpi_checked) ? implode(',',$report_kpi_checked) : '');
                        $data = array(
                            'type' => $type_option['w'],
                            'year' => $year,
                            'month' => $month,
                            'week' => $week,
                            'last_day_of_month' => $last_day_of_month,
                            'site_id' => $site_id,
                            'cv_key'  => $cv_key,
                            'site_target_key'  => $site_target_key,
                            'kpi_list'=>$kpi_list_string,
                            'created_user' => $this->loginUser['SsmUser']['id'],
                            'created_at' => date('Y-m-d H-i-s'),
                        );

                        $this->SsmReport->save($data);
                        $report_id = $this->SsmReport->getLastInsertId();

                        //create info detail report week
                        $start_end_day  = $this->OttClient->return_start_end_day($week, $last_day_of_month);
                        $start_day      = $start_end_day[0];
                        $end_day        = $start_end_day[1];

                        $this->SsmReportWeek->create();

                        //Get date_dead_week, hour_dead_week in SsmSite
                        $info_SsmSite = $this->SsmSite->find('first', array(
                            'conditions' => array(
                                'SsmSite.id' => $site_id
                            ),
                            'fields' => array(
                                'SsmSite.date_dead_week',
                                'SsmSite.hour_dead_week',
                                'SsmSite.site_manage_user'
                            )
                        ));

                        $day_SsmSite = $this->OttClient->return_dead_day($start_day, $end_day, $month, $year, $info_SsmSite['SsmSite']['date_dead_week'], $last_day_of_month);

                        //Data show in report content ========================================================

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

                        $this->SsmGA->getReportMonth($year,$month);

                        $t['week_des'] = $this->SsmGA->week_title[$week]['week_des2'];

                        if($this->SsmGA->week_title[$week]['week_in'] == 'present'){

                            $total_day_in_week  = $this->SsmGA->week_title[$week]['week_end_day'] - ($this->SsmGA->week_title[$week]['week_start_day'] - 1);
                            $total_day_get_data = (intval(date('d')) -1) - ($this->SsmGA->week_title[$week]['week_start_day'] - 1);
                            //Box 1
                            $str_kpi_week = "";

                            if(count($report_kpi_checked)){
                                $report_kpi_checked_box_1 = $report_kpi_checked;

                                $predict = $this->SsmGA->reCalculateField($this->SsmGA->gaPredict($this->SsmGA->actual[$week],$total_day_get_data,$total_day_in_week));

                                if($predict['transactionRevenue'] == 0){
                                    foreach ($report_kpi_checked_box_1 as $k=>$v) {
                                        if($v == 'transactionRevenue' || $v == 'revenuePerTransaction' || $v == 'revenuePerTransaction_1'){
                                            unset($report_kpi_checked_box_1[$k]);
                                        }
                                    }
                                }

                                foreach ($kpis_list as $kpi) {
                                    $key_kpi = $kpi['key'];
                                    if(in_array($key_kpi,$report_kpi_checked_box_1)){
                                        $title_kpi = (($key_kpi == 'transactionsPerSession' || $key_kpi == 'transactionsPerSession_1') ? $kpi['title']." " : $kpi['title']);
                                        $now  = $this->SsmGA->displayActualKPIpageV2($key_kpi,$predict,$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);
                                        $prev = $this->SsmGA->displayActualKPIpageV2($key_kpi,$this->SsmGA->actual_prev[$week],$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);

                                        if($key_kpi == 'transactionRevenue' ){
                                            $tab = "\t\t\t\t\t";
                                        }elseif($key_kpi == 'topBounceRate' ){
                                            $tab = "\t\t\t";
                                        }elseif($key_kpi == 'pageviewsPerSession' ){
                                            $tab = "\t\t";
                                        }elseif($key_kpi == 'avgSessionDuration' ){
                                            $tab = "\t";
                                        }elseif($key_kpi == 'sessions' ){
                                            $tab = "\t\t\t";
                                        }elseif($key_kpi == 'percentNewSessions' ){
                                            $tab = "\t\t";
                                        }else{
                                            $tab = "\t\t\t\t";
                                        }

                                        if(in_array($key_kpi,array('bounceRate','topBounceRate'))){
                                            $start_char = ($predict[$key_kpi] < $this->SsmGA->actual_prev[$week][$key_kpi] ? "★":"");
                                        }else{
                                            $start_char = ($predict[$key_kpi] > $this->SsmGA->actual_prev[$week][$key_kpi] ? "★":"");
                                        }

                                        $str_kpi_week .=$title_kpi.$tab."：".$now."（".$prev."）".$start_char."\n";
                                    }
                                }
                            }
                            //End box 1
                        }else{
                            //Box 1
                            $str_kpi_week = "";

                            if(count($report_kpi_checked)){
                                $report_kpi_checked_box_1 = $report_kpi_checked;

                                if($this->SsmGA->actual[$week]['transactionRevenue'] == 0){
                                    foreach ($report_kpi_checked_box_1 as $k=>$v) {
                                        if($v == 'transactionRevenue' || $v == 'revenuePerTransaction' || $v == 'revenuePerTransaction_1'){
                                            unset($report_kpi_checked_box_1[$k]);
                                        }
                                    }
                                }

                                foreach ($kpis_list as $kpi) {
                                    $key_kpi = $kpi['key'];
                                    if(in_array($key_kpi,$report_kpi_checked_box_1)){
                                        $title_kpi = (($key_kpi == 'transactionsPerSession' || $key_kpi == 'transactionsPerSession_1') ? $kpi['title']." " : $kpi['title']);
                                        $now  = $this->SsmGA->displayActualKPIpageV2($key_kpi,$this->SsmGA->actual[$week],$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);
                                        $prev = $this->SsmGA->displayActualKPIpageV2($key_kpi,$this->SsmGA->actual_prev[$week],$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);

                                        if($key_kpi == 'transactionRevenue' ){
                                            $tab = "\t\t\t\t\t";
                                        }elseif($key_kpi == 'topBounceRate' ){
                                            $tab = "\t\t\t";
                                        }elseif($key_kpi == 'pageviewsPerSession' ){
                                            $tab = "\t\t";
                                        }elseif($key_kpi == 'avgSessionDuration' ){
                                            $tab = "\t";
                                        }elseif($key_kpi == 'sessions' ){
                                            $tab = "\t\t\t";
                                        }elseif($key_kpi == 'percentNewSessions' ){
                                            $tab = "\t\t";
                                        }else{
                                            $tab = "\t\t\t\t";
                                        }

                                        if(in_array($key_kpi,array('bounceRate','topBounceRate'))){
                                            $start_char = ($this->SsmGA->actual[$week][$key_kpi] < $this->SsmGA->actual_prev[$week][$key_kpi] ? "★":"");
                                        }else{
                                            $start_char = ($this->SsmGA->actual[$week][$key_kpi] > $this->SsmGA->actual_prev[$week][$key_kpi] ? "★":"");
                                        }

                                        if($now == "-" || $prev == '-'){
                                            $start_char = "";
                                        }

                                        $str_kpi_week .=$title_kpi.$tab."：".$now."（".$prev."）".$start_char."\n";
                                    }
                                }
                            }

                            //End Box 1
                        }


                        //Box 2

                        if($year == date('Y') && $month == date('m')){
                            $str_kpi_crr_month = "";
                            $predict = $this->SsmGA->reCalculateField($this->SsmGA->total_actual[$week]);

                            if(count($report_kpi_checked)){
                                $report_kpi_checked_box_2 = $report_kpi_checked;
                                if($predict['transactionRevenue'] == 0){
                                    foreach ($report_kpi_checked_box_2 as $k=>$v) {
                                        if($v == 'transactionRevenue' || $v == 'revenuePerTransaction' || $v == 'revenuePerTransaction_1'){
                                            unset($report_kpi_checked_box_2[$k]);
                                        }
                                    }
                                }

                                foreach ($kpis_list as $kpi) {
                                    $key_kpi = $kpi['key'];
                                    if(in_array($key_kpi,$report_kpi_checked_box_2)){
                                        $title_kpi = (($key_kpi == 'transactionsPerSession' || $key_kpi == 'transactionsPerSession_1') ? $kpi['title']." " : $kpi['title']);
                                        $now  = $this->SsmGA->displayActualKPIpageV2($key_kpi,$predict,$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);
                                        $prev = $this->SsmGA->displayActualKPIpageV2($key_kpi,$this->SsmGA->col_prev,$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);
                                        $target = $this->SsmGA->displayValue($this->SsmGA->col_target[$key_kpi],$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);

                                        if($key_kpi == 'transactionRevenue' ){
                                            $tab = "\t\t\t\t\t";
                                        }elseif($key_kpi == 'topBounceRate' ){
                                            $tab = "\t\t\t";
                                        }elseif($key_kpi == 'pageviewsPerSession' ){
                                            $tab = "\t\t";
                                        }elseif($key_kpi == 'avgSessionDuration' ){
                                            $tab = "\t";
                                        }elseif($key_kpi == 'sessions' ){
                                            $tab = "\t\t\t";
                                        }elseif($key_kpi == 'percentNewSessions' ){
                                            $tab = "\t\t";
                                        }else{
                                            $tab = "\t\t\t\t";
                                        }

                                        if(in_array($key_kpi,array('bounceRate','topBounceRate'))){
                                            $start_char = ($predict[$key_kpi] < $this->SsmGA->col_prev[$key_kpi] ? "★":"");
                                        }else{
                                            $start_char = ($predict[$key_kpi] > $this->SsmGA->col_prev[$key_kpi] ? "★":"");
                                        }

                                        if($now == "-" || $prev == '-'){
                                            $start_char = "";
                                        }

                                        $str_kpi_crr_month .= $title_kpi.$tab.":".$now."／目標：".$target."（".$prev."）".$start_char."\n";
                                    }
                                }
                            }
                        }else{
                            $str_kpi_crr_month = "";

                            if($week != count($this->SsmGA->temp_var['week_title'])){
                                $predict = $this->SsmGA->reCalculateField($this->SsmGA->total_actual[$week]);
                            }else{
                                $predict = $this->SsmGA->reCalculateField($this->SsmGA->col_actual);
                            }

                            if(count($report_kpi_checked)){
                                $report_kpi_checked_box_2 = $report_kpi_checked;
                                if($this->SsmGA->col_actual['transactionRevenue'] == 0){
                                    foreach ($report_kpi_checked_box_2 as $k=>$v) {
                                        if($v == 'transactionRevenue' || $v == 'revenuePerTransaction' || $v == 'revenuePerTransaction_1'){
                                            unset($report_kpi_checked_box_2[$k]);
                                        }
                                    }
                                }

                                foreach ($kpis_list as $kpi) {
                                    $key_kpi = $kpi['key'];
                                    if(in_array($key_kpi,$report_kpi_checked_box_2)){
                                        $title_kpi = (($key_kpi == 'transactionsPerSession' || $key_kpi == 'transactionsPerSession_1') ? $kpi['title']." " : $kpi['title']);
                                        $now  = $this->SsmGA->displayActualKPIpageV2($key_kpi,$predict,$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);
                                        $prev = $this->SsmGA->displayActualKPIpageV2($key_kpi,$this->SsmGA->col_prev,$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);
                                        $target = $this->SsmGA->displayValue($this->SsmGA->col_target[$key_kpi],$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);

                                        if($key_kpi == 'transactionRevenue' ){
                                            $tab = "\t\t\t\t\t";
                                        }elseif($key_kpi == 'topBounceRate' ){
                                            $tab = "\t\t\t";
                                        }elseif($key_kpi == 'pageviewsPerSession' ){
                                            $tab = "\t\t";
                                        }elseif($key_kpi == 'avgSessionDuration' ){
                                            $tab = "\t";
                                        }elseif($key_kpi == 'sessions' ){
                                            $tab = "\t\t\t";
                                        }elseif($key_kpi == 'percentNewSessions' ){
                                            $tab = "\t\t";
                                        }else{
                                            $tab = "\t\t\t\t";
                                        }

                                        if(in_array($key_kpi,array('bounceRate','topBounceRate'))){
                                            $start_char = ($predict[$key_kpi] < $this->SsmGA->col_prev[$key_kpi] ? "★":"");
                                        }else{
                                            $start_char = ($predict[$key_kpi] > $this->SsmGA->col_prev[$key_kpi] ? "★":"");
                                        }

                                        if($now == "-" || $prev == '-'){
                                            $start_char = "";
                                        }

                                        $str_kpi_crr_month .= $title_kpi.$tab.":".$now."／目標：".$target."（".$prev."）".$start_char."\n";
                                    }
                                }
                            }

                        }
                        //End box 2

                        //Box 3
                        $str_kpi_prev_month = "";
                        if(intval($month) > 1){
                            $prev_m = (intval($month) - 1);
                            $prev_y = $year;
                        }else{
                            $prev_m = 12;
                            $prev_y = $year - 1;
                        }

                        //Get prev2 ga data
                        $this->SsmGA->resetResult();
                        $this->SsmGA->getReportMonth($prev_y,$prev_m);

                        if(count($report_kpi_checked)){

                            $report_kpi_checked_box_3 = $report_kpi_checked;
                            if( $this->SsmGA->col_actual['transactionRevenue'] == 0){
                                foreach ($report_kpi_checked_box_3 as $k=>$v) {
                                    if($v == 'transactionRevenue' || $v == 'revenuePerTransaction' || $v == 'revenuePerTransaction_1'){
                                        unset($report_kpi_checked_box_3[$k]);
                                    }
                                }
                            }

                            foreach ($kpis_list as $kpi) {
                                $key_kpi = $kpi['key'];
                                if(in_array($key_kpi,$report_kpi_checked_box_3)){
                                    $title_kpi = (($key_kpi == 'transactionsPerSession' || $key_kpi == 'transactionsPerSession_1') ? $kpi['title']." " : $kpi['title']);
                                    $now  = $this->SsmGA->displayActualKPIpageV2($key_kpi,$this->SsmGA->col_actual,$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);
                                    $prev = $this->SsmGA->displayActualKPIpageV2($key_kpi,$this->SsmGA->col_prev,$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);
                                    $target = $this->SsmGA->displayValue($this->SsmGA->col_target[$key_kpi],$kpi['type_data'],$kpi['pre_char'],$kpi['sub_char'],$kpi['decimal']);

                                    if($key_kpi == 'transactionRevenue' ){
                                        $tab = "\t\t\t\t\t";
                                    }elseif($key_kpi == 'topBounceRate' ){
                                        $tab = "\t\t\t";
                                    }elseif($key_kpi == 'pageviewsPerSession' ){
                                        $tab = "\t\t";
                                    }elseif($key_kpi == 'avgSessionDuration' ){
                                        $tab = "\t";
                                    }elseif($key_kpi == 'sessions' ){
                                        $tab = "\t\t\t";
                                    }elseif($key_kpi == 'percentNewSessions' ){
                                        $tab = "\t\t";
                                    }else{
                                        $tab = "\t\t\t\t";
                                    }

                                    if(in_array($key_kpi,array('bounceRate','topBounceRate'))){
                                        $start_char = ($this->SsmGA->col_actual[$key_kpi] < $this->SsmGA->col_prev[$key_kpi] ? "★":"");
                                    }else{
                                        $start_char = ($this->SsmGA->col_actual[$key_kpi] > $this->SsmGA->col_prev[$key_kpi] ? "★":"");
                                    }
                                    if($now == "-" || $prev == '-'){
                                        $start_char = "";
                                    }
                                    $str_kpi_prev_month .= $title_kpi.$tab.":".$now."／目標：".$target."（".$prev."）".$start_char."\n";
                                }
                            }
                        }

                        $default_text = <<<EOF
------------------------------------------------------------
サイトのデータ
------------------------------------------------------------
▼集計期間：{$t['week_des']}
※（）内は前週のデータです
※★は良くなったKPI

{$str_kpi_week}

▼{$month}月の累計
※（）内は先月の実績
※★は良くなったKPI

{$str_kpi_crr_month}

▼{$prev_m}月の累計
※（）内は先月の実績
※★は良くなったKPI

{$str_kpi_prev_month}

------------------------------------------------------------
topic
------------------------------------------------------------

EOF;
                        //End data show in report content ===============================================

                        $detail_report_week = array(
                            'report_id' => $report_id,
                            'content'   => $default_text,
                            'status'    => 0,
                            'year'      => $day_SsmSite[2],
                            'month'     => $day_SsmSite[1],
                            'day'       => $day_SsmSite[0],
                            'hour'      => $info_SsmSite['SsmSite']['hour_dead_week'],
                             'user_id'   => $info_SsmSite['SsmSite']['site_manage_user']
                            );

                        $this->SsmReportWeek->save($detail_report_week);
                        $this->Session->setFlash(__('レポートは作成されました！'), 'success');
                        $this->redirect(array('action'=>'see_week', 'report_id' => $report_id));

                    } else {
                        $this->Session->setFlash(__('週レポートはすでに存在します！'), 'warning');
                        $this->redirect(array('action'=>'index', 'site_id' => $site_id));
                    }

                } else {
                    $this->Session->setFlash('週情報を入力してください！', 'warning');
                }
            }else{
            }
        }


        $year_current = date('Y');
        $month_current = intval(date('m'));

        /*
        $crr    = $this->Cshishimai->getMonthToBuildOptionReport($year_current,$month_current);
        $prev_1 = $this->Cshishimai->getMonthToBuildOptionReport($crr['year'],$crr['month'],'prev');
        $prev_2 = $this->Cshishimai->getMonthToBuildOptionReport($prev_1['year'],$prev_1['month'],'prev');
        $prev_3 = $this->Cshishimai->getMonthToBuildOptionReport($prev_2['year'],$prev_2['month'],'prev');
        $prev_4 = $this->Cshishimai->getMonthToBuildOptionReport($prev_3['year'],$prev_3['month'],'prev');
        $next   = $this->Cshishimai->getMonthToBuildOptionReport($crr['year'],$crr['month'],'next');

        $prev_month = $month_current > 1 ? $month_current -1 : 12 ;
        $prev_month = $prev_month < 10 ? '0' . $prev_month : $prev_month;
        $prev_year = $month_current > 1 ? $year_current : $year_current - 1;
        $prev_month_info = $this->SsmModel->getMonthInfo($prev_year, $prev_month);
        $last_day_number = $prev_month_info['last_day_number'];

        $current_month_info = $this->SsmModel->getMonthInfo($year_current,$month_current);
        $current_day_number = $current_month_info['last_day_number'];

        $next_month = $month_current < 12 ? $month_current + 1 : 1;
        $next_year = $month_current < 12 ? $year_current : $year_current + 1;
        $next_month_info = $this->SsmModel->getMonthInfo($next_year,$next_month);
        $next_day_number = $next_month_info['last_day_number'];

        $arr_last_day = [
            array(
                'month'    =>$prev_month,
                'last_day' =>$last_day_number,
                'year'     =>$prev_year
            ),
            array(
                'month'    =>$month_current,
                'last_day' =>$current_day_number,
                'year'     =>$year_current
            ),
            array(
                'month'    =>$next_month,
                'last_day' =>$next_day_number,
                'year'     =>$next_year
            )
        ];

        $arr_last_day = [
            $prev_4,$prev_3,$prev_2,$prev_1,$crr,$next
        ];

        */
        $year_current = date('Y');
        $month_current = intval(date('m'));
        //Update show option
        $arr_last_day = array();
        $year_list   = array(($year_current -2),($year_current -1),($year_current),($year_current + 1),($year_current + 2));
        $month_list  = array(1,2,3,4,5,6,7,8,9,10,11,12);

        if(!isset($this->request->query['year']) || !in_array($this->request->query['year'],$year_list)){
            $active_year = $year_current;
        }else{
            $active_year = $this->request->query['year'];
        }

        if($active_year == $year_current){
            foreach ($month_list as $key => $value) {
                if($value > ($month_current +1)){
                    //unset($month_list[$key]);
                }
            }
        }

        if(!isset($this->request->query['month']) || !in_array($this->request->query['month'],$month_list)){
            $active_month = $month_list[0];
            if($active_year == $year_current){
                $active_month = $month_current;
            }
        }else{
            $active_month = $this->request->query['month'];
        }

        if(!in_array($active_month,$month_list)){
            $active_month = $month_list[0];
        }

        $active_month_option = $this->Cshishimai->getMonthToBuildOptionReport($active_year,$active_month);
        $arr_last_day[] = $active_month_option;

        $this->set('year_list',$year_list);
        $this->set('active_year',$active_year);
        $this->set('month_list',$month_list);
        $this->set('active_month',$active_month);
        //End show option

        $this->set('arr_last_day', $arr_last_day);
        $this->set('year_current', $year_current);
        $this->set('month_current', $month_current);
    }

    public function see_week(){
        $this->layout = 'ott';
        $this->loadModel("SsmReport");
        $report_id = $this->request->params['named']['report_id'];
        $reportInfo = $this->SsmReport->find('first', array(
            'conditions' => array(
                'id' => $report_id
            )
        ));

        //User and Site define ========================================================
        //$site_id    = $this->site_id;
        $site_id = $reportInfo['SsmReport']['site_id'];
        $loginUser  = $this->loginUser;

        if ( !$site_id ) {
            $site_id    = $this->site_id;
        }

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);

        $status_report = $this->SsmReport->find('first', array(
            'conditions' => array(
                'SsmReport.id' => $report_id
            ),
            'fields' => array(
                'SsmReport.status'
            )
        ));

        if($this->user_role !="admin"){
            if(!$this->SsmAuth->checkContractSite($site_id)){
                $this->render('/OttError/no_contract');
                return;
            } else {
                if( $status_report['SsmReport']['status'] == 0 ){
                    $this->redirect(array('action' => 'index'));
                    return;
                }
            }
        }

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        $this->loadModel("SsmSite");
        $this->loadModel("SsmReportWeek");
        $this->loadModel("SsmUser");
        // Get value month, start_day, end_day
        $info_time = $this->SsmReport->find("first", array(
            'joins'=>array(
                array(
                    'table'=>'ssm_sites',
                    'alias'=>'SsmSite',
                    'type'=>'INNER',
                    'conditions'=>array(
                        'SsmSite.id = SsmReport.site_id'
                    )
                )
            ),
            'conditions' => array(
                'SsmReport.id'  => $report_id,
                'SsmSite.id'    => $site_id
                ),
            'fields' => array(
                'SsmReport.month',
                'SsmReport.week',
                'SsmReport.year',
                'SsmReport.site_id',
                'SsmReport.last_day_of_month',
                'SsmReport.cv_key',
                'SsmReport.site_target_key',
                'SsmReport.kpi_list',
                'SsmReport.week_start',
                'SsmReport.week_end'
                )
            ));
        if(!$info_time){
            $this->Session->setFlash('存在しないアドレスになります。レポートの一覧から表示したいレポート名を選択してください。', 'warning');
            $this->redirect(array('action'=>'index'));
        }
        $year = $info_time['SsmReport']['year'];
        $month = $info_time['SsmReport']['month'];
        $week = $info_time['SsmReport']['week'];
        $site_id = $info_time['SsmReport']['site_id'];
        $last_day_of_month = $info_time['SsmReport']['last_day_of_month'];

        $start_end_day = $this->OttClient->return_start_end_day($week, $last_day_of_month);
        $start_day = $start_end_day[0];
        $end_day = $start_end_day[1];
        // Get value site_name, site_url
        $info_site = $this->SsmSite->find("first", array(
            'conditions' => array(
                'SsmSite.id' => $site_id
                ),
            'fields' => array(
                'SsmSite.site_name',
                'SsmSite.site_url'
                )
            ));
        $site_name = $info_site['SsmSite']['site_name'];
        $site_url = $info_site['SsmSite']['site_url'];
        // Get content 
        $info_content = $this->SsmReportWeek->find('first', array(
            'conditions' => array(
                'SsmReportWeek.report_id' =>  $report_id
                ),
            'fields' => array(
                'SsmReportWeek.content'
                )
            ));
        $content = $info_content['SsmReportWeek']['content'];

        //Get info_client
        $data_report_week = $this->SsmReportWeek->find('first', array(
            'conditions' => array(
                'SsmReportWeek.report_id' => $report_id
            ),
            'fields' => array(
                'SsmReportWeek.user_id'
            )
        ));

        $user_id_report_week = $data_report_week['SsmReportWeek']['user_id'];

        $info_client = $this->SsmUser->find('first', array(
            'conditions' => array(
                'SsmUser.id' => $user_id_report_week,
            ),
            'fields' => array(
                'SsmUser.first_name',
                'SsmUser.last_name',
                'SsmUser.department',
                'SsmUser.avatar',
                'SsmUser.position',
                'SsmUser.id',
            )
        ));

        $this->set('info_time',$info_time);
        $this->set('year', $year);
        $this->set('month', $month);
        $this->set('week', $week);
        $this->set('start_day', $start_day);
        $this->set('end_day', $end_day);
        $this->set('site_name', $site_name);
        $this->set('site_url', $site_url);
        $this->set('content', $content);
        $this->set('report_id', $report_id);
        $this->set('info_client', $info_client);
    }

    public function edit_week(){
        $this->layout = 'ott';
        $this->loadModel("SsmReportWeek");
        $this->loadModel("SsmReport");
        $this->loadModel("SsmSiteUser");
        $this->loadModel("SsmUser");
        $this->loadModel('SsmSite');

        $report_id = $this->request->params['named']['report_id'];
        $reportInfo = $this->SsmReport->find('first', array(
            'conditions' => array(
                'id' => $report_id
            )
        ));

        //User and Site define ========================================================
        //$site_id    = $this->site_id;
        $site_id = $reportInfo['SsmReport']['site_id'];
        $loginUser  = $this->loginUser;

        if ( !$site_id ) {
            $site_id    = $this->site_id;
        }

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        //Get content
        //$report_id = $this->request->params['named']['report_id'];
        $week_info = $this->SsmReportWeek->find("first", array(
            'joins'=>array(
                array(
                    'table'=>'ssm_reports',
                    'alias'=>'SsmReport',
                    'type'=>'INNER',
                    'conditions'=>array(
                        'SsmReportWeek.report_id = SsmReport.id'
                    )
                ),
                array(
                    'table'=>'ssm_sites',
                    'alias'=>'SsmSite',
                    'type'=>'INNER',
                    'conditions'=>array(
                        'SsmSite.id = SsmReport.site_id'
                    )
                )
            ),
            'conditions' => array(
                'SsmReport.id'  => $report_id,
                'SsmSite.id'    => $site_id
            ),
            'fields' => array(
                'SsmReportWeek.content',
                'SsmReportWeek.month',
                'SsmReportWeek.day',
                'SsmReportWeek.hour',
                'SsmReportWeek.user_id',
                )
            ));

        if(!$week_info){
            $this->Session->setFlash('存在しないアドレスになります。レポートの一覧から表示したいレポート名を選択してください。', 'warning');
            $this->redirect(array('action'=>'index'));
        }

        $content = $week_info['SsmReportWeek']['content'];
        $month_deadline = $week_info['SsmReportWeek']['month'];
        $day_deadline = $week_info['SsmReportWeek']['day'];
        $hour_deadline = $week_info['SsmReportWeek']['hour'];
        $user_id = $week_info['SsmReportWeek']['user_id'];
        $this->set('user_id', $user_id);
        // Get value month, start_day, end_day
        $id = $this->request->params['named']['report_id'];      
        $info_time = $this->SsmReport->find("first", array(
            'conditions' => array(
                'SsmReport.id' => $id
                ),
            'fields' => array(
                'SsmReport.month',
                'SsmReport.week',
                'SsmReport.last_day_of_month',
                'SsmReport.year',
                'SsmReport.status',
                )
            ));
        $status = $info_time['SsmReport']['status'];
        $month = $info_time['SsmReport']['month'];
        $week = $info_time['SsmReport']['week'];
        $last_day_of_month = $info_time['SsmReport']['last_day_of_month'];
        $year = $info_time['SsmReport']['year'];
        $start_end_day = $this->OttClient->return_start_end_day($week, $last_day_of_month);
        $start_day = $start_end_day[0];
        $end_day = $start_end_day[1];

        //Get data_client
        $data_client = $this->SsmUser->find('all', array(
            'join' => array(
                'table' => 'ssm_site_users',
                'alias' => 'SsmSiteUser',
                'type' => 'INNER',
                'conditions' => array(
                    'SsmUser.id' => 'SsmSiteUser.user_id'
                )
            ),
            'conditions' => array(
                'SsmUser.role' => 'admin',
                'SsmUser.status' => 1
            ),
            'fields' => array(
                // 'SsmUser.position',
                'SsmUser.first_name',
                'SsmUser.last_name',
                'SsmUser.id'
            )
        ));

        //Get site_manage_user in site
        $site_manage_user = $this->SsmSite->find('first', array(
            'conditions' => array(
                'SsmSite.id' => $site_id
            ),
            'fields' => array(
                'SsmSite.site_manage_user'
            )
        ));

        //Data send from setting_edit_week
        if($this->request->is('post')){
            //Day in deadline
            $day_in_week = $this->request->data['day_in_week'];
            $hour = $this->request->data['hour'];
            $position = $this->request->data['position'];

            $dead_day = $this->OttClient->return_dead_day($start_day, $end_day, $month, $year, $day_in_week, $last_day_of_month);

            $this->set('hour', $hour);
            $this->set('dead_day', $dead_day[0]);

            //Update info_ssmsite in SsmSite
            $key_api = $this->SsmUser->find('first', array(
                'conditions' => array(
                    'SsmUser.id' => $position
                ),
                'fields' => array(
                    'SsmUser.chatwork_api'
                )
            ));
            $key_api_user = $key_api['SsmUser']['chatwork_api'];

            $this->SsmSite->id = $site_id;
            $info_ssmsite = array(
                'site_manage_user' => $position,
                'hour_dead_week' => $hour,
                'date_dead_week' => $day_in_week,
                'chatwork_api' => $key_api_user
            );
            $this->SsmSite->set($info_ssmsite);
            $this->SsmSite->save(); 

            // Update dealine all report week
            $info_report_id_site = $this->SsmReport->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'ssm_report_weeks',
                        'alias' => 'SsmReportWeek',
                        'type' => 'INNER',
                        'conditions' => array(
                            'SsmReport.id = SsmReportWeek.report_id' 
                        )
                     )
                ),
                'conditions' => array(
                    'SsmReport.site_id' => $site_id 
                ),
                'fields' => array(
                    'SsmReportWeek.report_id'
                )
            ));

            $year_current  = date('Y');
            $month_current = intval(date('m'));
            $day_current   = intval(date('d'));
            $week_current  = $this->OttClient->return_week_current($day_current);

            //Lặp tất cả report của site
            foreach($info_report_id_site as $key => $report_id_week){

                $time_compare = $this->SsmReport->find('first', array(
                    'conditions' => array(
                        'SsmReport.id' => $report_id_week['SsmReportWeek']['report_id']
                    ),
                    'fields' => array(
                        'SsmReport.year',
                        'SsmReport.month',
                        'SsmReport.week',
                        'SsmReport.last_day_of_month'
                    )
                ));

                //Năm report
                $year_compare  = $time_compare['SsmReport']['year'];
                //tháng report
                $month_compare = $time_compare['SsmReport']['month'];
                //tuần report
                $week_compare  = $time_compare['SsmReport']['week'];
                //ngày cuối tháng của report
                $last_day_of_month_compare = $time_compare['SsmReport']['last_day_of_month'];

                $start_end_day_compare = $this->OttClient->return_start_end_day($week_compare, $last_day_of_month_compare);

                //ngày bắt đầu của report và ngày cuối của tuần report
                $start_day_compare = $start_end_day_compare[0];
                $end_day_compare   = $start_end_day_compare[1];

                //Truyền vào ngày bắt đầu ,ngày kết thúc ,tháng ,năm , ngày trong tuần ,ngày cuối của tháng của report
                $day_SsmSite_compare = $this->OttClient->return_dead_day($start_day_compare, $end_day_compare, $month_compare, $year_compare, $day_in_week, $last_day_of_month_compare);

                if($year_compare >= $year_current && $month_compare >= $month_current && $week_compare >= $week_current){
                    $this->SsmReportWeek->updateAll(
                        array(
                            'SsmReportWeek.hour'  => $hour,
                            'SsmReportWeek.day'   => $day_SsmSite_compare[0],
                            'SsmReportWeek.month' => $day_SsmSite_compare[1],
                            'SsmReportWeek.year'  => $day_SsmSite_compare[2],
                        ),
                        array(
                            'SsmReportWeek.report_id' => $report_id_week['SsmReportWeek']['report_id']
                        )
                    );
                }
            }
        }

        // $this->set('site_manage_user', $site_manage_user['SsmSite']['site_manage_user']);
        $this->set('status', $status);
        $this->set('content', $content);
        $this->set('report_id', $report_id);
        $this->set('month', $month);
        $this->set('start_day', $start_day);
        $this->set('end_day', $end_day);
        $this->set('data_client', $data_client);
        $this->set('last_day_of_month', $last_day_of_month);
        $this->set('month_deadline', $month_deadline);
        $this->set('day_deadline', $day_deadline);
        $this->set('hour_deadline', $hour_deadline);
    }

    public function setting_edit_week(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        $this->layout = 'ott';
        $this->loadModel('SsmUser');
        $this->loadModel('SsmSite');
        $this->loadModel('SsmSiteUser');
        $report_id = $this->request->params['named']['report_id'];
        Configure::load('config_shishimai');
        $day_in_week = Configure::read('day_in_week');

        //Get data_client
        $data_client = $this->SsmUser->find('all', array(
            'join' => array(
                'table' => 'ssm_site_users',
                'alias' => 'SsmSiteUser',
                'type' => 'INNER',
                'conditions' => array(
                    'SsmUser.id' => 'SsmSiteUser.user_id'
                )
            ),
            'conditions' => array(
                'SsmUser.role' => 'admin',
                'SsmUser.status' => 1
            ),
            'fields' => array(
                'SsmUser.first_name',
                'SsmUser.last_name',
                'SsmUser.id',
            )
        ));
        //Get site_manage_user in site
        $site_manage_user = $this->SsmSite->find('first', array(
            'conditions' => array(
                'SsmSite.id' => $site_id
            ),
            'fields' => array(
                'SsmSite.site_manage_user',
                'SsmSite.hour_dead_week',
                'SsmSite.date_dead_week'
            )
        ));

        $this->set('site_manage_user', $site_manage_user['SsmSite']['site_manage_user']);
        $this->set('hour_dead_week', $site_manage_user['SsmSite']['hour_dead_week']);
        $this->set('date_dead_week', $site_manage_user['SsmSite']['date_dead_week']);
        $this->set('data_client', $data_client);
        $this->set('report_id', $report_id);
        $this->set('cf_day_in_week', $day_in_week);

    }


    public function see_month(){
        $this->layout = 'ott';
        $this->loadModel("SsmReport");
        $report_id = $this->request->params['named']['report_id'];
        $reportInfo = $this->SsmReport->find('first', array(
            'conditions' => array(
                'id' => $report_id
            )
        ));

        //User and Site define ========================================================
        //$site_id    = $this->site_id;
        $site_id = $reportInfo['SsmReport']['site_id'];
        $loginUser  = $this->loginUser;

        if ( !$site_id ) {
            $site_id    = $this->site_id;
        }

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);
        //check expiration contract 
        $report_id = $this->request->params['named']['report_id'];

        $status_report = $this->SsmReport->find('first', array(
            'conditions' => array(
                'SsmReport.id' => $report_id
            ),
            'fields' => array(
                'SsmReport.status' 
            )
        ));

        if($this->user_role !="admin"){
            if(!$this->SsmAuth->checkContractSite($site_id)){
                $this->render('/OttError/no_contract');
                return;
            } else {
                if( $status_report['SsmReport']['status'] == 0 ){
                    $this->redirect(array('action' => 'index'));
                    return;
                }
            }
        }

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        $this->loadModel("SsmReportSlide");
        $this->loadModel("SsmSite");


        //Report info
        $report_info = $this->SsmReport->find("first",
            array(
                'joins'=>array(
                array(
                    'table'=>'ssm_sites',
                    'alias'=>'SsmSite',
                    'type'=>'INNER',
                    'conditions'=>array(
                        'SsmSite.id = SsmReport.site_id'
                    )
                )
            ),
            'conditions' => array(
                'SsmReport.id'  => $report_id,
                'SsmSite.id'    => $site_id
            ),
            'fields' => array('SsmReport.month','SsmReport.year','SsmReport.cv_key','SsmReport.site_target_key','SsmReport.kpi_list')
        ));

        if(!$report_info){
            $this->Session->setFlash('存在しないアドレスになります。レポートの一覧から表示したいレポート名を選択してください。', 'warning');
            $this->redirect(array('action'=>'index'));
        }
        //Report Slide
        $slides = $this->SsmReportSlide->find("all", array(
            "conditions" => array("SsmReportSlide.report_id" => $report_id),
            'order' => 'SsmReportSlide.order_num',
        ));
        //Get site name

        $site_name = $this->SsmSite->find("first", array(
            'conditions' => array(
                'SsmSite.id' => $site_id
                ),
            'fields' => array(
                'SsmSite.site_name',
                'SsmSite.site_url'
                )
            ));

        $this->set('report_id',$report_id);
        $this->set('report_info',$report_info);
        $this->set('slides',$slides);
        $this->set('site_name', $site_name);
        $this->set('site_url', $site_url);
        $this->set('site_id', $site_id);
    }

    public function edit_month(){
        $this->loadModel("SsmReport");

        $report_id = $this->request->params['named']['report_id'];
        $reportInfo = $this->SsmReport->find('first', array(
            'conditions' => array(
                'id' => $report_id
            )
        ));

        //User and Site define ========================================================
        //$site_id    = $this->site_id;
        $site_id = $reportInfo['SsmReport']['site_id'];
        $loginUser  = $this->loginUser;

        if ( !$site_id ) {
            $site_id    = $this->site_id;
        }

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        $this->layout = 'ott';
        $this->loadModel("SsmReport");
        $this->loadModel("SsmReportSlide");
        $this->loadModel("SsmSite");

        $report_id = $this->request->params['named']['report_id'];
        //Report info
        $report_info = $this->SsmReport->find("first",
        array(
            'joins'=>array(
                array(
                    'table'=>'ssm_sites',
                    'alias'=>'SsmSite',
                    'type'=>'INNER',
                    'conditions'=>array(
                        'SsmSite.id = SsmReport.site_id'
                    )
                )
            ),
            'conditions' => array(
                'SsmReport.id'  => $report_id,
                'SsmSite.id'    => $site_id
            ),
            'fields' => array('SsmReport.month','SsmReport.year','SsmReport.cv_key','SsmReport.site_target_key','SsmReport.kpi_list','SsmReport.status')
            )
        );

        if(!$report_info){
            $this->Session->setFlash('存在しないアドレスになります。レポートの一覧から表示したいレポート名を選択してください。', 'warning');
            $this->redirect(array('action'=>'index'));
        }
        //Report Slide
        $slides = $this->SsmReportSlide->find("all", array(
            "conditions" => array("SsmReportSlide.report_id" => $report_id),
            'order' => 'SsmReportSlide.order_num',
        ));
        //Get site name
        $currentSiteUserInfo = $this->Cshishimai->getUserSiteInfo();
        $info_site = $this->SsmSite->find("first", array(
            'conditions' => array(
                'SsmSite.id' => $site_id
            ),
            'fields' => array(
                'SsmSite.site_name',
                'SsmSite.site_url',
                'SsmSite.site_description',
                )
            )
        );
        Configure::load('config_shishimai');
        $report_publish_button = Configure::read('report_publish_button');
        $button_array['report_publish_button'] = $report_publish_button[$report_info['SsmReport']['status']];
        $report_revisions = $this->SsmReportRevision->get_report_revisions($site_id,$report_info['SsmReport']['year'],$report_info['SsmReport']['month']);

        $this->set('report_id',$report_id);
        $this->set('report_info',$report_info);
        $this->set('slides',$slides);
        $this->set('info_site', $info_site);
        $this->set('button_array', $button_array);
        $this->set('report_revisions', $report_revisions);
    }

    public function new_slide(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        $this->layout = 'ott';
        $this->loadModel("OttReport");
        $this->loadModel("SsmReportSlide");
        Configure::load('config_shishimai');
        $upload_dir   = Configure::read('upload_dir');

        if($this->request->is("post")){

            $this->SsmReportSlide->create();

            if(!empty($this->request->data)){
                $this->OttReport->set('title',$this->request->data['title']);
                $this->OttReport->set('description',$this->request->data['description']);
                $this->OttReport->set('data',$this->request->data['info_image']['name']);

                if( $this->OttReport->validate_new_slide()){
                    $type_image = $this->request->data['type_image'];

                    $report_id = $this->request->params['named']['report_id'];
                    $file = $this->request->data['info_image'];
                    $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                    $arr_ext = array('jpg', 'jpeg', 'gif', 'png'); 

                    $order_num = $this->SsmReportSlide->find("count", array(
                        'conditions' => array(
                            'SsmReportSlide.report_id' => $report_id
                            ),
                        'fields' => array(
                            'SsmReportSlide.order_num'
                            )
                        ));

                        $this->SsmReportSlide->set('options', $type_image);
                        $this->SsmReportSlide->set('report_id', $report_id);
                        $this->SsmReportSlide->set('order_num', $order_num + 1);

                        if(!empty($file['name'])){
                            if(in_array($ext, $arr_ext)){
                                $new_filename = $report_id."_".time().".".$ext;
                                if(move_uploaded_file($file['tmp_name'], WWW_ROOT . $upload_dir['report'] . DS . $new_filename)){
                                   $this->SsmReportSlide->set('data',$new_filename);
                                }
                            } else {
                                $this->Session->setFlash(__('エラーになりました。画像を選択してください！'),'warning');
                                return false;
                            }
                        }

                        $this->set('site_id',$site_id);

                        if ($this->SsmReportSlide->save($this->request->data)) 
                            {
                                 $this->Session->setFlash(__('データは保存されました！'),'success');
                                 $this->SsmReportRevision->save_report_revision($report_id);
                                 return $this->redirect(array("action" => "edit_month", "report_id" => $report_id, "site_id" => $site_id));
                            }
                            else
                            {
                                 $this->Session->setFlash(__('データは保存できません。再度試してください！'),'warning');
                            }

                } else{
                    $error = '(*)必須!';
                    $this->set('error', $error);
                    $this->Session->setFlash(__('タイトルの入力は必須です。'),'warning');
                }
            }
        }
    }

    //Function delete report
    public function delete_report(){

        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        Configure::load('config_shishimai');
        $cf_type_option   = Configure::read('type_option');
        $cf_report_status = Configure::read('report_status');
        $uploadDir = Configure::read('upload_dir.report');
        $link = ltrim($uploadDir,'/');
        $uploaddir = WWW_ROOT . $link . DS;

        $this->loadModel("SsmReport");
        $this->loadModel("SsmReportSlide");
        $this->loadModel("SsmReportWeek");

        if(isset($this->request->params['named']['report_id'])){
            $report_id = $this->request->params['named']['report_id'];
            $report = $this->SsmReport->find('first',array(
                'conditions'=>array(
                    'id'=>$report_id
                )
            ));

            if($report){
                if($loginUser['SsmUser']['role'] == 'admin' || $loginUser['SsmUser']['role'] == 'partner'){
                    if($report['SsmReport']['status'] == 1){
                        $this->Session->setFlash('公開レポートは削除できません！', 'warning');
                        $this->redirect(array('action'=>'index', 'site_id' => $site_id));
                    }else{
                        $this->SsmReport->deleteAll(array('SsmReport.id' => $report_id), false);
                        if($report['SsmReport']['type'] == $cf_type_option['m']){
                            //Find image and delete
                            $arr_img = $this->SsmReportSlide->find('all', array(
                                'conditions' => array(
                                    'SsmReportSlide.report_id' => $report_id
                                ),
                                'fields' => array(
                                    'SsmReportSlide.data'
                                )
                            ));
                            foreach($arr_img as $key_img => $img)
                            {
                                if(!empty($img['SsmReportSlide']['data']))
                                {
                                    //unlink($uploaddir.$img['SsmReportSlide']['data']);
                                }
                            }
                            //delete report slide
                            $this->SsmReportSlide->deleteAll(array('SsmReportSlide.report_id' => $report_id), false);
                        }else{
                            //delete report week info
                            $this->SsmReportWeek->deleteAll(array('SsmReportWeek.report_id' => $report_id), false);
                        }
                        $this->Session->setFlash('レポートを正常に削除しました！', 'success');
                        $this->redirect(array('action'=>'index', 'site_id' => $site_id));
                    }
                }else{
                    $this->Session->setFlash('このアカウントはこの機能にアクセスする権限がありません', 'warning');
                    $this->redirect(array('action'=>'index', 'site_id' => $site_id));
                }
            }else{
                $this->Session->setFlash('存在しないアドレスになります。レポートの一覧から表示したいレポート名を選択してください。', 'warning');
                $this->redirect(array('action'=>'index', 'site_id' => $site_id));
            }

        }else{
            $this->Session->setFlash('レポートIDは無効です！', 'warning');
            $this->redirect(array('action'=>'index', 'site_id' => $site_id));
        }

    }

    public function postChatwork(){
        $this->loadModel("SsmSite");
        $this->loadModel("SsmReportWeek");

        //Set Idroom and CwApi
        $site_id = $this->SsmAuth->getActiveSite();
        $info_Cw = $this->SsmSite->find('first', array(
            'conditions' => array(
                'SsmSite.id' => $site_id
                ),
            'fields' => array(
                'SsmSite.chatwork_id', 'SsmSite.chatwork_api'
                )
            )
        );

        //Only run on product server
        if($_SERVER['HTTP_HOST'] == 'web-otetsudai.jp'){
            $chatwork_id    = $info_Cw['SsmSite']['chatwork_id'];
            $chatwork_api   = $info_Cw['SsmSite']['chatwork_api'];
        }else{
            $chatwork_id    = "";
            $chatwork_api   = "";
        }

        //check chatword_id in [room_id]
        $opt_check = array(
            CURLOPT_URL => "https://api.chatwork.com/v2/rooms",
            CURLOPT_HTTPHEADER => array( 'X-ChatWorkToken: ' . $chatwork_api ),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_POST => FALSE,
            );

            $ch_check = curl_init();
            curl_setopt_array( $ch_check, $opt_check );
            $res_check = curl_exec( $ch_check );
            curl_close( $ch_check );

            $convert_json = json_decode($res_check);
            $room_id = array();

            foreach($convert_json as $key => $room){
               $room_id[$key] = $room->room_id;
            }

            if(in_array($chatwork_id, $room_id)){
            //Get content of message
                $report_id = $this->request->params['named']['report_id'];

                $ssmReportWeek_info = $this->SsmReportWeek->find("first", array(
                        'conditions' => array(
                            'SsmReportWeek.report_id' => $report_id
                        ),
                        'fields' => array(
                            'SsmReportWeek.content'
                        )
                    )
                );
                $content = $ssmReportWeek_info['SsmReportWeek']['content'];

                if($content){
                // Input value after 'rid' on url that you want send file
                $rid   = $chatwork_id;
                //Input API key
                $token = $chatwork_api;
                //Input message
                $msg = <<<MSG
$content
MSG;

                header( "Content-type: text/html; charset=utf-8" );
                $data = array(
                  'body' => $msg
                );

                $opt = array(
                  CURLOPT_URL => "https://api.chatwork.com/v2/rooms/{$rid}/messages",
                  CURLOPT_HTTPHEADER => array( 'X-ChatWorkToken: ' . $token ),
                  CURLOPT_RETURNTRANSFER => TRUE,
                  CURLOPT_SSL_VERIFYPEER => FALSE,
                  CURLOPT_POST => TRUE,
                  CURLOPT_POSTFIELDS => http_build_query( $data, '', '&' )
                );

                $ch = curl_init();
                curl_setopt_array( $ch, $opt );
                $res = curl_exec( $ch );
                curl_close( $ch );
                echo 'success';
                } else {
                    echo 'error_data';
                }

            } else {
                echo 'error_room_id';
            }
        exit;
    }

}
