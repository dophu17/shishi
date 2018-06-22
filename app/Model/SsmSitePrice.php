<?php
App::uses('AppModel', 'Model');

class SsmSitePrice extends AppModel {
	var $component = array('SsmAuth');


	function getHourCalculate($price, $price_per_hour, $man_hours_rate){
		if(empty($price) || empty($price_per_hour)){
			return 0;
		}else{
			return ( intval($price) * $man_hours_rate ) / intval($price_per_hour);
		}		
	}

	function updateSitePriceNow($site_id,$price,$price_per_hour){
		$y = intval(date('Y'));
		$m = intval(date('m'));

		$site_price = $this->find('first',array(
			'conditions'=>array(
				'site_id'	=> $site_id,
				'year'		=> $y,
				'month'		=> $m
			)
		));

		$ok = false;

		$price = $price ? intval($price) : 0;
		$price_per_hour = $price_per_hour ? intval($price_per_hour) : 0;

		if($site_price){
			//Update
			$ok = $this->updateAll(
	            [
	            	'price' 			=> $price,
	            	'price_per_hour' 	=> $price_per_hour
	        	],
	            [
	            	'year' 		=> $y,
	            	'month'		=> $m,
	            	'site_id'	=> $site_id
	            ]
	        );
		}else{
			//Create
			$data = [
	            'site_id'     		=> $site_id,
	            'year'          	=> $y,
	            'month'         	=> $m,
	            'price'       		=> $price,
	            'price_per_hour'  	=> $price_per_hour
	        ];
	        $this->create();
	        $ok = $this->save($data);
		}

		return $ok;
	}
}
?>