<?php
App::uses('SsmAdminController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class OttUserController extends SsmAdminController {
	public $helpers    = array('Html', 'Form', 'Session');
    public $components = array('Session','SsmAuth');

	public function beforeFilter(){
		parent::beforeFilter();
	}

    public function beforeRender(){
        parent::beforeRender();
    }

    /*public function add(){
    	$this->layout = "shishimai";
    	$this->loadModel('SsmUser');

    	if($this->request->is('post')){
    		if(!empty($this->request->data)){
    			$data_user = array(
    				'username' => $this->request->data['SsmUser']['username'],
    				'password' => $this->SsmAuth->ssmHash(trim($this->request->data['SsmUser']['password']), 'e'),
    				'repeat_password' => $this->SsmAuth->ssmHash(trim($this->request->data['SsmUser']['repeat_password']), 'e'),
    				'role' => $this->request->data['SsmUser']['role'],
                    'status' => 1,
    				'created' => date('Y-m-d H-i-s'),
    				'modified' => date('Y-m-d H-i-s')
    				);

    			$this->SsmUser->set($data_user);
    			if($this->SsmUser->save()){
    				$this->Session->setFlash("User have been created", 'success');
    			} else {
    				$this->Session->setFlash("Error, please try again", 'error');
    			}
    		}
    	}
    }*/

    /**
     * Login page
     * @return [type] [description]
     */
	public function login(){
        $this->Session->destroy();
		$this->loadModel('SsmUser');
        $this->loadModel('SsmSite');
        $this->loadModel('SsmSiteUser');

        $show_warning = '';

		if($this->request->is('post')){
    		$username = trim($this->request->data['SsmUser']['username']);
            $password = trim($this->request->data['SsmUser']['password']);

            $data = $this->SsmUser->find("first", array(
                'conditions' => array(
                    'SsmUser.username' => $username,
                    'SsmUser.password' => $this->SsmAuth->ssmHash(trim($password), 'e'),
                    'SsmUser.status'   => 1,
                )
            ));

            if(!empty($data)){
                if($data['SsmUser']['role'] != 'admin'){
                    //Client
                    $site = $this->SsmSiteUser->find("all", array(
                        'joins' => array(
                            array(
                                'table' => 'ssm_contracts',
                                'alias' => 'SsmContract',
                                'type'  => 'LEFT',
                                'conditions' => array(
                                    'SsmContract.site_id = SsmSiteUser.site_id'
                                )
                            )
                        ),
                        'conditions' => array(
                            'SsmSiteUser.user_id'       =>$data['SsmUser']['id'],
                            'SsmContract.start_day <='  =>date('Y-m-d'),
                            'SsmContract.end_day >='    =>date('Y-m-d')
                        )
                    ));

                    if(!$site){
                        $show_warning = 'true';
                    }else{
                        if($this->SsmAuth->login($data)){
                            if($this->SsmAuth->getActiveSite()){
                                $site_id    = $this->SsmAuth->getActiveSite();
                            }else{
                                $siteList   = $this->SsmSite->getListSiteIDOfUser($data['SsmUser']['role'],$data['SsmUser']['id']);
                                $site_id    = $this->SsmAuth->setActiveSite($siteList['arr_id'][0]);
                            }

                            $this->redirect('/OttHome?site_id='.$site_id);
                        } else {
                            $this->Session->setFlash(__('エラーになりました。再度試してください！'), 'warning');
                        }
                    }
                }else{
                    //Admin
                    if($this->SsmAuth->login($data)){

                        if($this->SsmAuth->getActiveSite()){
                            $site_id  = $this->SsmAuth->getActiveSite();
                        }else{
                            $siteList = $this->SsmSite->getListSiteIDOfUser($data['SsmUser']['role'],$data['SsmUser']['id']);
                            if(in_array(30,$siteList['arr_id'])){
                                $site_id  = $this->SsmAuth->setActiveSite(30);
                            }else{
                                $site_id  = $this->SsmAuth->setActiveSite($siteList['arr_id'][0]);
                            }
                        }

                        $this->redirect('/OttHome?site_id='.$site_id);
                    } else {
                        $this->Session->setFlash(__('エラーになりました。再度試してください！'), 'warning');
                    }
                }
            }else{
                $this->Session->setFlash(__('ログイン情報が間違っています。'), 'warning');
            }
		}
        $this->set('show_warning',$show_warning);
	}

    /**
     * Account profile page
     * @return [type] [description]
     */
    public function myaccount(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = "ott";
        $this->loadModel('SsmUser');

        $crr_user = $this->SsmAuth->getLoginUser();
        $crr_user = $this->SsmUser->findById($crr_user['SsmUser']['id']);

        if($this->request->is('post') || $this->request->is('put')){
            $this->SsmUser->id = $crr_user['SsmUser']['id'];
            if ($this->SsmUser->validate_edit()) {
                if ($this->SsmUser->save($this->request->data)) {
                    $this->Session->setFlash(__('更新は成功です。'),'success');
                    $this->redirect(array('action'=>'myaccount'));
                }else{
                    $this->Session->setFlash(__('更新は失敗です。再度試してください。'), 'warning');
                }
            }
            $this->request->data['SsmUser']['username'] = $crr_user['SsmUser']['username'];
        }else{
            $this->request->data = $crr_user;
        }
    }

    /**
     * Change password page
     * @return [type] [description]
     */
    public function changepassword(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = "ott";
        $this->loadModel('SsmUser');

        $crr_user = $this->SsmAuth->getLoginUser();
        $crr_user = $this->SsmUser->findById($crr_user['SsmUser']['id']);

        if($this->request->is('post') || $this->request->is('put')){
            $this->SsmUser->set($this->request->data);
            if ($this->SsmUser->validate_changepassword()) {
                $this->SsmUser->id = $crr_user['SsmUser']['id'];

                if ($this->SsmUser->saveField('password',$this->SsmAuth->ssmHash(trim($this->request->data['SsmUser']['password']), 'e'))) {
                    $this->Session->setFlash(__('更新は成功です。'),'success');
                    $this->redirect(array('action'=>'changepassword'));
                }else{
                    $this->Session->setFlash(__('更新は失敗です。再度試してください。'), 'warning');
                    $this->redirect(array('action'=>'changepassword'));
                }
            }else{
                $this->Session->setFlash(__('エラーになりました。再度試してください！'), 'warning');
            }
        }

        //$this->request->data = $crr_user;
    }

    /**
     * Edit chatwork page
     * @return [type] [description]
     */
    public function editchatwork(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================
        $user_id = $loginUser['SsmUser']['id'];
        $this->layout = "ott";
        // $this->loadModel("SsmSite");
        $this->loadModel("SsmUser");
        $this->loadModel("SsmSite");

        if($this->request->is('post')){
            $chatwork_api = trim($this->request->data['chatwork_api']);
            if(empty($chatwork_api)){
                echo 'empty';
                exit;
            } else {
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

                if(empty($room_id[0])){
                    //key invalid
                    echo 'error';exit;
                } else {
                    //success
                    //Update key Admin
                    $this->SsmUser->id = $user_id;
                    $this->SsmUser->updateAll(
                        array(
                            'SsmUser.chatwork_api' => "'".$chatwork_api."'"
                        ),
                        array(
                            'SsmUser.id' => $user_id
                        )
                    );
                    //Update key all site user manage
                    if($this->loginUser['SsmUser']['role'] == 'admin'){
                        $this->SsmSite->updateAll(
                            array(
                                'SsmSite.chatwork_api' => "'".$chatwork_api."'"
                            ),
                            array(
                                'SsmSite.site_manage_user' => $user_id
                        ));
                    }
                    
                    echo 'success';exit;
                }
            }
        }
    }

    /**
     * Logout
     * @return [type] [description]
     */
	public function logout() {
        if($this->SsmAuth->logout()){
            $this->redirect(array("action" => "login"));
        }
	}

    /**
     * Admin page
     * @return [type] [description]
     */
    public function admin(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = "ott";
        $this->loadModel('SsmUser');
        $staff = $this->SsmUser->find('all',array(
            'conditions'=>array(
                'role'=>'admin',
                'status !='=>0
            ),
            'fields'=>array('id','first_name','last_name','username','status')
        ));
        $this->set('list',$staff);
    }

    /**
     * Client page
     * @return [type] [description]
     */
    public function client(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = "ott";
        $this->loadModel('SsmSite');
        $this->loadModel('SsmUser');

        $sites = $this->SsmSite->find('all',array(
            'fields'=>array('id','site_description','site_url')
        ));

        $this->set('list_site',$sites);

        $site_user = $this->SsmSite->find('all',
            array(
                'joins' => array(
                    array(
                        'table' => 'ssm_site_users',
                        'alias' => 'SsmSiteUser',
                        'type' => 'INNER',
                        'conditions' => array(
                            'SsmSiteUser.site_id = SsmSite.id'
                        )
                    ),
                    array(
                        'table' => 'ssm_users',
                        'alias' => 'SsmUser',
                        'type' => 'INNER',
                        'conditions' => array(
                            'SsmSiteUser.user_id = SsmUser.id'
                        )
                    ),
                ),
                'conditions' => array('SsmUser.role !=' => 'admin'),
                'fields'=> array('SsmSite.id','SsmUser.id','SsmUser.first_name','SsmUser.last_name','SsmUser.username','SsmUser.status','SsmUser.role')
            )
        );

        $user_grouped_by_site = array();
        if($site_user){
            foreach ($site_user as $item) {
                $user_grouped_by_site[$item['SsmSite']['id']][] = $item;
            }
        }

        $this->set('list_site_user',$user_grouped_by_site);
    }


    /*public function partner(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = "ott";
        $this->loadModel('SsmSite');
        $this->loadModel('SsmUser');

        $sites = $this->SsmSite->find('all',array(
            'fields'=>array('id','site_description','site_url')
        ));

        $this->set('list_site',$sites);

        $site_user = $this->SsmSite->find('all',
            array(
                'joins' => array(
                    array(
                        'table' => 'ssm_site_users',
                        'alias' => 'SsmSiteUser',
                        'type'  => 'INNER',
                        'conditions' => array(
                            'SsmSiteUser.site_id = SsmSite.id'
                        )
                    ),
                    array(
                        'table' => 'ssm_users',
                        'alias' => 'SsmUser',
                        'type'  => 'INNER',
                        'conditions' => array(
                            'SsmSiteUser.user_id = SsmUser.id'
                        )
                    ),
                ),
                'conditions' => array('SsmUser.role' => 'partner'),
                'fields' => array('SsmSite.id','SsmUser.id','SsmUser.first_name','SsmUser.last_name','SsmUser.username','SsmUser.status')
            )
        );

        $user_grouped_by_site = array();
        if($site_user){
            foreach ($site_user as $item) {
                $user_grouped_by_site[$item['SsmSite']['id']][] = $item;
            }
        }

        $this->set('list_site_user',$user_grouped_by_site);
    }*/

    /**
     * Invite admin page
     * @return [type] [description]
     */
    public function inviteadmin(){

        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        Configure::load('config_shishimai');
        $email_send = Configure::read('email_send');

        $this->layout = "ott";
        $this->loadModel('SsmUser');
        if($this->request->is('post') || $this->request->is('put')){
            $this->SsmUser->set($this->request->data);
            if ($this->SsmUser->validate_inviteadmin()) {
                //Send mail
                $data = array(
                    'username'  =>trim($this->request->data['SsmUser']['username']),
                    'password'  =>$this->SsmAuth->ssmHash($this->SsmAuth->randomPassword(),'e'),
                    'role'      =>'admin',
                    'status'    =>'2',
                );
                $this->SsmUser->set('username',$data['username']);
                $this->SsmUser->set('password',$data['password']);
                $this->SsmUser->set('role',$data['role']);
                $this->SsmUser->set('status',$data['status']);
                $this->SsmUser->save();

                if($_SERVER['SERVER_NAME'] == 'web-otetsudai.jp'){
                    $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/report/OttUser/update?token=".$data['password'];
                }else{
                    $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/OttUser/update?token=".$data['password'];
                }

                $Email = new CakeEmail();
                $Email->config('default');
                $Email
                ->subject('【ウェブ解析お手伝いさん】ユーザー招待のお知らせ')
                ->emailFormat('html')
                ->template('inviteadmin')
                ->to(trim($this->request->data['SsmUser']['username']))
                ->from($email_send)
                ->viewVars(array(
                    'email' =>trim($this->request->data['SsmUser']['username']),
                    'link'  =>$url
                ));

                if (!$Email->send ()) {
                    return false;
                }

                $this->Session->setFlash(__('ユーザーを招待しました。'), 'success');
                return $this->redirect(array('action'=>'admin'));
            }else{
                $this->Session->setFlash(__('エラーになりました。再度試してください！'), 'warning');
            }
        }
    }

    /**
     * Invite client & partner page
     * @return [type] [description]
     */
    public function inviteuser(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        Configure::load('config_shishimai');
        $email_send = Configure::read('email_send');

        $this->layout = "ott";
        $this->loadModel('SsmUser');
        $this->loadModel('SsmSiteUser');
        if(!isset($this->request->query['site_id']) || $this->request->query['site_id'] <=0){
            $this->Session->setFlash(__('Site id invalid'),'warning');
            return $this->redirect($this->referer());
        }

        if($this->request->is('post') || $this->request->is('put')){
            $this->SsmUser->set($this->request->data);
            if ($this->SsmUser->validate_invite()) {

                $user = $this->SsmUser->find('first',
                    array(
                        'conditions'=>array(
                            'username'=>trim($this->request->data['SsmUser']['username'])
                        )
                    )
                );

                if($user){
                    if(trim($user['SsmUser']['role']) == 'admin'){
                        $this->Session->setFlash(__('このユーザーはすでに参加済みです。'), 'warning');
                    }else{

                        if($user['SsmUser']['role'] == 'client' && $this->request->data['SsmUser']['role'] == 'partner' ){
                            $this->Session->setFlash(__('入力されたメールアドレスは既に存在しています。'), 'warning');
                        }else if($user['SsmUser']['role'] == 'partner' && $this->request->data['SsmUser']['role'] == 'client'){
                            $this->Session->setFlash(__('このメールアドレスは代理店のアカウントとして登録されています。'), 'warning');
                        }else{
                            //check user added to site
                            $exits = $this->SsmSiteUser->find('first',array(
                                'conditions'=>array('user_id'=>$user['SsmUser']['id'],'site_id'=>$this->request->query['site_id'])
                            ));

                            if($exits){
                                $this->Session->setFlash(__('メールアドレスはすでに登録されています。'),'warning');
                            }else{
                                $this->SsmSiteUser->set('site_id',$this->request->query['site_id']);
                                $this->SsmSiteUser->set('user_id',$user['SsmUser']['id']);
                                $this->SsmSiteUser->save();
                                $this->Session->setFlash(__('クライアントにユーザーが追加されました。'),'success');
                                return $this->redirect(array('action'=>'client'));
                            }
                        }
                    }
                }else{
                    //Send mail
                    $data = array(
                        'username'  =>trim($this->request->data['SsmUser']['username']),
                        'password'  =>$this->SsmAuth->ssmHash($this->SsmAuth->randomPassword(),'e'),
                        'role'      =>$this->request->data['SsmUser']['role'],
                        'status'    =>'2',
                    );

                    $this->SsmUser->set('username',$data['username']);
                    $this->SsmUser->set('password',$data['password']);
                    $this->SsmUser->set('role',$data['role']);
                    $this->SsmUser->set('status',$data['status']);

                    if($this->SsmUser->save()){
                        $user_id = $this->SsmUser->getLastInsertId();
                        $this->SsmSiteUser->set('site_id',$this->request->query['site_id']);
                        $this->SsmSiteUser->set('user_id',$user_id);
                        $this->SsmSiteUser->save();
                    }

                    if($_SERVER['SERVER_NAME'] == 'web-otetsudai.jp'){
                        $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/report/OttUser/update?token=".$data['password'];
                    }else{
                        $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/OttUser/update?token=".$data['password'];
                    }

                    $role_teplate = 'invite'.$data['role'];

                    $Email = new CakeEmail();
                    $Email->config('default');
                    $Email
                    ->subject('【ウェブ解析お手伝いさん】ユーザー招待のお知らせ')
                    ->emailFormat('html')
                    ->template($role_teplate)
                    ->to($this->request->data['SsmUser']['username'])
                    ->from($email_send)
                    ->viewVars(array(
                        'email' =>trim($this->request->data['SsmUser']['username']),
                        'link'  =>$url
                    ));

                    if (!$Email->send ()) {
                        return false;
                    }
                    $this->Session->setFlash(__('ユーザーを招待しました。'), 'success');
                    return $this->redirect(array('action'=>'client'));
                }

            }else{
                $this->Session->setFlash(__('エラーになりました。再度試してください！'), 'warning');
            }
        }
    }


    /**
     * Update account info
     * @return [type] [description]
     */
    public function update(){
        $this->layout = "ott_blank";
        $this->set('title_for_layout', 'View Active Users');
        $this->loadModel('SsmUser');
        if($this->request->query['token']){
            $tk =$this->request->query['token'];
            //End get user by token
            $crr_user = $this->SsmUser->find('first',array(
                'conditions'=>array(
                    'password'  =>$tk,
                    'status'    =>2
                ),
                'fields'    =>array(
                    'username','id'
                )
            ));

            if($this->request->is('post') || $this->request->is('put')){
                if(!$crr_user){
                    $this->Session->setFlash(__('トークンコードは不正、またすでに登録されています。'), 'warning');
                }else{
                    $this->SsmUser->id = $crr_user['SsmUser']['id'];
                    $str_len = strlen($this->request->data['SsmUser']['password']);
                    if ($this->SsmUser->validate_edit() && $str_len >= 6) {

                        if($this->request->data['SsmUser']['password'] != "" && $this->request->data['SsmUser']['repeat_password'] != ""){
                            $this->request->data['SsmUser']['password'] = $this->SsmAuth->ssmHash(trim($this->request->data['SsmUser']['password']), 'e');
                            $this->request->data['SsmUser']['repeat_password'] = $this->SsmAuth->ssmHash(trim($this->request->data['SsmUser']['repeat_password']), 'e');
                        }

                        $this->request->data['SsmUser']['status'] = 1;

                        if ($this->SsmUser->save($this->request->data)) {
                            $this->Session->setFlash(__('更新は成功です。'),'success');
                            return $this->redirect(array('action'=>'login'));
                            $this->request->data = array();
                        }else{
                            $this->Session->setFlash(__('更新は失敗です。再度試してください。'), 'warning');
                            $this->request->data['SsmUser']['password'] = "";
                            $this->request->data['SsmUser']['repeat_password'] = "";
                        }
                    }else{
                        if($str_len < 6){
                            $this->SsmUser->invalidate('password',"パスワードは6文字以上でなければなりません。");
                        }
                        $this->Session->setFlash(__('エラーになりました。再度試してください！'), 'warning');
                    }
                }

            }else{
                $this->request->data = $crr_user;
            }

        }else{
            $this->Session->setFlash(__('トークンコードは不正です。'), 'warning');
        }
    }

    public function delete(){
        $this->loadModel('SsmUser');
        $this->loadModel('SsmSiteUser');
        if(!$this->request->query['user_id']){
            $this->Session->setFlash(__('ユーザーIDは間違ってます。'),'warning');
        }

        $user = $this->SsmUser->find('first',array(
            'conditions'=>array(
                'id'  =>$this->request->query['user_id']
            ),
            'fields'    =>array(
                'role'
            )
        ));

        if($user){
            if($user['SsmUser']['role'] == 'admin'){
                if($this->SsmUser->deleteAll(array('SsmUser.id'=>$this->request->query['user_id']))){
                    $this->SsmSiteUser->deleteAll(array('SsmSiteUser.user_id'=>$this->request->query['user_id']));
                    $this->Session->setFlash(__('アカウントの削除は成功です'),'success');
                }else{
                    $this->Session->setFlash(__('アカウントの削除は失敗です。再度試してください'),'warning');
                }
            }else{

                $user_site = $this->SsmSiteUser->find('all',array(
                    'conditions'=>array('SsmSiteUser.user_id'=>$this->request->query['user_id'])
                ));

                if(count($user_site) == 1){
                    $this->SsmUser->deleteAll(array('SsmUser.id'=>$this->request->query['user_id']));
                }

                $array_where = array('SsmSiteUser.user_id'=>$this->request->query['user_id']);
                if(isset($this->request->query['site_id'])){
                    $array_where['SsmSiteUser.site_id'] = $this->request->query['site_id'];
                }

                if($this->SsmSiteUser->deleteAll($array_where)){
                    $this->Session->setFlash(__('ユーザーを削除しました。'),'success');
                }else{
                    $this->Session->setFlash(__('アカウントの削除は失敗です。再度試してください'),'warning');
                }
            }
        }else{

        }
        return $this->redirect($this->referer());
    }
}
?>