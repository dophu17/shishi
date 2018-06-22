<?php
App::uses('SsmAdminController', 'Controller');
App::uses('AppHelper', 'View/Helper');

class SsmClientController extends SsmAdminController{
    public $helpers     = array('Shishimai','Html', 'Session');
    public $components  = array('Session','SsmAuth', 'Cshishimai', 'OttClient');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function beforeRender(){
        parent::beforeRender();
    }

    /**
     * List site
     * @return [type] [description]
     */
    public function index(){

        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = 'ott';
        $this->loadModel("SsmUser");
        $this->loadModel("SsmSite");
        $this->loadModel("SsmContract");
        $this->loadModel("SsmPlan");
        Configure::load('config_shishimai');
        // $plan = Configure::read('plan');
        // $site_satisfaction = Configure::read('site_satisfaction');

        //Reset Active Site
        if($this->listSiteIDInfo[$this->site_id]['show_on_menu'] == 0){
            if(in_array(30,array_keys($this->listSiteIDName))){
                $this->SsmAuth->setActiveSite(30);
            }else{
                foreach ($this->listSiteIDInfo as $key=>$value) {
                    if($value['show_on_menu'] == 1){
                        $this->SsmAuth->setActiveSite($key);
                        break;
                    }
                }
            }
        }

        //get info site and contract
        if($this->loginUser['SsmUser']['role'] == 'admin'){
            $info_site = $this->SsmSite->query("
            select 
            ssm_sites.site_name , 
            ssm_sites.site_description, 
            ssm_sites.id, 
            ssm_sites.site_manage_user, 
            ssm_sites.site_satisfaction,
            ssm_sites.suspend,
            ssm_users.first_name,
            ssm_users.last_name,  ssm_users.avatar

            FROM ssm_sites
            LEFT JOIN ssm_users on ssm_sites.site_manage_user = ssm_users.id
            ORDER BY `ssm_sites`.`id` ASC");
        }elseif($this->loginUser['SsmUser']['role'] == 'partner'){

            $listID = implode(",", $this->listSiteID);

            $info_site = $this->SsmSite->query("
            select 
            ssm_sites.site_name , 
            ssm_sites.site_description, 
            ssm_sites.id, 
            ssm_sites.site_manage_user, 
            ssm_sites.site_satisfaction,
            ssm_sites.suspend,
            ssm_users.first_name,
            ssm_users.last_name,  ssm_users.avatar

            FROM ssm_sites
            LEFT JOIN ssm_users on ssm_sites.site_manage_user = ssm_users.id
            WHERE ssm_sites.id in (".$listID.")
            ORDER BY `ssm_sites`.`id` ASC");
        }else{
            $info_site = array();
        }


        $contracts = $this->SsmContract->find('all',array(
            'joins' => array(
                array(
                    'table' => 'ssm_plans',
                    'alias' => 'SsmPlan',
                    'type' => 'INNER',
                    'conditions' => array(
                        'SsmContract.plan_id = SsmPlan.id'
                    )
                )
            ),
            'fields'=>array(
                'SsmContract.site_id','SsmContract.start_day','SsmContract.end_day','SsmPlan.id','SsmPlan.name','SsmPlan.price','SsmContract.plan_price','SsmContract.is_customize_price'
            ),
            'order' => array('SsmContract.start_day ASC'),
        ));

        $ct_site = array();
        foreach ($contracts as $ct) {
            $siteId = $ct['SsmContract']['site_id'];
            $ct_site[$siteId][] = $ct;
        }

        $contract_site = array();

        $now = date('Ymd');

        foreach ($ct_site as $site_id => $site_contract) {
            $count = count($site_contract);

            if ($count == 1) {
                $contract_site[$site_id] = $site_contract[0];
            }else{

                $contract_site[$site_id] = array();
                $prev = 0;
                $next = 0;

                foreach ($site_contract as $key => $ct) {
                    $startDay = str_replace('-','',$ct['SsmContract']['start_day']);
                    $endDay   = str_replace('-','',$ct['SsmContract']['end_day']);

                    if($startDay <= $now && $now <= $endDay){
                        $contract_site[$site_id] = $ct;
                        break;
                    }else{
                        if($startDay < $now){
                            if($endDay > str_replace('-','',$site_contract[$prev]['end_day'])){
                                $prev = $key;
                            }
                        }
                        if($endDay > $now && $next == 0){
                            $next = $key;
                        }
                    }
                }

                if(empty($contract_site[$site_id])){

                    $int_prev_start = intval(str_replace('-','',$site_contract[$prev]['SsmContract']['start_day']));
                    $int_next_start = intval(str_replace('-','',$site_contract[$next]['SsmContract']['start_day']));

                    $int_prev_end = intval(str_replace('-','',$site_contract[$prev]['SsmContract']['end_day']));
                    $int_next_end = intval(str_replace('-','',$site_contract[$next]['SsmContract']['end_day']));

                    if($int_prev_start < $now &&  $int_next_start < $now){

                        if($int_prev_start < $int_next_start){
                            $default_ct = $site_contract[$next];
                        }else{
                            $default_ct = $site_contract[$prev];
                        }
                    }elseif($int_prev_start > $now &&  $int_next_start > $now){
                        if($int_prev_start < $int_next_start){
                            $default_ct = $site_contract[$prev];
                        }else{
                            $default_ct = $site_contract[$next];
                        }
                    }else{

                        if($site_contract[$prev]['SsmContract']['start_day'] < $now){
                            if((intval($now) - $int_prev_end) > ($int_next_start - $now)){
                                $default_ct = $site_contract[$next];
                            }else{
                                $default_ct = $site_contract[$prev];
                            }
                        }else{
                            if((intval($now) - $int_next_end) > ($int_prev_start - $now)){
                                $default_ct = $site_contract[$prev];
                            }else{
                                $default_ct = $site_contract[$next];
                            }
                        }
                    }

                    $contract_site[$site_id] = $default_ct;
                }
            }
        }

        $today = date('Y-m-d');
        $contract_site = array_map(function($contract) use ($today) {
            if ($contract['SsmContract']['end_day'] <= $today) {
                $contract['SsmContract']['expired'] = true;
            }

            return $contract;
        }, $contract_site);

        $this->set('info_site', $info_site);
        $this->set('contract_site', $contract_site);
    }

    /**
     * Add Site
     */
    public function add(){

        //User and Site define ========================================================

        $loginUser  = $this->loginUser;
        $this->set('loginUser',$loginUser);

        //End User and Site define ====================================================

        $this->layout = 'ott';
        $this->loadModel("SsmUser");
        $this->loadModel("SsmSite");
        $this->loadModel("SsmContract");
        $this->loadModel("SsmPlan");
        Configure::load('config_shishimai');
        $kpis = Configure::read('kpis');
        $site_satisfaction = Configure::read('site_satisfaction');
        $cf_ga_email_service = Configure::read('ga_email_service');

        //Get KPI
        $kpi_arr = [];
        foreach($kpis as $key => $val){
            $kpi_arr[$key] = $val['key'];
        }

        $data_user = $this->SsmUser->find("all", array(
            'conditions' => array(
                'SsmUser.role' => 'admin',
                'SsmUser.status' =>1,
                ),
            'fields' => array(
                'SsmUser.first_name','SsmUser.last_name',
                'SsmUser.id',
                'SsmUser.chatwork_api'
                )
            ));
        //Get Plan
        $plan = $this->SsmPlan->find('all', array(
            'fields' => array(
                'SsmPlan.name',
                'SsmPlan.price',
                'SsmPlan.id',
            )
        ));

        $planInfo_map_plan_id = array();
        foreach ($plan as $plan_item) {
            $planInfo_map_plan_id[$plan_item['SsmPlan']['id']] = $plan_item['SsmPlan'];
        }

        $this->set('data_user', $data_user);
        $this->set('plan', $plan);
        $this->set('cf_site_satisfaction', $site_satisfaction);
        $this->set('cf_ga_email_service', $cf_ga_email_service);

        if(!$this->request->is('post')){
            $this->request->data['show_on_menu'] = 1;
        }

        if($this->request->is('post')){
            //Send to form key Chatwork
            if(isset($this->request->data['hidden_data'])){
                $cw_api = trim($this->request->data['cw_api']);
                $show_data = unserialize($this->request->data['hidden_data']);
                $this->request->data = $show_data;
                $this->request->data['cw_api'] = $cw_api;

                $arr_contract = $this->OttClient->arr_contract($show_data);
                $check_contract = 1;
                $this->set('check_contract', $check_contract);
                $this->set('arr_contract', $arr_contract);

                //Update key CW for admin active
                $this->SsmUser->UpdateAll(array(
                    'SsmUser.chatwork_api' => "'".$cw_api."'"
                ), array(
                    'SsmUser.id' => $show_data['user_id']
                ));
            }
            // Finish form
            else{
                //Input again contracts
                $complete_contract = $this->OttClient->arr_contract($this->request->data);

                //echo "<pre>";
                //print_r($complete_contract);exit;

                $site_name          = $this->request->data['site_name'];
                $site_url           = $this->request->data['site_url'];
                $site_description   = $this->request->data['site_description'];
                $site_manage_user   = intval($this->request->data['user_id']);
                $satisfaction       = $this->request->data['satisfaction'];
                $site_note          = $this->request->data['remarks'];
                $ga_ecommerce       = $this->request->data['ecommerce'];
                $auto_send_cw       = $this->request->data['auto_send_cw'] ? $this->request->data['auto_send_cw'] : 0;
                $show_on_menu       = $this->request->data['show_on_menu'] ? $this->request->data['show_on_menu'] : 0;
                $ga_view_id         = trim($this->request->data['ga_view_id']);
                $ga_adword          = trim($this->request->data['ga_adword']);
                $chatwork_id        = !isset($this->request->data['room']) ? 0 : trim($this->request->data['room']);
                $cw_api             = trim($this->request->data['cw_api']);
                $start_day          = $this->request->data['start_day'];
                $end_day            = $this->request->data['end_day'];
                $error_end_day      = '';
                $error_start_day    = '';
                $error_site_description;
                $error_site_name;
                $error_site_url;
                $error_site_ga_view_id;

                //Validate site and contract
                $validate_site = $this->OttClient->have_error_site($site_name, $error_site_name, $site_url, $error_site_url, $site_description, $error_site_description, $ga_view_id, $error_site_ga_view_id, $site_manage_user, $error_site_manage_user);

                $validate_contract;
                $arr_contract       = $this->OttClient->arr_contract($this->request->data);
                $error_contract     = "入力された日付は無効です。再度確認して下さい。";
                $validate_contract  = $this->OttClient->validate_contract_server($arr_contract, $validate_contract);

                if($validate_site){
                    $this->set('validate_site',$validate_site);
                    $this->set('error_site_name',$validate_site['error_site_name']);
                    $this->set('error_site_url',$validate_site['error_site_url']);
                    $this->set('error_site_description',$validate_site['error_site_description']);
                    $this->set('error_site_ga_view_id',$validate_site['error_site_ga_view_id']);
                    // $this->set('error_site_manage_user',$validate_site['error_site_manage_user']);
                }

                if($validate_contract){
                    $this->set('error_contract', $error_contract);
                    $this->set('validate_contract', $validate_contract);
                }

                //Validate chatwork
                $error_validate_chatwork = "(*) 必須!";
                $check_cw_api       = $this->request->data['cw_api'];
                $room_id_inputed    = $this->request->data['room'];
                $check_chatwork_id  = !isset($room_id_inputed) ? $this->request->data['chatwork_id'] : $room_id_inputed;
                $validate_chatwork  = $this->OttClient->validate_chatwork($check_cw_api, $check_chatwork_id);

                if($validate_chatwork == 'api'){
                    if($validate_site){
                        $this->Session->setFlash('必須フィールドに入力してください!', 'warning');
                    } else {
                        $this->Session->setFlash('選択された主担当者はチャットワーク連携未設定です。', 'warning');
                    }
                    $this->set('check_cw_api', $check_cw_api);
                }

                if($validate_chatwork == 'room_id_wrong'){
                    if($validate_site){
                        $this->Session->setFlash('必須フィールドに入力してください!', 'warning');
                    } else {
                        $this->Session->setFlash('ルームIDが正しくありません。', 'warning');
                    }

                    $this->set('room_id_inputed', $room_id_inputed);
                    $this->set('error_validate_chatwork', $error_validate_chatwork);
                    $this->set('check_cw_api', $check_cw_api);
                }

                $this->set('validate_chatwork', $validate_chatwork);
                $this->set('arr_contract', $arr_contract);

                $have_error_site_price = $this->OttClient->have_error_site_price($this->request->data['site_price'],$this->request->data['site_price_per_hour']);

                if($have_error_site_price){
                    $this->set('error_site_price', '数値で入力してください。');
                }

                if(!$validate_site && !$validate_contract && (!$validate_chatwork || $validate_chatwork == 'room_id_empty' || $validate_chatwork == 'room_id_true' ) && !$have_error_site_price){
                    //check site
                    $check_site = $this->SsmSite->find("first", array(
                        'conditions' => array(
                            'SsmSite.site_url' => $site_url
                            )
                        ));

                    if(!empty($check_site)){
                        //Site exist
                        $this->Session->setFlash('サイトはすでに存在します！', 'warning');
                        //Insert site
                    } else {
                        //Check ga_ecommerce
                        if($ga_ecommerce == 1){
                            $report_kpi = serialize($kpi_arr);
                        } else {
                            $report_kpi = serialize(array_slice($kpi_arr, 1));
                        }

                        $this->SsmSite->create();
                        $data_site = array(
                            'site_name'         => $site_name,
                            'site_url'          => $site_url,
                            'site_description'  => $site_description,
                            'site_manage_user'  => $site_manage_user,
                            'site_satisfaction' => $satisfaction,
                            'site_note'         => $site_note,
                            'ga_ecommerce'      => $ga_ecommerce,
                            'chatwork_id'       => $chatwork_id,
                            'chatwork_api'      => $cw_api,
                            'ga_view_id'        => $ga_view_id,
                            'ga_adword'         => $ga_adword,
                            'report_kpi'        => $report_kpi,
                            'auto_send_cw'      => $auto_send_cw,
                            'show_on_menu'      => $show_on_menu
                            );

                        $this->SsmSite->set($data_site);
                        //insert contract
                        if($this->SsmSite->save()){
                            $site_id = $this->SsmSite->getLastInsertId();
                            $current_time = date('Y-m-d H-i-s');

                            foreach($complete_contract as $id => $contract){
                                $plan_id    = $contract['plan_id'];
                                $start_day  = date_format(date_create($contract['start_day']),'Y-m-d');
                                $end_day    = date_format(date_create($contract['end_day']),'Y-m-d');
                                if($contract['price'] != $planInfo_map_plan_id[$plan_id]['price']){
                                    $is_customize_price =  1;
                                    $plan_price = $contract['price'];
                                }else{
                                    $is_customize_price =  0;
                                    $plan_price = 0;
                                }
                                $this->SsmContract->create();
                                $data_contract = array(
                                    'site_id'   => $site_id,
                                    'start_day' => $start_day,
                                    'end_day'   => $end_day,
                                    'plan_id'   => $plan_id,
                                    'plan_price'=> $plan_price,
                                    'is_customize_price'=> $is_customize_price,
                                    'created_at'=> $current_time,
                                    'updated_at'=> $current_time
                                );
                                $this->SsmContract->set($data_contract);
                                $this->SsmContract->save();
                            }

                            //Update site price of this site
                            $this->loadModel('SsmSitePrice');
                            $this->SsmSitePrice->updateSitePriceNow($site_id,$this->request->data['site_price'],$this->request->data['site_price_per_hour']);

                            $this->Session->setFlash('サイトは作成されました。', 'success');
                            $this->redirect(array('action' => 'index'));
                        } else {
                            $this->Session->setFlash('再度試してください！', 'warning');
                        }
                    }
                }
                // else {

                //     $this->Session->setFlash('必須フィールドに入力してください!', 'warning');
                //     $arr_contract = $this->OttClient->arr_contract($this->request->data);
                //     $check_contract = 1;
                //     $this->set('check_contract', $check_contract);
                //     $this->set('arr_contract', $arr_contract);
                // }
            }

        }
    }

    /**
     * Site detail
     * @return [type] [description]
     */
    public function detail(){

        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = 'ott';
        $this->loadModel("SsmSite");
        $this->loadModel("SsmUser");
        $this->loadModel("SsmContract");
        $this->loadModel("SsmPlan");
        Configure::load('config_shishimai');
        // $plan = Configure::read('plan');
        $site_satisfaction = Configure::read('site_satisfaction');

        //Get site_id, check site
        $site_id = $this->request->params['named']['site_id'];
        $this->set('site_id',$site_id);
        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }
        //info_basic
        $info_basic = $this->SsmSite->find('first', array(
            'joins' => array(
                array(
                    'table' => 'ssm_users',
                    'alias' => 'SsmUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'SsmUser.id = SsmSite.site_manage_user'
                    )
                )
            ),
            'conditions' => array(
                'SsmSite.id' => $site_id
            ),
            'fields' => array(
                'SsmUser.first_name',
                 'SsmUser.last_name',
                 'SsmSite.site_url',
                 'SsmSite.chatwork_id',
                 'SsmSite.ga_view_id',
                 'SsmSite.ga_ecommerce',
                 'SsmSite.site_satisfaction',
                 'SsmSite.site_note',
                 'SsmSite.site_name',
                 'SsmSite.auto_send_cw',
                 'SsmSite.show_on_menu'
            )
        ));

        $info_contract = $this->SsmPlan->find('all', array(
            'joins' => array(
                array(
                    'table' => 'ssm_contracts',
                    'alias'=> 'SsmContract',
                    'type' => 'INNER',
                    'conditions' => array(
                        'SsmPlan.id = SsmContract.plan_id'
                    )
                )
            ),
            'conditions' => array(
                'SsmContract.site_id' => $site_id
            ),
            'fields' => array(
                'SsmContract.plan_id',
                'SsmContract.start_day',
                'SsmContract.end_day',
                'SsmContract.id',
                'SsmContract.plan_price',
                'SsmContract.is_customize_price',
                'SsmPlan.name',
                'SsmPlan.price'
            ),
            'order' => 'SsmContract.id ASC'
        ));

        $this->set('first_name',$info_basic['SsmUser']['first_name']);
        $this->set('last_name',$info_basic['SsmUser']['last_name']);
        $this->set('site_name',$info_basic['SsmSite']['site_name']);
        $this->set('site_url',$info_basic['SsmSite']['site_url']);
        $this->set('chatwork_id',$info_basic['SsmSite']['chatwork_id']);
        $this->set('ga_view_id',$info_basic['SsmSite']['ga_view_id']);
        $this->set('ga_ecommerce',$info_basic['SsmSite']['ga_ecommerce']);
        $this->set('site_satisfaction',$info_basic['SsmSite']['site_satisfaction']);
        $this->set('site_note',$info_basic['SsmSite']['site_note']);
        $this->set('info_contract', $info_contract);
        $this->set('cf_site_satisfaction', $site_satisfaction);
        $this->set('auto_send_cw',$info_basic['SsmSite']['auto_send_cw']);
        $this->set('show_on_menu',$info_basic['SsmSite']['show_on_menu']);
        // $this->set('cf_plan', $plan);

    }

    /**
     * Edit site
     * @return [type] [description]
     */
    public function edit(){

        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = 'ott';
        $this->loadModel("SsmUser");
        $this->loadModel("SsmSite");
        $this->loadModel("SsmContract");
        $this->loadModel("SsmPlan");

        $this->loadModel("SsmKpis");
        $this->loadModel("SsmKpiChanges");
        $this->loadModel("SsmKpiGaMonths");
        $this->loadModel("SsmKpiGaWeeks");
        $this->loadModel("SsmKpiGaWeekReports");
        $this->loadModel("SsmKpiNotes");
        $this->loadModel("SsmReports");
        $this->loadModel("SsmReportSlides");
        $this->loadModel("SsmReportWeeks");
        $this->loadModel('SsmSitePrice');
        $this->loadModel('SsmSettings');

        Configure::load('config_shishimai');
        // $plan = Configure::read('plan');
        $site_satisfaction = Configure::read('site_satisfaction');
        $cf_ga_email_service = Configure::read('ga_email_service');
        $kpis = Configure::read('kpis');

        //User and Site define ========================================================

        $site_id = $this->request->params['named']['site_id'];

        $this->set('site_id',$site_id);

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        //Get kpi data store in db back to edit
        $kpi_arr = $siteInfo['report_kpi'];

        //End User and Site define ====================================================

        $info_site = $this->SsmSite->find('first', array(
            'conditions' => array(
                'SsmSite.id' => $site_id
                ),
            'fields' => array(
                'SsmSite.site_name',
                'SsmSite.site_url',
                'SsmSite.site_manage_user',
                'SsmSite.site_satisfaction',
                'SsmSite.site_description',
                'SsmSite.site_note',
                'SsmSite.chatwork_id',
                'SsmSite.chatwork_api',
                'SsmSite.ga_ecommerce',
                'SsmSite.ga_view_id',
                'SsmSite.ga_adword',
                'SsmSite.auto_send_cw',
                'SsmSite.show_on_menu'
                )
            ));

        $info_user = $this->SsmUser->find('all', array(
            'conditions' => array(
                'SsmUser.role' => 'admin',
                'SsmUser.status' =>1,
                ),
            'fields' => array(
                'SsmUser.first_name','SsmUser.last_name',
                'SsmUser.id',
                'SsmUser.chatwork_api',
                'SsmUser.id'
                )
            ));

        $plan = $this->SsmPlan->find('all', array(
            'fields' => array(
                'SsmPlan.name',
                'SsmPlan.price',
                'SsmPlan.id'
            )
        ));

        $site_price_db = $this->SsmSitePrice->find('first',array(
            'conditions'=>array(
                'site_id'   => $site_id,
                'year'      => intval(date('Y')),
                'month'     => intval(date('m'))
            )
        ));

        $defaut_site_price = "";
        $defaut_site_price_per_hour = "";
        if($site_price_db){
            $defaut_site_price = intval($site_price_db['SsmSitePrice']['price']);
            $defaut_site_price_per_hour = intval($site_price_db['SsmSitePrice']['price_per_hour']);
        }

        $this->set('defaut_site_price',$defaut_site_price);
        $this->set('defaut_site_price_per_hour',$defaut_site_price_per_hour);

        $planInfo_map_plan_id = array();
        foreach ($plan as $plan_item) {
            $planInfo_map_plan_id[$plan_item['SsmPlan']['id']] = $plan_item['SsmPlan'];
        }

        $info_contract = $this->SsmContract->find('all', array(
            'conditions' => array(
                'SsmContract.site_id' => $site_id
            ),
            'order' => 'SsmContract.start_day ASC'
        ));

        if(!$this->request->is('post')){
            //If custom price => price display in input will be price in contract table
            foreach ($info_contract as $key=>$info) {
                $item = $info['SsmContract'];
                $item['price'] = $item['is_customize_price'] ? $info['SsmContract']['plan_price'] : $planInfo_map_plan_id[$item['plan_id']]['price'];
                $info_contract[$key] = $item;
            }
        }

        $check_date = true;

        $this->set('info_site', $info_site);
        $this->set('info_user', $info_user);
        $this->set('info_contract', $info_contract);
        $this->set('plan', $plan);

        $this->set('check_date', $check_date);
        $this->set('cf_site_satisfaction', $site_satisfaction);
        $this->set('cf_ga_email_service', $cf_ga_email_service);

        //checked
        if($info_site['SsmSite']['ga_ecommerce'] == 1)
            $this->set('checked_ecommerce','checked');
        else
            $this->set('checked_ecommerce','');

        if($info_site['SsmSite']['auto_send_cw'] == 1)
            $this->set('checked_auto_send_cw','checked');
        else
            $this->set('checked_auto_send_cw','');

        if($info_site['SsmSite']['show_on_menu'] == 1)
            $this->set('checked_show_on_menu','checked');
        else
            $this->set('checked_show_on_menu','');

        $this->set('default_user_id',$info_site['SsmSite']['site_manage_user']);

        if($this->request->is('post')){
            //Data send from edit_chatwork
            if(isset($this->request->data['hidden_data'])){
                $cw_api = trim($this->request->data['cw_api']);
                $show_data = unserialize($this->request->data['hidden_data']);
                $this->request->data = $show_data;
                $this->request->data['cw_api'] = $cw_api;

                if($this->request->data['ecommerce'] == 1){
                    $this->set('checked_ecommerce','checked');
                } else {
                    $this->set('checked_ecommerce','');
                }

                if($this->request->data['auto_send_cw'] == 1){
                    $this->set('checked_auto_send_cw','checked');
                } else {
                    $this->set('checked_auto_send_cw','');
                }

                if($this->request->data['show_on_menu'] == 1){
                    $this->set('checked_show_on_menu','checked');
                } else {
                     $this->set('checked_show_on_menu','');
                }

                if($this->request->data['user_id']){
                    $this->set('default_user_id',$this->request->data['user_id']);
                }

                $arr_contract = $this->OttClient->arr_contract($show_data);
                $check_contract = 1;
                $this->set('check_contract', $check_contract);
                $this->set('arr_contract', $arr_contract); 

                //Update key CW for admin active
                $this->SsmUser->UpdateAll(array(
                    'SsmUser.chatwork_api' => "'".$cw_api."'"
                ), array(
                    'SsmUser.id' => $show_data['user_id']
                ));
            }
            //Data send from edit_client
            else {

                $site_name = $this->request->data['site_name'];
                $site_url = $this->request->data['site_url'];
                $site_description = $this->request->data['site_description'];
                $site_manage_user = intval($this->request->data['user_id']);
                $ga_view_id = trim($this->request->data['ga_view_id']);
                $confirm_change_view_id = $this->request->data['confirm_change_view_id'];
                $ga_adword = trim($this->request->data['ga_adword']);
                $plan_id = $this->request->data['plan_id'];
                $user_id = $this->request->data['user_id'];
                $SsmContract_id = $this->request->data['SsmContract_id'];
                $start_day = date_format(date_create($this->request->data['start_day']),'Y-m-d');
                $end_day = date_format(date_create($this->request->data['end_day']),'Y-m-d'); 
                $error_site_name;
                $error_site_url;
                $error_site_description;
                $error_site_ga_view_id;

                //Validate site and contract
                $validate_site = $this->OttClient->have_error_site($site_name, $error_site_name, $site_url, $error_site_url, $site_description, $error_site_description, $ga_view_id, $error_site_ga_view_id, $site_manage_user, $error_site_manage_user);

                $validate_contract;
                $arr_contract = $this->OttClient->arr_contract($this->request->data);

                $error_contract = "入力された日付は無効です。再度確認して下さい。";
                $validate_contract = $this->OttClient->validate_contract_server($arr_contract, $validate_contract);

                if($validate_site){
                    $this->set('validate_site',$validate_site);
                    $this->set('error_site_name',$validate_site['error_site_name']);
                    $this->set('error_site_url',$validate_site['error_site_url']);
                    $this->set('error_site_description',$validate_site['error_site_description']);
                    $this->set('error_site_ga_view_id',$validate_site['error_site_ga_view_id']);
                    $this->set('error_site_manage_user',$validate_site['error_site_manage_user']);
                    $this->Session->setFlash('必須フィールドに入力してください!', 'warning');
                }
                if($validate_contract){
                    $this->set('error_contract', $error_contract);
                    $this->set('validate_contract', $validate_contract);
                    $this->Session->setFlash('必須フィールドに入力してください!', 'warning');
                }

                //Validate chatwork
                $error_validate_chatwork = "(*) 必須!";
                $type_error;
                $check_cw_api = $this->request->data['cw_api'];
                $room_id_inputed = $this->request->data['room'];
                $check_chatwork_id = !isset($room_id_inputed) ? $this->request->data['chatwork_id'] : $room_id_inputed;

                $validate_chatwork = $this->OttClient->validate_chatwork($check_cw_api, $check_chatwork_id);

                if($validate_chatwork == 'api'){
                    if($validate_site){
                        $this->Session->setFlash('必須フィールドに入力してください!', 'warning');
                    } else {
                        $this->Session->setFlash('選択された主担当者はチャットワーク連携未設定です。', 'warning');
                    }

                    $this->set('check_cw_api', $check_cw_api);
                }

                if($validate_chatwork == 'room_id_wrong'){
                    if($validate_site){
                        $this->Session->setFlash('必須フィールドに入力してください!', 'warning');
                    } else {
                        $this->Session->setFlash('ルームIDが正しくありません。', 'warning');
                    }
                    $this->set('room_id_inputed', $room_id_inputed);
                    $this->set('error_validate_chatwork', $error_validate_chatwork);
                    $this->set('check_cw_api', $check_cw_api);

                }

                $this->set('validate_chatwork', $validate_chatwork);
                $this->set('arr_contract', $arr_contract);
                $this->set('user_id', $user_id);

                $have_error_site_price = $this->OttClient->have_error_site_price($this->request->data['site_price'],$this->request->data['site_price_per_hour']);

                if($have_error_site_price){
                    $this->set('error_site_price', '数値で入力してください。');
                }

                //Check validate form
                if(!$validate_site && !$validate_contract && (!$validate_chatwork || $validate_chatwork =='room_id_empty' || $validate_chatwork =='room_id_true') && !$have_error_site_price){
                    //check site
                    $check_site = $this->SsmSite->find("first", array(
                        'conditions' => array(
                            'SsmSite.site_url' => strtolower($site_url),
                            ),
                        'field' => array(
                            'SsmSite.id'
                        )
                    ));

                    $check_site_id = $check_site['SsmSite']['id'];

                    if(!empty($check_site) && $check_site_id != $site_id){
                        //Site exist
                        $this->Session->setFlash('サイトはすでに存在します！', 'warning');
                        //Update site
                    } else {
                        //Check ga_ecommerce
                        if($this->request->data['ecommerce'] == 1){
                            $report_kpi = serialize($kpi_arr);
                        } else {
                            unset($kpi_arr['transactionRevenue']);
                            foreach ($kpi_arr as $key=>$value) {
                                if($value == 'transactionRevenue'){
                                    unset($kpi_arr[$key]);
                                }
                            }
                            $report_kpi = serialize($kpi_arr);
                        }

                        
                            //Updat info_site
                            $this->SsmSite->id = $site_id;
                            $update_site = array(
                                'site_name' => $site_name,
                                'site_url' => $site_url,
                                'site_manage_user' => $this->request->data['user_id'],
                                'site_satisfaction' => $this->request->data['satisfaction'],
                                'site_description' => $site_description,
                                'site_note' => $this->request->data['remarks'],
                                'ga_ecommerce' => $this->request->data['ecommerce'],
                                 'chatwork_id' => !isset($this->request->data['room']) ? trim($this->request->data['chatwork_id']) : trim($this->request->data['room']),
                                'chatwork_api' => trim($this->request->data['cw_api']),
                                'ga_view_id' => $ga_view_id,
                                'ga_adword' => $ga_adword,
                                'report_kpi' => $report_kpi,
                                'auto_send_cw' => $this->request->data['auto_send_cw'] ? $this->request->data['auto_send_cw'] : 0,
                                'show_on_menu' => $this->request->data['show_on_menu'] ? $this->request->data['show_on_menu'] : 0
                                );
                            $this->SsmSite->set($update_site);

                            if($this->SsmSite->save()){

                                //Add contract and update contract
                                $arr_contract = $this->OttClient->arr_contract($this->request->data);

                                //array id old
                                $array_front_id = array();
                                foreach ($arr_contract as $key => $value) {
                                    if($value['SsmContract_id'] > 0)
                                    $array_front_id[] = $value['SsmContract_id'];
                                }

                                $all_contract_database = $this->SsmContract->find('all', array(
                                    'conditions' => array(
                                        'SsmContract.site_id' => $site_id
                                    ),
                                    'fields' => array(
                                        'SsmContract.id'
                                    )
                                ));

                                //array id trong db
                                $array_db_id = array();
                                foreach ($all_contract_database as $key => $value) {
                                    $array_db_id[] = $value['SsmContract']['id'];
                                }

                                //delete all id not in front
                                $delete_id = array();
                                $update_id = array();
                                foreach ($array_db_id as $id_in_db) {
                                    if(!in_array($id_in_db, $array_front_id)){
                                        $delete_id[] = $id_in_db;
                                    }else{
                                        $update_id[] = $id_in_db;
                                    }
                                }

                                //delete
                                foreach($delete_id as $id){
                                    $this->SsmContract->deleteAll(array('SsmContract.id' => $id), false);
                                }

                                foreach($arr_contract as $key => $contract){
                                    if($contract['price'] != $planInfo_map_plan_id[$contract['plan_id']]['price']){
                                        $is_customize_price = 1;
                                        $plan_price         = $contract['price'];
                                    }else{
                                        $is_customize_price = 0;
                                        $plan_price         = 0;
                                    }

                                    if(!empty($contract['SsmContract_id']) && in_array($contract['SsmContract_id'], $update_id)){
                                        //update
                                        $SsmContract_id = $contract['SsmContract_id'];
                                        $sd = str_replace('/', '-', $contract['start_day']);
                                        $ed = str_replace('/', '-', $contract['end_day']);

                                        $data_update = array(
                                                'SsmContract.start_day' => "'".$sd."'",
                                                'SsmContract.end_day'   => "'".$ed."'",
                                                'SsmContract.plan_id'   => $contract['plan_id'],
                                                'SsmContract.plan_price'=> $plan_price,
                                                'SsmContract.is_customize_price'=> $is_customize_price,
                                                'SsmContract.updated_at'=>  "'".date('Y-m-d H:i:s')."'"
                                            );
                                        $this->SsmContract->UpdateAll(
                                            $data_update,
                                            array(
                                                'SsmContract.id' => $SsmContract_id
                                            )
                                        );

                                    } else {
                                        // add
                                         $this->SsmContract->create();
                                         $new_contract = array(
                                            'start_day' => date_format(date_create($contract['start_day']),'Y-m-d'),
                                            'end_day'   => date_format(date_create($contract['end_day']),'Y-m-d'),
                                            'plan_id'   => $contract['plan_id'],
                                            'plan_price'=> $plan_price,
                                            'is_customize_price'=> $is_customize_price,
                                            'site_id'   => $site_id,
                                            'created_at'=>  "'".date('Y-m-d H:i:s')."'"
                                         );

                                         $this->SsmContract->set($new_contract);
                                         $this->SsmContract->save();

                                    }
                                }

                                $this->SsmSitePrice->updateSitePriceNow($site_id,$this->request->data['site_price'],$this->request->data['site_price_per_hour']);                                

                                $this->Session->setFlash('データは更新されました。', 'success');
                                $this->redirect(array('action'=>'index'));
                            } else {
                                $this->Session->setFlash('エラーになりました。再度試してください！', 'warning');
                            }
                    }
                }
            }
        }

        $ssmSetting = $this->SsmSettings->find('first', array(
            'fields'     => array('value'),
            'conditions' => array(
                'SsmSettings.key' => 'man_hours_rate'
            )
        ));

        $this->set('manHoursRate', $ssmSetting['SsmSettings']['value']);

        //Data send from database
        $cw_api =  trim($info_site['SsmSite']['chatwork_api']);
        $chatwork_id =  trim($info_site['SsmSite']['chatwork_id']);
        $this->set("cw_api", $cw_api);
        $this->set("chatwork_id", $chatwork_id);
    }

    /**
     * Edit chatwork
     * @return [type] [description]
     */
    public function edit_chatwork(){

        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = 'ott';
        $this->loadModel("SsmSite");

        if($this->request->is('post')){
            //Form from Add client
            if($this->request->data['fromAdd'] == 1){
            $hidden_data = serialize($this->request->data);
            $this->set('hidden_data', $hidden_data);
            // Form from Edit client
            }else if($this->request->data['fromEdit'] == 1){
                $hidden_data = serialize($this->request->data);
                $site_id = $this->request->params['named']['site_id'];
                $this->set('hidden_data', $hidden_data);
                $this->set('site_id', $site_id);
            }
        }
    }

    /**
     * Edit account
     * @return [type] [description]
     */
    public function edit_account(){
        $this->layout = "ott";
        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================
        $this->loadModel('SsmUser');

        //Show info_user
        $user_id = $this->request->params['named']['user_id'];
        $info_user = $this->SsmUser->find('first', array(
            'conditions' => array(
                'SsmUser.id' => $user_id
            ),
            'fields' => array(
                'SsmUser.username',
                'SsmUser.department',
                'SsmUser.position',
                'SsmUser.first_name',
                'SsmUser.last_name',
                'SsmUser.avatar'
            )
        ));
        $this->set('info_user', $info_user);

        //Update info user
        if($this->request->is('post')){
            $this->SsmUser->id = $user_id;
            if ($this->SsmUser->validate_edit()) {
                if ($this->SsmUser->save($this->request->data)) {
                    $this->Session->setFlash(__('更新は成功です。'),'success');
                    $this->redirect(array('action'=>'edit_account', 'user_id' => $user_id));
                }else{
                    $this->Session->setFlash(__('更新は失敗です。再度試してください。'), 'warning');
                }
            }else{
                $this->Session->setFlash(__('エラーになりました。再度試してください！'), 'warning');
            }
        }
    }

    /**
     * Remove plan
     * @return [type] [description]
     */
    public function remove_plan(){
        $this->loadModel('SsmContract');

        if($this->request->is('post')){
            $id = $this->request->data['id'];

            if($this->SsmContract->deleteAll(array('SsmContract.id' => $id), false)){
                echo 'success';
            } else{
                echo 'error';
            }
        }
        exit;
    }


    /**
     * List site and kpi
     * @return [type] [description]
     */
    public function kpi(){
        //User and Site define ========================================================
        $loginUser  = $this->loginUser;
        //End User and Site define ====================================================
        $this->layout = 'ott';
        $this->loadModel("SsmUser");
        $this->loadModel("SsmSite");
        $this->loadModel("SsmContract");
        $this->loadModel("SsmPlan");
        Configure::load('config_shishimai');

        //Reset Active Site
        if($this->listSiteIDInfo[$this->site_id]['show_on_menu'] == 0){
            if(in_array(30,array_keys($this->listSiteIDName))){
                $this->SsmAuth->setActiveSite(30);
            }else{
                foreach ($this->listSiteIDInfo as $key=>$value) {
                    if($value['show_on_menu'] == 1){
                        $this->SsmAuth->setActiveSite($key);
                        break;
                    }
                }
            }
        }
        //Load list site and kpi
        $year    = !empty($this->request->query['year']) ? $this->request->query['year'] : date('Y');
        $month   = !empty($this->request->query['month']) ? $this->request->query['month'] : intval(date('m'));

        if($month == 12){
            $prev_param = array(
            'year'   => $year,
            'month'  => $month - 1
            );

            $next_param = array(
            'year'   => $year + 1,
            'month'  => 1
            );

        }elseif($month == 1){
            $prev_param = array(
            'year'   => $year - 1,
            'month'  => 12
            );

            $next_param = array(
            'year'   => $year,
            'month'  => $month + 1
            );
        }else{
            $prev_param = array(
            'year'   => $year,
            'month'  => $month - 1
            );

            $next_param = array(
            'year'   => $year,
            'month'  => $month + 1
            );
        }
        $base_link = "/SsmClient/kpi?";
        $prev_link = $base_link.http_build_query($prev_param);
        $next_link = $base_link.http_build_query($next_param);
        $this->set('prev_link',$prev_link);
        $this->set('next_link',$next_link);
        $this->set('year',$year);
        $this->set('month',$month);
    }

    public function man_hours_rate_setting()
    {
        $this->layout = 'ott';

        $this->loadModel('SsmSettings');
        $this->loadModel('SsmSitePrice');

        $ssmSetting = $this->SsmSettings->find('first', array(
            'conditions' => array(
                'SsmSettings.key' => 'man_hours_rate'
            )
        ));

        if ($this->request->is('put')) {
            $this->SsmSettings->id = $ssmSetting['SsmSettings']['id'];

            if ($this->SsmSettings->save($this->request->data)) {
                $this->SsmSitePrice->updateAll([
                    'man_hours_rate' => $this->request->data['SsmSettings']['value']
                ],
                [
                    'year'  => date('Y'),
                    'month' => date('m')
                ]);

                $this->Session->setFlash(__('更新は成功です。'),'success');
                return $this->redirect(array('action' => 'man_hours_rate_setting'));
            }

            $this->Session->setFlash(__('更新は失敗です。再度試してください。'), 'warning');
        } else{
            $this->request->data = $ssmSetting;
        }
    }

    /**
     * Add Site
     */
    public function suspend(){
        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->loadModel("SsmSite");

        $site_id = $this->request->params['named']['site_id'];

        $update = $this->SsmSite->UpdateAll(array(
            'SsmSite.suspend' => '1'
        ), array(
            'SsmSite.id' => $site_id
        ));
        if($update){
            $this->Session->setFlash(__('契約が完了しました。'),'success');
        }else{
            $this->Session->setFlash(__('契約が完了しませんでした。'),'warning');
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function unSuspend(){
        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->loadModel("SsmSite");

        $site_id = $this->request->params['named']['site_id'];

        $update = $this->SsmSite->UpdateAll(array(
            'SsmSite.suspend' => '0'
        ), array(
            'SsmSite.id' => $site_id
        ));
        if($update){
            $this->Session->setFlash(__('契約が復旧しました。'),'success');
        }else{
            $this->Session->setFlash(__('契約が復旧しませんでした。'),'warning');
        }
        return $this->redirect(array('action' => 'index'));
    }
}

?>