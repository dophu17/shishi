<?php

class SsmKpiGaWeekReport extends AppModel {
	function getGAWeekData($GA_OBJ,$site_id,$start_date,$end_date){
		$start_date = trim($start_date);
		$end_date = trim($end_date);
        //Get GA in DB
        $data = $this->find('first',
            array(
                'conditions'    =>  array(
                    'start_day' =>$start_date,
                    'end_day'   =>$end_date,
                    'site_id'   =>$site_id
                )
            )
        );

        if(empty($data)){

           $data_ga = $GA_OBJ->getReport('kpis',$start_date,$end_date);
            $ga_insert_data = $data_ga;
            $ga_insert_data['start_day']    = $start_date;
            $ga_insert_data['end_day']    	= $end_date;
            $ga_insert_data['site_id']  	= $site_id;
            $this->clear();
            $this->set($ga_insert_data);
            $this->save();
            return $data_ga;
        }else{
            //have data in db
            return $data['SsmKpiGaWeekReport'];
        }
    }
}

?>