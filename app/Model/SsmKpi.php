<?php
App::uses('SsmModel', 'Model');

class SsmKpi extends SsmModel {


	function getDataTargetMonth($year,$month,$site_id){
        $int_year   = intval($year);
        $int_month  = intval($month);
		$dataSsmKpi = $this->find('all',
            array(
                'conditions'    =>  array(
                    'year'		=>$int_year,
                    'month'		=>$int_month,
                    'site_id'	=>$site_id
                )
            )
        );

        $data_target = $this->default_zero;

        $target_week  = array();

        if(!isset($this->_month_info[$int_year."_".$int_month])){
            $this->_month_info[$int_year."_".$int_month] = $this->getMonthInfo($int_year,$int_month);
        }

        if(!empty($dataSsmKpi)){
            $count_week_in_month = $this->_month_info[$int_year."_".$int_month]['week_count'];

            $col_defined['pageviewsPerSession']     = 0;
            $col_defined['revenuePerTransaction']   = 0;
            $col_defined['revenuePerTransaction_1'] = 0;
            $col_defined['transactionsPerSession']  = 0;
            $col_defined['transactionsPerSession_1']= 0;
            $col_defined['bounceRate']              = 0;
            $col_defined['topBounceRate']           = 0;
            $col_defined['avgSessionDuration']      = 0;
            $col_defined['percentNewSessions']      = 0;

            foreach ($dataSsmKpi as $kpi_month) {
                $target_week[$kpi_month['SsmKpi']['week']]['transactionRevenue'] = $kpi_month['SsmKpi']['transactionRevenue'];
                $target_week[$kpi_month['SsmKpi']['week']]['transactions']       = $kpi_month['SsmKpi']['transactions'];
                $target_week[$kpi_month['SsmKpi']['week']]['transactions_1']     = $kpi_month['SsmKpi']['transactions_1'];
                $target_week[$kpi_month['SsmKpi']['week']]['pageviews']          = $kpi_month['SsmKpi']['pageviews'];

                //Update data target array
                $data_target['sessions']            = $data_target['sessions'] + $kpi_month['SsmKpi']['sessions'];
                $data_target['avgSessionDuration']  = $data_target['avgSessionDuration'] + $kpi_month['SsmKpi']['avgSessionDuration'];
                $data_target['pageviews']           = $data_target['pageviews'] + $kpi_month['SsmKpi']['pageviews'];
                $data_target['transactionRevenue']  = $data_target['transactionRevenue'] + $kpi_month['SsmKpi']['transactionRevenue'];
                $data_target['transactions']        = $data_target['transactions'] + $kpi_month['SsmKpi']['transactions'];
                $data_target['transactions_1']        = $data_target['transactions_1'] + $kpi_month['SsmKpi']['transactions_1'];
                $data_target['uniqueUsers']         = $data_target['uniqueUsers'] + $kpi_month['SsmKpi']['uniqueUsers'];

                $data_target['topBounceRate']       = $data_target['topBounceRate'] + $kpi_month['SsmKpi']['topBounceRate'];
                $data_target['pageviewsPerSession'] = $data_target['pageviewsPerSession'] + $kpi_month['SsmKpi']['pageviewsPerSession'];
                $data_target['revenuePerTransaction']  = $data_target['revenuePerTransaction'] + $kpi_month['SsmKpi']['revenuePerTransaction'];
                $data_target['transactionsPerSession'] = $data_target['transactionsPerSession'] + $kpi_month['SsmKpi']['transactionsPerSession'];

                $data_target['revenuePerTransaction_1']  = $data_target['revenuePerTransaction_1'] + $kpi_month['SsmKpi']['revenuePerTransaction_1'];
                $data_target['transactionsPerSession_1'] = $data_target['transactionsPerSession_1'] + $kpi_month['SsmKpi']['transactionsPerSession_1'];

                $data_target['bounceRate']          = $data_target['bounceRate'] + $kpi_month['SsmKpi']['bounceRate'];
                $data_target['percentNewSessions']  = $data_target['percentNewSessions'] + $kpi_month['SsmKpi']['percentNewSessions'];

                //End update data target array
                if($kpi_month['SsmKpi']['pageviewsPerSession'] > 0){
                    $col_defined['pageviewsPerSession']++;
                }
                if($kpi_month['SsmKpi']['revenuePerTransaction'] > 0){
                    $col_defined['revenuePerTransaction']++;
                }

                if($kpi_month['SsmKpi']['revenuePerTransaction_1'] > 0){
                    $col_defined['revenuePerTransaction_1']++;
                }

                if($kpi_month['SsmKpi']['transactionsPerSession'] > 0){
                    $col_defined['transactionsPerSession']++;
                }
                if($kpi_month['SsmKpi']['transactionsPerSession_1'] > 0){
                    $col_defined['transactionsPerSession_1']++;
                }
                if($kpi_month['SsmKpi']['bounceRate'] > 0){
                    $col_defined['bounceRate']++;
                }
                if($kpi_month['SsmKpi']['topBounceRate'] > 0){
                    $col_defined['topBounceRate']++;
                }
                if($kpi_month['SsmKpi']['avgSessionDuration'] > 0){
                    $col_defined['avgSessionDuration']++;
                }
                if($kpi_month['SsmKpi']['percentNewSessions'] > 0){
                    $col_defined['percentNewSessions']++;
                }
            }

            //get the average
            if(isset($data_target['pageviewsPerSession']) && $data_target['pageviewsPerSession'] != 0 ){
                $data_target['pageviewsPerSession'] = $data_target['pageviewsPerSession']/$col_defined['pageviewsPerSession'];
            }
            if(isset($data_target['revenuePerTransaction']) && $data_target['revenuePerTransaction'] != 0) {
                $data_target['revenuePerTransaction'] = $data_target['revenuePerTransaction']/$col_defined['revenuePerTransaction'];
            }
            if(isset($data_target['revenuePerTransaction_1']) && $data_target['revenuePerTransaction_1'] != 0) {
                $data_target['revenuePerTransaction_1'] = $data_target['revenuePerTransaction_1']/$col_defined['revenuePerTransaction_1'];
            }
            if(isset($data_target['transactionsPerSession']) && $data_target['transactionsPerSession'] != 0 ){
                $data_target['transactionsPerSession'] = $data_target['transactionsPerSession']/$col_defined['transactionsPerSession'];
            }
            if(isset($data_target['transactionsPerSession_1']) && $data_target['transactionsPerSession_1'] != 0 ){
                $data_target['transactionsPerSession_1'] = $data_target['transactionsPerSession_1']/$col_defined['transactionsPerSession_1'];
            }
            if(isset($data_target['bounceRate']) && $data_target['bounceRate'] != 0 ){
                $data_target['bounceRate'] = $data_target['bounceRate']/$col_defined['bounceRate'];
            }
            if(isset($data_target['topBounceRate']) && $data_target['topBounceRate'] != 0 ){
                $data_target['topBounceRate'] = $data_target['topBounceRate']/$col_defined['topBounceRate'];
            }
            if(isset($data_target['avgSessionDuration']) && $data_target['avgSessionDuration'] != 0 ){
                $data_target['avgSessionDuration'] = $data_target['avgSessionDuration']/$col_defined['avgSessionDuration'];
            }
            if(isset($data_target['percentNewSessions']) && $data_target['percentNewSessions'] != 0 ){
                $data_target['percentNewSessions'] = $data_target['percentNewSessions']/$col_defined['percentNewSessions'];
            }
        }

        return array(
        	'kpi_month'	=>$data_target,
        	'kpi_week'	=>$target_week
        );

	}

    function getDataTargetWeek($year,$month,$week,$site_id){
        $int_year   = intval($year);
        $int_month  = intval($month);
        $int_week   = intval($week);

        $dataSsmKpi_db = $this->find('first',
            array(
                'conditions'    =>  array(
                    'year'      =>$int_year,
                    'month'     =>$int_month,
                    'week'      =>$int_week,
                    'site_id'   =>$site_id
                )
            )
        );

        if(!$dataSsmKpi_db){
            //Update data target array
            $dataSsmKpi = $this->default_zero;
        }else{

            $dataSsmKpi = $dataSsmKpi_db['SsmKpi'];
        }
        return $dataSsmKpi;
    }


	function getDataTargetAllWeekInMonth($year,$month,$site_id){
        $int_year = intval($year);
        $int_month = intval($month);
		$dataSsmKpi = $this->find('all',
            array(
                'conditions'    =>  array(
                    'year'		=>$int_year,
                    'month'		=>$int_month,
                    'site_id'	=>$site_id
                )
            )
        );

        $data_target = $this->default_zero;
        $target_week = array();

        if(!isset($this->_month_info[$int_year."_".$int_month])){
            $this->_month_info[$int_year."_".$int_month] = $this->getMonthInfo($int_year,$int_month);
        }

        if(!empty($dataSsmKpi)){
            $count_week_in_month = $this->_month_info[$int_year."_".$int_month]['week_count'];

            $col_defined['pageviewsPerSession']     = 0;
            $col_defined['revenuePerTransaction']   = 0;
            $col_defined['revenuePerTransaction_1'] = 0;
            $col_defined['transactionsPerSession']  = 0;
            $col_defined['transactionsPerSession_1']= 0;
            $col_defined['bounceRate']              = 0;
            $col_defined['topBounceRate']           = 0;
            $col_defined['avgSessionDuration']      = 0;
            $col_defined['percentNewSessions']      = 0;

            foreach ($dataSsmKpi as $kpi_month) {
                $week = $kpi_month['SsmKpi']['week'];

                //target week
            	$target_week[$week]['sessions']                 = $kpi_month['SsmKpi']['sessions'];
            	$target_week[$week]['avgSessionDuration']       = $kpi_month['SsmKpi']['avgSessionDuration'];
            	$target_week[$week]['pageviews']                = $kpi_month['SsmKpi']['pageviews'];
            	$target_week[$week]['transactionRevenue']       = $kpi_month['SsmKpi']['transactionRevenue'];
            	$target_week[$week]['bounceRate']               = $kpi_month['SsmKpi']['bounceRate'];
            	$target_week[$week]['topBounceRate']            = $kpi_month['SsmKpi']['topBounceRate'];

            	$target_week[$week]['uniqueUsers']              = $kpi_month['SsmKpi']['uniqueUsers'];
                $target_week[$week]['pageviewsPerSession']      = $kpi_month['SsmKpi']['pageviewsPerSession'];
                $target_week[$week]['percentNewSessions']       = $kpi_month['SsmKpi']['percentNewSessions'];
                $target_week[$week]['transactions']             = $kpi_month['SsmKpi']['transactions'];
                $target_week[$week]['transactionsPerSession']   = $kpi_month['SsmKpi']['transactionsPerSession'];
                $target_week[$week]['revenuePerTransaction']    = $kpi_month['SsmKpi']['revenuePerTransaction'];

                $target_week[$week]['transactions_1']             = $kpi_month['SsmKpi']['transactions_1'];
                $target_week[$week]['transactionsPerSession_1']   = $kpi_month['SsmKpi']['transactionsPerSession_1'];
                $target_week[$week]['revenuePerTransaction_1']    = $kpi_month['SsmKpi']['revenuePerTransaction_1'];
                //End target week

                //Update data target total array
                $data_target['sessions']            = $data_target['sessions'] + $kpi_month['SsmKpi']['sessions'];
                $data_target['avgSessionDuration']  = $data_target['avgSessionDuration'] + $kpi_month['SsmKpi']['avgSessionDuration'];
                $data_target['pageviews']           = $data_target['pageviews'] + $kpi_month['SsmKpi']['pageviews'];
                $data_target['transactionRevenue']  = $data_target['transactionRevenue'] + $kpi_month['SsmKpi']['transactionRevenue'];
                $data_target['uniqueUsers']         = $data_target['uniqueUsers'] + $kpi_month['SsmKpi']['uniqueUsers'];

                $data_target['transactions']        = $data_target['transactions'] + $kpi_month['SsmKpi']['transactions'];
                $data_target['transactions_1']        = $data_target['transactions_1'] + $kpi_month['SsmKpi']['transactions_1'];

                //Get the total
                $data_target['bounceRate']              = $data_target['bounceRate'] + $kpi_month['SsmKpi']['bounceRate'];
                $data_target['pageviewsPerSession']     = $data_target['pageviewsPerSession'] + $kpi_month['SsmKpi']['pageviewsPerSession'];
                $data_target['topBounceRate']           = $data_target['topBounceRate'] + $kpi_month['SsmKpi']['topBounceRate'];
                $data_target['percentNewSessions']           = $data_target['percentNewSessions'] + $kpi_month['SsmKpi']['percentNewSessions'];

                $data_target['revenuePerTransaction']   = $data_target['revenuePerTransaction'] + $kpi_month['SsmKpi']['revenuePerTransaction'];
                $data_target['transactionsPerSession']  = $data_target['transactionsPerSession'] + $kpi_month['SsmKpi']['transactionsPerSession'];
                $data_target['revenuePerTransaction_1']   = $data_target['revenuePerTransaction_1'] + $kpi_month['SsmKpi']['revenuePerTransaction_1'];
                $data_target['transactionsPerSession_1']  = $data_target['transactionsPerSession_1'] + $kpi_month['SsmKpi']['transactionsPerSession_1'];
                //End update data target array
                if($kpi_month['SsmKpi']['pageviewsPerSession'] > 0){
                    $col_defined['pageviewsPerSession']++;
                }
                if($kpi_month['SsmKpi']['revenuePerTransaction'] > 0){
                    $col_defined['revenuePerTransaction']++;
                }

                if($kpi_month['SsmKpi']['revenuePerTransaction_1'] > 0){
                    $col_defined['revenuePerTransaction_1']++;
                }

                if($kpi_month['SsmKpi']['transactionsPerSession'] > 0){
                    $col_defined['transactionsPerSession']++;
                }

                if($kpi_month['SsmKpi']['transactionsPerSession_1'] > 0){
                    $col_defined['transactionsPerSession_1']++;
                }

                if($kpi_month['SsmKpi']['bounceRate'] > 0){
                    $col_defined['bounceRate']++;
                }
                if($kpi_month['SsmKpi']['topBounceRate'] > 0){
                    $col_defined['topBounceRate']++;
                }
                if($kpi_month['SsmKpi']['avgSessionDuration'] > 0){
                    $col_defined['avgSessionDuration']++;
                }
                if($kpi_month['SsmKpi']['percentNewSessions'] > 0){
                    $col_defined['percentNewSessions']++;
                }
            }

            //get the average
            if(isset($data_target['pageviewsPerSession']) && $data_target['pageviewsPerSession'] != 0 ){
                $data_target['pageviewsPerSession'] = $data_target['pageviewsPerSession']/$col_defined['pageviewsPerSession'];
            }
            if(isset($data_target['revenuePerTransaction']) && $data_target['revenuePerTransaction'] != 0) {
                $data_target['revenuePerTransaction'] = $data_target['revenuePerTransaction']/$col_defined['revenuePerTransaction'];
            }

            if(isset($data_target['transactionsPerSession']) && $data_target['transactionsPerSession'] != 0 ){
                $data_target['transactionsPerSession'] = $data_target['transactionsPerSession']/$col_defined['transactionsPerSession'];
            }

            if(isset($data_target['transactionsPerSession_1']) && $data_target['transactionsPerSession_1'] != 0 ){
                $data_target['transactionsPerSession_1'] = $data_target['transactionsPerSession_1']/$col_defined['transactionsPerSession_1'];
            }

            if(isset($data_target['revenuePerTransaction_1']) && $data_target['revenuePerTransaction_1'] != 0) {
                $data_target['revenuePerTransaction_1'] = $data_target['revenuePerTransaction_1']/$col_defined['revenuePerTransaction_1'];
            }

            if(isset($data_target['bounceRate']) && $data_target['bounceRate'] != 0 ){
                $data_target['bounceRate'] = $data_target['bounceRate']/$col_defined['bounceRate'];
            }
            if(isset($data_target['topBounceRate']) && $data_target['topBounceRate'] != 0 ){
                $data_target['topBounceRate'] = $data_target['topBounceRate']/$col_defined['topBounceRate'];
            }
            if(isset($data_target['avgSessionDuration']) && $data_target['avgSessionDuration'] != 0 ){
                $data_target['avgSessionDuration'] = $data_target['avgSessionDuration']/$col_defined['avgSessionDuration'];
            }
            if(isset($data_target['percentNewSessions']) && $data_target['percentNewSessions'] != 0 ){
                $data_target['percentNewSessions'] = $data_target['percentNewSessions']/$col_defined['percentNewSessions'];
            }
        }

        return array(
        	'kpi_month'	=>$data_target,
        	'kpi_week'	=>$target_week
        );
	}

}