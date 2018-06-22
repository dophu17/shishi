<?php
App::uses('SsmModel', 'Model');
class SsmKpiGaMonth extends SsmModel {

    public $primaryKey = 'ssm_kpi_ga_month_id';

    function getGAMonthData($GA_OBJ,$site_id,$year,$month){
        $month = intval($month);
        $month_str = ($month > 9) ? $month : "0".$month;
        $start  = $year."-".$month_str."-01";
        $date   = date($year."-".$month_str."-01");
        $end    = date('Y-m-t', strtotime($date));
        $ex = explode('-',$end);
        $total_day_in_month = $ex[2];
        if($total_day_in_month > 28){
            $count_week = 5;
        }else{
            $count_week = 4;
        }

        $data_ga_default = $this->default_zero;

        //if month is the past get in db
        $cr_day     = intval(date('d'));
        $cr_month   = intval(date('m'));

        if($year > date('Y')){
            $data_ga  = $this->default_zero;
            $ga_start_day = null;
            $ga_end_day   = null;
            $ga_day       = null;
        }elseif($year == date('Y')){
            if($month < $cr_month){

                //Get data in DB
                $dataSsmkpiGaMonth = $this->find('first',
                    array(
                        'conditions'    =>  array(
                            'year'      =>intval($year),
                            'month'     =>intval($month_str),
                            'site_id'   =>$site_id
                        )
                    )
                );

                if(empty($dataSsmkpiGaMonth)){
                    $data_ga = $GA_OBJ->getReport('kpis',$start,$end);

                    $ga_insert_data = $data_ga;
                    $ga_insert_data['year']     = $year;
                    $ga_insert_data['month']    = intval($month);
                    $ga_insert_data['site_id']  = $site_id;
                    $this->clear();
                    $this->set($ga_insert_data);
                    $this->save();

                }else{
                    $data_ga = $dataSsmkpiGaMonth['SsmKpiGaMonth'];
                }

                $ga_start_day = $start;
                $ga_end_day  = $end;
                $ga_day      = $total_day_in_month;

            }elseif($month == $cr_month){
                //Get data in GA
                if($cr_day != 1){
                    $prev_day = $cr_day - 1;
                    $end = $year."-".$month_str."-".(($prev_day > 9) ? $prev_day : "0".$prev_day);
                    $data_ga  = $GA_OBJ->getReport('kpis',$start,$end);
                    $ga_start_day = $start;
                    $ga_end_day  = $end;
                    $ga_day      = $prev_day;
                }else{
                    $data_ga  = $this->default_zero;
                    $ga_start_day = null;
                    $ga_end_day  = null;
                    $ga_day      = null;
                }

            }else{
                //return default data
                $data_ga = $this->default_zero;
                $ga_start_day = null;
                $ga_end_day  = null;
                $ga_day      = null;
            }


        }else{
            //Get data in DB
            $dataSsmkpiGaMonth = $this->find('first',
                array(
                    'conditions'    =>  array(
                        'year'      =>intval($year),
                        'month'     =>intval($month_str),
                        'site_id'   =>$site_id
                    )
                )
            );

            if(empty($dataSsmkpiGaMonth)){
                $data_ga = $GA_OBJ->getReport('kpis',$start,$end);
                $ga_insert_data = $data_ga;
                $ga_insert_data['year']     = $year;
                $ga_insert_data['month']    = intval($month);
                $ga_insert_data['site_id']  = $site_id;
                $this->clear();
                $this->set($ga_insert_data);
                $this->save();

            }else{
                $data_ga = $dataSsmkpiGaMonth['SsmKpiGaMonth'];
            }

            $ga_start_day = $start;
            $ga_end_day  = $end;
            $ga_day      = $total_day_in_month;
        }

        return array(
            'kpis'=>$data_ga,
            'ga_start_day'  => $ga_start_day,
            'ga_end_day'    => $ga_end_day,
            'ga_day'        => $ga_day,
            'total_day_in_month'  => $total_day_in_month
        );
    }

    function getGABeginMonthToNow($GA_OBJ){
        $start = date('Y-m-01');
        $prev_day = (intval(date('d')) - 1);
        $end = date('Y-m-').(($prev_day > 9)? $prev_day : '0'.$prev_day);
        $data_ga = $GA_OBJ->getReport('kpis',$start,$end);

        return $data_ga;
    }
}