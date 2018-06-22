<?php
App::uses('Component', 'Controller');

class SsmAuthComponent extends Component {
	public $components = array('Session');

	//all action require permission
	public $action_required = array(
        'otthome__index',

        'ottimport__index',

        'ottkpi__index',

        'ottplan__index',
        'ottplan__add',
        'ottplan__update',
        'ottplan__delete',

        'ottreport__index',
        'ottreport__report_month',
        'ottreport__report_week',
        'ottreport__see_week',
        'ottreport__edit_week',
        'ottreport__setting_edit_week',
        'ottreport__see_month',
        'ottreport__edit_month',
        'ottreport__new_slide',
        'ottreport__delete_report',
        'ottreport__postChatwork',

        'ottsetting__index',
        'ottsetting__update',

        'ottthank__index',
        'ottthank__send',

        'ottuser__myaccount',
        'ottuser__changepassword',
        'ottuser__editchatwork',
        'ottuser__admin',
        'ottuser__client',
        'ottuser__partner',
        'ottuser__inviteadmin',
        'ottuser__inviteuser',
        'ottuser__delete',

        'ssmclient__index',
        'ssmclient__add',
        'ssmclient__detail',
        'ssmclient__edit',
        'ssmclient__edit_chatwork',
        'ssmclient__edit_account',
        'ssmclient__remove_plan',
        'ssmclient__kpi',
        'ssmclient__suspend',

        'otttask__index',
        'otttask__my',

        'otttasklabel__index',
        'otttasklabel__add',
        'otttasklabel__update',
        'otttasklabel__delete',
    );

	//permission of admin
    public $admin_allow = array(
        'otthome__index',

        'ottimport__index',

        'ottkpi__index',

        'ottplan__index',
        'ottplan__add',
        'ottplan__update',
        'ottplan__delete',

        'ottreport__index',
        'ottreport__report_month',
        'ottreport__report_week',
        'ottreport__see_week',
        'ottreport__edit_week',
        'ottreport__setting_edit_week',
        'ottreport__see_month',
        'ottreport__edit_month',
        'ottreport__new_slide',
        'ottreport__delete_report',
        'ottreport__postChatwork',

        'ottsetting__index',
        'ottsetting__update',

        'ottthank__index',
        'ottthank__send',

        'ottuser__myaccount',
        'ottuser__changepassword',
        'ottuser__editchatwork',
        'ottuser__admin',
        'ottuser__client',
        'ottuser__partner',
        'ottuser__inviteadmin',
        'ottuser__inviteuser',
        'ottuser__delete',

        'ssmclient__index',
        'ssmclient__add',
        'ssmclient__detail',
        'ssmclient__edit',
        'ssmclient__edit_chatwork',
        'ssmclient__edit_account',
        'ssmclient__remove_plan',
        'ssmclient__kpi',
        'ssmclient__suspend',

        'otttask__index',
        'otttask__my',

        'otttasklabel__index',
        'otttasklabel__add',
        'otttasklabel__update',
        'otttasklabel__delete',
    );

    //permission of partner
    public $partner_allow = array(
        'otthome__index',

        'ottimport__index',

        'ottkpi__index',

        //'ottplan__index',
        //'ottplan__add',
        //'ottplan__update',
        //'ottplan__delete',

        'ottreport__index',
        'ottreport__report_month',
        //'ottreport__report_week',
        'ottreport__see_week',
        //'ottreport__edit_week',
        //'ottreport__setting_edit_week',
        'ottreport__see_month',
        'ottreport__edit_month',
        'ottreport__new_slide',
        'ottreport__delete_report',
        //'ottreport__postChatwork',

        //'ottsetting__index',
        //'ottsetting__update',

        //'ottthank__index',
        //'ottthank__send',

        'ottuser__myaccount',
        'ottuser__changepassword',
        //'ottuser__editchatwork',
        //'ottuser__admin',
        //'ottuser__client',
        //'ottuser__partner',
        //'ottuser__inviteadmin',
        //'ottuser__inviteuser',
        //'ottuser__delete',

        //'ssmclient__index',
        //'ssmclient__add',
        //'ssmclient__detail',
        //'ssmclient__edit',
        //'ssmclient__edit_chatwork',
        //'ssmclient__edit_account',
        //'ssmclient__remove_plan',
        //'ssmclient__kpi',

        //'otttask__index',
        //'otttask__my',

        //'otttasklabel__index',
        //'otttasklabel__add',
        //'otttasklabel__update',
        //'otttasklabel__delete',
    );

    //permission of worker
    public $worker_allow = array(
        'otthome__index',

        'ottimport__index',

        'ottkpi__index',

        //'ottplan__index',
        //'ottplan__add',
        //'ottplan__update',
        //'ottplan__delete',

        'ottreport__index',
        'ottreport__report_month',
        //'ottreport__report_week',
        'ottreport__see_week',
        //'ottreport__edit_week',
        //'ottreport__setting_edit_week',
        'ottreport__see_month',
        'ottreport__edit_month',
        'ottreport__new_slide',
        'ottreport__delete_report',
        //'ottreport__postChatwork',

        //'ottsetting__index',
        //'ottsetting__update',

        'ottthank__index',
        'ottthank__send',

        'ottuser__myaccount',
        'ottuser__changepassword',
        'ottuser__editchatwork',
        //'ottuser__admin',
        //'ottuser__client',
        //'ottuser__partner',
        //'ottuser__inviteadmin',
        //'ottuser__inviteuser',
        //'ottuser__delete',

        //'ssmclient__index',
        //'ssmclient__add',
        //'ssmclient__detail',
        //'ssmclient__edit',
        //'ssmclient__edit_chatwork',
        //'ssmclient__edit_account',
        //'ssmclient__remove_plan',
        //'ssmclient__kpi',

        'otttask__index',
        'otttask__my',

        //'otttasklabel__index',
        //'otttasklabel__add',
        //'otttasklabel__update',
        //'otttasklabel__delete',
    );

    //permission of client
    public $client_allow = array(
        'otthome__index',

        //'ottimport__index',

        'ottkpi__index',

        //'ottplan__index',
        //'ottplan__add',
        //'ottplan__update',
        //'ottplan__delete',

        'ottreport__index',
        'ottreport__report_month',
        'ottreport__report_week',
        'ottreport__see_week',
        //'ottreport__edit_week',
        //'ottreport__setting_edit_week',
        'ottreport__see_month',
        //'ottreport__edit_month',
        //'ottreport__new_slide',
        //'ottreport__delete_report',
        //'ottreport__postChatwork',

        //'ottsetting__index',
        //'ottsetting__update',

        //'ottthank__index',
        //'ottthank__send',

        'ottuser__myaccount',
        'ottuser__changepassword',
        'ottuser__editchatwork',
        //'ottuser__admin',
        //'ottuser__client',
        //'ottuser__partner',
        //'ottuser__inviteadmin',
        //'ottuser__inviteuser',
        //'ottuser__delete',

        //'ssmclient__index',
        //'ssmclient__add',
        //'ssmclient__detail',
        //'ssmclient__edit',
        //'ssmclient__edit_chatwork',
        //'ssmclient__edit_account',
        //'ssmclient__remove_plan',
        //'ssmclient__kpi',

        'otttask__index',
        //'otttask__my',

        //'otttasklabel__index',
        //'otttasklabel__add',
        //'otttasklabel__update',
        //'otttasklabel__delete',
    );

    //action on ui
    public $ui_action = array(
        //action for admin
        'admin'=>array(
            'change_site_note'  => true,
            'change_site_range' => true,
            'change_site_kpi'   => true,
            'copy_month_data'   => true,
            'send_cw_month_data'=> true,
            'import_csv'        => true,
            'export_csv'        => true,
            'edit_advice_title' => true,
            'edit_advice_note'  => true,
            'edit_week_target'  => true,
            'edit_week_actual'  => true,

            'create_report_month'=>true,
            'view_report_month'  =>true,
            'edit_report_month'  =>true,
                //action in edit report month
                'create_slide'  =>true,
                'sort_slide'    =>true,
                'public_report' =>true,
            'delete_report_month'=>true,


            'create_report_week'=>true,
            'view_report_week'  =>true,
            'edit_report_week'  =>true,
            'delete_report_week'=>true,

            'api_getKpiClient'  =>true,
            'task_showuser'     =>true,    
        ),
        //action for partner
        'partner'=>array(
            'change_site_note'  => true,
            'change_site_range' => true,
            'change_site_kpi'   => true,
            'copy_month_data'   => true,
            'send_cw_month_data'=> false,
            'import_csv'        => true,
            'export_csv'        => true,
            'edit_advice_title' => true,
            'edit_advice_note'  => true,
            'edit_week_target'  => true,
            'edit_week_actual'  => true,

            'create_report_month'=>true,
            'view_report_month'  =>true,
            'edit_report_month'  =>true,
                //action in edit report month
                'create_slide'  =>true,
                'sort_slide'    =>true,
                'public_report' =>true,
            'delete_report_month'=>true,

            'create_report_week'=>false,
            'view_report_week'  =>true,
            'edit_report_week'  =>false,
            'delete_report_week'=>false,

            'api_getKpiClient'  =>false,
            'task_showuser'     =>false,
        ),
        //action for partner
        'worker'=>array(
            'change_site_note'  => true,
            'change_site_range' => true,
            'change_site_kpi'   => true,
            'copy_month_data'   => true,
            'send_cw_month_data'=> false,
            'import_csv'        => true,
            'export_csv'        => true,
            'edit_advice_title' => true,
            'edit_advice_note'  => true,
            'edit_week_target'  => true,
            'edit_week_actual'  => true,

            'create_report_month'=>true,
            'view_report_month'  =>true,
            'edit_report_month'  =>true,
                //action in edit report month
                'create_slide'  =>true,
                'sort_slide'    =>true,
                'public_report' =>true,
            'delete_report_month'=>true,

            'create_report_week'=>false,
            'view_report_week'  =>true,
            'edit_report_week'  =>false,
            'delete_report_week'=>false,

            'api_getKpiClient'  =>false,
            'task_showuser'     =>false,
        ),
        //action for client
        'client'=>array(
            'change_site_note'  => false,
            'change_site_range' => false,
            'change_site_kpi'   => false,
            'copy_month_data'   => false,
            'send_cw_month_data'=> false,
            'import_csv'        => false,
            'export_csv'        => false,
            'edit_advice_title' => true,
            'edit_advice_note'  => true,
            'edit_week_target'  => false,
            'edit_week_actual'  => false,

            'create_report_month'=>false,
            'view_report_month'  =>true,
            'edit_report_month'  =>false,
                //action in edit report month
                'create_slide'  =>false,
                'sort_slide'    =>false,
                'public_report' =>false,
            'delete_report_month'=>false,

            'create_report_week'=>false,
            'view_report_week'  =>true,
            'edit_report_week'  =>false,
            'delete_report_week'=>false,

            'api_getKpiClient'  =>false,
            'task_showuser'     =>false,
        )
    );

	public $site_list_id = null;

    /**
     * Login
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
	public function login($user){
		if(!empty($user)){
			$this->Session->write('checkAuth',$user);
			return true;
		} else {
			return false;
		}
	}

    /**
     * Logout
     * @return [type] [description]
     */
	public function logout(){
		$this->Session->destroy();
		return true;
	}

	/**
     * Get login user data
     * @return [type] [description]
     */
	public function getLoginUser(){
		if(!$this->Session->check('checkAuth')){
			return false;
		} else {
			return $this->Session->read('checkAuth');
		}
	}

	/**
     * Check role user with site
     * @param  [type] $site_id [description]
     * @return [type]          [description]
     */
	public function checkRoleSite($site_id){
		$user = $this->getLoginUser();
		if(!$user){
			return false;
		}else{
			if($user['SsmUser']['role'] == 'admin'){
				return 'admin';
			}
		}

		if($this->site_list_id == null){
			$ssm_site = ClassRegistry::init('SsmSite');
			$sites = $ssm_site->find('all',array(
				'conditions'=>array(
					'site_manage_user'=>$user['SsmUser']['id']
				)
			));

			if(!$sites){
				$this->site_list_id = array();
				return false;
			}else{
				foreach ($sites as $site) {
					$this->site_list_id[] = $site['SsmSite']['id'];
				}
			}
		}

		if(in_array($site_id,$this->site_list_id)){
			return $user['SsmUser']['role'];
		}else{
			return false;
		}
	}

    /**
     * Set active site session
     * @param [type] $site_id [description]
     */
	public function setActiveSite($site_id){
		$this->Session->write('active_site_id',$site_id);
		return $site_id;
	}

    /**
     * Set active site session
     * @return [type] [description]
     */
	public function getActiveSite(){
		if(!$this->Session->check('active_site_id')){
			return false;
		} else {
			return $this->Session->read('active_site_id');
		}
	}

	/**
     * Hash password
     * @param  [type] $string [description]
     * @param  string $action [description]
     * @return [type]         [description]
     */
	public function ssmHash( $string, $action = 'e' ) {

	    $secret_key = 'DF32rdfsfs2guVoUsdf3fFFDSDFsdfds';
	    $secret_iv  = '23423434234322924319834283894343';

	    $output = false;
	    $encrypt_method = "AES-256-CBC";
	    $key = hash( 'sha256', $secret_key );
	    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

	    if( $action == 'e' ) {
	        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	    }
	    else if( $action == 'd' ){
	        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
	    }
	    return $output;
	}

    /**
     * Make random password
     * @return [type] [description]
     */
	public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Check site have any contract active
     * @param  [type] $site_id [description]
     * @return [type]          [description]
     */
    public function checkContractSite($site_id){
    	$ssm_contract = ClassRegistry::init('SsmContract');
    	$ct = $ssm_contract->find('all',
    		array(
    			'conditions'=>array(
    				'site_id'		=>$site_id,
    				'start_day <='	=>date('Y-m-d'),
                    'end_day >='	=>date('Y-m-d')
    			)
    		)
    	);

    	if($ct){
    		return true;
    	}else{
    		return false;
    	}
    }
}