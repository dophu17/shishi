<?php
App::uses('AppModel', 'Model');
	class OttReport extends AppModel {

		function validate_new_slide(){

			$this->validate = array(
		        'title' => array(
		            'notBlank' => array(	
		            	'rule' => 'notEmpty',                
		                'required' => true,
		                'message' => 'タイトルの入力は必須です。'
		            ),
		          
	        	),
		        // 'description' => array(
		        //     'notBlank' => array(
		        //     	'rule' => 'notEmpty',
		        //         'required' => true,
		        //         'message' => 'Description is not empty!'
		        //     ),	          
	        	// ),
	        	// 'data' => array(
		        //     'notBlank' => array(
		        //     	'rule' => 'notEmpty',
		        //         'required' => true,
		        //         'message' => 'Image is not empty!'
		        //     ),	          
	        	// ),
		       
	    	);

	    	if($this->validates($this->validate)){
	            return TRUE;
	    	}else{
	            return FALSE;
	        }
		}
	}
	
?>