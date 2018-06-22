<?php
App::uses('SsmModel', 'Model');
class SsmKpiGaWeek extends SsmModel {

    public $primaryKey = 'ssm_kpi_ga_week_id';

	function getExitsData($year,$month,$count_week,$site_id){
        $current_year   = date('Y');
        $current_month  = intval(date('m'));
        $get_data = 0;
        if($year < $current_year){
            //get ga data from DB
            $get_data = 1;
        }elseif($year == $current_year){
            if($month <= $current_month){
                //get GA from DB
                $get_data = 1;
            }
        }

        if($get_data){
            $dataSsmkpiGa = $this->find('all',
                array(
                    'conditions'    =>  array(
                        'year'      =>intval($year),
                        'month'     =>intval($month),
                        'site_id'   =>$site_id
                    )
                )
            );

            $return_data = array();

            if(!empty($dataSsmkpiGa)){
                foreach ($dataSsmkpiGa as $ga_week) {
                    $return_data[$ga_week['SsmKpiGaWeek']['week']] = $ga_week['SsmKpiGaWeek'];
                }
            }
            return $return_data;
        }else{
            //set default data for n week in year,month given
            $return_data = array();
            for ($i = 1; $i <= $count_week ; $i++) {
                $return_data[$i] = $this->default_zero;
            }
            return $return_data;
        }
	}


    function getGAWeekData($GA_OBJ,$site_id,$year,$month,$week){
        $month = intval($month);
        $month_str = ($month > 9) ? $month : "0".$month;
        if($week == 'last'){
            $date = date($year."-".$month_str."-01");
            $date = date('Y-m-t', strtotime($date));
            $ex = explode('-',$date);
            if($ex[2] > 28){
                $week = 5;
                $start = $year."-".$month_str."-29";
                $end   = $date;
            }else{
                $week = 4;
                $start = $year."-".$month_str."-22";
                $end   = $date;
            }
        }else{
            if($week == 1){
                $start = $year."-".$month_str."-01";
                $end   = $year."-".$month_str."-07";
            }elseif($week == 2){
                $start = $year."-".$month_str."-08";
                $end   = $year."-".$month_str."-14";
            }elseif($week == 3){
                $start = $year."-".$month_str."-15";
                $end   = $year."-".$month_str."-21";
            }elseif($week == 4){
                $start = $year."-".$month_str."-22";
                $end   = $year."-".$month_str."-28";
            }elseif($week == 5){
                $date = date($year."-".$month_str."-01");
                $date = date('Y-m-t', strtotime($date));
                $ex = explode('-',$date);
                if($ex[2] > 28){
                    $week = 5;
                    $start = $year."-".$month_str."-29";
                    $end   = $date;
                }else{
                    return $this->default_zero;
                }
            }
        }

        //Get GA in DB
        $dataSsmkpiGa = $this->find('first',
            array(
                'conditions'    =>  array(
                    'year'      =>intval($year),
                    'month'     =>intval($month_str),
                    'week'      =>intval($week),
                    'site_id'   =>$site_id
                )
            )
        );

        if(empty($dataSsmkpiGa)){

            $int_date_Ymd   = intval(date('Ymd'));
            $int_date_start = intval(str_replace('-','',$start));
            $int_date_end   = intval(str_replace('-','',$end));

            //Lấy dữ liệu GA
            if($int_date_start > $int_date_Ymd){
                //no data ga
                return $this->default_zero;
            }elseif($int_date_start == $int_date_Ymd){
                //no data ga
                return $this->default_zero;
            }else{ ////int_date_start < int_date_Ymd
                if($int_date_end < $int_date_Ymd){

                    if($GA_OBJ->view_id != ""){
                        $data_ga = $GA_OBJ->getReport('kpis',$start,$end);
                    }else{
                        $data_ga = $this->default_zero;
                    }

                    if(intval(str_replace('-','',$end)) < intval(date('Ymd'))){
                        $ga_insert_data = $data_ga;
                        $ga_insert_data['year']     = $year;
                        $ga_insert_data['month']    = intval($month);
                        $ga_insert_data['week']     = $week;
                        $ga_insert_data['site_id']  = $site_id;
                        $this->clear();
                        $this->set($ga_insert_data);
                        $this->save();
                    }
                    return $data_ga;
                }else{
                    //get dât to prev day
                    $date_number = intval((date('d') - 1));
                    $date_number = $date_number > 9 ? $date_number : '0'.$date_number;
                    $end = $year."-".$month_str."-".$date_number;
                    if($GA_OBJ->view_id != ""){
                        $data_ga = $GA_OBJ->getReport('kpis',$start,$end);
                    }else{
                        $data_ga = $this->default_zero;
                    }
                    return $data_ga;
                }
            }

        }else{
            //have data in db
            return $dataSsmkpiGa['SsmKpiGaWeek'];
        }
    }



}