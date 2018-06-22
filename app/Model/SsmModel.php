<?php

App::uses('AppModel', 'Model');

class SsmModel extends AppModel {
	public $_month_info = array();

	public $default_zero = array(
        'sessions'              => 0,
        'avgSessionDuration'    => 0,
        'pageviews'             => 0,
        'transactionRevenue'    => 0,
        'revenuePerTransaction' => 0,
        'revenuePerTransaction_1' => 0,
        'bounceRate'            => 0,
        'transactions'          => 0,
        'transactions_1'          => 0,
        'transactionsPerSession'=> 0,
        'transactionsPerSession_1'=> 0,
        'uniqueUsers'           => 0,
        'pageviewsPerSession'   => 0,
        'topBounceRate'         => 0,
        'percentNewSessions'    => 0
    );


    //Get month info
	function getMonthInfo($year,$month){
        if(strlen($month) == 1){
            $month = "0".$month;
        }
        $begin_date  = date($year.'-'.$month.'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));
        $end_date_ex = explode('-',$end_date);
        $end_day_number = $end_date_ex[2];

        if(intval($end_day_number)>28){
            $week_count = 5;
        }else{
            $week_count = 4;
        }

        return array(
            'year'              =>$year,
            'month'             =>$month,
            'week_count'        =>$week_count,
            'begin_date'        =>$begin_date,
            'end_date'          =>$end_date,
            'last_day_number'   =>$end_day_number
        );
    }

    //Get week info
    function getWeekInfo($year,$month,$week){
        $month = intval($month);
        $month_str = ($month > 9) ? $month : "0".$month;
        if($week == 'last'){
            $date = date($year."-".$month_str."-01");
            $date = date('Y-m-t', strtotime($date));
            $ex = explode('-',$date);
            if($ex[2] > 28){
                $week       = 5;
                $start      = $year."-".$month_str."-29";
                $begin_day  = 29;
                $end        = $date;
                $end_day    = $ex[2];
                $total_day_in_week = ($ex[2] +1) - 29;
            }else{
                $week       = 4;
                $start      = $year."-".$month_str."-22";
                $begin_day  = 22;
                $end        = $date;
                $end_day    = $ex[2];
                $total_day_in_week = ($ex[2] +1) - 15;
            }
        }else{
            if($week == 1){
                $start      = $year."-".$month_str."-01";
                $begin_day  = 1;
                $end        = $year."-".$month_str."-07";
                $end_day    = 7;
                $total_day_in_week = 7;
            }elseif($week == 2){
                $start      = $year."-".$month_str."-08";
                $begin_day  = 8;
                $end        = $year."-".$month_str."-14";
                $end_day    = 14;
                $total_day_in_week = 7;
            }elseif($week == 3){
                $start      = $year."-".$month_str."-15";
                $begin_day  = 15;
                $end        = $year."-".$month_str."-21";
                $end_day    = 21;
                $total_day_in_week = 7;
            }elseif($week == 4){
                $start      = $year."-".$month_str."-22";
                $begin_day  = 22;
                $end        = $year."-".$month_str."-28";
                $end_day    = 28;
                $total_day_in_week = 7;
            }elseif($week == 5){
                $date = date($year."-".$month_str."-01");
                $date = date('Y-m-t', strtotime($date));
                $ex = explode('-',$date);
                if($ex[2] > 28){
                    $week       = 5;
                    $begin_day  = 29;
                    $start      = $year."-".$month_str."-29";
                    $end        = $date;
                    $end_day    = $ex[2];
                    $total_day_in_week = ($ex[2] +1) - 29;
                }else{

                }
            }
        }

        return array(
            'year'              =>$year,
            'month'             =>$month,
            'week'              =>$week,
            'begin_day'         =>$begin_day,
            'begin_day_str'     =>$start,
            'end_day'           =>$end_day,
            'end_day_str'       =>$end,
            'total_day_in_week' =>$total_day_in_week
        );
    }


    //Check kpi_key input in array field allow change data
    function inArrKpi($check) {
        if(!in_array($check['kpi_key'],
            array(
            'transactionRevenue','pageviews','pageviewsPerSession','sessions','avgSessionDuration','uniqueUsers','transactions','transactionsPerSession','revenuePerTransaction','transactions_1','transactionsPerSession_1','revenuePerTransaction_1','bounceRate','topBounceRate','percentNewSessions'
            ))){
            return false;
        }else{
            return true;
        }
    }
}
