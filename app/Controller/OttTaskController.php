<?php
App::uses('SsmAdminController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class OttTaskController extends SsmAdminController {
    public $helpers     = array('Html', 'Form', 'Session');
    public $components  = array('Session','SsmAuth');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function beforeRender(){
        parent::beforeRender();
    }

    public function index(){
        $this->layout = 'ott';

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

        //Report log 
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

        $base_link = "/OttTask?";
        $prev_link = $base_link.http_build_query($prev_param);
        $next_link = $base_link.http_build_query($next_param);
        $this->set('prev_link',$prev_link);
        $this->set('next_link',$next_link);
        $this->set('year',$year);
        $this->set('month',$month);

        //Get last day of motnh
        $month_str      = $month > 9 ? $month : '0'.$month;
        $begin_date     = $year."-".$month_str."-01";
        $last_date      = date('Y-m-t', strtotime($year."-".$month_str."-01"));

        $this->loadModel("SsmTaskLog");
        $this->loadModel('SsmSiteUser');
        $this->loadModel('SsmSitePrice');
        $this->loadModel('SsmSite');
        $this->loadModel('SsmUser');
        $this->loadModel('SsmTaskLabel');

        //List user

        $list_user_option = $this->SsmUser->find('all',array(
            'fields' => array('SsmUser.first_name','SsmUser.last_name','SsmUser.username','SsmUser.id','SsmUser.role'),
            
            'conditions'=>array(
                'SsmUser.role'=>array('admin','worker')
            )
        ));

        $conditions = array(
            'SsmTaskLog.created_at >='  => $begin_date,
            'SsmTaskLog.created_at <='  => $last_date,
            'SsmTaskLog.site_id'        => array_merge($this->listSiteID,array(0))
        );

        $list_db = $this->SsmTaskLog->find('all',array(
            'fields' => array(
                'sum(SsmTaskLog.task_time) as total_time','SsmTaskLog.site_id','SsmTaskLog.label_id'
            ),
            'conditions'=>$conditions,
            'group' => array(
                'SsmTaskLog.label_id',
                'SsmTaskLog.site_id'
            ),
            'order' =>array('SsmTaskLog.id ASC')
        ));

        $label_option = $this->SsmTaskLabel->getArrTaskLabel();

        $list = array();
        $my_site_id = array();

        $total_data = array(
            'group_by_label'    => array(),
            'total_all_label'   => 0,
            'total_site_hour_calculate' => 0
        );

        foreach ($list_db as $task) {

            $site_id = $task['SsmTaskLog']['site_id'];

            $label_id = $task['SsmTaskLog']['label_id'];

            $task[0]['total_time'] = floatval($task[0]['total_time']);

            $list[$site_id]['task'][$label_id] = $task[0]['total_time'];

            $list[$site_id]['task_total'] = $list[$site_id]['task_total'] + $task[0]['total_time'];

            $my_site_id[] = $site_id;

            //Add to total
            $total_data['group_by_label'][$label_id] += $task[0]['total_time'];
            $total_data['total_all_label'] += $task[0]['total_time'];
        }
       
        $user_group_by_site = array();
        if(!empty($my_site_id)){
            //get site name and site price
            $site_have_task = $this->SsmSite->find('all',array(
                'fields' => array('SsmSite.id','SsmSite.site_name','SsmSitePrice.price','SsmSitePrice.price_per_hour','SsmSitePrice.man_hours_rate'),
                'joins' => array(
                    array(
                        'table' => 'ssm_site_prices',
                        'alias' => 'SsmSitePrice',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'SsmSite.id = SsmSitePrice.site_id',
                            'SsmSitePrice.year' =>$year,
                            'SsmSitePrice.month'=>$month
                        )
                    )                  
                ),
                'conditions'=>array(
                    'SsmSite.id'=>$my_site_id
                )
            ));

            if($site_have_task){
                foreach($site_have_task as $item){
                    $site_id = $item['SsmSite']['id'];
                    $list[$site_id]['site_name'] = $item['SsmSite']['site_name'];

                    $site_hour_calculate = $this->SsmSitePrice->getHourCalculate($item['SsmSitePrice']['price'],$item['SsmSitePrice']['price_per_hour'], $item['SsmSitePrice']['man_hours_rate']);
                    $list[$site_id]['site_hour_calculate'] = $site_hour_calculate;
                    $list[$site_id]['users'] = '';

                    $total_data['total_site_hour_calculate'] += $site_hour_calculate;
                }
            }

            if(isset($list[0])){
                $list[0]['site_name'] = 'その他';
                $list[0]['users']     = '';
            }

            //build list user role 'client' for showing side by side the site name
            $list_user = $this->SsmSiteUser->find('all',array(
                'fields' => array('SsmSiteUser.site_id','SsmUser.first_name','SsmUser.last_name','SsmUser.username'),
                'joins' => array(
                    array(
                        'table' => 'ssm_users',
                        'alias' => 'SsmUser',
                        'type' => 'INNER',
                        'conditions' => array(
                            'SsmSiteUser.user_id = SsmUser.id'
                        )
                    )                    
                ),
                'conditions'=>array(
                    'SsmSiteUser.site_id'=>$my_site_id
                )
            ));


            if($list_user){
                foreach($list_user as $item){
                    $user_group_by_site[$item['SsmSiteUser']['site_id']][] = $item['SsmUser']['first_name'].$item['SsmUser']['last_name']."(".$item['SsmUser']['username'].")";
                }

                foreach($user_group_by_site as $site_id => $site_user){
                    $user_group_by_site[$site_id] = implode('<br>', $site_user);

                    $list[$site_id]['users'] = $user_group_by_site[$site_id];
                }
            }
        }

        $this->set('list_user_option',$list_user_option);

        $this->set('label_option',$label_option);

        $this->set('total_data',$total_data);

        $this->set('list',$list);
    }

    public function user(){
        $this->layout = 'ott';

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

        //Report log 
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

        //Get last day of motnh
        $month_str      = $month > 9 ? $month : '0'.$month;
        $begin_date     = $year."-".$month_str."-01";
        $last_date      = date('Y-m-t', strtotime($year."-".$month_str."-01"));

        $this->loadModel("SsmTaskLog");
        $this->loadModel('SsmSiteUser');
        $this->loadModel('SsmSitePrice');
        $this->loadModel('SsmSite');
        $this->loadModel('SsmUser');
        $this->loadModel('SsmTaskLabel');

        if(!isset($this->request->query['user_id']) || !$this->allow_action['task_showuser']){
            throw new NotFoundException();
        }

        $show_user = $this->SsmUser->find('first',array(
            'fields' => array('SsmUser.first_name','SsmUser.last_name','SsmUser.username','SsmUser.id','SsmUser.role'),            
            'conditions'=>array(
                'SsmUser.id'=>intval($this->request->query['user_id'])
            )
        ));

        if(!$show_user){
            throw new NotFoundException();
        }

        $prev_param['user_id'] = $show_user['SsmUser']['id'];
        $next_param['user_id'] = $show_user['SsmUser']['id'];

        $base_link = "/OttTask/user?";
        $prev_link = $base_link.http_build_query($prev_param);
        $next_link = $base_link.http_build_query($next_param);
        $this->set('prev_link',$prev_link);
        $this->set('next_link',$next_link);
        $this->set('year',$year);
        $this->set('month',$month);

        $conditions = array(
            'SsmTaskLog.created_at >='  => $begin_date,
            'SsmTaskLog.created_at <='  => $last_date,
            'SsmTaskLog.user_id'        => $show_user['SsmUser']['id']
        );

        $list_db = $this->SsmTaskLog->find('all',array(
            'fields' => array(
                'sum(SsmTaskLog.task_time) as total_time','SsmTaskLog.site_id','SsmTaskLog.label_id'
            ),
            'conditions'=>$conditions,
            'group' => array(
                'SsmTaskLog.label_id',
                'SsmTaskLog.site_id'
            ),
            'order' =>array('SsmTaskLog.id ASC')
        ));

        $label_option = $this->SsmTaskLabel->getArrTaskLabel();

        $list = array();
        $my_site_id = array();

        $total_data = array(
            'group_by_label'    => array(),
            'total_all_label'   => 0,
            'total_site_hour_calculate' => 0
        );

        foreach ($list_db as $task) {
            $site_id = $task['SsmTaskLog']['site_id'];

            $label_id = $task['SsmTaskLog']['label_id'];

            $task[0]['total_time'] = floatval($task[0]['total_time']);

            $list[$site_id]['task'][$label_id] = $task[0]['total_time'];

            $list[$site_id]['task_total'] = $list[$site_id]['task_total'] + $task[0]['total_time'];

            $my_site_id[] = $site_id;

            //Add to total
            $total_data['group_by_label'][$label_id] += $task[0]['total_time'];
            $total_data['total_all_label'] += $task[0]['total_time'];
        }
        
        $user_group_by_site = array();
        if(!empty($my_site_id)){
            //get site name and site price
            $site_have_task = $this->SsmSite->find('all',array(
                'fields' => array('SsmSite.id','SsmSite.site_name','SsmSitePrice.price','SsmSitePrice.price_per_hour','SsmSitePrice.man_hours_rate'),
                'joins' => array(
                    array(
                        'table' => 'ssm_site_prices',
                        'alias' => 'SsmSitePrice',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'SsmSite.id = SsmSitePrice.site_id',
                            'SsmSitePrice.year' =>$year,
                            'SsmSitePrice.month'=>$month
                        )
                    )                  
                ),
                'conditions'=>array(
                    'SsmSite.id'=>$my_site_id
                )
            ));

            if($site_have_task){
                foreach($site_have_task as $item){
                    $site_id = $item['SsmSite']['id'];
                    $list[$site_id]['site_name'] = $item['SsmSite']['site_name'];

                    $site_hour_calculate = $this->SsmSitePrice->getHourCalculate($item['SsmSitePrice']['price'],$item['SsmSitePrice']['price_per_hour'], $item['SsmSitePrice']['man_hours_rate']);
                    $list[$site_id]['site_hour_calculate'] = $site_hour_calculate;
                    $list[$site_id]['users'] = '';

                    $total_data['total_site_hour_calculate'] += $site_hour_calculate;
                }
            }

            if(isset($list[0])){
                $list[0]['site_name'] = 'その他';
                $list[0]['users']     = '';
            }

            //build list user role 'client' for showing side by side the site name
            $list_user = $this->SsmSiteUser->find('all',array(
                'fields' => array('SsmSiteUser.site_id','SsmUser.first_name','SsmUser.last_name','SsmUser.username'),
                'joins' => array(
                    array(
                        'table' => 'ssm_users',
                        'alias' => 'SsmUser',
                        'type' => 'INNER',
                        'conditions' => array(
                            'SsmSiteUser.user_id = SsmUser.id'
                        )
                    )                    
                ),
                'conditions'=>array(
                    'SsmSiteUser.site_id'=>$my_site_id
                )
            ));


            if($list_user){
                foreach($list_user as $item){
                    $user_group_by_site[$item['SsmSiteUser']['site_id']][] = $item['SsmUser']['first_name'].$item['SsmUser']['last_name']."(".$item['SsmUser']['username'].")";
                }

                foreach($user_group_by_site as $site_id => $site_user){
                    $user_group_by_site[$site_id] = implode('<br>', $site_user);

                    $list[$site_id]['users'] = $user_group_by_site[$site_id];
                }
            }
        }

        $this->set('show_user',$show_user);

        $this->set('label_option',$label_option);

        $this->set('total_data',$total_data);

        $this->set('list',$list);
    }

    public function my(){
        $this->layout = 'ott';
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================
        $this->loadModel("SsmTask");
        $this->loadModel("SsmTaskLog");
        $this->loadModel("SsmSiteUser");
        $this->loadModel("SsmTaskLabel");

        //build list client option
        $list_client_option = "";

        /*
        $list_user = $this->SsmSiteUser->find('all',array(
            'fields' => array('SsmSiteUser.site_id','SsmUser.first_name','SsmUser.last_name','SsmUser.username'),
            'joins' => array(
                array(
                    'table' => 'ssm_users',
                    'alias' => 'SsmUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'SsmSiteUser.user_id = SsmUser.id',
                        //'SsmUser.role' => 'client'
                    )
                )                    
            ),
            'conditions'=>array(
                'SsmSiteUser.site_id'=>$this->listSiteID,
            ),
            'group' => array('SsmSiteUser.site_id')
        ));

        $u_map_site = array();
        if($list_user){
            foreach($list_user as $u_in_site){
                $name = ($u_in_site['SsmUser']['first_name'].$u_in_site['SsmUser']['last_name']) != "" ? $u_in_site['SsmUser']['first_name'].$u_in_site['SsmUser']['last_name'] : $u_in_site['SsmUser']['username'];

                $u_map_site[$u_in_site['SsmSiteUser']['site_id']] = $name;
            }
        }
        */
        $list_client_option .= "<option value='0'>企業名をご選択ください</option>";
        foreach($this->listSiteIDInfo as $i){
            //$list_client_option .= "<option value='".$i['id']."'>".(isset($u_map_site[$i['id']]) ? $u_map_site[$i['id']] : $i['site_name'])."</option>";
            $list_client_option .= "<option value='".$i['id']."'>".$i['site_name']."</option>";
        }

        //Build list label option
        $label_option = $this->SsmTaskLabel->getArrTaskLabel(false);

        $list_label_option  = "";
        $list_label_option  = "<option value='0'>ジャンルをご選択ください</option>";
        if($label_option){
            foreach($label_option as $lid =>$label){
                $list_label_option .= "<option value='".$lid."'>".$label."</option>";
            }
        }

        //get config chatwork
        Configure::load('config_shishimai');
        $to_group_cw_id         = Configure::read('cw_task_group_id');//Send msg to this group
        $from_chatwork_api      = $this->loginUser['SsmUser']['chatwork_api'];        

        $hide_task      = "";
        $arr_task_log   = [];
        $note           = "";

        $stt = time();

        $arr_hide_task = [];

        if($this->request->is('post')){
            //Get chatwork user info
            $error = 0;
            $task_log       = $this->request->data['task_id'];
            $task_site      = $this->request->data['task_site'];
            $task_label     = $this->request->data['task_label'];
            $task_est       = $this->request->data['task_est'];

            $task_note      = $this->request->data['task_note'] ? $this->request->data['task_note'] : "";
            $hide_task      = $this->request->data['hide_task'];
            $taskName       = $this->request->data['task_name'];

            if(count($task_log)){

                $arr_hide_task = explode(',',$hide_task);

                //Build arr_task_log
                foreach ($task_log as $key => $task_id) {
                    $label_id  = $task_label[$key];

                    $task_time = $task_est[$key];
                    $site_id   = $task_site[$key];
                    $task_name = $taskName[$key];

                    $arr_task_log[$key] = [
                        'site_id'   => $site_id,
                        'task_id'   => $task_id,
                        'label_id'  => $label_id,
                        'task_time' => $task_time,
                        'user_id'   => $this->loginUser['SsmUser']['id'],
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ];
                    $arrTodayWorks[$key] = [
                        'site_id'   => $site_id,
                        'task_id'   => $task_id,
                        'label_id'  => $label_id,
                        'task_time' => $task_time,
                        'user_id'   => $this->loginUser['SsmUser']['id'],
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s'),
                        'taskName'  => $task_name
                    ];
                }

                if($this->SsmTaskLog->saveAll($arr_task_log)){
                    //Hide task checked
                    $this->SsmTask->updateAll(
                        [
                            'SsmTask.status'        => 1,
                            'SsmTask.updated_at'    => "'".date('Y-m-d H:i:s')."'"
                        ], 
                        [
                            'SsmTask.id' => $arr_hide_task
                        ]
                    );                        

                    //Send msg to cw.
                    if(!$error){
                        $task_id_arr = array_unique ($task_log);

                        $task_report = $this->SsmTask->find('all',
                            array(
                                'conditions'=>array(
                                    'id'=>$task_id_arr
                                )
                            )
                        );

                        $arr_task_name = array();
                        $tasks = array();
                        foreach($task_report as $task){
                            $taskId = $task['SsmTask']['id'];
                            $arr_task_name[$taskId] = $task['SsmTask']['name'];
                            $tasks[$taskId] = $task['SsmTask'];
                        }

                        $todayWorks = array();
                        // $taskHoursLeft  = array();
                        $total_time = 0;
                        foreach($arrTodayWorks as $key=>$log){
                            $t_id                  = $log['task_id'];
                            $t_site_id             = $log['site_id'];
                            $t_site_name           = $t_site_id > 0 ? (mb_strlen($this->listSiteIDInfo[$t_site_id]['site_name']) > 7 ? (mb_substr($this->listSiteIDInfo[$t_site_id]['site_name'], 0, 5)."...様") : $this->listSiteIDInfo[$t_site_id]['site_name']) : ' その他 ' ;
                            $t_label_id            = $log['label_id'];
                            $t_label_text          = $t_label_id > 0 ? $label_option[$t_label_id] : ' その他 ';
                            //$todayWorks[]          = "・【".$t_site_name."】".$t_label_text." - (".floatval($log['task_time'])."h) - ".$arr_task_name[$t_id]. "\n";
                            $todayWorks[]          = "・【".$t_site_name."】".$t_label_text." - (".floatval($log['task_time'])."h) - ".$log['taskName']. "\n";
                            // $taskHoursLeft[$t_id]  = $this->getTaskHoursLeftReport($arr_task_name[$t_id], $tasks[$t_id], $log['task_time']);
                            $total_time           += $log['task_time'];
                        }

                        $tasksLeft = $this->getTasksLeftReport();

                        $chatwork_msg  = "■本日の作業 \n";
                        $chatwork_msg .= implode('', $todayWorks);
                        $chatwork_msg .= "      合計時間：" . floatval($total_time) . "h\n";
                        $chatwork_msg .= "\n";
                        $chatwork_msg .= "■タスクリスト \n";
                        $chatwork_msg .= implode('', $tasksLeft);
                        $chatwork_msg .= "\n";
                        $chatwork_msg .= "【思ったこと・次への一言！】 \n"; 
                        $chatwork_msg .= $task_note;                     
                        $chatwork_msg .= "\n";

                        header( "Content-type: text/html; charset=utf-8" );
                        $data = array(
                            'body' => $chatwork_msg
                        );

                        $opt = array(
                            CURLOPT_URL               => "https://api.chatwork.com/v2/rooms/{$to_group_cw_id}/messages",
                            CURLOPT_HTTPHEADER        => array( 'X-ChatWorkToken: ' . trim($from_chatwork_api)),
                            CURLOPT_RETURNTRANSFER    => TRUE,
                            CURLOPT_SSL_VERIFYPEER    => FALSE,
                            CURLOPT_POST              => TRUE,
                            CURLOPT_POSTFIELDS        => http_build_query( $data, '', '&' )
                        );

                        $ch = curl_init();
                        curl_setopt_array( $ch, $opt );
                        $res = curl_exec( $ch );
                        curl_close( $ch );

                        $res_k = json_decode($res);

                        if(isset($res_k->message_id) && !empty($res_k->message_id) ){

                        } else if( isset($res_k->errors) && $res_k->errors[0] == "Invalid API Token"){
                            $info = "Chatworkとの連携と失敗しました。APIトークンをご確認ください";
                            $this->Session->setFlash($info, 'warning');
                            $error++;
                        } else if( isset($res_k->errors) && $res_k->errors[0] == "You don't have permission to send/edit message." ){
                            $info = "ルームIDが正しくありません";
                            $this->Session->setFlash($info, 'warning');
                            $error++;
                        } else if( isset($res_k->errors) && $res_k->errors[0] == "Invalid Endpoint or HTTP method" ){
                            $info = "ルームIDが正しくありません";
                            $this->Session->setFlash($info, 'warning');
                            $error++;
                        }
                        //End send msg to cw.
                    }else{
                        $info = "タスクジャンルをご選択ください";
                        $this->Session->setFlash($info, 'warning');
                    }

                    if (!$error) {
                        $this->Session->setFlash('データはチャットワークにアップロードされました。', 'success');
                        return $this->redirect(array('action' => 'my'));
                    }
                } else {
                    $this->Session->setFlash('システムエラーが発生しました。もう一度お試しください。', 'warning');
                }

                $this->set('arr_task_log',$arr_task_log);
                $this->set('task_note',$task_note);
                $this->set('hide_task',$hide_task);

            }else{
                $this->Session->setFlash('タスクをご選択ください。', 'warning');
            }
        }

        $my_task = $this->SsmTask->find('all',
            array(
                'conditions'=>array(
                    'user_id'   => $this->loginUser['SsmUser']['id'],
                    'status'    => 0
                ),
                'order' => 'SsmTask.sort ASC'
            )
        );

        if(!empty($my_task)){
            $arr = array();
            foreach ($my_task as $task) {
                $arr[$task['SsmTask']['id']] = array(
                    'id'    => $task['SsmTask']['id'],
                    'name'  => $task['SsmTask']['name'],
                    'est'   => $task['SsmTask']['est'],
                    'sort'   => $task['SsmTask']['sort'],
                );
            }
            $my_task = $arr;
        }

        $this->set('stt',$stt);
        $this->set('my_task',$my_task);
        $this->set('list_client_option',$list_client_option);
        $this->set('list_label_option',$list_label_option);
        $this->set('arr_hide_task',$arr_hide_task);
        $this->set('baseUrl', $this->webroot);
    }

    protected function getTaskHoursLeftReport($taskName, $task, $hoursDone)
    {
        // Not report deleted task
        if ($task['status'] == 1) {
            return '';
        }

        $estimateHours = $task['est'];
        $hoursLeft     = floatval($estimateHours) - floatval($hoursDone);

        return '・' . $taskName . ' - (' . $hoursLeft . 'h)' . "\n";
    }

    protected function getTasksLeftReport()
    {
        $tasks = $this->SsmTask->find('all', [
            'conditions'  => [
                'user_id' => $this->loginUser['SsmUser']['id'],
                'status'  => 0
            ],
            'order' => 'SsmTask.sort ASC'
        ]);

        $tasks = array_map(function($task) {
            return $task['SsmTask'];
        }, $tasks);

        // $tasksLeft = array_values($taskHoursLeft);

        foreach ($tasks as $task) {
            //set new task name quick edit from view
            // $taskId = $task['id'];
            // if ($taskHoursLeft[$taskId]) {
            //     continue;
            // }

            if ( $task['est'] > 0 && $task['est'] != '0' ) {
                $tasksLeft[] = '・' . $task['name'] . ' - (' . floatval($task['est']) . 'h)' . "\n";
            }
        }

        return $tasksLeft;
    }
}
?>