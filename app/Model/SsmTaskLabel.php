<?php
App::uses('AppModel', 'Model');

class SsmTaskLabel extends AppModel {

	var $component = array('SsmAuth');
	public $validate = array(
	    'name' => array(
	        'isUnique' => array(
	            'rule' => 'isUnique',
	            'message' => 'タスクジャンルが重複しています。',
	            'last' => true,
	        ),
	        'notEmpty' => array(
	            'rule' => 'notEmpty',
	            'message' => '(*) 必須!',
	        )
	    )
	);

	public function beforeSave($options = array()) {
        return parent::beforeSave($options);
    }

    public function getArrTaskLabel($addOtherOption = true){
    	$db_task_label = $this->find('all');
        $label_option       = array();
        if($addOtherOption){
        	$label_option[0] = ' その他 ';
        }        
        if($db_task_label){
            foreach($db_task_label as $label){
                $label_option[$label['SsmTaskLabel']['id']] = $label['SsmTaskLabel']['name'];
            }
        }

        return $label_option;
    }
}
?>