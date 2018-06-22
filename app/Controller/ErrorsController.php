<?php 
class ErrorsController extends AppController {
    public $name = 'Errors';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('error404');
    }

    public function error404() {
    	if(AuthComponent::$sessionKey = 'Auth.Client') {
        	$this->layout = 'client_mypage';
    	}
    }

    public function missing_action() {
    	$this->layout = 'client';
    }
}