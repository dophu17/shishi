<?php
include_once(__DIR__."/../Vendor/Google/GA.php");
include_once(__DIR__."/../Vendor/Google/GoogleSlide.php");
App::uses('Controller', 'Controller');
/**
 * Dashboards Controller
 *
 * @property Dashboard $Dashboard
 * @property PaginatorComponent $Paginator
 */
class ShishimaiApiController extends Controller {

    public $helpers = array('Shishimai');
    public $components = array('Cshishimai', 'Session','SsmAuth','SsmGA','SsmReportRevision');

    public function beforeFilter(){
      parent::beforeFilter();
      $this->loadModel('SsmSite');
      $this->loginUser = $this->SsmAuth->getLoginUser();

      //Check login
      if(!$this->loginUser){
        echo 'USER_NOT_LOGIN';exit;
      }

      $this->user_role    = $this->loginUser['SsmUser']['role'];
      $this->allow_action = $this->SsmAuth->ui_action[$this->user_role];

      $siteList = $this->SsmSite->getListSiteIDOfUser($this->loginUser['SsmUser']['role'],$this->loginUser['SsmUser']['id']);
      $this->listSiteID       = $siteList['arr_id'];
      $this->listSiteIDName   = $siteList['arr_id_name'];
      $this->listSiteIDInfo   = $siteList['arr_site_info'];
    }

    /**
     * Check site id in user site list
     * @param  [type] $site_id [description]
     * @return [type]          [description]
     */
    public function checkSiteInSiteList($site_id){
      if(isset($site_id) && in_array($site_id,$this->listSiteID)){
        return true;
      }
      return false;
    }

    /**
     * Response Check site id in user site list
     * @param  [type] $site_id [description]
     * @return [type]          [description]
     */
    public function responseCheckInSiteList($site_id){
      if(!$this->checkSiteInSiteList($site_id)){
        echo 'SITE_NOT_IN_SITE_LIST';exit;
      }
    }

    /**
     * User can access to ui action
     * @param  [type] $uiAction [description]
     * @return [type]           [description]
     */
    public function checkLoginUserAllowUiAction($uiAction){
      if(!empty($this->allow_action[$uiAction])){
        return true;
      }else{
        return false;
      }
    }

    /**
     * Response when User can't access to ui action
     * @param  [type] $uiAction [description]
     * @return [type]           [description]
     */
    public function responseCheckLoginUserAllowUiAction($uiAction){
      if(!$this->checkLoginUserAllowUiAction($uiAction)){
        echo 'ERROR_PERMISSION';exit;
      }
    }

    /**
     * Response when User can't access to site and ui action
     * @param  [type] $site_id  [description]
     * @param  [type] $uiAction [description]
     * @return [type]           [description]
     */
    public function responseCheckSiteAndUiAction($site_id,$uiAction){
      $this->responseCheckInSiteList($site_id);
      $this->responseCheckLoginUserAllowUiAction($uiAction);
    }

//============================================= Function Use For This Controller ========================================================

    /**
     * Update target value
     * @return [type] [description]
     */
    public function updateTagetValue(){
    	$this->loadModel('SsmKpi');
      //get input
      $input = $this->request->query;
      $this->responseCheckSiteAndUiAction($input['site_id'],'edit_week_target');

      $site_id =  $input['site_id'];

      //Set validate
      $this->SsmKpi->validate = array(
      	'year' => array(
            'nonEmpty' => array(
                'rule' => array('nonEmpty','numeric'),
                'message' => 'A Year is required',
                'allowEmpty' => false
            )
        ),
        'month' => array(
            'nonEmpty' => array(
                'rule' => array('nonEmpty','numeric'),
                'message' => 'A Month is required',
                'allowEmpty' => false
            )
        ),
        'week' => array(
            'nonEmpty' => array(
                'rule' => array('nonEmpty','numeric'),
                'message' => 'A Week is required',
                'allowEmpty' => false
            )
        ),
        'kpi_key' => array(
            'nonEmpty' =>array(
      				'rule'=>array('nonEmpty'),
      				'message'=>'A kpi_key value is required'
      	     ),
      	     'inArrKpi' =>array(
      				'rule'=>array('inArrKpi'),
      				'message'=>'A kpi_key value is not valid'
      		),
        ),
        'new_value' => array(
            'nonEmpty' => array(
                'rule' => array('nonEmpty'),
                'message' => 'A new value is required',
                'allowEmpty' => false
            )
        ),
     	);

      //Set data to validate
      $this->SsmKpi->set($input);

      //Validate
     	if($this->SsmKpi->validates()){
     		//Reset validate
     		$this->SsmKpi->validate = array();
     		//Clear setted value
     		$this->SsmKpi->clear();

        //Check target exits
        $exist = $this->SsmKpi->find('first',array(
          'conditions'=>array(
            'year'    =>$input['year'],
            'month'   =>$input['month'],
            'week'    =>$input['week'],
            'site_id' =>$site_id
          )
        ));

        $input['kpi_key'] = trim($input['kpi_key']);
        if($input['kpi_key'] == 'avgSessionDuration'){
          if(preg_match("/^((\d{1,2}):)?(\d{1,2}):(\d{1,2})$/",$input['new_value'])){
            sscanf($input['new_value'], "%d:%d:%d", $hours, $minutes, $seconds);
            $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
            $input['new_value'] = $time_seconds;
          }else{
            $input['new_value'] = intval($input['new_value']);
          }
        }else{
          $input['new_value'] = $input['new_value'] >= 0 ? $input['new_value'] : -$input['new_value'] ;
        }

        if($exist){
          //Update
          //Set value to update
          $time           = date('Y-m-d H:i:s');

          if($input['kpi_key'] == 'transactions_1' || $input['kpi_key'] == 'transactions'){
            $data_update    = array(
              'SsmKpi.transactions_1' =>$input['new_value'],
              'SsmKpi.transactions'   =>$input['new_value']
            );
          }elseif($input['kpi_key'] == 'transactionsPerSession_1' || $input['kpi_key'] == 'transactionsPerSession'){
            $data_update    = array(
              'SsmKpi.transactionsPerSession_1' =>$input['new_value'],
              'SsmKpi.transactionsPerSession'   =>$input['new_value']
            );
          }elseif($input['kpi_key'] == 'revenuePerTransaction_1' || $input['kpi_key'] == 'revenuePerTransaction'){
            $data_update    = array(
              'SsmKpi.revenuePerTransaction_1' =>$input['new_value'],
              'SsmKpi.revenuePerTransaction'   =>$input['new_value']
            );
          }else{
            $data_update    = array(
              'SsmKpi.'.$input['kpi_key'] =>$input['new_value']
            );
          }

          $data_condition = array(
            'SsmKpi.year'    =>$input['year'],
            'SsmKpi.month'   =>$input['month'],
            'SsmKpi.week'    =>$input['week'],
            'SsmKpi.site_id' =>$site_id
          );

          $this->SsmKpi->updateAll($data_update,array(
            'SsmKpi.year'    =>$input['year'],
            'SsmKpi.month'   =>$input['month'],
            'SsmKpi.week'    =>$input['week'],
            'SsmKpi.site_id' =>$site_id
          ));
          echo json_encode(
            array(
            'status'=>'success',
            'data'  =>array_merge($data_update,$data_condition)

          ));
        }else{
          $time = date('Y-m-d H:i:s');

          if($input['kpi_key'] == 'transactions_1' || $input['kpi_key'] == 'transactions'){
            $data = array(
              'year'    =>$input['year'],
              'month'   =>$input['month'],
              'week'    =>$input['week'],
              'site_id' =>$site_id,
              'transactions_1' =>$input['new_value'],
              'transactions'   =>$input['new_value']
            );
          }elseif($input['kpi_key'] == 'transactionsPerSession_1' || $input['kpi_key'] == 'transactionsPerSession'){

            $data = array(
              'year'    =>$input['year'],
              'month'   =>$input['month'],
              'week'    =>$input['week'],
              'site_id' =>$site_id,
              'transactionsPerSession_1' =>$input['new_value'],
              'transactionsPerSession'   =>$input['new_value']
            );

          }elseif($input['kpi_key'] == 'revenuePerTransaction_1' || $input['kpi_key'] == 'revenuePerTransaction'){

            $data = array(
              'year'    =>$input['year'],
              'month'   =>$input['month'],
              'week'    =>$input['week'],
              'site_id' =>$site_id,
              'revenuePerTransaction_1' =>$input['new_value'],
              'revenuePerTransaction'   =>$input['new_value']
            );

          }else{
            $data = array(
              'year'    =>$input['year'],
              'month'   =>$input['month'],
              'week'    =>$input['week'],
              'site_id' =>$site_id,
              $input['kpi_key'] =>$input['new_value']
            );
          }

          $this->SsmKpi->save($data);
          echo json_encode(array(
            'status'=>'success',
            'data'  =>array_merge($data)
          ));
        }

     	}else{
     		//Fail
     		echo json_encode(array(
     			'status'=>'error',
     			'data'	=>array()
     		));
     	}
     	exit;
    }

    /**
     * Update actual value menu 2
     * @return [type] [description]
     */
    public function updateActualValue(){
    	$this->loadModel('SsmKpiChange');
      //get input
      $input = $this->request->query;

      $this->responseCheckSiteAndUiAction($input['site_id'],'edit_week_actual');

      $site_id = $input['site_id'];

      //Set validate
      $this->SsmKpiChange->validate = array(
      	'year' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank','numeric'),
                'message' => 'A Year is required',
                'allowEmpty' => false
            )
        ),
        'month' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank','numeric'),
                'message' => 'A Month is required',
                'allowEmpty' => false
            )
        ),
        'week' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank','numeric'),
                'message' => 'A Week is required',
                'allowEmpty' => false
            )
        ),
        'kpi_key' => array(
            'nonEmpty' =>array(
      				'rule'=>array('notBlank'),
      				'message'=>'A kpi_key value is required'
      		),
      		'inArrKpi' =>array(
      				'rule'=>array('inArrKpi'),
      				'message'=>'A kpi_key value is not valid'
      		),
        ),
        'cr_value' => array(
            'nonEmpty' => array(
                'rule' => array('numeric'),
                'message' => 'A current value is required',
                'allowEmpty' => false
            )
        ),
        'new_value' => array(
            'nonEmpty' => array(
                'rule' => array('numeric'),
                'message' => 'A new value is required',
                'allowEmpty' => false
            )
        ),
     	);

      //Set data to validate
      $this->SsmKpiChange->set($input);

      //Validate
     	if($this->SsmKpiChange->validates()){

        $input['new_value'] = $input['new_value'] >= 0 ? $input['new_value'] : -$input['new_value'] ;

     		//Reset validate
     		$this->SsmKpiChange->validate = array();
     		//Clear setted value
     		$this->SsmKpiChange->clear();
     		//Set value to update
        $time = date('Y-m-d H:i:s');

     		$data = array(
            'year'      =>$input['year'],
            'month'     =>$input['month'],
            'week'      =>$input['week'],
            'site_id'   =>$site_id,
            $input['kpi_key']=>($input['new_value'] - $input['cr_value']),

        );
     		$this->SsmKpiChange->save($data);
     		echo json_encode(array(
     			'status'=>'success',
     			'data'	=>$data
     		));
     	}else{
     		//Fail
     		echo json_encode(array(
     			'status'=>'error',
     			'data'	=>array()
     		));
     	}
     	exit;

    }

    /**
     * Update report range
     * @return [type] [description]
     */
    public function updateSiteRange(){
      $input = $this->request->query;
      $this->responseCheckSiteAndUiAction($input['site_id'],'change_site_range');
      $site_id = $input['site_id'];

      $range = array(
          '1'=>$input['number_1'].'_'.$input['number_2'],
          '2'=>$input['number_3'].'_'.$input['number_4']
      );

      $site = $this->SsmSite->find('first',array('conditions'=>array(
        'id'=>$input['site_id']
      )));

      if($site){
        $this->SsmSite->set('id',$input['site_id']);
        $this->SsmSite->set('report_range',serialize($range));
        $this->SsmSite->save();
        echo "SUCCESS";
      }else{
        //create
        echo "SITE_NOT_EXIST_ERROR";
      }
      exit;
    }

    /**
     * Update site kpi
     * @return [type] [description]
     */
    public function updateSiteKPI(){

      $this->responseCheckSiteAndUiAction($this->request->data['site_id'],'change_site_kpi');

      $key_will_update  = (isset($this->request->data['view']) && $this->request->data['view'] == 'transactions') ? 'report_kpi_view2' : 'report_kpi';
      $kpi_checked      = $this->request->data['kpi'];
      $getUserSiteInfo  = $this->Cshishimai->getUserSiteInfo();

      $site_id = $this->request->data['site_id'];

      $site = $this->SsmSite->find('first',array('conditions'=>array(
        'id'=>$site_id
      )));

      if($site){
        $this->SsmSite->set('id',$site_id);
        $this->SsmSite->set($key_will_update,serialize($kpi_checked));
        $this->SsmSite->save();
        echo "SUCCESS";
      }else{
        //create
        echo "SITE_NOT_EXIST_ERROR";
      }
      exit;
    }

    /**
     * Update site note
     * @return [type] [description]
     */
    public function updateSiteNote(){
      $this->responseCheckSiteAndUiAction($this->request->data['site_id'],'edit_advice_note');
      $site_id = $this->request->data['site_id'];

      $site_note      = $this->request->data['site_note'];

      $site = $this->SsmSite->find('first',array('conditions'=>array(
        'id'=>$site_id
      )));

      if($site){
        $this->SsmSite->set('id',$site_id);
        $this->SsmSite->set('site_note',$site_note);
        $this->SsmSite->save();
        echo "SUCCESS";
      }else{
        //create
        echo "SITE_NOT_EXIST_ERROR";
      }
      exit;
    }

    /**
     * Update month note/advice note (add key_note param to update advice note)
     * @return [type] [description]
     */
    public function updateMonthNote(){

      $this->responseCheckSiteAndUiAction($this->request->data['site_id'],'edit_advice_note');
      $site_id = $this->request->data['site_id'];

      $this->loadModel('SsmKpiNote');

      if(!empty($this->request->data['key_note'])){
        $update_key = $this->request->data['key_note'];
      }else{
        $update_key = 'note';
      }

      $note      = $this->request->data['note'];
      $year      = $this->request->data['year'];
      $month     = $this->request->data['month'];

      $kpiNote = $this->SsmKpiNote->find('first',array('conditions'=>array(
        'year'    =>$year,
        'month'   =>$month,
        'site_id' =>$site_id
      )));

      //Check update key advice_note_8 (仕組み作りにかかるコスト) || advice_note_10 (認知／集客対策にかかるコスト)
      if($update_key == 'advice_note_8' || $update_key == 'advice_note_10' || $update_key == 'advice_sessions'){
        $note = intval($note);
      }elseif($update_key == 'advice_transactionsPerSession' || $update_key == 'advice_revenuePerTransaction'){
        $note = floatval($note);
      }

      if($kpiNote){
        $this->SsmKpiNote->clear();
        $this->SsmKpiNote->id = $kpiNote['SsmKpiNote']['id'];
        $this->SsmKpiNote->set('year',$year);
        $this->SsmKpiNote->set('month',$month);
        $this->SsmKpiNote->set($update_key,$note);
        if($this->SsmKpiNote->save()){
          //Get updated record and response
          $kpiNoteUpdated = $this->SsmKpiNote->find('first',array('conditions'=>array(
            'year'    =>$year,
            'month'   =>$month,
            'site_id' =>$site_id
          )));

          $kpiNoteUpdated['SsmKpiNote']['estimated_cost'] = $kpiNoteUpdated['SsmKpiNote']['advice_note_8'] + $kpiNoteUpdated['SsmKpiNote']['advice_note_10'];
          //Build more data display in kpi page
          $kpiNoteUpdated['SsmKpiNote']['estimated_display'] = "¥".number_format(($kpiNoteUpdated['SsmKpiNote']['advice_sessions'] * $kpiNoteUpdated['SsmKpiNote']['advice_transactionsPerSession'] * $kpiNoteUpdated['SsmKpiNote']['advice_revenuePerTransaction'])/100);
          $kpiNoteUpdated['SsmKpiNote']['estimated_cost_display'] = "¥".number_format($kpiNoteUpdated['SsmKpiNote']['estimated_cost']);

          echo json_encode(array(
            'status'=>'SUCCESS',
            'data'=>$kpiNoteUpdated['SsmKpiNote']
          ));
          exit;
        }else{
          echo json_encode(array(
            'status'=>'ERROR',
            'data'=>array()
          ));
          exit;
        }
      }else{
        $this->SsmKpiNote->clear();
        $this->SsmKpiNote->set('year',$year);
        $this->SsmKpiNote->set('month',$month);
        $this->SsmKpiNote->set('site_id',$site_id);
        $this->SsmKpiNote->set($update_key,$note);
        if($this->SsmKpiNote->save()){
          //Get updated record and response
          $kpiNoteUpdated = $this->SsmKpiNote->find('first',array('conditions'=>array(
            'year'    =>$year,
            'month'   =>$month,
            'site_id' =>$site_id
          )));

          $kpiNoteUpdated['SsmKpiNote']['estimated_cost'] = $kpiNoteUpdated['SsmKpiNote']['advice_note_8'] + $kpiNoteUpdated['SsmKpiNote']['advice_note_10'];
          $kpiNoteUpdated['SsmKpiNote']['estimated_cost_display'] = "¥".number_format($kpiNoteUpdated['SsmKpiNote']['estimated_cost']);

          echo json_encode(array(
            'status'=>'SUCCESS',
            'data'=>$kpiNoteUpdated['SsmKpiNote']
          ));
          exit;
        }else{
          echo json_encode(array(
            'status'=>'ERROR',
            'data'=>array()
          ));
          exit;
        }
      }
    }


    /**
     * Load chart report month
     * @return [type] [description]
     */
    public function getchartmonth(){
      $input = $this->request->query;
      //Example Url : shishimaiApi/getchartmonth?year=2017&month=7
      //====================================================================================
      //set up view_id,site_id for module
        $this->loadModel('SsmKpi');
        $this->loadModel('SsmKpiChange');
        $this->loadModel('SsmKpiGaMonth');

        //$this->layout = 'shishimai';
        $this->autoLayout = false;

        if(!isset($input['site_id'])){
          echo "サイトは存在しません。再度確認してください。";exit;
        }
        $site_id = $input['site_id'];

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);

        if(!$siteInfo){
          echo "サイトは存在しません。再度確認してください。";exit;
        }

        $view_id = $siteInfo['ga_view_id'];

        $report_year  = $input['year'];
        $report_month = intval($input['month']) < 10 ? "0".intval($input['month']) : $input['month'];

        $begin_date  = date($report_year.'-'.$report_month.'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));
        $end_date_ex = explode('-',$end_date);
        $end_day_number = $end_date_ex[2];

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $report_kpi_checked = $siteInfo['report_kpi'];


        $cv_key = isset($input['cv_key']) ? $input['cv_key'] : (($siteInfo['ga_ecommerce'] != 1) ? 'transactions_1' : 'transactions');

        $site_target_key = in_array('transactionRevenue',$report_kpi_checked) ? 'transactionRevenue' : ( (in_array('transactions_1',$report_kpi_checked) || in_array('transactions',$report_kpi_checked)) ? $cv_key : 'pageviews' );

        $site_target_key = $input['site_target_key'];

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

        $last_day_number_of_month     = $end_day_number;
        $int_last_day_number_of_month = intval($last_day_number_of_month);
        $rest_day_of_last_week        = 36 - $int_last_day_number_of_month;

        $crr_year  = date('Y');
        $crr_month = date('m');
        if($report_year == $crr_year){
          if($report_month == $crr_month){
            $current_day      = intval(date('d'));
          }else{
            $current_day      = intval($last_day_number_of_month);
          }
        }else{
          $current_day      = intval($last_day_number_of_month);
        }

        $int_current_day  = intval($current_day);

        $this->set('int_current_day',$int_current_day);
        $this->set('last_day_number_of_month',$last_day_number_of_month);

        //Default value =============================================
        //KPI Current value
        $data_current = $this->Cshishimai->gaEmptyData();
        //KPI Predict value
        $data_predict = $this->Cshishimai->gaEmptyData();
        //KPI Target value
        $data_target = $this->Cshishimai->gaEmptyData();
        //KPI Target rate
        $data_target_rate = $this->Cshishimai->gaEmptyData();
        //KPI prev month compare
        $data_prevM_compare = $this->Cshishimai->gaEmptyData();
        //KPI prev year compare
        $data_prevY_compare = $this->Cshishimai->gaEmptyData();
        //KPI status box icon
        $data_status_box = $this->Cshishimai->gaEmptyData();
        //End default value =========================================

        //Query & calculate form DB ======================================================================================================================================
        //Target KPI value
        $dataSsmKpi     = $this->SsmKpi->getDataTargetMonth($report_year,$report_month,$site_id);
        $data_target    = $dataSsmKpi['kpi_month'];
        $target_week    = $dataSsmKpi['kpi_week'];
        $target_total   = $dataSsmKpi['kpi_month'][$site_target_key];
        $this->set('target_total',$target_total);

        //Change KPI value
        $dataSsmKpiChange       = $this->SsmKpiChange->getDataChangeMonth($site_id,$report_year,$report_month);
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
                echo 'システムはこのウェブサイトのこのデータを取得出来ません';exit;
            }elseif(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have sufficient permissions for this profile."){
                echo '閲覧権限の期限が切れたか、Google view idが異なっている可能性があります。担当者にお問い合わせください。';exit;
            }else{
                echo '未定義のエラーです。';exit;
            }
        }

        //Build date string============================
        $day_string = "";
        $arr_first_day_of_week = array();
        for($i = 1;$i<=$int_last_day_number_of_month;$i++)
        {
            if($i==1){
                $day_string .= "'{$report_month}月01日'";

                $arr_first_day_of_week[] = "'{$report_month}月01日'";
            }else{
                if($i == 8){
                    $day_string .= ",'{$report_month}月08日'";
                }elseif($i == 15){
                    $day_string .= ",'{$report_month}月15日'";
                }elseif($i == 22){
                    $day_string .= ",'{$report_month}月22日'";
                }elseif($i == 29){
                    $day_string .= ",'{$report_month}月29日'";
                }else{
                    if($i<10){
                        $day_string .= ",'{$report_month}月0{$i}日'";
                    }else{
                        $day_string .= ",'{$report_month}月{$i}日'";
                    }
                }

                if(($i-1)%7 == 0){
                    if($i<10){
                        $arr_first_day_of_week[] = "'{$report_month}月0{$i}日'";
                    }else{
                        $arr_first_day_of_week[] = "'{$report_month}月{$i}日'";
                    }
                }
            }
        }

        //rest day of last week string
        for($i = 1;$i <= $rest_day_of_last_week;$i++){
            $in_crr_month  = intval($report_month);
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

        //Begin top total left
        if($report_year == $crr_year && $report_month == $crr_month){
          $report_begin_date  = "{$report_year}-{$report_month}-01";
          $report_end_date    = "{$report_year}-{$report_month}-".((intval($current_day) > 10) ? (intval($current_day) - 1) : "0".(intval($current_day) - 1));
          $now_show_current_month = true;
        }else{
          $report_begin_date  = "{$report_year}-{$report_month}-01";
          $report_end_date    = "{$report_year}-{$report_month}-".$current_day;
          $now_show_current_month = false;
        }

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

        if($now_show_current_month){
          $target_all = (($total_left_after_check_change)/(intval($current_day) - 1)) * intval($last_day_number_of_month);
        }else{
          $target_all = (($total_left_after_check_change)/(intval($current_day))) * intval($last_day_number_of_month);
        }

        $this->set('now_show_current_month',$now_show_current_month);

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

        //Chart ====================================================================================================
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
            $get_start_end_of_week = $this->Cshishimai->getStartEnDateOfWeek($report_year,$report_month,$week);
            $get_start_end_of_week['start_day'] = intval($get_start_end_of_week['start_day']);
            $get_start_end_of_week['end_day'] = intval($get_start_end_of_week['end_day']);
            $avg_value = $week_change[$site_target_key]/($get_start_end_of_week['end_day'] - $get_start_end_of_week['start_day'] + 1);

            for($i = $get_start_end_of_week['start_day'];$i <= $get_start_end_of_week['end_day'] ;$i++){
                $actual_value[$i]['value'] = $avg_value;
                $total_alll += $actual_value[$i]['value'];

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


        if($now_show_current_month){
          $last_day_show_total_chart = ($last_day_show_total_chart > ($current_day-1)) ? $last_day_show_total_chart : ($current_day-1);
        }else{
          $last_day_show_total_chart = $current_day;
        }

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

        $ratio_with_target = ($data_total_left_display['total_left_after_check_change'] * 100) / $total_2;
        $this->set('ratio_with_target',$ratio_with_target);

        $this->set('actual_value_chart_string',$actual_value_chart_string);
        $this->set('actual_value_chart_2_string',$actual_value_chart_2_string);

        $this->set('target_value_chart_string',$target_value_chart_string);
        $this->set('target_value_chart_2_string',$target_value_chart_2_string);

        if($report_year == $crr_year && $report_month == $crr_month){
          $start_date_display     = $report_month."/1";
          $current_date_display   = $report_month."/".($current_day-1);
        }else{
          $start_date_display     = $report_month."/1";
          $current_date_display   = $report_month."/".$current_day;
        }

        $this->set('start_date_display',$start_date_display);
        $this->set('current_date_display',$current_date_display);
        $this->set('last_day_of_month',$report_month."/".$last_day_number_of_month);

      //====================================================================================

    }

    /**
     * Get kpi box
     * @return [type] [description]
     */
    public function getkpibox(){
      //http://shishimai.dev/shishimaiApi/getkpibox?year=2017&month=8
      Configure::load('config_shishimai');

      $input = $this->request->query;
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

    /**
     * Get kpi box week
     * @return [type] [description]
     */
    public function getkpiboxweek(){
      //http://shishimai.dev/shishimaiApi/getkpiboxweek?year=2017&month=1&week=3&site_id=1
      Configure::load('config_shishimai');
      $input = $this->request->query;
      $this->loadModel('SsmKpi');
      $this->loadModel('SsmKpiChange');
      $this->loadModel('SsmKpiGaWeek');

      $this->autoLayout = false;

      if(!isset($input['site_id'])){
        echo "サイトは存在しません。再度確認してください。";exit;
      }
      $site_id = $input['site_id'];

      $siteInfo = $this->SsmSite->getSiteInfo($site_id);

      if(!$siteInfo){
        echo "site_not_exist";exit;
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

      //Set report kpi checked data
      if(isset($input['kpi_list'])){
        if($input['kpi_list'] != "" && $input['kpi_list'] != 'all'){
          $report_kpi_checked = explode(',',$input['kpi_list']);
        }
      }

      $site_target_key = in_array('transactionRevenue',$report_kpi_checked) ? 'transactionRevenue' : ( (in_array('transactions_1',$report_kpi_checked) || in_array('transactions',$report_kpi_checked)) ? $cv_key : 'pageviews' );

      $this->set('report_kpi_checked',$report_kpi_checked);
      $view_id = $siteInfo['ga_view_id'];

      $report_year  = $input['year'];
      $report_month = intval($input['month']);
      $report_week  = intval($input['week']);

      $GA = new GA($view_id);
      try{
        $result = $GA->test();
      }catch (Exception $e) {
        $msg = $e->getMessage();
        $msg_obj = json_decode($msg);
        if(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have any Google Analytics account."){
            echo 'システムはこのウェブサイトのこのデータを取得出来ません';exit;
        }elseif(isset($msg_obj->error->status) && $msg_obj->error->status == "PERMISSION_DENIED" && $msg_obj->error->message == "User does not have sufficient permissions for this profile."){
            echo '閲覧権限の期限が切れたか、Google view idが異なっている可能性があります。担当者にお問い合わせください。';exit;
        }else{
            echo '未定義のエラーです。';exit;
        }
      }

      //Get target week
      $target = $this->SsmKpi->getDataTargetWeek($report_year,$report_month,$report_week,$site_id);

      $this->set('data_target',$target);
      //Get actual week
      $actual_ga     = $this->SsmKpiGaWeek->getGAWeekData($GA,$site_id,$report_year,$report_month,$report_week);
      $actual_change = $this->SsmKpiChange->getDataWeekChange($site_id,$report_year,$report_month,$report_week);
      $actual        = $this->Cshishimai->reCalculateField($this->SsmGA->gaTotal($actual_ga,$actual_change));

      $this->set('actual',$actual);
      $week_in = $this->Cshishimai->getWeekIn($report_year,$report_month,$report_week);

      $this->set('week_in',$week_in);
      if($week_in == 'present'){
        $crr_week_info = $this->SsmKpiGaWeek->getWeekInfo($report_year,$report_month,$report_week);
        $day_get_data = intval(date('d')) - $crr_week_info['begin_day'];
        $predict = $this->Cshishimai->reCalculateField($this->Cshishimai->gaPredict($actual,$day_get_data,$crr_week_info['total_day_in_week']));
        $this->set('data_predict',$predict);
      }else{
        $predict = $actual;
        $this->set('data_predict',$predict);
      }

      $ratio_actual_target = $this->Cshishimai->gaRatio($predict,$target,1);
      $this->set('data_target_rate',$ratio_actual_target);

      //week prev month
      $prev_year  = ($report_month > 1) ? $report_year : ($report_year - 1);
      $prev_month = ($report_month > 1) ? ($report_month - 1) : 12;

      $prev_ga     = $this->SsmKpiGaWeek->getGAWeekData($GA,$site_id,$prev_year,$prev_month,$report_week);
      $prev_change = $this->SsmKpiChange->getDataWeekChange($site_id,$prev_year,$prev_month,$report_week);
      $prev        = $this->Cshishimai->reCalculateField($this->SsmGA->gaTotal($prev_ga,$prev_change));

      $ratio_actual_prev = $this->Cshishimai->gaRatio($predict,$prev);
      $this->set('data_prevM_compare',$ratio_actual_prev);

      //Prev year
      $prev_year  = $report_year - 1;
      $prev_month = $report_month;

      $prevY_ga     = $this->SsmKpiGaWeek->getGAWeekData($GA,$site_id,$prev_year,$prev_month,$report_week);
      $prevY_change = $this->SsmKpiChange->getDataWeekChange($site_id,$prev_year,$prev_month,$report_week);
      $prevY        = $this->Cshishimai->reCalculateField($this->Cshishimai->gaTotal($prevY_ga,$prevY_change));

      $ratio_actual_prevY = $this->Cshishimai->gaRatio($predict,$prevY);
      $this->set('data_prevY_compare',$ratio_actual_prevY);

      $this->set('data_status_box',$this->Cshishimai->iconStatus($predict,$target));

      if($report_week == 1){
          $start = $report_month."/1";
          $end   = $report_month."/7";
      }elseif($report_week == 2){
          $start = $report_month."/8";
          $end   = $report_month."/14";
      }elseif($report_week == 3){
          $start = $report_month."/15";
          $end   = $report_month."/21";
      }elseif($report_week == 4){
          $start = $report_month."/22";
          $end   = $report_month."/28";
      }elseif($report_week == 5){
          $date = date($report_year."-".($report_month > 9 ? $report_month : "0".intval($report_month))."-01");
          $date = date('m/t', strtotime($date));
          $start = $report_month."/29";
          $end   = $date;
      }
      $this->set('start_date_display',$start);
      $this->set('current_date_display',$end);
      $this->set('last_day_of_month',$end);
    }

    /**
     * Edit report slide
     * @return [type] [description]
     */
    public function editReportSlide() {
        Configure::load('config_shishimai');
        $slide_option = Configure::read('slide_option');

        $this->loadModel("SsmReportSlide");

        if ($this->request->is("post")) {
            $this->SsmReportSlide->id = $this->request->data["report_slide_id"];
            $options = $this->request->data["options"];
            $title = $this->request->data["edit_title"];

            //Validate Title of slide_image
            if (($options == 'image_slide_title' || $options == 'image_slide') && empty($title)) {
                echo 'error_title';
                exit;
            }

            $data = array(
                "title" => $title,
                "report_id" => $this->request->data["report_id"],
                "options" => $options,
                "description" => $this->request->data["edit_description"],
            );

            $this->SsmReportSlide->set($data);
            $this->SsmReportSlide->save($data);

            $this->SsmReportRevision->save_report_revision($this->request->data["report_id"]);

            echo 'success';
            exit;
        }
    }

    /**
     * Delete report slide
     * @return [type] [description]
     */
    public function deleteReportSlide() {
        $this->loadModel("SsmReportSlide");

        $slideDelete = $this->SsmReportSlide->find("first", array(
            'conditions' => array(
                'SsmReportSlide.id' => $this->request->data['report_slide_id']
            )
        ));

        if ($slideDelete) {
            $this->SsmReportSlide->delete($slideDelete['SsmReportSlide']['id']);

            $this->SsmReportRevision->save_report_revision($slideDelete['SsmReportSlide']['report_id']);

            echo "success";
        } else {
            echo "error";
        }
        exit;
    }

    /**
     * Update order number of slide
     * @return [type] [description]
     */
    public function editOrderNum() {
        $this->loadModel("SsmReportSlide");
        if ($this->request->is('post')) {
            $data = $this->request->data;
            if (!empty($data)) {
                $id = 0;
                foreach ($data as $item) {
                    $id = $item['slide_id'];
                    $number = $item['slide_index'];
                    $this->SsmReportSlide->id = $id;
                    $this->SsmReportSlide->set('order_num', $number);
                    $this->SsmReportSlide->save();
                }
                $report_slide = $this->SsmReportSlide->find('first', [
                        'conditions' => ['id' => $id]
                    ]
                );
                if ($report_slide) {
                    $this->SsmReportRevision->save_report_revision($report_slide["SsmReportSlide"]["report_id"]);
                }
                echo "success";
            }
        } else {
            echo "Error";
        }
        exit();
    }

    /**
     * Update report week
     * @return [type] [description]
     */
    public function doingEditWeek(){
      $this->loadModel("SsmReportWeek");
      $this->loadModel("SsmReport");

      if($this->request->is('post')){

        if(!empty($this->request->data)){
          $info_week = $this->request->data;

          //Update info in SsmReportWeek
          $this->SsmReportWeek->updateAll(
            array(
              'SsmReportWeek.content' => "'".$info_week['content']."'",
              // 'SsmReportWeek.month' => $info_week['month'],
              // 'SsmReportWeek.day' => $info_week['day'],
              // 'SsmReportWeek.hour' => $info_week['hour'],
              'SsmReportWeek.user_id' => $info_week['fromUser'],
              'SsmReportWeek.status' => $info_week['status']
            ),
            array('SsmReportWeek.report_id' => $info_week['report_id'])
          );

          //Update status in SsmReport
          $this->SsmReport->id = $info_week['report_id'];
          $status = array(
            'status' => 0
          );
          $this->SsmReport->set($status);
          $this->SsmReport->save();

          echo "success";
        }
      } else {
        echo 'error';
      }
      exit;
    }

    /**
     * Edit week report
     * @return [type] [description]
     */
    public function doneEditWeek(){
      $this->loadModel("SsmReportWeek");
      $this->loadModel("SsmReport");

      if($this->request->is('post')){
        if(!empty($this->request->data)){
          $info_week = $this->request->data;
          //updat info in SsmReportWeek
          $this->SsmReportWeek->updateAll(
            array(
              'SsmReportWeek.content' => "'".$info_week['content']."'",
              // 'SsmReportWeek.month' => $info_week['month'],
              // 'SsmReportWeek.day' => $info_week['day'],
              // 'SsmReportWeek.hour' => $info_week['hour'],
              'SsmReportWeek.user_id' => $info_week['fromUser'],
              'SsmReportWeek.status' => $info_week['status']
            ),
            array('SsmReportWeek.report_id' => $info_week['report_id'])
          );
          //updat info in SsmReport
          $this->SsmReport->id =  $info_week['report_id'];
          $data_report = array(
            'status' => 1
            );

          $this->SsmReport->set($data_report);
          $this->SsmReport->save();
          echo "success";
        }
      } else {
        echo 'error';
      }
      exit;
    }

    /**
     * Update Status report to public
     * @return [type] [description]
     */
    public function publicReportMonth(){
      $response = [
          'status' => 'error',
          'data' => '',
          'button' => '',
      ];
      if($this->request->is('post')){
        $id = $this->request->data['id'];
        $this->loadModel('SsmReport');
        $info = $this->SsmReport->find('first', array('conditions' => array('id' => $id)));
        $status = (!$info['SsmReport']['status']) ? 1 : 0;
        $this->SsmReport->id = $id;
        $data = array(
          'status' => $status
        );
        $this->SsmReport->set($data);
        $this->SsmReport->save();
        Configure::load('config_shishimai');
        $report_publish_button = Configure::read('report_publish_button');
        $response = [
            'status' => 'success',
            'data' => $status,
            'button' => $report_publish_button[$status],
        ];
      }
      echo json_encode($response);
      exit;
    }

    /**
     * Check room CW
     * @return [type] [description]
     */
    public function getRoomCw(){
      if($this->request->is('post')){

        if(!empty($this->request->data['cw_api'])){
          $token = $this->request->data['cw_api'];
          $opt = array(
          CURLOPT_URL => "https://api.chatwork.com/v2/rooms",
          CURLOPT_HTTPHEADER => array( 'X-ChatWorkToken: ' . $token ),
          CURLOPT_RETURNTRANSFER => TRUE,
          CURLOPT_SSL_VERIFYPEER => FALSE,
          CURLOPT_POST => FALSE,
          );

          $ch = curl_init();
          curl_setopt_array( $ch, $opt );
          $res = curl_exec( $ch );
          curl_close( $ch );
          echo $res;
        } else {
          echo "not_found_key";
        }
      }else{
        echo "method_wrong";
      }
      exit;
    }

    /**
     * Check Ga view id is right?
     * @return [type] [description]
     */
    public function checkGaKey(){
      Configure::load('config_shishimai');
      $type_option = Configure::read('type_option');
      if(isset($this->request->data['ga_view_id']) && $this->request->data['ga_view_id'] !=""){
        $view_id = $this->request->data['ga_view_id'];
        $ga = new GA($view_id);
        try {
            $result = $ga->test();
        } catch (Exception $e) {
            //var_dump($e->getMessage());
            echo "INVALID_GA_VIEW_ID";exit;
        }
        echo 'SUCCESS';exit;
      }else{
        echo "VIEW_ID_EMPTY";exit;
      }
    }

    /**
     * Upload file report page
     * @return [type] [description]
     */
    public function uploadfile(){

          $this->loadModel('SsmReportSlide');
          Configure::load('config_shishimai');
          $uploadDir = Configure::read('upload_dir.report');
          $uploaddir = WWW_ROOT . ltrim($uploadDir,'/') . DS;

          if($this->request->is('post')){

            if(($_FILES["file"]["type"]) == ""){
              echo 'nothing';
            }  // if(!isset($_FILES["file"]["type"]))
            else
            {
              $validextensions = array("jpeg", "jpg", "png");
              $temporary = explode(".", $_FILES["file"]["name"]);
              $file_extension = end($temporary);
              if (
                (
                  ($_FILES["file"]["type"] == "image/png") || 
                  ($_FILES["file"]["type"] == "image/jpg") || 
                  ($_FILES["file"]["type"] == "image/jpeg")
                ) && 
                ($_FILES["file"]["size"] < 3000000) && 
                in_array($file_extension, $validextensions)
              ){
                if ($_FILES["file"]["error"] > 0)
                {
                  echo "error";
                  //echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
                }
                else
                {
                  $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                  $file_name = time()."_".rand(300,500);
                  $targetPath = $uploaddir.$file_name.".".$file_extension; // Target path where file is to be stored
                  move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                  // echo $file_name;
                  $id = $this->request->data['slide_id'];
                  $report_id = $this->request->params['named']['report_id'];

                  //Get old image and delete
                  $old_image = $this->SsmReportSlide->find('first', array(
                    'conditions' => array(
                      'SsmReportSlide.id' => $id
                    ),
                    'fields' => array(
                      'SsmReportSlide.data'
                    )
                  ));
                  $data_image = $old_image['SsmReportSlide']['data'];
                  unlink($uploaddir.$data_image);

                  //Update new image
                  $this->SsmReportSlide->id = $id;
                  $data = array(
                    'data' => $file_name.".".$file_extension
                  );
                  $this->SsmReportSlide->save($data);
                  echo 'success';
                }
              }
              else
              {
              echo "error_size_type";
              }
            }
            exit;
        }
    }

    /**
     * Get Advice Chart
     * @return [type] [description]
     */
    public function getAdviceChart(){
      $this->autoLayout = false;
      $this->loadModel('SsmKpi');
      $this->loadModel('SsmKpiChange');
      $this->loadModel('SsmKpiGaMonth');
      $this->loadModel('SsmKpiNote');

      if(!isset($this->request->query['site_id'])){
        echo "サイトは存在しません。再度確認してください。";exit;
      }
      $site_id = $this->request->query['site_id'];

      Configure::load('config_shishimai');
      //User and Site define ========================================================

      $this->set('site_id',$site_id);

      $siteInfo = $this->SsmSite->getSiteInfo($site_id);
      $this->set('siteInfo',$siteInfo);

      if(!$siteInfo){
          echo 'サイトは存在しません。再度確認してください。';exit;
      }

      //End User and Site define ====================================================

      //Range config
      $range_config = !empty($siteInfo['report_range']) ? $siteInfo['report_range'] : array(
          '1'=>'1_6',
          '2'=>'7_12');
      //Set range display on popup
      $range_1 = explode('_',$range_config['1']);
      $range_2 = explode('_',$range_config['2']);

      $current_year  = date('Y');
      $current_month = date('m');
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
      foreach ($range_data as $range_item) {
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

      $advice_note    = array();
      if(!empty($kpiNote)){
          foreach ($kpiNote as $note) {
              $advice_note[$note['SsmKpiNote']['month']] = $note['SsmKpiNote'];
          }
      }

      //And setup advice field

      $advice_keys = Configure::read('advice_note_caculate');

      //build kpi list array map by key
      $kpis_list_map_by_key = array();
      foreach ($kpis_list as $value) {
          $kpis_list_map_by_key[$value['key']] = $value;
      }

      //Advice Calculate
      $i = 1;
      $advice_by_month = array();
      foreach ($range_data as $range_item) {
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

      $estimated_string = array();
      $estimated_cost_string = array();
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
          $estimated_string[] = round($a_data['estimated']);
          $estimated_cost_string[] = round($a_data['estimated_cost']);
      }
      $estimated_string = implode(',',$estimated_string);
      $estimated_cost_string = implode(',',$estimated_cost_string);

      $m_string = array();
      foreach ($range_data as $m_in_range) {
        $m_string[] = "'".$m_in_range['m'].'月'."'";
      }
      $m_string = implode(',',$m_string);

      $this->set('m_string',$m_string);
      $this->set('estimated_string',$estimated_string);
      $this->set('estimated_cost_string',$estimated_cost_string);
    }

    /**
     * Get kpi clients limit (for route)
     * @return [type] [description]
     */
    public function getKpiClient(){
      set_time_limit(120);
      $this->autoLayout = false;
      $this->loadModel('SsmKpiChange');
      $this->loadModel('SsmKpiGaMonth');

      $this->responseCheckLoginUserAllowUiAction('api_getKpiClient');

      //Now
      $now_year   = date('Y');
      $now_month  = intval(date('m'));
      $now_day    = intval(date('d'));

      //Total current month
      $date   = date("Y-m-01");
      $end    = date('Y-m-t', strtotime($date));
      $ex = explode('-',$end);
      $total_day_in_month = $ex[2];

      //Get begin site id ,year ,month
      $site_id = !empty($this->request->query['begin_site_id']) ? intval($this->request->query['begin_site_id']) : 0;
      $year    = !empty($this->request->query['year']) ? intval($this->request->query['year']) : $now_year;
      $month   = !empty($this->request->query['month']) ? intval($this->request->query['month']) : $now_month;
      $prev_stt = !empty($this->request->query['prev_stt']) ? intval($this->request->query['prev_stt']) : 0;

      //get site list
      $list = $this->SsmSite->query('
      SELECT 
        ssm_sites.id,
        ssm_sites.site_name,
        ssm_sites.ga_view_id,
        ssm_sites.show_on_menu,
        ssm_sites.report_kpi,
        ssm_sites.ga_ecommerce,
        t.transactionRevenue_target,
        t.transactions_1_target,
        t.transactions_target,
        t.pageviews_target
      FROM ssm_sites
      LEFT JOIN
      (
        SELECT
          site_id,
          sum(transactionRevenue) as transactionRevenue_target,
          sum(transactions_1) as transactions_1_target,
          sum(transactions) as transactions_target,
          sum(pageviews) as pageviews_target
        FROM ssm_kpis
        WHERE
        year = '.$year.' AND month = '.$month.' AND site_id > '.$site_id.'
        GROUP BY site_id
      ) as t ON t.site_id = ssm_sites.id
      WHERE
      ssm_sites.id > '.$site_id.' AND ssm_sites.show_on_menu = 1
      ORDER by ssm_sites.id ASC
      LIMIT 10
      ');
      //AND ssm_sites.id = 24

      //build data for display in view
      $data_display = array();
      if($list){
        Configure::load('config_shishimai');
        $kpis_list = Configure::read('kpis');
        $kpis_list_1 = Configure::read('kpis_1');

        foreach ($list as $item) {
          $item_converted = $item['ssm_sites'];
          if($item_converted['report_kpi'] != ""){
            $item_converted['report_kpi'] = unserialize($item_converted['report_kpi']);
          }else{
            $item_converted['report_kpi'] = array();
          }

          $item_converted['transactionRevenue_target'] = ($item['t']['transactionRevenue_target']!== null )? $item['t']['transactionRevenue_target'] : 0;
          $item_converted['transactions_1_target']  = ($item['t']['transactions_1_target']!== null )? $item['t']['transactions_1_target'] : 0;
          $item_converted['transactions_target']    = ($item['t']['transactions_target']!== null )? $item['t']['transactions_target'] : 0;
          $item_converted['pageviews_target']       = ($item['t']['pageviews_target']!== null )? $item['t']['pageviews_target'] : 0;
          try {
              if($i%5 == 0){
                usleep(1000000);//1000000ms = 1s
              }

              if($item_converted['ga_ecommerce'] != 1){
                  $cv_key  = 'transactions_1';
                  $revenue = 'revenuePerTransaction_1';
                  $site_target_key = in_array('transactionRevenue',$item_converted['report_kpi']) ? 'transactionRevenue' : (in_array('transactions_1',$item_converted['report_kpi']) ? $cv_key : 'pageviews' );
              }else{
                  $cv_key  = 'transactions';
                  $revenue = 'revenuePerTransaction';
                  $site_target_key = in_array('transactionRevenue',$item_converted['report_kpi']) ? 'transactionRevenue' : (in_array('transactions',$item_converted['report_kpi']) ? $cv_key : 'pageviews' );
              }

              //Calculate GA data
              $GA = new GA($item_converted['ga_view_id']);
              if($year == $now_year && $month == $now_month){
                $GA->get_topBounceRate = false;
              }

              $data_ga_crr_month  = $this->SsmKpiGaMonth->getGAMonthData($GA,$item_converted['id'],$year,$month);
              $data_ga            = $data_ga_crr_month['kpis'];
              $data_change_in_month     = $this->SsmKpiChange->getDataChangeMonth($item_converted['id'],$year,$month);
              $data_current_with_change = $this->Cshishimai->gaTotal($data_ga,$data_change_in_month['change_data_in_month']);
              $actual = $this->Cshishimai->reCalculateField($data_current_with_change);

              //End caculate Ga data
              //売上
              $item_converted['transactionRevenue'] = $actual['transactionRevenue'];
              //売上予測 ????
              $item_converted['transactionRevenue_predict']  = 0;
              //達成率 ??????
              $item_converted['predict']  = 0;
              $item_converted['rate']     = 0;

              //Update predict and rate
              if($year == $now_year && $month == $now_month && $now_day != 1)
              {
                $item_converted['predict'] = ($actual[$site_target_key] / ($now_day - 1)) * $total_day_in_month;
                if($site_target_key == 'transactionRevenue'){
                  $item_converted['transactionRevenue_predict'] = $item_converted['predict'];
                }else{
                  $item_converted['transactionRevenue_predict'] = ($actual['transactionRevenue'] / ($now_day - 1)) * $total_day_in_month;
                }
              }elseif(
                ($year == $now_year && $month < $now_month) || ($year < $now_year)
              )
              {
                $item_converted['predict'] = $actual[$site_target_key];
                if($site_target_key == 'transactionRevenue'){
                  $item_converted['transactionRevenue_predict'] = $item_converted['predict'];
                }else{
                  $item_converted['transactionRevenue_predict'] = $actual['transactionRevenue'];
                }
              }

              /*if($item_converted['predict'] != 0 && $item_converted[$site_target_key.'_target'] > 0){
                $item_converted['rate'] = ($item_converted['predict'] * 100)/ $item_converted[$site_target_key.'_target'];
              }*/
              if($actual[$site_target_key] != 0 && $item_converted[$site_target_key.'_target'] > 0){
                $item_converted['rate'] = ($actual[$site_target_key] * 100)/ $item_converted[$site_target_key.'_target'];
              }

              //UU
              $item_converted['uniqueUsers']  = $actual['uniqueUsers'];
              //PV
              $item_converted['pageviews']    = $actual['pageviews'];
              //直帰率
              $item_converted['bounceRate']   = $actual['bounceRate'];
              //コンバージョン
              $item_converted['transactions'] = $actual[$cv_key];
              //客単価
              $item_converted['revenuePerTransaction'] = $actual[$revenue];

              $data_display[] = $item_converted;
              $i++;
          } catch (Exception $e) {
              //errror
              $item_converted['transactionRevenue'] = 0;
              //売上予測 ????
              $item_converted['transactionRevenue_predict'] = 0;
              //達成率 ??????
              $item_converted['predict']  = 0;
              $item_converted['rate']     = 0;
              //UU
              $item_converted['uniqueUsers']  = 0;
              //PV
              $item_converted['pageviews']    = 0;
              //直帰率
              $item_converted['bounceRate']   = 0;
              //コンバージョン
              $item_converted['transactions'] = 0;
              //客単価
              $item_converted['revenuePerTransaction'] = 0;
              $data_display[] = $item_converted;
          }
        }
      }

      //Set data display in view
      $this->set('prev_stt',$prev_stt);
      $this->set('data_display',$data_display);
    }


    /**
     * Copy data month (note data or target data)
     * @return [type] [description]
     */
    public function copyDataMonth(){
      $this->autoLayout = false;
      $this->loadModel('SsmKpi');
      $this->loadModel('SsmKpiChange');
      $this->loadModel('SsmKpiGaMonth');
      $this->loadModel('SsmKpiNote');

      //Validate input data
      if(
        !isset($this->request->data['copy_data_from_site']) ||
        !isset($this->request->data['copy_data_to_site'])   ||
        !isset($this->request->data['copy_data_type'])      ||
        !isset($this->request->data['copy_data_from_year']) ||
        !isset($this->request->data['copy_data_from_month'])||
        !isset($this->request->data['copy_data_to_year'])   ||
        !isset($this->request->data['copy_data_to_month'])  ||
        !in_array($this->request->data['copy_data_type'],array('note_data','target_data'))
      ){
        echo json_encode(array(
          'status'=>'input_invalid',
          'data'  =>array()
        ));exit;
      }

      $from_site_id = intval($this->request->data['copy_data_from_site']);
      $from_year    = intval($this->request->data['copy_data_from_year']);
      $from_month   = intval($this->request->data['copy_data_from_month']);

      $to_site_id = intval($this->request->data['copy_data_to_site']);
      $to_year    = intval($this->request->data['copy_data_to_year']);
      $to_month   = intval($this->request->data['copy_data_to_month']);

      $this->responseCheckInSiteList($from_site_id);
      $this->responseCheckInSiteList($to_site_id);
      $this->responseCheckLoginUserAllowUiAction('copy_month_data');

      if($from_site_id == $to_site_id && $from_year == $to_year && $from_month == $to_month){
        echo json_encode(array(
          'status'=>'from_is_to',
          'data'  =>array()
        ));exit;
      }

      if($this->request->data['copy_data_type'] == 'target_data'){
        //Copy data kpi target
        $begin_date  = date($to_year.'-'.(($to_month > 9) ? $to_month : '0'.$to_month).'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));
        $end_date_ex = explode('-',$end_date);
        $end_day_number = intval($end_date_ex[2]);

        if($end_day_number > 28){
          $count_week = 5;
        }else{
          $count_week = 4;
        }

        //Get from and to data
        $from_and_to_data = $this->SsmKpi->find('all',array(
          'conditions'=>array(
            'OR'=>array(
              array(
                'site_id' => $to_site_id,
                'year'    => $to_year,
                'month'   => $to_month,
                'week <'  => ($count_week + 1)
              ),
              array(
                'site_id' => $from_site_id,
                'year'    => $from_year,
                'month'   => $from_month
              )
            )
          )
        ));

        if(empty($from_and_to_data) && !$this->request->data['copy_confirmed']){
          echo json_encode(array(
            'status'=>'from_not_exist_data',
            'data'  =>array()
          ));exit;
        }

        $data_group = array();
        foreach ($from_and_to_data as $kpi_row) {
          $data_group[$kpi_row['SsmKpi']['site_id']."_".$kpi_row['SsmKpi']['year']."_".$kpi_row['SsmKpi']['month']][$kpi_row['SsmKpi']['week']] = $kpi_row['SsmKpi'];
        }

        $from_group_key = $from_site_id."_".$from_year."_".$from_month;
        $to_group_key   = $to_site_id."_".$to_year.'_'.$to_month;

        //From kpi empty data
        if(empty($data_group[$from_group_key]) && !$this->request->data['copy_confirmed']){
          echo json_encode(array(
            'status'=>'from_not_exist_data',
            'data'  =>array()
          ));exit;
        }

        if(!$this->request->data['copy_confirmed'] && !empty($data_group[$to_group_key])){
          echo json_encode(array(
            'status'=>'target_exits_data',
            'data'  =>array()
          ));exit;
        }

        for ($i = 1;$i <= $count_week ; $i++) {

          //echo "week : $i ===========================<br>";

          if(isset($data_group[$from_group_key][$i])){
            $from_data = $data_group[$from_group_key][$i];
          }else{
            $from_data = array(
              'transactionRevenue'  =>0,
              'pageviews'           =>0,
              'pageviewsPerSession' =>0,
              'sessions'            =>0,
              'avgSessionDuration'  =>0,
              'uniqueUsers'         =>0,
              'transactions'        =>0,
              'transactions_1'      =>0,
              'transactionsPerSession'=>0,
              'transactionsPerSession_1'=>0,
              'revenuePerTransaction'   =>0,
              'revenuePerTransaction_1' =>0,
              'bounceRate'              =>0,
              'topBounceRate'           =>0,
              'percentNewSessions'      =>0
            );
          }

          if(isset($data_group[$to_group_key][$i])){
            //Update
            $data_update = array(
              'SsmKpi.transactionRevenue' =>$from_data['transactionRevenue'] ? $from_data['transactionRevenue'] : 0,
              'SsmKpi.pageviews'          =>$from_data['pageviews'] ? $from_data['pageviews']: 0 ,
              'SsmKpi.pageviewsPerSession'=>$from_data['pageviewsPerSession'] ? $from_data['pageviewsPerSession'] : 0,
              'SsmKpi.sessions'           =>$from_data['sessions'] ? $from_data['sessions'] : 0,
              'SsmKpi.avgSessionDuration' =>$from_data['avgSessionDuration'] ? $from_data['avgSessionDuration'] : 0,
              'SsmKpi.uniqueUsers'        =>$from_data['uniqueUsers'] ? $from_data['uniqueUsers'] : 0,
              'SsmKpi.transactions'       =>$from_data['transactions'] ? $from_data['transactions'] : 0,
              'SsmKpi.transactions_1'     =>$from_data['transactions_1'] ? $from_data['transactions_1'] : 0,
              'SsmKpi.transactionsPerSession' =>$from_data['transactionsPerSession'] ? $from_data['transactionsPerSession'] : 0,
              'SsmKpi.transactionsPerSession_1' =>$from_data['transactionsPerSession_1'] ? $from_data['transactionsPerSession_1'] : 0,
              'SsmKpi.revenuePerTransaction'    =>$from_data['revenuePerTransaction'] ? $from_data['revenuePerTransaction'] : 0,
              'SsmKpi.revenuePerTransaction_1'  =>$from_data['revenuePerTransaction_1'] ? $from_data['revenuePerTransaction_1'] : 0,
              'SsmKpi.bounceRate'         =>$from_data['bounceRate'] ? $from_data['bounceRate'] : 0,
              'SsmKpi.topBounceRate'      =>$from_data['topBounceRate'] ? $from_data['topBounceRate'] : 0,
              'SsmKpi.percentNewSessions' =>$from_data['percentNewSessions'] ? $from_data['percentNewSessions'] : 0
            );

            $this->SsmKpi->UpdateAll(
                $data_update,
                array(
                  'SsmKpi.site_id' => $to_site_id,
                  'SsmKpi.year'    => $to_year,
                  'SsmKpi.month'   => $to_month,
                  'SsmKpi.week'    => $i,
                )
            );

            /*echo "Update <br>";
            print_r($data_update);
            echo "<br>";*/

          }else{
            //Insert
            $this->SsmKpi->create();
            $new_data = array(
              'transactionRevenue' =>$from_data['transactionRevenue'] ? $from_data['transactionRevenue'] : 0,
              'pageviews'          =>$from_data['pageviews'] ? $from_data['pageviews']: 0 ,
              'pageviewsPerSession'=>$from_data['pageviewsPerSession'] ? $from_data['pageviewsPerSession'] : 0,
              'sessions'           =>$from_data['sessions'] ? $from_data['sessions'] : 0,
              'avgSessionDuration' =>$from_data['avgSessionDuration'] ? $from_data['avgSessionDuration'] : 0,
              'uniqueUsers'        =>$from_data['uniqueUsers'] ? $from_data['uniqueUsers'] : 0,
              'transactions'       =>$from_data['transactions'] ? $from_data['transactions'] : 0,
              'transactions_1'     =>$from_data['transactions_1'] ? $from_data['transactions_1'] : 0,
              'transactionsPerSession' =>$from_data['transactionsPerSession'] ? $from_data['transactionsPerSession'] : 0,
              'transactionsPerSession_1' =>$from_data['transactionsPerSession_1'] ? $from_data['transactionsPerSession_1'] : 0,
              'revenuePerTransaction'    =>$from_data['revenuePerTransaction'] ? $from_data['revenuePerTransaction'] : 0,
              'revenuePerTransaction_1'  =>$from_data['revenuePerTransaction_1'] ? $from_data['revenuePerTransaction_1'] : 0,
              'bounceRate'         =>$from_data['bounceRate'] ? $from_data['bounceRate'] : 0,
              'topBounceRate'      =>$from_data['topBounceRate'] ? $from_data['topBounceRate'] : 0,
              'percentNewSessions' =>$from_data['percentNewSessions'] ? $from_data['percentNewSessions'] : 0,
              'site_id'            =>$to_site_id,
              'year'               =>$to_year,
              'month'              =>$to_month,
              'week'               =>$i
            );

            $this->SsmKpi->set($new_data);
            $this->SsmKpi->save();

            /*echo "Create <br>";
            print_r($new_data);
            echo "<br>";*/
          }
        }

        //Response error
        echo json_encode(array(
          'status'=>'success',
          'data'  =>array()
        ));exit;
      }else{
        //Copy data month note
        $from_and_to_data = $this->SsmKpiNote->find('all',array(
          'conditions'=>array(
            'OR'=>array(
              array(
                'site_id' => $to_site_id,
                'year'    => $to_year,
                'month'   => $to_month
              ),
              array(
                'site_id' => $from_site_id,
                'year'    => $from_year,
                'month'   => $from_month
              ),
            )
          )
        ));

        if(count($from_and_to_data) == 1){
          if($from_and_to_data[0]['SsmKpiNote']['year'] == $from_year && $from_and_to_data[0]['SsmKpiNote']['month'] == $from_month){
            $from_data = $from_and_to_data[0]['SsmKpiNote'];
          }else{
            $to_data = $from_and_to_data[0]['SsmKpiNote'];
          }
        }else{
          if($from_and_to_data[0]['SsmKpiNote']['year'] == $from_year && $from_and_to_data[0]['SsmKpiNote']['month'] == $from_month){
            $from_data  = $from_and_to_data[0]['SsmKpiNote'];
            $to_data    = $from_and_to_data[1]['SsmKpiNote'];
          }else{
            $from_data  = $from_and_to_data[1]['SsmKpiNote'];
            $to_data    = $from_and_to_data[0]['SsmKpiNote'];
          }
        }

        if(!$this->request->data['copy_confirmed'] && empty($from_data)){
          echo json_encode(array(
            'status'=>'from_not_exist_data',
            'data'  =>array()
          ));exit;
        }

        if(!$this->request->data['copy_confirmed'] && !empty($to_data)){
          echo json_encode(array(
            'status'=>'target_exits_data',
            'data'  =>array()
          ));exit;
        }

        if(!empty($to_data)){
          //Update
          $data_update = array(
            'SsmKpiNote.note'          =>$from_data['note'] ? "'".$from_data['note']."'" : "''",
            'SsmKpiNote.advice_note_1' =>$from_data['advice_note_1'] ? "'".$from_data['advice_note_1']."'" : "''" ,
            'SsmKpiNote.advice_note_2' =>$from_data['advice_note_2'] ? "'".$from_data['advice_note_2']."'" : "''",
            'SsmKpiNote.advice_note_3' =>$from_data['advice_note_3'] ? "'".$from_data['advice_note_3']."'" : "''",
            'SsmKpiNote.advice_note_4' =>$from_data['advice_note_4'] ? "'".$from_data['advice_note_4']."'" : "''",
            'SsmKpiNote.advice_note_5' =>$from_data['advice_note_5'] ? "'".$from_data['advice_note_5']."'" : "''",
            'SsmKpiNote.advice_note_6' =>$from_data['advice_note_6'] ? "'".$from_data['advice_note_6']."'" : "''",
            'SsmKpiNote.advice_note_7' =>$from_data['advice_note_7'] ? "'".$from_data['advice_note_7']."'" : "''",
            'SsmKpiNote.advice_note_8' =>$from_data['advice_note_8'] ? "'".$from_data['advice_note_8']."'" : "''",
            'SsmKpiNote.advice_note_9' =>$from_data['advice_note_9'] ? "'".$from_data['advice_note_9']."'" : "''",
            'SsmKpiNote.advice_note_10'=>$from_data['advice_note_10'] ? "'".$from_data['advice_note_10']."'" : "''",
            'SsmKpiNote.advice_note_11'=>$from_data['advice_note_11'] ? "'".$from_data['advice_note_11']."'" : "''",
            'SsmKpiNote.advice_note_12'=>$from_data['advice_note_12'] ? "'".$from_data['advice_note_12']."'" : "''",
            'SsmKpiNote.advice_note_13'=>$from_data['advice_note_13'] ? "'".$from_data['advice_note_13']."'" : "''",
            'SsmKpiNote.advice_note_14'=>$from_data['advice_note_14'] ? "'".$from_data['advice_note_14']."'" : "''",
            'SsmKpiNote.advice_note_15'=>$from_data['advice_note_15'] ? "'".$from_data['advice_note_15']."'" : "''",
            'SsmKpiNote.advice_sessions'=>$from_data['advice_sessions'] ? "'".$from_data['advice_sessions']."'" : "''",
            'SsmKpiNote.advice_transactionsPerSession'=>$from_data['advice_transactionsPerSession'] ? "'".$from_data['advice_transactionsPerSession']."'" : "''",
            'SsmKpiNote.advice_revenuePerTransaction'=>$from_data['advice_revenuePerTransaction'] ? "'".$from_data['advice_revenuePerTransaction']."'" : "''"
          );

          $this->SsmKpiNote->UpdateAll(
              $data_update,
              array(
                'SsmKpiNote.site_id' => $to_site_id,
                'SsmKpiNote.year'    => $to_year,
                'SsmKpiNote.month'   => $to_month
              )
          );

          //Response error
          echo json_encode(array(
            'status'=>'success',
            'data'  =>array()
          ));exit;
        }else{
          //Create new
          $this->SsmKpiNote->create();
          $new_data = array(
            'note'          =>$from_data['note'] ? $from_data['note'] : "",
            'advice_note_1' =>$from_data['advice_note_1']? $from_data['advice_note_1'] : "",
            'advice_note_2' =>$from_data['advice_note_2']? $from_data['advice_note_2'] : "",
            'advice_note_3' =>$from_data['advice_note_3']? $from_data['advice_note_3'] : "",
            'advice_note_4' =>$from_data['advice_note_4']? $from_data['advice_note_4'] : "",
            'advice_note_5' =>$from_data['advice_note_5']? $from_data['advice_note_5'] : "",
            'advice_note_6' =>$from_data['advice_note_6']? $from_data['advice_note_6'] : "",
            'advice_note_7' =>$from_data['advice_note_7']? $from_data['advice_note_7'] : "",
            'advice_note_8' =>$from_data['advice_note_8']? $from_data['advice_note_8'] : "",
            'advice_note_9' =>$from_data['advice_note_9']? $from_data['advice_note_9'] : "",
            'advice_note_10'=>$from_data['advice_note_10']? $from_data['advice_note_10'] : "",
            'advice_note_11'=>$from_data['advice_note_11']? $from_data['advice_note_11'] : "",
            'advice_note_12'=>$from_data['advice_note_12']? $from_data['advice_note_12'] : "",
            'advice_note_13'=>$from_data['advice_note_13']? $from_data['advice_note_13'] : "",
            'advice_note_14'=>$from_data['advice_note_14']? $from_data['advice_note_14'] : "",
            'advice_note_15'=>$from_data['advice_note_15']? $from_data['advice_note_15'] : "",
            'advice_sessions'=>$from_data['advice_sessions']? $from_data['advice_sessions'] : 0,
            'advice_transactionsPerSession'=>$from_data['advice_transactionsPerSession']? $from_data['advice_transactionsPerSession'] : 0,
            'advice_revenuePerTransaction'=>$from_data['advice_revenuePerTransaction']? $from_data['advice_revenuePerTransaction'] : 0,
            'site_id'       => $to_site_id,
            'year'          => $to_year,
            'month'         => $to_month
          );

          $this->SsmKpiNote->set($new_data);
          $this->SsmKpiNote->save();
          echo json_encode(array(
            'status'=>'success',
            'data'  =>array()
          ));exit;
        }
      }
    }

    public function restoreReportRevision() {
        $report_id   = intval($this->request->data['report_id']);
        $revision_id = intval($this->request->data['revision_id']);

        $site_id     = intval($this->request->data['site_id']);
        $year        = intval($this->request->data['year']);
        $month       = intval($this->request->data['month']);

        $this->responseCheckInSiteList($site_id);
        $this->SsmReportRevision->restore_report_revision($site_id,$year,$month,$revision_id,$report_id);
    }

    public function updateAdviceTitle(){
      $this->autoLayout = false;
      $this->loadModel('SsmAdviceKey');

      //Validate input data
      if(
        !isset($this->request->data['key'])     ||
        !isset($this->request->data['value'])   || trim($this->request->data['value']) == ""  || (mb_strlen($this->request->data['value'],'UTF-8') > 30) || count(explode('<br />',nl2br(htmlspecialchars($this->request->data['value'])))) > 2 ||
        !isset($this->request->data['year'])    ||
        !isset($this->request->data['start_month'])    ||
        !isset($this->request->data['site_id'])    ||
        !in_array($this->request->data['key'],array('advice_note_13','advice_note_14','advice_note_15'))
      ){
        echo json_encode(array(
          'status'=>'INPUT_INVALID',
          'data'  =>array()
        ));exit;
      }

      $site_id      = intval($this->request->data['site_id']);
      $value        = trim($this->request->data['value']);
      $key          = trim($this->request->data['key']);
      $year         = intval($this->request->data['year']);
      $start_month  = intval($this->request->data['start_month']);

      $this->responseCheckInSiteList($site_id);
      $this->responseCheckLoginUserAllowUiAction('edit_advice_title');

      $db_key_title = $this->SsmAdviceKey->find('first',array(
          'conditions'=>array(
            array(
              'site_id' => $site_id,
              'advkey'  => $key,
              'year'    => $year,
              'start_month' => $start_month
            )
          )
        )
      );

      if($db_key_title){
        //Update
        $data_update = array(
          'SsmAdviceKey.advvalue' => ($value != "" ? $value : "")
        );

        $this->SsmAdviceKey->id = $db_key_title['SsmAdviceKey']['id'];
        $this->SsmAdviceKey->set('advvalue',($value != "" ? $value : ""));
        $this->SsmAdviceKey->save();

      }else{
        //Insert
        $this->SsmAdviceKey->create();
        $new_data = array(
          'site_id'       =>$site_id,
          'year'          =>$year,
          'start_month'   =>$start_month,
          'advkey'           =>$key,
          'advvalue'         =>$value
        );

        $this->SsmAdviceKey->set($new_data);
        $this->SsmAdviceKey->save();
      }

      echo json_encode(array(
        'status'=>'SUCCESS',
        'data'  =>array()
      ));exit;

    }


    /**
     * Update report range
     * @return [type] [description]
     */
    public function updateReportDeadline(){
      $this->loadModel('SsmReport');
      $this->loadModel('SsmReportWeek');
      $input = $this->request->data;

      $report_id = intval($input['report_id']);
      $month  = intval($input['month']);
      $day    = intval($input['day']);
      $hour   = intval($input['hour']);

      $current = date('Y-m-d-H');

      $current_ex = explode('-',$current);
      $crr_year  = $current_ex[0];
      $crr_month = intval($current_ex[1]);
      $crr_day   = intval($current_ex[2]);
      $crr_hour  = intval($current_ex[3]);

      if(!$report_id || !$month || !$day || !$hour){
        echo json_encode(array(
          'status'=>'input_invalid',
          'data'  =>array()
        ));exit;
      }

      $week = $this->SsmReport->find(
        "first", 
        array(
          'conditions' => array(
              'SsmReport.id' => $report_id,
              'type'    =>'type_week'
          )
        )
      );

      if(!$week){
        echo json_encode(array(
          'status'=>'report_not_exits',
          'data'  =>array()
        ));exit;
      }

      if( $month < $crr_month){
        echo json_encode(array(
          'status'=>'input_invalid',
          'data'  =>array()
        ));exit;
      }

      if( $month ==  $crr_month && $day < $crr_day){
        echo json_encode(array(
          'status'=>'input_invalid',
          'data'  =>array()
        ));exit;
      }

      if( $month ==  $crr_month && $day == $crr_day && $hour < $crr_hour){
        echo json_encode(array(
          'status'=>'input_invalid',
          'data'  =>array()
        ));exit;
      }

      //$this->responseCheckSiteAndUiAction($week['SsmReport']['site_id'],'update_report_deadline');

      //update
      $this->SsmReportWeek->updateAll(
          array(
              'SsmReportWeek.hour'  => $hour,
              'SsmReportWeek.day'   => $day,
              'SsmReportWeek.month' => $month,
              'SsmReportWeek.year'  => $crr_year,
          ),
          array(
              'SsmReportWeek.report_id' => $report_id
          )
      );

      echo json_encode(array(
          'status'=>'success',
          'data'  =>array()
        ));exit;

    }

    //Create new task in module task
    public function addTask(){
      $this->loadModel('SsmTask');
      $this->loadModel('SsmTaskLog');
      $input = $this->request->data;
      $user_id = $this->loginUser['SsmUser']['id'];
        $tasks = $this->SsmTask->find('first', array(
            'fields' => array('SsmTask.sort'),
            'order' => 'SsmTask.sort DESC'
        ));

      $task_name = trim($input['task_name']);

      if(!$task_name || $task_name == "" || strlen($task_name) > 140){
        echo json_encode(array(
          'status'=>'input_invalid',
          'data'  =>array()
        ));exit;
      }

      $data = [
          'name'    =>$task_name,
          'est'     => 0,
          'status'  => 0,
          'user_id' => $user_id,
          'created_at' =>date('Y-m-d H:i:s'),
          'updated_at' =>date('Y-m-d H:i:s'),
          'sort' => $tasks['SsmTask']['sort'] + 1
      ];

      $this->SsmTask->create();
      $this->SsmTask->save($data);
      $task_id = $this->SsmTask->getLastInsertId();

      $data_html = <<<MSG
      <li class="list-group-item list-group-item-left d-flex justify-content-between lh-condensed" task_id="{$task_id}" task_name="{$task_name}">
      <div class="wrap_check">
        <input type="checkbox" class="hide_task_checkbox" value="{$task_id}">
      </div>
      <div class="wrap_task_name">
        <h6 class="task_name">{$task_name}</h6>
      </div>
      <span class="wrap_input_time">
        <input type="number" class="input_time" value="0">
        <span class="h_title">h</span>
        <button class="btn_update_time btn btn-default btn-xs edit-actual-submit-btn">保存</button>
      </span>
      </li>
MSG;

      echo json_encode(array(
          'status'=>'success',
          'data'  =>$data_html
        ));exit;

    }

    //Update Estimate time in module task (done)
    public function updateTaskEst(){
        $this->loadModel('SsmTask');
        $this->loadModel('SsmTaskLog');
        $input    = $this->request->data;
        $user_id  = $this->loginUser['SsmUser']['id'];

        $task_id  = intval($input['task_id']);
        $est      = doubleval($input['est']);

        if(!$task_id || $est < 0){
          echo json_encode(array(
            'status'=>'input_invalid',
            'data'  =>array()
          ));exit;
        }

        $this->SsmTask->id = $task_id;
        $this->SsmTask->save(
            ['est' => $est]
        );

        echo json_encode(array(
          'status'=>'success',
          'data'  =>[]
        ));exit;

    }

    //Remove task in module task
    public function removeTask(){
        $this->loadModel('SsmTask');
        $this->loadModel('SsmTaskLog');
        $input    = $this->request->data;
        $user_id  = $this->loginUser['SsmUser']['id'];

        $task_id  = trim($input['task_id']);

        if(!$task_id || $task_id == ""){
          echo json_encode(array(
            'status'=>'input_invalid',
            'data'  =>array()
          ));exit;
        }

        $task_id = explode(',',$task_id);

        $this->SsmTask->updateAll(
            array('SsmTask.status' => 1), 
            array(
                'SsmTask.user_id' => $user_id,
                'id'              => $task_id
            )
        );

        echo json_encode(array(
            'status'=>'success',
            'data'  =>array()
          )
        );exit;
    }

    public function getSiteAutocomplete() {
        $this->autoRender = false;
        $this->loadModel('SsmSite');
        $this->loadModel('SsmSiteUser');
        $userLogin = $this->loginUser;
        $siteName = $this->request->data['siteName'];
        $cond['SsmSite.site_name LIKE'] = '%' . $siteName . '%';
         if ( $userLogin['SsmUser']['role'] == 'admin' ) {
             $sites = $this->SsmSite->find('all', array(
                 'conditions' => $cond,
                 'fields' => array('SsmSite.site_name, SsmSite.id, SsmSite.suspend'),
                 /*'order' => 'SsmSite.site_name ASC'*/
             ));
         } else {
             $cond['SsmSiteUser.user_id'] = $userLogin['SsmUser']['id'];
             $sites = $this->SsmSiteUser->find('all', array(
                 'conditions' => $cond,
                 'joins' => array(
                     array(
                         'table' => 'ssm_sites',
                         'alias' => 'SsmSite',
                         'type' => 'INNER',
                         'conditions' => array(
                             'SsmSiteUser.site_id = SsmSite.id'
                         )
                     )
                 ),
                 'fields' => array('SsmSiteUser.site_id, SsmSiteUser.user_id, SsmSiteUser.id, SsmSite.site_name, SsmSite.id, SsmSite.suspend'),
                 /*'order' => 'SsmSite.site_name ASC'*/
             ));
         }
        $tmpSites = array();
        foreach ( $sites as $site ) {
            $tmpSites[] = $site['SsmSite'];
        }
        return json_encode(array(
            'error' => false,
            'data' => $tmpSites
        ));
    }

    public function changeSort() {
        $this->autoRender = false;
        $this->loadModel('SsmTask');
        $userLogin = $this->loginUser;
        $fromIndex = $this->request->data['fromIndex'];
        $toIndex = $this->request->data['toIndex'];
        $taskId = $this->request->data['taskId'];

        $tasks = $this->SsmTask->find('all', array(
            'conditions' => array(
                'SsmTask.user_id' => $userLogin['SsmUser']['id'],
                'SsmTask.status' => 0
            ),
            'order' => array('SsmTask.sort ASC')
        ));
        $taskTo = array();
        $tmpBetween = array();

        foreach ( $tasks as $index => $task ) {
            if ( $index == $toIndex ) {
                $taskTo['id'] = $task['SsmTask']['id'];
                $taskTo['sort'] = $task['SsmTask']['sort'];
            }
            if ( $fromIndex > $toIndex ) {
                if ( $fromIndex > $index && $toIndex <= $index ) {
                    $tmpBetween[] = array(
                        'id' => $task['SsmTask']['id'],
                        'sort' => $task['SsmTask']['sort'] + 1
                    );
                }
            } else {
                if ( $fromIndex < $index && $toIndex >= $index ) {
                    $tmpBetween[] = array(
                        'id' => $task['SsmTask']['id'],
                        'sort' => $task['SsmTask']['sort'] - 1
                    );
                }
            }
        }
        $tmpBetween[] = array(
            'id' => $taskId,
            'sort' => $taskTo['sort']
        );
        if ( $this->SsmTask->saveAll($tmpBetween) ) {
            return json_encode(array(
                'error' => false,
                'data' => 'Updated success!'
            ));
        }

        return json_encode(array(
            'error' => true,
            'data' => 'Updated fail!'
        ));
    }
}