<?php
App::uses('Component', 'Controller');

class CshishimaiComponent extends Component {

    public function initialize(Controller $controller) {
        $this->controller = $controller;
    }
    // $date_time_string (Y-m-d)
    public function getDateOfWeek($date_time_string,$lang = 'ja'){
    	$date = date('w',strtotime($date_time_string));
    	switch ($date) {
    		case 0:
    			# sunday
    			return ($lang == 'ja') ? '日' : 'Sunday';
    			break;
    		case 1:
    			# monday
    			return ($lang == 'ja') ? '月' : 'Monday';
    			break;
    		case 2:
    			# code...
    			return ($lang == 'ja') ? '火' : 'Tuesday';
    			break;
    		case 3:
    			# code...
    			return ($lang == 'ja') ? '水' : 'Wednesday';
    			break;
    		case 4:
    			# code...
    			return ($lang == 'ja') ? '木' : 'Thursday';
    			break;
    		case 5:
    			# code...
    			return ($lang == 'ja') ? '金' : 'Friday';
    			break;
    		case 6:
    			# code...
    			return ($lang == 'ja') ? '土' : 'Saturday';
    			break;

    		default:
    			# code...
    			break;
    	}
    }

    //Total two ga data array
    public function gaCalculate($ssmKpi,$ssmKpiChange){
        $sessions               = $ssmKpi['sessions'] + $ssmKpiChange['sessions'];
        $pageviews              = $ssmKpi['pageviews'] + $ssmKpiChange['pageviews'];
        $transactionRevenue     = $ssmKpi['transactionRevenue'] + $ssmKpiChange['transactionRevenue'];
        $transactions_1           = $ssmKpi['transactions_1'] + $ssmKpiChange['transactions_1'];
    	return array(
    		'sessions'			=>$sessions,
    		'avgSessionDuration'=>(($ssmKpi['avgSessionDuration'] + $ssmKpiChange['avgSessionDuration'])/2),
    		'pageviews'			=>$pageviews,
    		'transactionRevenue'=>$transactionRevenue,
    		'bounceRate'		=>(($ssmKpi['bounceRate'] + $ssmKpiChange['bounceRate'])/2),
    		'transactions'		=>$transactions_1,
    		'uniqueUsers'		=>$ssmKpi['uniqueUsers'] + $ssmKpiChange['uniqueUsers'],
    		'topBounceRate'		=>(($ssmKpi['topBounceRate'] + $ssmKpiChange['topBounceRate'])/2),
            'percentNewSessions'     =>(($ssmKpi['percentNewSessions'] + $ssmKpiChange['percentNewSessions'])/2),

            'transactionsPerSession'    =>($transactions != 0 && $sessions != 0 )? $transactions/$sessions : 0,
            'revenuePerTransaction'     =>($transactions !=0 && $transactionRevenue != 0) ? $transactionRevenue/$transactions : 0,

            'transactionsPerSession_1'    =>($transactions_1 != 0 && $sessions != 0 )? $transactions_1/$sessions : 0,
            'revenuePerTransaction_1'     =>($transactions_1 !=0 && $transactionRevenue != 0) ? $transactionRevenue/$transactions_1 : 0,
    		'pageviewsPerSession'       =>($pageviews !=0 && $sessions !=0) ? $pageviews/$sessions : 0
    	);
    }

    //Total function total ga data with ga changed data
    public function gaTotal($kpiAArr,$kpiBArr){
        $sessions               = $kpiAArr['sessions']              + $kpiBArr['sessions'];
        $avgSessionDuration     = $kpiAArr['avgSessionDuration']    + $kpiBArr['avgSessionDuration'];
        $pageviews              = $kpiAArr['pageviews']             + $kpiBArr['pageviews'];
        $transactionRevenue     = $kpiAArr['transactionRevenue']    + $kpiBArr['transactionRevenue'];
        $bounceRate             = $kpiAArr['bounceRate']            + $kpiBArr['bounceRate'];
        $transactions           = $kpiAArr['transactions']          + $kpiBArr['transactions'];
        $transactions_1           = $kpiAArr['transactions_1']          + $kpiBArr['transactions_1'];
        $uniqueUsers            = $kpiAArr['uniqueUsers']           + $kpiBArr['uniqueUsers'];
        $topBounceRate          = $kpiAArr['topBounceRate']         + $kpiBArr['topBounceRate'];

        $transactionsPerSession = $kpiAArr['transactionsPerSession'] + $kpiBArr['transactionsPerSession'];
        $revenuePerTransaction  = $kpiAArr['revenuePerTransaction']  + $kpiBArr['revenuePerTransaction'];

        $transactionsPerSession_1 = $kpiAArr['transactionsPerSession_1'] + $kpiBArr['transactionsPerSession_1'];
        $revenuePerTransaction_1  = $kpiAArr['revenuePerTransaction_1']  + $kpiBArr['revenuePerTransaction_1'];

        $pageviewsPerSession    = $kpiAArr['pageviewsPerSession']    + $kpiBArr['pageviewsPerSession'];
        $percentNewSessions    = $kpiAArr['percentNewSessions']    + $kpiBArr['percentNewSessions'];
        return array(
            'sessions'          =>$sessions,
            'avgSessionDuration'=>$avgSessionDuration,
            'pageviews'         =>$pageviews,
            'transactionRevenue'=>$transactionRevenue,
            'bounceRate'        =>$bounceRate,
            'transactions'      =>$transactions,
            'transactions_1'      =>$transactions_1,
            'uniqueUsers'       =>$uniqueUsers,
            'topBounceRate'     =>$topBounceRate,
            'transactionsPerSession'    =>$transactionsPerSession,
            'revenuePerTransaction'     =>$revenuePerTransaction,

            'transactionsPerSession_1'    =>$transactionsPerSession_1,
            'revenuePerTransaction_1'     =>$revenuePerTransaction_1,

            'pageviewsPerSession'       =>$pageviewsPerSession,
            'percentNewSessions'        =>$percentNewSessions
        );
    }

    //Display total target
    public function totalAfterCalculate($total,$total_number_of_columns_added,$total_number_of_columns){
        if(is_array($total_number_of_columns_added)){
            $total_added = 'array';
        }else{
            $total_added = $total_number_of_columns_added;
        }
        //transactionRevenue
        //pageviews
        //pageviewsPerSession
        //sessions
        //avgSessionDuration
        //uniqueUsers
        //transactions
        //transactionsPerSession
        //revenuePerTransaction
        //bounceRate
        //topBounceRate
        //percentNewSessions
        return array(
            'transactionRevenue'            =>$total['transactionRevenue'],//1
            'pageviews'                     =>$total['pageviews'],//2
            'pageviewsPerSession'           =>($total['pageviewsPerSession']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['pageviewsPerSession'] : $total_number_of_columns_added)),//3
            'sessions'                      =>$total['sessions'],//4
            'avgSessionDuration'            =>($total['avgSessionDuration']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['avgSessionDuration']: $total_number_of_columns_added)),//5
            'uniqueUsers'                   =>$total['uniqueUsers'],//6
            'transactions'                  =>$total['transactions'],//7
            'transactions_1'                  =>$total['transactions_1'],

            'transactionsPerSession'        =>($total['transactionsPerSession']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['transactionsPerSession']: $total_number_of_columns_added)),//8
            'revenuePerTransaction'         =>($total['revenuePerTransaction']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['revenuePerTransaction']: $total_number_of_columns_added)),//9

            'transactionsPerSession_1'        =>($total['transactionsPerSession_1']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['transactionsPerSession_1']: $total_number_of_columns_added)),//8
            'revenuePerTransaction_1'         =>($total['revenuePerTransaction_1']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['revenuePerTransaction_1']: $total_number_of_columns_added)),//9


            'bounceRate'                    =>($total['bounceRate']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['bounceRate']: $total_number_of_columns_added)),//10
            'topBounceRate'                 =>($total['topBounceRate']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['topBounceRate']: $total_number_of_columns_added)),//11
            'percentNewSessions'            =>($total['percentNewSessions']/($total_number_of_columns_added == 'array' ? $total_number_of_columns_added['percentNewSessions']: $total_number_of_columns_added)),//12
        );
    }

    //get the ratio of array ga A with array ga B
    public function gaRatio($ssmKpiArray,$targetArray,$reverse = 0){
        if($targetArray['sessions'] != 0 && $ssmKpiArray['sessions'] == 0){
            $sessions = 0;
        }elseif($targetArray['sessions'] == "-" || $targetArray['sessions'] == 0){
            $sessions = "-";
        }else{
            $sessions = ($ssmKpiArray['sessions'] * 100) / $targetArray['sessions'];
        }

        if($targetArray['avgSessionDuration'] != 0 && $ssmKpiArray['avgSessionDuration'] == 0){
            $avgSessionDuration = 0;
        }elseif($targetArray['avgSessionDuration'] == "-" || $targetArray['avgSessionDuration'] == 0){
            $avgSessionDuration = "-";
        }else{
            $avgSessionDuration = ($ssmKpiArray['avgSessionDuration'] * 100) / $targetArray['avgSessionDuration'];
        }

        if($targetArray['pageviews'] != 0 && $ssmKpiArray['pageviews'] == 0){
            $pageviews = 0;
        }elseif($targetArray['pageviews'] == "-" || $targetArray['pageviews'] == 0){
            $pageviews = "-";
        }else{
            $pageviews = ($ssmKpiArray['pageviews'] * 100) / $targetArray['pageviews'];
        }

        if($targetArray['revenuePerTransaction'] != 0 && $ssmKpiArray['revenuePerTransaction'] == 0){
            $revenuePerTransaction = 0;
        }elseif($targetArray['transactionRevenue'] == "-" || $targetArray['transactionRevenue'] == 0){
            $transactionRevenue = "-";
        }else{
            $transactionRevenue = ($ssmKpiArray['transactionRevenue'] * 100) / $targetArray['transactionRevenue'];
        }

        if($targetArray['revenuePerTransaction'] != 0 && $ssmKpiArray['revenuePerTransaction'] == 0){
            $revenuePerTransaction = 0;
        }elseif($targetArray['revenuePerTransaction'] == "-" || $targetArray['revenuePerTransaction'] == 0){
            $revenuePerTransaction = "-";
        }else{
            $revenuePerTransaction = ($ssmKpiArray['revenuePerTransaction'] * 100) / $targetArray['revenuePerTransaction'];
        }

        if(!$reverse){
            if($targetArray['bounceRate'] != 0 && $ssmKpiArray['bounceRate'] == 0){
                $bounceRate = 0;
            }elseif($targetArray['bounceRate'] == "-" || $targetArray['bounceRate'] == 0){
                $bounceRate = "-";
            }else{
                $bounceRate = ($ssmKpiArray['bounceRate'] * 100) / $targetArray['bounceRate'];
            }

            if($targetArray['topBounceRate'] != 0 && $ssmKpiArray['topBounceRate'] == 0){
                $topBounceRate = 0;
            }elseif($targetArray['topBounceRate'] == "-" || $targetArray['topBounceRate'] == 0){
                $topBounceRate = "-";
            }else{
                $topBounceRate = ($ssmKpiArray['topBounceRate'] * 100) / $targetArray['topBounceRate'];
            }

        }else{
            if($targetArray['bounceRate'] != 0 && $ssmKpiArray['bounceRate'] == 0){
                $bounceRate = 0;
            }elseif($targetArray['bounceRate'] == "-" || $targetArray['bounceRate'] == 0){
                $bounceRate = "-";
            }else{
                $bounceRate = ($targetArray['bounceRate'] * 100) / $ssmKpiArray['bounceRate'];
            }

            if($targetArray['topBounceRate'] != 0 && $ssmKpiArray['topBounceRate'] == 0){
                $topBounceRate = 0;
            }elseif($targetArray['topBounceRate'] == "-" || $targetArray['topBounceRate'] == 0){
                $topBounceRate = "-";
            }else{
                $topBounceRate = ($targetArray['topBounceRate'] * 100) / $ssmKpiArray['topBounceRate'];
            }
        }

        if($targetArray['transactions'] != 0 && $ssmKpiArray['transactions'] == 0){
            $transactions = 0;
        }elseif($targetArray['transactions'] == "-" || $targetArray['transactions'] == 0){
            $transactions = "-";
        }else{
            $transactions = ($ssmKpiArray['transactions'] * 100) / $targetArray['transactions'];
        }

        if($targetArray['transactions_1'] != 0 && $ssmKpiArray['transactions_1'] == 0){
            $transactions_1 = 0;
        }elseif($targetArray['transactions_1'] == "-" || $targetArray['transactions_1'] == 0){
            $transactions_1 = "-";
        }else{
            $transactions_1 = ($ssmKpiArray['transactions_1'] * 100) / $targetArray['transactions_1'];
        }

        if($targetArray['transactionsPerSession'] != 0 && $ssmKpiArray['transactionsPerSession'] == 0){
            $transactionsPerSession = 0;
        }elseif($targetArray['transactionsPerSession'] == "-" || $targetArray['transactionsPerSession'] == 0){
            $transactionsPerSession = "-";
        }else{
            $transactionsPerSession = ($ssmKpiArray['transactionsPerSession'] * 100) / $targetArray['transactionsPerSession'];
        }

        if($targetArray['transactionsPerSession_1'] != 0 && $ssmKpiArray['transactionsPerSession_1'] == 0){
            $transactionsPerSession_1 = 0;
        }elseif($targetArray['transactionsPerSession_1'] == "-" || $targetArray['transactionsPerSession_1'] == 0){
            $transactionsPerSession_1 = "-";
        }else{
            $transactionsPerSession_1 = ($ssmKpiArray['transactionsPerSession_1'] * 100) / $targetArray['transactionsPerSession_1'];
        }

        if($targetArray['revenuePerTransaction_1'] != 0 && $ssmKpiArray['revenuePerTransaction_1'] == 0){
            $revenuePerTransaction_1 = 0;
        }elseif($targetArray['revenuePerTransaction_1'] == "-" || $targetArray['revenuePerTransaction_1'] == 0){
            $revenuePerTransaction_1 = "-";
        }else{
            $revenuePerTransaction_1 = ($ssmKpiArray['revenuePerTransaction_1'] * 100) / $targetArray['revenuePerTransaction_1'];
        }

        if($targetArray['uniqueUsers'] != 0 && $ssmKpiArray['uniqueUsers'] == 0){
            $uniqueUsers = 0;
        }elseif($targetArray['uniqueUsers'] == "-" || $targetArray['uniqueUsers'] == 0){
            $uniqueUsers = "-";
        }else{
            $uniqueUsers = ($ssmKpiArray['uniqueUsers'] * 100) / $targetArray['uniqueUsers'];
        }

        

        if($targetArray['pageviewsPerSession'] != 0 && $ssmKpiArray['pageviewsPerSession'] == 0){
            $pageviewsPerSession = 0;
        }elseif($targetArray['pageviewsPerSession'] == "-" || $targetArray['pageviewsPerSession'] == 0){
            $pageviewsPerSession = "-";
        }else{
            $pageviewsPerSession = ($ssmKpiArray['pageviewsPerSession'] * 100) / $targetArray['pageviewsPerSession'];
        }

        if($targetArray['percentNewSessions'] != 0 && $ssmKpiArray['percentNewSessions'] == 0){
            $percentNewSessions = 0;
        }elseif($targetArray['percentNewSessions'] == "-" || $targetArray['percentNewSessions'] == 0){
            $percentNewSessions = "-";
        }else{
            $percentNewSessions = ($ssmKpiArray['percentNewSessions'] * 100) / $targetArray['percentNewSessions'];
        }

    	$return = array(
            'sessions'                => $sessions,
            'avgSessionDuration'      => $avgSessionDuration,
            'pageviews'               => $pageviews,
            'transactionRevenue'      => $transactionRevenue,
            'revenuePerTransaction'   => $revenuePerTransaction,
            'bounceRate'              => $bounceRate ,
            'transactions'            => $transactions,
            'transactions_1'          => $transactions_1,
            'transactionsPerSession'  => $transactionsPerSession,
            'transactionsPerSession_1'=> $transactionsPerSession_1,
            'revenuePerTransaction_1' => $revenuePerTransaction_1,
            'uniqueUsers'             => $uniqueUsers,
            'topBounceRate'           => $topBounceRate,
            'pageviewsPerSession'     => $pageviewsPerSession,
            'percentNewSessions'      => $percentNewSessions
        );
        return $return;
    }

    public function gaRatioBK($ssmKpiArray,$targetArray){
        return array(
            'sessions'                => ($targetArray['sessions'] != 0 && $ssmKpiArray['sessions'] != 0) ? ($ssmKpiArray['sessions'] * 100) / $targetArray['sessions'] : "-" ,
            'avgSessionDuration'      => ($targetArray['avgSessionDuration'] != 0 && $ssmKpiArray['avgSessionDuration'] != 0) ? ($ssmKpiArray['avgSessionDuration'] * 100) / $targetArray['avgSessionDuration']: "-" ,
            'pageviews'               => ($targetArray['pageviews'] != 0 && $ssmKpiArray['pageviews'] != 0) ? ($ssmKpiArray['pageviews'] * 100) / $targetArray['pageviews']: "-" ,
            'transactionRevenue'      => ($targetArray['transactionRevenue'] != 0 && $ssmKpiArray['transactionRevenue'] != 0) ? ($ssmKpiArray['transactionRevenue'] * 100) / $targetArray['transactionRevenue']: "-" ,
            'revenuePerTransaction'   => ($targetArray['revenuePerTransaction'] != 0 && $ssmKpiArray['revenuePerTransaction'] != 0) ? ($ssmKpiArray['revenuePerTransaction'] * 100) / $targetArray['revenuePerTransaction']: "-" ,
            'bounceRate'              => ($targetArray['bounceRate'] != 0 && $ssmKpiArray['bounceRate'] != 0) ? ($ssmKpiArray['bounceRate'] * 100) / $targetArray['bounceRate']: "-" ,
            'transactions'            => ($targetArray['transactions'] != 0 && $ssmKpiArray['transactions'] != 0) ? ($ssmKpiArray['transactions'] * 100) / $targetArray['transactions']: "-" ,
            'transactions_1'            => ($targetArray['transactions_1'] != 0 && $ssmKpiArray['transactions_1'] != 0) ? ($ssmKpiArray['transactions_1'] * 100) / $targetArray['transactions_1']: "-" ,

            'transactionsPerSession'  => ($targetArray['transactionsPerSession'] != 0 && $ssmKpiArray['transactionsPerSession'] != 0) ? ($ssmKpiArray['transactionsPerSession'] * 100) / $targetArray['transactionsPerSession']: "-" ,

            'transactionsPerSession_1'  => ($targetArray['transactionsPerSession_1'] != 0 && $ssmKpiArray['transactionsPerSession_1'] != 0) ? ($ssmKpiArray['transactionsPerSession_1'] * 100) / $targetArray['transactionsPerSession_1']: "-" ,
            'revenuePerTransaction_1'   => ($targetArray['revenuePerTransaction_1'] != 0 && $ssmKpiArray['revenuePerTransaction_1'] != 0) ? ($ssmKpiArray['revenuePerTransaction_1'] * 100) / $targetArray['revenuePerTransaction_1']: "-" ,

            'uniqueUsers'             => ($targetArray['uniqueUsers'] != 0 && $ssmKpiArray['uniqueUsers'] != 0) ? ($ssmKpiArray['uniqueUsers'] * 100) / $targetArray['uniqueUsers']: "-",
            'topBounceRate'           => ($targetArray['topBounceRate'] != 0 && $ssmKpiArray['topBounceRate'] != 0) ? ($ssmKpiArray['topBounceRate'] * 100) / $targetArray['topBounceRate']: "-" ,
            'pageviewsPerSession'     => ($targetArray['pageviewsPerSession'] != 0 && $ssmKpiArray['pageviewsPerSession'] != 0) ? ($ssmKpiArray['pageviewsPerSession'] * 100) / $targetArray['pageviewsPerSession']: "-" ,
            'percentNewSessions'     => ($targetArray['percentNewSessions'] != 0 && $ssmKpiArray['percentNewSessions'] != 0) ? ($ssmKpiArray['percentNewSessions'] * 100) / $targetArray['percentNewSessions']: "-" ,
        );
    }


    public function gaPredict($ssmKpiArray,$number_day_get_data,$number_day_in_month){
    	$number_day_get_data = intval($number_day_get_data);
    	$number_day_in_month = intval($number_day_in_month);
    	return array(
    		'sessions'                =>(($ssmKpiArray['sessions']/$number_day_get_data) * $number_day_in_month),
    		'avgSessionDuration'      =>$ssmKpiArray['avgSessionDuration'],
    		'pageviews'               =>(($ssmKpiArray['pageviews']/$number_day_get_data) * $number_day_in_month),
    		'transactionRevenue'      =>(($ssmKpiArray['transactionRevenue']/$number_day_get_data) * $number_day_in_month),
    		'revenuePerTransaction'   =>$ssmKpiArray['revenuePerTransaction'],
            'revenuePerTransaction_1'   =>$ssmKpiArray['revenuePerTransaction_1'],
    		'bounceRate'              =>$ssmKpiArray['bounceRate'],
    		'transactions'            =>(($ssmKpiArray['transactions']/$number_day_get_data) * $number_day_in_month),
            'transactions_1'            =>(($ssmKpiArray['transactions_1']/$number_day_get_data) * $number_day_in_month),
    		'transactionsPerSession'  =>$ssmKpiArray['transactionsPerSession'],
            'transactionsPerSession_1'  =>$ssmKpiArray['transactionsPerSession_1'],
    		'uniqueUsers'             =>(($ssmKpiArray['uniqueUsers']/$number_day_get_data) * $number_day_in_month),
    		'topBounceRate'           =>$ssmKpiArray['topBounceRate'],
    		'pageviewsPerSession'     =>$ssmKpiArray['pageviewsPerSession'],
            'percentNewSessions'      =>$ssmKpiArray['percentNewSessions'],
    	);
    }

    public function gaProspective($ssmKpiArray,$number_day_get_data,$total_day){
    	$number_day_get_data = intval($number_day_get_data);
    	$total_day = intval($total_day);
    	return array(
    		'sessions'                =>(($ssmKpiArray['sessions']/$number_day_get_data) * $total_day),
    		'avgSessionDuration'      =>(($ssmKpiArray['avgSessionDuration']/$number_day_get_data) * $total_day),
    		'pageviews'               =>(($ssmKpiArray['pageviews']/$number_day_get_data) * $total_day),
    		'transactionRevenue'      =>(($ssmKpiArray['transactionRevenue']/$number_day_get_data) * $total_day),
    		'revenuePerTransaction'   =>$ssmKpiArray['revenuePerTransaction'],
            'revenuePerTransaction_1'   =>$ssmKpiArray['revenuePerTransaction_1'],
    		'bounceRate'              =>$ssmKpiArray['bounceRate'],
    		'transactions'            =>(($ssmKpiArray['transactions']/$number_day_get_data) * $total_day),
    		'transactionsPerSession'  =>$ssmKpiArray['transactionsPerSession'],

            'transactions_1'            =>(($ssmKpiArray['transactions_1']/$number_day_get_data) * $total_day),
            'transactionsPerSession_1'  =>$ssmKpiArray['transactionsPerSession_1'],

    		'uniqueUsers'             =>(($ssmKpiArray['uniqueUsers']/$number_day_get_data) * $total_day),
    		'topBounceRate'           =>(($ssmKpiArray['topBounceRate']/$number_day_get_data) * $total_day),
    		'pageviewsPerSession'     =>(($ssmKpiArray['pageviews']/$number_day_get_data) * $total_day) / (($ssmKpiArray['sessions']/$number_day_get_data) * $total_day),
            'percentNewSessions'      =>$ssmKpiArray['percentNewSessions'],
    	);
    }


    function getAVG($data,$field = array(),$avg_number){


        if(in_array('sessions',$field)){
            if($data['sessions'] != 0)
            $data['sessions'] = $data['sessions']/$avg_number;
        }
        if(in_array('avgSessionDuration',$field)){
            if($data['avgSessionDuration'] != 0)
            $data['avgSessionDuration'] = $data['avgSessionDuration']/$avg_number;
        }
        if(in_array('pageviews',$field)){
            if($data['pageviews'] != 0)
            $data['pageviews'] = $data['pageviews']/$avg_number;
        }
        if(in_array('transactionRevenue',$field)){
            if($data['transactionRevenue'] != 0)
            $data['transactionRevenue'] = $data['transactionRevenue']/$avg_number;
        }
        if(in_array('revenuePerTransaction',$field)){
            if($data['revenuePerTransaction'] != 0)
            $data['revenuePerTransaction'] = $data['revenuePerTransaction']/$avg_number;
        }

        if(in_array('revenuePerTransaction_1',$field)){
            if($data['revenuePerTransaction_1'] != 0)
            $data['revenuePerTransaction_1'] = $data['revenuePerTransaction_1']/$avg_number;
        }

        if(in_array('bounceRate',$field)){
            if($data['bounceRate'] != 0)
            $data['bounceRate'] = $data['bounceRate']/$avg_number;
        }
        if(in_array('transactions',$field)){
            if($data['transactions'] != 0)
            $data['transactions'] = $data['transactions']/$avg_number;
        }

        if(in_array('transactions_1',$field)){
            if($data['transactions_1'] != 0)
            $data['transactions_1'] = $data['transactions_1']/$avg_number;
        }

        if(in_array('transactionsPerSession',$field)){
            if($data['transactionsPerSession'] != 0)
            $data['transactionsPerSession'] = $data['transactionsPerSession']/$avg_number;
        }

        if(in_array('transactionsPerSession_1',$field)){
            if($data['transactionsPerSession_1'] != 0)
            $data['transactionsPerSession_1'] = $data['transactionsPerSession_1']/$avg_number;
        }

        if(in_array('uniqueUsers',$field)){
            if($data['uniqueUsers'] != 0)
            $data['uniqueUsers'] = $data['uniqueUsers']/$avg_number;
        }
        if(in_array('pageviewsPerSession',$field)){
            if($data['pageviewsPerSession'] != 0)
            $data['pageviewsPerSession'] = $data['pageviewsPerSession']/$avg_number;
        }
        if(in_array('topBounceRate',$field)){
            if($data['topBounceRate'] != 0)
            $data['topBounceRate'] = $data['topBounceRate']/$avg_number;
        }
        if(in_array('percentNewSessions',$field)){
            if($data['percentNewSessions'] != 0)
            $data['percentNewSessions'] = $data['percentNewSessions']/$avg_number;
        }

        return $data;
    }


    public function gaEmptyData(){
    	return array(
    		'sessions'				=>0,
    		'avgSessionDuration'	=>0,
    		'pageviews'				=>0,
    		'transactionRevenue'	=>0,
    		'revenuePerTransaction'	=>0,
    		'bounceRate'			=>0,
    		'transactions'			=>0,
    		'transactionsPerSession'=>0,
    		'uniqueUsers'			=>0,
    		'topBounceRate'			=>0,
    		'pageviewsPerSession'	=>0,
            'percentNewSessions'    =>0,
            'transactions_1'          =>0,
            'transactionsPerSession_1'=>0,
            'revenuePerTransaction_1' =>0,
    	);
    }


    //Range report
    function getListRangeReport(){
        return array(
            1=>'1_6',
            2=>'2_7',
            3=>'3_8',
            4=>'4_9',
            5=>'5_10',
            6=>'6_11',
            7=>'7_12',
            8=>'8_1',
            9=>'9_2',
            10=>'10_3',
            11=>'11_4',
            12=>'12_5'
        );
    }

    //Get prev range
    function getPrevRangeANDYear($str_current_range,$current_year){

        $ex = explode('_',$str_current_range);
        $end = ($ex[0] == 1) ? 12 : $ex[0] - 1;
        $begin = ($end >= 6) ? ($end - 5) : (($end + 12) - 5);
        if($begin > 6){
            $year = $current_year - 1;
        }else{
            $year = $current_year;
        }

        $data_return = array(
            'range_report'=>$begin."_".$end,
            'year_report' =>$year
        );
        return $data_return;
    }

    //get next range
    function getNextRangeANDYear($str_current_range,$current_year){

        $ex     = explode('_',$str_current_range);
        $begin  = ($ex[1] == 12) ? 1 : ($ex[1] + 1);
        $end    = ($begin <= 7) ? ($begin + 5) : (($begin + 5) - 12);

        if($end >= 6 && $end != 12){
            $year = $current_year + 1;
        }else{
            $year = $current_year;
        }

        $data_return = array(
            'range_report'=> $begin."_".$end,
            'year_report' => $year
        );
        return $data_return;
    }

    //get list month in range
    function getListmonth($str_range){
        $ex = explode('_',$str_range);
        $list = array();
        if($ex[0] < $ex[1]){
            for ($i = $ex[0]; $i <= $ex[1]; $i++) {
                $list[] = $i;
            }
        }else{
            for ($i = $ex[0]; $i <= 12; $i++) {
                $list[] = $i;
            }
            for ($i = 1; $i <= $ex[1]; $i++) {
                $list[] = $i;
            }
        }
        return $list;
    }

    //get array range info
    function getRangeInfo($year,$list_month){
        $return = array();
        if($list_month[5] > $list_month[0]){
            foreach ($list_month as $month) {
                $return[] = array(
                    'year'  => $year,
                    'month' => $month
                );
            }
        }else{
            foreach ($list_month as $key=>$month) {
                if(isset($list_month[$key-1]) && $list_month[$key-1] > $month){
                    $year = $year + 1;
                }
                $return[] = array(
                    'year'  => $year,
                    'month' => $month
                );
            }
        }
        return $return;
    }

    //Check month report in range
    function checkMonthInRange($month,$str_rangeReport){
        $list = $this->getListmonth($str_rangeReport);
        if(!in_array($month,$list)){
            return false;
        }else{
            return true;
        }
    }


    function getStartEnDateOfWeek($year,$month,$week){
        $month = intval($month);
        $month_str = ($month > 9) ? $month : "0".$month;
        if($week == 'last' || $week == 5){
            $date    = date('Y-m-t', strtotime($year."-".$month_str."-01"));
            $ex = explode('-',$date);

            if($ex[2] > 28){
                $week = 5;
                $start = $year."-".$month_str."-29";
                $end   = $date;
                $start_day  = 29;
                $end_day    = $ex[2];
            }else{
                $week = 4;
                $start = $year."-".$month_str."-22";
                $end   = $year."-".$month_str."-28";
                $start_day  = 22;
                $end_day    = 28;
            }
        }else{
            if($week == 1){
                $start = $year."-".$month_str."-01";
                $end   = $year."-".$month_str."-07";
                $start_day  = 1;
                $end_day    = 7;
            }elseif($week == 2){
                $start = $year."-".$month_str."-08";
                $end   = $year."-".$month_str."-14";
                $start_day  = 8;
                $end_day    = 14;
            }elseif($week == 3){
                $start = $year."-".$month_str."-15";
                $end   = $year."-".$month_str."-21";
                $start_day  = 15;
                $end_day    = 21;
            }elseif($week == 4){
                $start = $year."-".$month_str."-22";
                $end   = $year."-".$month_str."-28";
                $start_day  = 22;
                $end_day    = 28;
            }
        }

        return  array(
            'start_date'    =>$start,
            'end_date'      =>$end,
            'start_day'     =>$start_day,
            'end_day'       =>$end_day,
            'week'          =>$week
        );
    }


    function getStartEnDateOfCurrentWeek(){
        $crr_day = intval(date('d'));
        $last_date    = date("Y-m-t");
        $ex = explode('-',$last_date);
        $last_day_of_month = $ex[2];

        if($crr_day >=1 && $crr_day <= 7){
            $start  = 1;
            $end    = 7;
            $week = 1;
        }elseif($crr_day >=8 && $crr_day <= 14){
            $start  = 8;
            $end    = 14;
            $week = 2;
        }elseif($crr_day >=15 && $crr_day <= 21){
            $start  = 15;
            $end    = 21;
            $week = 3;
        }elseif($crr_day >=22 && $crr_day <= 28){
            $start  = 22;
            $end    = 28;
            $week = 4;
        }else{
            $start  = 22;
            $end    = $ex[2];
            $week = 5;
        }
        return array('start'=>$start,'end'=>$end,'week'=>$week);
    }


    function getUserSiteInfo(){
        $return = array(
            'user_id'=> 1,
            'role'   => 'admin'
        );

        $return['site_id'] = $this->getSiteId(1);

        return $return;
    }

    function getSiteId($default_site_id){

        if(isset($this->controller->request->query['site_id'])){
            return $this->controller->request->query['site_id'];
        }elseif(isset($this->controller->request->params['named']['site_id'])){
            return $this->controller->request->params['named']['site_id'];
        }else{
            return $default_site_id;
        }
    }

    function getDateRange($year,$month){
        $month = intval($month) > 9 ? $month : '0'.intval($month);
        //Month info
        $begin_date  = date($year.'-'.$month.'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));
        $end_date_ex = explode('-',$end_date);
        $end_day_number = intval($end_date_ex[2]);

        //Start monday
        $w_1_monday = intval(date("d", strtotime("first monday ".$year."-".$month)));
        $w_1_sunday = $w_1_monday + 6;

        $w[1]= array(
            'start_day' =>$w_1_monday,
            'end_day'   =>$w_1_sunday,
            'month'     =>$month,
            'year'      =>$year
        );

        $month_check = $month;

        $prev_monday = $w_1_monday;

        for ($i = 2; $i <= 5; $i++) {
            $prev_start = $w[$i-1]['start_day'];
            $prev_end = $w[$i-1]['end_day'];

            if($prev_start < $prev_end){
                $start = $prev_start + 7 ;
                $end = $prev_end + 7 ;

                if($end > $end_day_number){
                    $end = ($end - $end_day_number);
                }

                $w[$i]= array(
                    'start_day' =>$start,
                    'end_day'   =>$end,
                    'month'     =>$month,
                    'year'      =>$year
                );
            }
        }
        return $w;
    }

    function getPrevNextWeek($date){

    }

    /*
     * $start_date: Y-m-d
     * $deadline_day_num: 0: Sunday, 1: monday, ...
     */
    function getDealineDate($start_date, $deadline_day_num){
        $next_num_day = $deadline_day_num - 1;
        if ($next_num_day < 0)
            $next_num_day = 6;
        $next_num_day += 7;
        return date('Y-m-d', strtotime($start_date . ' + ' . $next_num_day . ' days'));
    }


    function getMonthToBuildOptionReport($cr_year,$cr_month,$next_or_prev = 'current'){
        $cr_month = intval($cr_month);

        if($next_or_prev =='next'){
            if($cr_month == 12){
                $year = $cr_year + 1;
                $month = 1;
            }else{
                $year = $cr_year;
                $month = $cr_month + 1;
            }
        }elseif($next_or_prev =='prev'){
            if($cr_month == 1){
                $year = $cr_year -1;
                $month = 12;
            }else{
                $year = $cr_year;
                $month = $cr_month - 1;
            }
        }else{
            $year = $cr_year;
            $month = $cr_month;
        }

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
        return array('year'=>$year,'month'=>$month,'last_day'=>$end_day_number);
    }

    function reCalculateField($data){
        if($data['transactionRevenue'] != 0 &&$data['transactions'] != 0){
            $data['revenuePerTransaction'] = $data['transactionRevenue']/$data['transactions'];
        }else{
            $data['revenuePerTransaction'] = 0;
        }

        if($data['transactionRevenue'] != 0 && $data['transactions'] !=0){
            $data['revenuePerTransaction'] = $data['transactionRevenue']/$data['transactions'];
        }else{
            $data['revenuePerTransaction'] = 0;
        }

        if($data['transactions'] != 0 && $data['sessions'] !=0){
            $data['transactionsPerSession'] = ($data['transactions']/$data['sessions'])*100;
        }else{
            $data['transactionsPerSession'] = 0;
        }

        if($data['transactionRevenue'] != 0 && $data['transactions_1'] !=0){
            $data['revenuePerTransaction_1'] = $data['transactionRevenue']/$data['transactions_1'];
        }else{
            $data['revenuePerTransaction_1'] = 0;
        }

        if($data['transactions_1'] != 0 && $data['sessions'] !=0){
            $data['transactionsPerSession_1'] = ($data['transactions_1']/$data['sessions'])*100;
        }else{
            $data['transactionsPerSession_1'] = 0;
        }
        return $data;
    }

    function getWeekIn($year,$month,$week){
        $year   = intval($year);
        $month  = intval($month);

        $begin_date  = date($year.'-'.($month > 9 ? $month : '0'.$month).'-01');
        $end_date    = date('Y-m-t', strtotime($begin_date));
        $end_ex = explode('-',$end_day);
        $start_day = ($week * 7) - 6;
        if($week == 5){
            $end_day = $end_ex[2];
        }else{
            $end_day = $start_day + 6;
        }

        $crr_year   = intval(date('Y'));
        $crr_month  = intval(date('m'));
        $crr_day    = intval(date('d'));

        if($year < $crr_year){
            return 'past';
        }elseif($year == $crr_year){
            if($month < $crr_month){
                return 'past';
            }elseif($month == $crr_month){
                if($start_day <= $crr_day  && $crr_day <= $end_day){
                    return 'present';
                }elseif($end_day < $crr_day){
                    return 'past';
                }else{
                    return 'future';
                }
            }else{
                return 'future';
            }
        }else{
            return 'future';
        }
    }

    function iconStatus($actual,$target){
        $kpi_list = array(
            'transactionRevenue',
            'pageviews',
            'pageviewsPerSession',
            'sessions',
            'avgSessionDuration',
            'uniqueUsers',
            'transactions',
            'transactionsPerSession',
            'revenuePerTransaction',

            'transactions_1',
            'transactionsPerSession_1',
            'revenuePerTransaction_1',

            'bounceRate',
            'topBounceRate',
            'percentNewSessions'
        );

        $return = array();
        foreach ($kpi_list as $kpi_key) {
            if($kpi_key == 'bounceRate' || $kpi_key == "topBounceRate"){
                if($target[$kpi_key] == 0 || $target[$kpi_key] >= $actual[$kpi_key]){
                    $return[$kpi_key] = 1;
                }else{
                    $return[$kpi_key] = 0;
                }
            }else{
                if($target[$kpi_key] == 0 || $target[$kpi_key] <= $actual[$kpi_key]){
                    $return[$kpi_key] = 1;
                }else{
                    $return[$kpi_key] = 0;
                }
            }
        }
        return $return;
    }
}

?>