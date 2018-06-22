<?php
include_once(__DIR__."/../Vendor/Google/Slide.php");
App::uses('SsmAdminController', 'Controller');
class OttExportController extends SsmAdminController {
    public $helpers     = array('Html', 'Form', 'Session');
    public $components  = array('Session','SsmAuth');
    public function beforeFilter(){
        parent::beforeFilter();
    }
    public function beforeRender(){
        parent::beforeRender();
    }
    public function index(){
        $this->autoRender = false;
        $this->loadModel("SsmReportSlide");
        $input = $this->request->query;

        // Get the API client and construct the service object.
        $slide  = new Slide();
        $code = "";
        if(isset($input['code'])){
            $code = $input['code'];
        }
        $client     = $slide->getClient($code);
        if(is_array($client) && isset($client['status']) && $client['status'] == 'redirect'){
            $this->redirect($client['data']);
        }
        //end Get the API client and construct the service object.

        //---------------------------set google slide---------------------------
        $presentationId = '10oAG5l6tqAXDh63_u-8FF6DRyALDtoPpEE90_5jPUko';
        //$slides = $slide->getSlide($presentationId);

        $reportSlides = $this->SsmReportSlide->find("all", array(
            "conditions" => array("SsmReportSlide.report_id" => $input['report_id']),
            'order' => 'SsmReportSlide.order_num',
        ));
        $arrReportSlides = array();
        foreach ( $reportSlides as $index => $value ) {
            $arrReportSlides[] = $value['SsmReportSlide'];

            $slide->addSlide($presentationId, $value['SsmReportSlide']);
        }
        //---------------------------end set google slide---------------------------

        return json_encode(array(
            'presentation_id' => $presentationId,
            'report_slides' => $arrReportSlides
        ));
    }

    public function getkpibox($input){
        //http://shishimai.dev/shishimaiApi/getkpibox?year=2017&month=8
        Configure::load('config_shishimai');

        $this->loadModel('SsmKpi');
        $this->loadModel('SsmKpiChange');
        $this->loadModel('SsmKpiGaMonth');

        $this->autoLayout = false;

        if(!isset($input['site_id'])){
            echo "サイトは存在しません。再度確認してください。";exit;
        }
        $site_id = $input['site_id'];

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);

        if(!$siteInfo){
            echo "サイトは存在しません。再度確認してください。";exit;
        }

        $cv_key = isset($input['cv_key']) ? $input['cv_key'] : (($siteInfo['ga_ecommerce'] != 1) ? 'transactions_1' : 'transactions');

        if($cv_key == 'transactions'){
            $kpis_list = Configure::read('kpis');
        }else{
            $kpis_list = Configure::read('kpis_1');
        }
        $this->set('kpis_list',$kpis_list);

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

        if(isset($input['kpi_list'])){
            if($input['kpi_list'] != "" && $input['kpi_list'] != 'all'){
                $report_kpi_checked = explode(',',$input['kpi_list']);
            }
        }

        $site_target_key = in_array('transactionRevenue',$report_kpi_checked) ? 'transactionRevenue' : ( (in_array('transactions_1',$report_kpi_checked) || in_array('transactions',$report_kpi_checked)) ? $cv_key : 'pageviews' );

        $this->set('report_kpi_checked',$report_kpi_checked);

        $view_id = $siteInfo['ga_view_id'];

        $status_ga = $this->SsmGA->initCom($site_id,$view_id);
        if($status_ga == "ERROR_GA_PERMISSION"){
            echo 'システムはこのウェブサイトのこのデータを取得出来ません';exit;
        }elseif($status_ga == "ERROR_GA_KEY"){
            echo '閲覧権限の期限が切れたか、Google view idが異なっている可能性があります。担当者にお問い合わせください。';exit;
            return;
        }elseif($status_ga == "ERROR_GA_UNKNOW"){
            echo '未定義のエラーです。';exit;
        }

        $this->SsmGA->getReportMonth($input['year'],$input['month']);

        if($this->SsmGA->crr_year == $this->SsmGA->query_year && $this->SsmGA->crr_month == $this->SsmGA->query_month){
            $this->set('month_in','present');

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

            if(intval($this->SsmGA->query_month) == 1){
                $year_rp    = $this->SsmGA->query_year - 1;
                $month_rp   = 12;
            }else{
                $year_rp    = $this->SsmGA->query_year;
                $month_rp   = $this->SsmGA->query_month - 1;
            }

            $ga_data_prev_month         = $this->SsmKpiGaMonth->getGAMonthData($this->SsmGA->ga,$site_id,$year_rp,$month_rp);
            $ga_data_change_prev_month  = $this->SsmKpiChange->getDataChangeMonth($site_id,$year_rp,$month_rp);
            $total_actual_prev_month    = $this->Cshishimai->gaTotal($ga_data_prev_month['kpis'],$ga_data_change_prev_month['change_data_in_month']);
            $prevM    = $this->Cshishimai->reCalculateField($total_actual_prev_month);

            $ga_data_prev_YearM         = $this->SsmKpiGaMonth->getGAMonthData($this->SsmGA->ga,$site_id,($this->SsmGA->query_year-1),$this->SsmGA->query_month);
            $ga_data_change_prev_YearM  = $this->SsmKpiChange->getDataChangeMonth($site_id,($this->SsmGA->query_year-1),$this->SsmGA->query_month);
            $prevY                    = $this->Cshishimai->reCalculateField($this->Cshishimai->gaTotal($ga_data_prev_YearM['kpis'],$ga_data_change_prev_YearM['change_data_in_month']));
            $this->set('col_icon_status',$this->Cshishimai->iconStatus($predict,$target));
        }else{
            $this->set('month_in','');
            $actual   = $this->SsmGA->col_actual;
            $target = $this->SsmGA->col_target;
            $predict  = $actual;
            $prevM = $this->SsmGA->col_prev;
            $prevY = $this->SsmGA->col_prevY;
            $this->set('col_icon_status',$this->Cshishimai->iconStatus($actual,$target));
        }

        $ratio_w_target = $this->Cshishimai->gaRatio($predict,$target,1);
        $ratio_w_prevM = $this->Cshishimai->gaRatio($predict,$prevM);
        $ratio_w_prevY = $this->Cshishimai->gaRatio($predict,$prevY);

        $this->set('show_list_month',$show_list_month);
        //Show total column

        $this->set('actual',$actual);
        $this->set('data_predict',$predict);
        $this->set('data_target',$target);

        $this->set('data_target_rate',$ratio_w_target);
        $this->set('data_prevM_compare',$ratio_w_prevM);
        $this->set('data_prevY_compare',$ratio_w_prevY);

        $start_date_display   = intval($input['month'])."/1";
        $end_date_display     = intval($input['month'])."/".$this->SsmGA->temp_var['end_day_number'];

        $this->set('start_date_display',$start_date_display);
        $this->set('end_date_display',$end_date_display);

    }
}
?>