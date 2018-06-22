<?php
App::uses('SsmAdminController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class OttImportController extends SsmAdminController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session','SsmAuth','SsmGA');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function beforeRender(){
        parent::beforeRender();
    }


    function index(){
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

        $this->set('kpis_list',$kpis_list);

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================
        $this->loadModel('SsmKpiGaWeek');
        $this->loadModel('SsmKpiChange');
        $this->loadModel('SsmKpi');

        if(isset($_FILES['file'])){


            //validate whether uploaded file is a csv file
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
            if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    //open uploaded csv file with read only mode
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                    $year = $this->request->data['year'];
                    //skip first line
                    $week_data  = fgetcsv($csvFile);

                    if((strpos(mb_convert_encoding($week_data[3], "UTF-8","JIS, eucjp-win, sjis-win"), '1日週') !== false) && (strpos(mb_convert_encoding($week_data[4], "UTF-8","JIS, eucjp-win, sjis-win"), '8日週') !== false) && (strpos(mb_convert_encoding($week_data[5], "UTF-8","JIS, eucjp-win, sjis-win"), '15日週') !== false)){

                        //Import csv week file
                        $month = mb_convert_encoding($week_data[5], "UTF-8","JIS, eucjp-win, sjis-win");
                        $month = intval(str_replace('月15日週','',$month));

                        //Get last day of month
                        $begin_date  = date($year.'-'.(intval($month) > 9 ? $month : "0".intval($month)).'-01');
                        $end_date    = date('Y-m-t', strtotime($begin_date));
                        $end_date_ex = explode('-',$end_date);
                        $end_day_number = $end_date_ex[2];

                        $week_title = array();
                        $count_col      = count($week_data)-1;
                        $week = 1;
                        $week_map_col = array();
                        for ($i = 3; $i < $count_col; $i++) {
                            $week_title[]           = mb_convert_encoding($week_data[$i], "UTF-8","JIS, eucjp-win, sjis-win");
                            $week_map_col[$week]    = $i;
                            $week++;
                        }

                        $lines = array();
                        $count_week = count($week_map_col);
                        $line_count = 1;
                        $kpi = array();

                        if(($end_day_number > 28 && $count_week < 5) || ($end_day_number < 29 && $count_week == 5)){
                            $this->Session->setFlash(__('CSVファイルのエラーを検出されました。!'), 'warning');
                            $this->redirect(array('action'=>'index'));
                        }

                        //parse data from csv file line by line
                        $line = 1;
                        $kpi_name = array();
                        while(($line = fgetcsv($csvFile)) !== FALSE){
                            $row_data = array();
                            for ($week = 1; $week <= $count_week; $week++){
                                $col = $week_map_col[$week];
                                $row_data[$week] = mb_convert_encoding($line[$col], "UTF-8","JIS, eucjp-win, sjis-win");
                            }
                            $lines[$line_count] = $row_data;

                            if($line_count % 5 == 1){
                                $kpi_name[] = mb_convert_encoding($line[0], "UTF-8","JIS, eucjp-win, sjis-win");
                            }
                            $line_count++;
                        }

                        //close opened csv file
                        if(!empty($lines)){
                            foreach ($lines as $key => $line_data) {
                                $line_in_group = $key % 5;
                                if($line_in_group == 1){
                                    $kpi_data = array();
                                    $kpi_data['target'] = $line_data;
                                }elseif($line_in_group == 2){
                                    $kpi_data['actual'] = $line_data;
                                    $kpi[] = $kpi_data;
                                }
                            }
                        }

                        $target_csv = array();
                        $acctual_csv = array();
                        foreach ($kpi_name as $key=>$item_name) {
                            if(trim($item_name) == '売上'){
                                $key_kpi = 'transactionRevenue';
                            }elseif(trim($item_name) == 'PV数'){
                                $key_kpi = 'pageviews';
                            }elseif(trim($item_name) == '一人あたりPV数'){
                                $key_kpi = 'pageviewsPerSession';
                            }elseif(trim($item_name) == 'セッション数'){
                                $key_kpi = 'sessions';
                            }elseif(trim($item_name) == '平均セッション時間'){
                                $key_kpi = 'avgSessionDuration';
                            }elseif(trim($item_name) == 'UU数'){
                                $key_kpi = 'uniqueUsers';
                            }elseif(trim($item_name) == 'CV数'){
                                $key_kpi = 'transactions';
                            }elseif(trim($item_name) == 'CVR'){
                                $key_kpi = 'transactionsPerSession';
                            }elseif(trim($item_name) == '客単価'){
                                $key_kpi = 'revenuePerTransaction';
                            }elseif(trim($item_name) == '直帰率'){
                                $key_kpi = 'bounceRate';
                            }elseif(trim($item_name) == 'Top直帰率'){
                                $key_kpi = 'topBounceRate';
                            }elseif(trim($item_name) == '新規セッション率'){
                                $key_kpi = 'percentNewSessions';
                            }

                            for ($w = 1; $w <= $count_week ; $w++){
                                $csv_target_value = str_replace(array('￥','%',','),array('','',''),$kpi[$key]['target'][$w]);
                                $csv_actual_value = str_replace(array('￥','%',','),array('','',''),$kpi[$key]['actual'][$w]);
                                $target_csv[$w][$key_kpi]  = ($csv_target_value == "-")? 0 : $csv_target_value;
                                $acctual_csv[$w][$key_kpi] = ($csv_actual_value == "-")? 0 : $csv_actual_value;

                                if($key_kpi == 'avgSessionDuration'){
                                    //Target
                                    sscanf($target_csv[$w][$key_kpi], "%d:%d:%d", $hours, $minutes, $seconds);
                                    $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                                    $target_csv[$w][$key_kpi] = $time_seconds;
                                    unset($hours);unset($minutes);unset($seconds);

                                    sscanf($acctual_csv[$w][$key_kpi], "%d:%d:%d", $hours, $minutes, $seconds);
                                    $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                                    $acctual_csv[$w][$key_kpi] = $time_seconds;
                                    unset($hours);unset($minutes);unset($seconds);
                                }
                            }
                        }

                        $err = 0;

                        $GA = new GA($view_id);
                        try{
                            $result = $GA->test();
                        }catch (Exception $e) {
                            $msg = $e->getMessage();
                            $msg_obj = json_decode($msg);
                            if(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have any Google Analytics account."){
                                $this->Session->setFlash(__('システムはこのウェブサイトのこのデータを取得出来ません'), 'warning');
                                $err++;
                            }elseif(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have sufficient permissions for this profile."){
                                $this->Session->setFlash(__('閲覧権限の期限が切れたか、Google view idが異なっている可能性があります。担当者にお問い合わせください。'), 'warning');
                                $err++;
                            }else{
                                $this->Session->setFlash(__('未定義のエラーです。'), 'warning');
                                $err++;
                            }
                        }

                        //Loop all week and get data GA
                        $data_ga = array();
                        if($err == 0){
                            //GA OK ======================================================================
                            $week_change = array();
                            for ($w = 1; $w <= $count_week ; $w++){
                                //Get current week
                                $actual_ga[$w] = $this->SsmKpiGaWeek->getGAWeekData($GA,$site_id,$year,$month,$w);
                                //Compare with csv
                                $week_change[$w] = $this->getDiffGaCsv($actual_ga[$w],$acctual_csv[$w]);
                            }
                            //Loop all KPI and Compare data GA with csv data

                            $this->SsmKpiChange->clear();
                            $this->SsmKpiChange->query("DELETE FROM ssm_kpi_changes WHERE year=".$year." AND month=".$month." AND site_id=".$site_id);
                            foreach ($week_change as $w => $change_data) {
                                $this->SsmKpiChange->clear();
                                $change_data['year']    = $year;
                                $change_data['month']   = $month;
                                $change_data['week']    = $w;
                                $change_data['site_id'] = $site_id;
                                $this->SsmKpiChange->save($change_data);

                                //Target week
                                $target_week = $this->SsmKpi->query("SELECT * FROM ssm_kpis as SsmKpi WHERE year=".$year." AND month=".$month." AND week=".$w." AND site_id=".$site_id ." LIMIT 1");
                                $target = $target_csv[$w];

                                if($target_week){
                                    //Update
                                    $target['transactions_1']               = isset($target['transactions']) ? $target['transactions'] : 0;
                                    $target['transactionsPerSession_1']     = isset($target['transactionsPerSession']) ? $target['transactionsPerSession']  : 0;
                                    $target['revenuePerTransaction_1']      = isset($target['revenuePerTransaction']) ? $target['revenuePerTransaction']  : 0;
                                    $this->SsmKpi->updateAll($target,array('ssm_kpi_id'=>$target_week[0]['SsmKpi']['ssm_kpi_id']));
                                }else{
                                    //insert
                                    $target['transactions_1']               = isset($target['transactions']) ? $target['transactions'] : 0;
                                    $target['transactionsPerSession_1']     = isset($target['transactionsPerSession']) ? $target['transactionsPerSession']  : 0;
                                    $target['revenuePerTransaction_1']      = isset($target['revenuePerTransaction']) ? $target['revenuePerTransaction']  : 0;
                                    $target['site_id']                      = $site_id;
                                    $target['year']      = $year;
                                    $target['month']     = $month;
                                    $target['week']      = $w;
                                    $this->SsmKpi->clear();
                                    $this->SsmKpi->save($target);
                                }

                            }
                            //End GA OK ===================================================================

                            $this->Session->setFlash(__('CSVファイルの取り込みに成功しました。'), 'success');
                            $this->redirect(array('action'=>'index'));
                        }

                        //End import csv week file
                    }else{
                        //wrong file
                        $this->Session->setFlash(__('CSVファイルのエラーを検出されました。!'), 'warning');
                        $this->redirect(array('action'=>'index'));
                        //Wrong file csv
                    }
                }else{

                }
                exit;
            }else{
                $this->Session->setFlash(__('ファイルが選択されていないか、またはファイルの形式がCSVファイルではありません。!'), 'warning');
            }
        }
    }

    function getDiffGaCsv($ga_data,$csv_data){

        unset($ga_data['ssm_kpi_ga_week_id']);
        unset($ga_data['year']);
        unset($ga_data['month']);
        unset($ga_data['week']);
        unset($ga_data['site_id']);
        unset($ga_data['created_user_id']);
        unset($ga_data['created_at']);
        unset($ga_data['updated_at']);

        $change_data = array();
        foreach ($ga_data as $key=>$value) {
            $change_data[$key] = (-1 * $value);
        }

        if(isset($csv_data['transactions'])){
            $csv_data['transactions_1']             = $csv_data['transactions'];
        }
        if(isset($csv_data['transactionsPerSession'])){
            $csv_data['transactionsPerSession_1']   = $csv_data['transactionsPerSession'];
        }
        if(isset($csv_data['revenuePerTransaction'])){
            $csv_data['revenuePerTransaction_1']    = $csv_data['revenuePerTransaction'];
        }

        foreach ($csv_data as $key=>$value) {
            $change_data[$key] = $csv_data[$key] - $ga_data[$key];
        }

        $arr_csv_key = array_keys($csv_data);
        foreach ($change_data as $key => $value) {
            if(!in_array($key,$arr_csv_key)){
                $change_data[$key] = 0;
            }
        }

        return $change_data;
    }
}