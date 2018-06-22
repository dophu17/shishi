<?php
App::uses('AppModel', 'Model');

class SsmPlan extends AppModel {

	var $component = array('SsmAuth');
	public $validate = array(
	    'name' => array(
	        'isUnique' => array(
	            'rule' => 'isUnique',
	            'message' => 'プランが重複しています。',
	            'last' => true,
	        ),
	        'notEmpty' => array(
	            'rule' => 'notEmpty',
	            'message' => '(*) 必須!',
	        )
	    ),
	    'price' => array(
	        'notEmpty' => array(
	            'rule' => 'notEmpty',
	            'message' => '(*) 必須!',
	        )
	    )
	);

	public function beforeSave($options = array()) {
        return parent::beforeSave($options);
    }
}
?>