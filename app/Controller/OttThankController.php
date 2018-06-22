<?php
App::uses('SsmAdminController', 'Controller');
App::uses('AppHelper', 'View/Helper');

class OttThankController extends SsmAdminController{
    public $helpers     = array('Shishimai','Html', 'Session');
    public $components  = array('Session','SsmAuth', 'Cshishimai', 'OttClient');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function beforeRender(){
        parent::beforeRender();
    }

    /**
     * Thank home
     * @return [type] [description]
     */
    public function index(){
        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

    }

    /**
     * Send thank form
     * @return [type] [description]
     */
    public function send(){
        $this->layout = "ott";
        //User and Site define ========================================================

        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================
        Configure::load('config_shishimai');
        $this->loadModel("SsmUser");
        $this->loadModel("SsmThank");
        $this->loadModel("SsmThankMsg");
        $admin_list = $this->SsmUser->find('all',
            array(
                'conditions'=>array(
                    //'role'  => 'admin',
                    'OR' => array(
                        'role' => 'admin',
                        'role' => 'worker'
                    ),
                    'status'=> 1
                )
            )
        );

        $admin_option = array();
        $userId_map_keyapi = array();
        if($admin_list){
            foreach ($admin_list as $item) {
                
                $admin_option[$item['SsmUser']['id']] = $item['SsmUser']['first_name'].$item['SsmUser']['last_name']."(".$item['SsmUser']['username'].")";
                
                $userId_map_keyapi[$item['SsmUser']['id']] = $item['SsmUser']['chatwork_api'];
            }
        }

        //Submit
        if($this->request->is('post')){
            //Get chatwork user info
            $error = 0;
            $from_user_id           = $loginUser['SsmUser']['id'];
            $msg                    = $this->request->data['Thank']['msg'];
            $to_user_id             = $this->request->data['Thank']['to'];
            $to_user_chatwork_api   = $userId_map_keyapi[$to_user_id];//key api chatwork receiver
            $to_group_cw_id         = Configure::read('cw_thank_group_id');//Send msg to this group
            $from_chatwork_api      = $userId_map_keyapi[$from_user_id];//Using userlogin chatwork api

            if(empty($msg)){
                $this->set('error_msg','(*) 必須!');
                $error++;
            }

            if(empty($to_user_id)){
                $this->set('error_to','(*) 必須!');
                $error++;
            }

            if(!isset($userId_map_keyapi[$from_user_id]) || trim($userId_map_keyapi[$from_user_id]) == ""){
                $info = "マイアカウントはチャットワーク連携されていません";
                $this->Session->setFlash($info, 'warning');
                $error++;
            }

            if(!isset($userId_map_keyapi[$to_user_id]) || trim($userId_map_keyapi[$to_user_id]) == ""){
                $info = "アリガトウ受信者のアカウントはチャットワーク連携されていません";
                $this->Session->setFlash($info, 'warning');
                $error++;
            }
            
            //ng gui
            if(!$error){
                $opt = array(
                  CURLOPT_URL               => "https://api.chatwork.com/v2/me",
                  CURLOPT_HTTPHEADER        => array( 'X-ChatWorkToken: ' . trim($from_chatwork_api) ),
                  CURLOPT_RETURNTRANSFER    => TRUE,
                  CURLOPT_SSL_VERIFYPEER    => FALSE,
                  CURLOPT_POST              => FALSE
                );

                $ch = curl_init();
                curl_setopt_array( $ch, $opt );
                $res = curl_exec( $ch );
                curl_close( $ch );

                $sender_k = json_decode($res);              

                if( isset($sender_k->errors) && $sender_k->errors[0] == "Invalid API Token"){
                    $info = "FROMのユーサーのアリガトウ受信者のチャットワーク情報を取得できません";
                    $this->Session->setFlash($info, 'warning');
                    $error++;
                } else if( isset($sender_k->errors) && $sender_k->errors[0] == "You don't have permission to send/edit message." ){
                    $info = "FROMのユーサーのアリガトウ受信者のチャットワーク情報を取得できません";
                    $this->Session->setFlash($info, 'warning');
                    $error++;
                } else if( isset($sender_k->errors) && $sender_k->errors[0] == "Invalid Endpoint or HTTP method" ){
                    $info = "FROMのユーサーのアリガトウ受信者のチャットワーク情報を取得できません";
                    $this->Session->setFlash($info, 'warning');
                    $error++;
                }
            }
            
            //nguoi nhan
            if(!$error){
                $opt = array(
                  CURLOPT_URL               => "https://api.chatwork.com/v2/me",
                  CURLOPT_HTTPHEADER        => array( 'X-ChatWorkToken: ' . trim($to_user_chatwork_api) ),
                  CURLOPT_RETURNTRANSFER    => TRUE,
                  CURLOPT_SSL_VERIFYPEER    => FALSE,
                  CURLOPT_POST              => FALSE
                );

                $ch = curl_init();
                curl_setopt_array( $ch, $opt );
                $res = curl_exec( $ch );
                curl_close( $ch );

                $res_k = json_decode($res);

                if( isset($res_k->errors) && $res_k->errors[0] == "Invalid API Token"){
                    $info = "TOのユーサーのアリガトウ受信者のチャットワーク情報を取得できません";
                    $this->Session->setFlash($info, 'warning');
                    $error++;
                } else if( isset($res_k->errors) && $res_k->errors[0] == "You don't have permission to send/edit message." ){
                    $info = "TOのユーサーのアリガトウ受信者のチャットワーク情報を取得できません";
                    $this->Session->setFlash($info, 'warning');
                    $error++;
                } else if( isset($res_k->errors) && $res_k->errors[0] == "Invalid Endpoint or HTTP method" ){
                    $info = "TOのユーサーのアリガトウ受信者のチャットワーク情報を取得できません";
                    $this->Session->setFlash($info, 'warning');
                    $error++;
                }
            }

            if(!$error){
                $receiver_cw_info = $res_k;
                //Send msg to chatwork user in the group
                $cw_msg = "[To:{$receiver_cw_info->account_id}] {$receiver_cw_info->name} \n".$msg;

                header( "Content-type: text/html; charset=utf-8" );
                $data = array(
                  'body' => $cw_msg
                );

                $opt = array(
                  CURLOPT_URL               => "https://api.chatwork.com/v2/rooms/{$to_group_cw_id}/messages",
                  CURLOPT_HTTPHEADER        => array( 'X-ChatWorkToken: ' . trim($from_chatwork_api )),
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

                if(!$error){
                    //Insert msg
                    $this->SsmThankMsg->create();
                    $data_thank_msg = array(
                        'msg'=> $msg
                    );
                    $this->SsmThankMsg->save($data_thank_msg);
                    $msg_id = $this->SsmThankMsg->getLastInsertId();
                    //Insert thank info
                    if($msg_id){
                        $this->SsmThank->create();
                        $data_thank = array(
                            'from_user_id'  => $from_user_id,
                            'to_user_id'    => $to_user_id,
                            'msg_id'        => $msg_id,
                            'created'       => date('Y-m-d H:i:s'),
                            'modified'      => date('Y-m-d H:i:s'),
                        );
                        $this->SsmThank->save($data_thank);
                        $this->Session->setFlash('アリガトウが送信されました', 'success');
                        $this->request->data = array();
                    }else{
                        $this->Session->setFlash('アリガトウが送信されませんでした', 'warning');
                    }
                }
            }
        }

        $this->set('admin_option',$admin_option);
    }

}