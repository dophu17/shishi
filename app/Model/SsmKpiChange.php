<?php
App::uses('SsmModel', 'Model');
class SsmKpiChange extends SsmModel {

	function getDataChangeMonth($site_id,$year,$month){
        $month = intval($month);
		$dataSsmKpiChange = $this->find('all',
            array(
                'conditions'    =>  array(
                    'year'      =>intval($year),
                    'month'     =>$month,
                    'site_id'   =>$site_id
                )
            )
        );

        $change_data = array();
        $change_data_in_month = $this->default_zero;

        $kpi_change = array(
            'percentNewSessions'=>0,
            'topBounceRate'     =>0,
            'bounceRate'        =>0
        );

        $change_data_in_week = array();
        $change_data_in_day  = array();
        foreach ($dataSsmKpiChange as $change_data_record) {
            $change_data_record = $change_data_record['SsmKpiChange'];
            $day    = $change_data_record['day'];
            $week   = $change_data_record['week'];

            //Week
            if(isset($change_data_in_week[$week])){
                $change_data_in_week[$week] = array(
                    'transactionRevenue'    =>$change_data_in_week[$week]['transactionRevenue'] + $change_data_record['transactionRevenue'],
                    'pageviews'             =>$change_data_in_week[$week]['pageviews'] + $change_data_record['pageviews'],
                    'pageviewsPerSession'   =>$change_data_in_week[$week]['pageviewsPerSession'] + $change_data_record['pageviewsPerSession'],
                    'sessions'              =>$change_data_in_week[$week]['sessions'] + $change_data_record['sessions'],
                    'avgSessionDuration'    =>$change_data_in_week[$week]['avgSessionDuration'] + $change_data_record['avgSessionDuration'],
                    'uniqueUsers'           =>$change_data_in_week[$week]['uniqueUsers'] + $change_data_record['uniqueUsers'],
                    'transactions'          =>$change_data_in_week[$week]['transactions'] + $change_data_record['transactions'],
                    'transactionsPerSession'=>$change_data_in_week[$week]['transactionsPerSession'] + $change_data_record['transactionsPerSession'],
                    'revenuePerTransaction' =>$change_data_in_week[$week]['revenuePerTransaction'] + $change_data_record['revenuePerTransaction'],

                    'transactions_1'          =>$change_data_in_week[$week]['transactions_1'] + $change_data_record['transactions_1'],
                    'transactionsPerSession_1'=>$change_data_in_week[$week]['transactionsPerSession_1'] + $change_data_record['transactionsPerSession_1'],
                    'revenuePerTransaction_1' =>$change_data_in_week[$week]['revenuePerTransaction_1'] + $change_data_record['revenuePerTransaction_1'],

                    'bounceRate'            =>$change_data_in_week[$week]['bounceRate'] + $change_data_record['bounceRate'],
                    'topBounceRate'         =>$change_data_in_week[$week]['topBounceRate'] + $change_data_record['topBounceRate'],
                    'percentNewSessions'    =>$change_data_in_week[$week]['percentNewSessions'] + $change_data_record['percentNewSessions']
                );
            }else{
                $change_data_in_week[$week] = array(
                    'transactionRevenue'    =>$change_data_record['transactionRevenue'],
                    'pageviews'             =>$change_data_record['pageviews'],
                    'pageviewsPerSession'   =>$change_data_record['pageviewsPerSession'],
                    'sessions'              =>$change_data_record['sessions'],
                    'avgSessionDuration'    =>$change_data_record['avgSessionDuration'],
                    'uniqueUsers'           =>$change_data_record['uniqueUsers'],
                    'transactions'          =>$change_data_record['transactions'],
                    'transactionsPerSession'=>$change_data_record['transactionsPerSession'],
                    'revenuePerTransaction' =>$change_data_record['revenuePerTransaction'],

                    'transactions_1'          =>$change_data_record['transactions_1'],
                    'transactionsPerSession_1'=>$change_data_record['transactionsPerSession_1'],
                    'revenuePerTransaction_1' =>$change_data_record['revenuePerTransaction_1'],

                    'bounceRate'            =>$change_data_record['bounceRate'],
                    'topBounceRate'         =>$change_data_record['topBounceRate'],
                    'percentNewSessions'    =>$change_data_record['percentNewSessions'],
                );
            }

            //Day
            if(isset($change_data_in_day[$day])){
                $change_data_in_day[$day] = array(
                    'transactionRevenue'    =>$change_data_in_day[$day]['transactionRevenue'] + $change_data_record['transactionRevenue'],
                    'pageviews'             =>$change_data_in_day[$day]['pageviews'] + $change_data_record['pageviews'],
                    'pageviewsPerSession'   =>$change_data_in_day[$day]['pageviewsPerSession'] + $change_data_record['pageviewsPerSession'],
                    'sessions'              =>$change_data_in_day[$day]['sessions'] + $change_data_record['sessions'],
                    'avgSessionDuration'    =>$change_data_in_day[$day]['avgSessionDuration'] + $change_data_record['avgSessionDuration'],
                    'uniqueUsers'           =>$change_data_in_day[$day]['uniqueUsers'] + $change_data_record['uniqueUsers'],
                    'transactions'          =>$change_data_in_day[$day]['transactions'] + $change_data_record['transactions'],
                    'transactionsPerSession'=>$change_data_in_day[$day]['transactionsPerSession'] + $change_data_record['transactionsPerSession'],
                    'revenuePerTransaction' =>$change_data_in_day[$day]['revenuePerTransaction'] + $change_data_record['revenuePerTransaction'],

                    'transactions_1'          =>$change_data_in_day[$day]['transactions_1'] + $change_data_record['transactions_1'],
                    'transactionsPerSession_1'=>$change_data_in_day[$day]['transactionsPerSession_1'] + $change_data_record['transactionsPerSession_1'],
                    'revenuePerTransaction_1' =>$change_data_in_day[$day]['revenuePerTransaction_1'] + $change_data_record['revenuePerTransaction_1'],

                    'bounceRate'            =>$change_data_in_day[$day]['bounceRate'] + $change_data_record['bounceRate'],
                    'topBounceRate'         =>$change_data_in_day[$day]['topBounceRate'] + $change_data_record['topBounceRate'],
                    'percentNewSessions'    =>$change_data_in_day[$day]['percentNewSessions'] + $change_data_record['percentNewSessions'],
                );
            }else{
                $change_data_in_day[$day] = array(
                    'transactionRevenue'    =>$change_data_record['transactionRevenue'],
                    'pageviews'             =>$change_data_record['pageviews'],
                    'pageviewsPerSession'   =>$change_data_record['pageviewsPerSession'],
                    'sessions'              =>$change_data_record['sessions'],
                    'avgSessionDuration'    =>$change_data_record['avgSessionDuration'],
                    'uniqueUsers'           =>$change_data_record['uniqueUsers'],

                    'transactions'          =>$change_data_record['transactions'],
                    'transactionsPerSession'=>$change_data_record['transactionsPerSession'],
                    'revenuePerTransaction' =>$change_data_record['revenuePerTransaction'],

                    'transactions_1'          =>$change_data_record['transactions_1'],
                    'transactionsPerSession_1'=>$change_data_record['transactionsPerSession_1'],
                    'revenuePerTransaction_1' =>$change_data_record['revenuePerTransaction_1'],

                    'bounceRate'            =>$change_data_record['bounceRate'],
                    'topBounceRate'         =>$change_data_record['topBounceRate'],
                    'percentNewSessions'    =>$change_data_record['percentNewSessions'],
                );
            }

            $change_data_in_month = array(
                    'transactionRevenue'    =>$change_data_in_month['transactionRevenue'] + $change_data_record['transactionRevenue'],
                    'pageviews'             =>$change_data_in_month['pageviews'] + $change_data_record['pageviews'],
                    'pageviewsPerSession'   =>$change_data_in_month['pageviewsPerSession'] + $change_data_record['pageviewsPerSession'],
                    'sessions'              =>$change_data_in_month['sessions'] + $change_data_record['sessions'],
                    'avgSessionDuration'    =>$change_data_in_month['avgSessionDuration'] + $change_data_record['avgSessionDuration'],
                    'uniqueUsers'           =>$change_data_in_month['uniqueUsers'] + $change_data_record['uniqueUsers'],

                    'transactions'          =>$change_data_in_month['transactions'] + $change_data_record['transactions'],
                    'transactionsPerSession'=>$change_data_in_month['transactionsPerSession'] + $change_data_record['transactionsPerSession'],
                    'revenuePerTransaction' =>$change_data_in_month['revenuePerTransaction'] + $change_data_record['revenuePerTransaction'],

                    'transactions_1'          =>$change_data_in_month['transactions_1'] + $change_data_record['transactions_1'],
                    'transactionsPerSession_1'=>$change_data_in_month['transactionsPerSession_1'] + $change_data_record['transactionsPerSession_1'],
                    'revenuePerTransaction_1' =>$change_data_in_month['revenuePerTransaction_1'] + $change_data_record['revenuePerTransaction_1'],

                    'bounceRate'            =>$change_data_in_month['bounceRate'] + $change_data_record['bounceRate'],
                    'topBounceRate'         =>$change_data_in_month['topBounceRate'] + $change_data_record['topBounceRate'],
                    'percentNewSessions'    =>$change_data_in_month['percentNewSessions'] + $change_data_record['percentNewSessions'],
            );

            /*foreach ($change_data_in_week as $w=>$w_change) {
                if($w_change['percentNewSessions'] != 0){
                    $kpi_change['percentNewSessions']++;
                }

                if($w_change['topBounceRate'] != 0){
                    $kpi_change['topBounceRate']++;
                }

                if($w_change['bounceRate'] != 0){
                    $kpi_change['bounceRate']++;
                }
            }

            if($change_data_in_month['percentNewSessions'] != 0 && $w_change['percentNewSessions'] != 0){
                $change_data_in_month['percentNewSessions'] = $change_data_in_month['percentNewSessions']/$w_change['percentNewSessions'];
            }

            if($change_data_in_month['topBounceRate'] != 0 && $w_change['topBounceRate'] != 0){
                $change_data_in_month['topBounceRate'] = $change_data_in_month['topBounceRate']/$w_change['topBounceRate'];
            }

            if($change_data_in_month['bounceRate'] != 0 && $w_change['bounceRate'] != 0){
                $change_data_in_month['bounceRate'] = $change_data_in_month['bounceRate']/$w_change['bounceRate'];
            }*/
        }

        return array(
        	'change_data_in_day'	=>$change_data_in_day,
        	'change_data_in_week'	=>$change_data_in_week,
        	'change_data_in_month'	=>$change_data_in_month
        );
	}

	function getDataWeekChange($site_id,$year,$month,$week){
        $month = intval($month);
		$month_str = ($month > 9) ? $month : "0".$month;
        if($week == 'last'){
            $date = date($year."-".$month_str."-t");
            $ex = explode('-',$date);
            if($ex[2] > 28){
                $week = 5;
                $start = $year."-".$month_str."-29";
                $end   = $date;
            }else{
                $week = 4;
                $start = $year."-".$month_str."-23";
                $end   = $year."-".$month_str."-28";
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
                $end   = $year."-".$month_str."-22";
            }elseif($week == 4){
                $start = $year."-".$month_str."-23";
                $end   = $year."-".$month_str."-28";
            }
        }

		$dataSsmKpiChange = $this->find('all',
            array(
                'conditions'    =>  array(
                    'year'      =>intval($year),
                    'month'     =>intval($month),
                    'week'     	=>intval($week),
                    'site_id'   =>$site_id
                )
            )
        );

        $change_data_in_week = $this->default_zero;
        if(!empty($dataSsmKpiChange)){
        	foreach ($dataSsmKpiChange as $change_data_record) {
	            $change_data_in_week = array(
                    'transactionRevenue'    =>$change_data_in_week['transactionRevenue'] 	+ $change_data_record['SsmKpiChange']['transactionRevenue'],
                    'pageviews'             =>$change_data_in_week['pageviews'] 			+ $change_data_record['SsmKpiChange']['pageviews'],
                    'pageviewsPerSession'   =>$change_data_in_week['pageviewsPerSession'] 	+ $change_data_record['SsmKpiChange']['pageviewsPerSession'],
                    'sessions'              =>$change_data_in_week['sessions'] 				+ $change_data_record['SsmKpiChange']['sessions'],
                    'avgSessionDuration'    =>$change_data_in_week['avgSessionDuration'] 	+ $change_data_record['SsmKpiChange']['avgSessionDuration'],
                    'uniqueUsers'           =>$change_data_in_week['uniqueUsers'] 			+ $change_data_record['SsmKpiChange']['uniqueUsers'],
                    'transactions'          =>$change_data_in_week['transactions'] 			+ $change_data_record['SsmKpiChange']['transactions'],
                    'transactionsPerSession'=>$change_data_in_week['transactionsPerSession'] + $change_data_record['SsmKpiChange']['transactionsPerSession'],
                    'revenuePerTransaction' =>$change_data_in_week['revenuePerTransaction']	+ $change_data_record['SsmKpiChange']['revenuePerTransaction'],

                    'transactions_1'          =>$change_data_in_week['transactions_1']          + $change_data_record['SsmKpiChange']['transactions_1'],
                    'transactionsPerSession_1'=>$change_data_in_week['transactionsPerSession_1'] + $change_data_record['SsmKpiChange']['transactionsPerSession_1'],
                    'revenuePerTransaction_1' =>$change_data_in_week['revenuePerTransaction_1'] + $change_data_record['SsmKpiChange']['revenuePerTransaction_1'],

                    'bounceRate'            =>$change_data_in_week['bounceRate'] 			+ $change_data_record['SsmKpiChange']['bounceRate'],
                    'topBounceRate'         =>$change_data_in_week['topBounceRate'] 		+ $change_data_record['SsmKpiChange']['topBounceRate'],
                    'percentNewSessions'    =>$change_data_in_week['percentNewSessions']         + $change_data_record['SsmKpiChange']['percentNewSessions'],
                );
	        }
        }

        return $change_data_in_week;
	}


}