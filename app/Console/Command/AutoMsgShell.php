<?php
App::uses('Shell', 'Console');
App::uses('CakeEmail', 'Network/Email');
class AutoMsgShell extends Shell {
    public $uses = array('SsmSite','SsmSetting');
    public function main() {

    	//Key CW using for send
    	//$CW_API_USING = '87c3cfc554ef13f1ba98a0a80802ec43';

    	//For test
    	$CW_API_USING = '9db2259d6a1253dd2723cd7d3eec3957';

    	$day_of_week  = date('w',strtotime(date('Y-m-d')));
    	$hour 	= intval(date('H'));
    	$minute = intval(date('i'));

    	//FAKE system time here for test
    	//$hour 	= 18;
    	//$minute = 30;

    	$this->out('Run cron at :'.date('Y-m-d')." $hour:$minute ");

    	if($hour == 9 && $minute == 30){
    		//MSG 9h30
    		$this->SsmSetting->getValueKey('msg_send_cw_morning');
			//Load from DB
			$msg = $this->SsmSetting->getValueKey('msg_send_cw_morning');

		}elseif($hour == 18 && $minute == 30){
			//MSG 18h30
			//Load from DB
			$msg = $this->SsmSetting->getValueKey('msg_send_cw_afternoon');
		}

    	//Get all group of CW_API_USING
		$opt = array(
		CURLOPT_URL => "https://api.chatwork.com/v2/rooms",
		CURLOPT_HTTPHEADER => array( 'X-ChatWorkToken: ' . $CW_API_USING ),
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_SSL_VERIFYPEER => FALSE,
		CURLOPT_POST => FALSE,
		);

		$ch = curl_init();
		curl_setopt_array( $ch, $opt );
		$res = curl_exec( $ch );
		curl_close( $ch );

		$allowGroupIds = array();
		$data = json_decode($res);
		if(!empty($data)){
			foreach ($data as $group) {
				if(isset($group->room_id))
				$allowGroupIds[] = $group->room_id;
			}
		}

    	if(
    		$day_of_week != 0 && 
    		$day_of_week != 6 && 
    		!empty($allowGroupIds)
    		&& (
    			//Time send
    			($hour == 9 && $minute == 30) ||
    			($hour == 18 && $minute == 30)
    		)
    	){

    		$string_group_id = implode(',',$allowGroupIds);
    		$sql 	= 'SELECT * FROM ssm_sites WHERE chatwork_api != "" AND auto_send_cw = "1" AND chatwork_id IN ('.$string_group_id.')';

	        $sites 	= $this->SsmSite->query($sql);

	        if($sites){
	        	$group 	= array();
	        	foreach ($sites as $site) {
		        	$group[$site['ssm_sites']['chatwork_id']] = $site['ssm_sites']['chatwork_api'];
		        }

		        foreach ($group as $group_id=>$token) {
		        	$data = array(
		              'body'=>$msg
		            );

		            if(!in_array($token,array('7937f31f6802c869f79e2ba4fb0abf1c'))){
			            try {
			            	$opt = array(
				              CURLOPT_URL => "https://api.chatwork.com/v2/rooms/{$group_id}/messages",
				              CURLOPT_HTTPHEADER => array( 'X-ChatWorkToken: ' . $CW_API_USING ),
				              CURLOPT_RETURNTRANSFER => TRUE,
				              CURLOPT_SSL_VERIFYPEER => FALSE,
				              CURLOPT_POST => TRUE,
				              CURLOPT_POSTFIELDS => http_build_query( $data, '', '&' )
				            );

				            $ch = curl_init();
				            curl_setopt_array( $ch, $opt );
				            $res = curl_exec( $ch );
				            curl_close( $ch );
			            } catch (Exception $e) {

			            }
		        	}
		        }
	        }
    	}
    	$this->out('Task Complete');
    }
}