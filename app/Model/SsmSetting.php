<?php
App::uses('SsmModel', 'Model');

class SsmSetting extends SsmModel {

	public $validate = array(
	    'value' => array(
	        'notEmpty' => array(
	            'rule' => 'notEmpty',
	            'message' => '(*) 必須!',
	        )
	    )
	);

	/**
	 * Get value of key setting
	 * @param  [string] $key [key setting]
	 * @return [string]      [value]
	 */
	function getValueKey($key){
		$data = $this->find('first',array(
			'conditions'=>array(
				'key'=>$key
			)
		));

		if($data){
			return $data['SsmSetting']['value'];
		}else{
			return false;
		}
	}
}
