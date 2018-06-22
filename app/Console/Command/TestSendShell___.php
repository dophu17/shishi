<?php
App::uses('Shell', 'Console');
App::uses('CakeEmail', 'Network/Email');
class TestSendShell extends Shell {
    public $uses = array('SsmSite');
    public function main() {

    	$day  = date('w',strtotime(date('Y-m-d')));
    	$this->out(date('Y-m-d H:i:s'));
    	$this->out('Today is:'.$day);
    	if($day != 0 && $day != 6 && !empty($allowGroupIds) && intval(date('H')) == 18 && intval(date('i')) == 30){

    	}
    	$this->out('Task Complete');
    }
}