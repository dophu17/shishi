<?php
App::uses('Component', 'Controller');

class OttClientComponent extends Component {
	public function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    function have_error_site($site_name, $error_site_name, $site_url, $error_site_url, $site_description, $error_site_description, $site_ga_view_id, $error_site_ga_view_id, $site_manage_user, $error_site_manage_user){
        $error = array();

        if(empty($site_name)){
            $error_site_name = '(*) 必須!';
            $error['error_site_name'] = $error_site_name;
        } 
        if(empty($site_url)){
            $error_site_url = '(*) 必須!';     
            $error['error_site_url'] = $error_site_url;
        } else {
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$site_url)) {
                $error_site_url = "(*) 無効"; 
                $error['error_site_url'] = $error_site_url;
            }    
        }
        if(empty($site_description)){
            $error_site_description = '(*) 必須!';
            $error['error_site_description'] = $error_site_description;
        }       
        //Tùng comment => for allow empty ga view_id
        /*if(!is_numeric($site_ga_view_id)){
            $error_site_ga_view_id = '(*) Google AnalyticsビューIDは数値です!';
            $error['error_site_ga_view_id'] = $error_site_ga_view_id;
        } */
        // if(empty($site_manage_user) || gettype($site_manage_user) != 'integer' ){
        //     $error_site_manage_user = '(*) 必須!'; 
        //     $error['error_site_manage_user'] = $error_site_manage_user;
        // }
        if(!empty($error)){
            return $error;
        }else{
            return false;
        }
    }

    function have_error_contract($start_day, $error_start_day, $end_day, $error_end_day){
        $error = array();
            
        if(empty($start_day)){
            $error_start_day = '(*) 必須!';
            $error['error_start_day'] = $error_start_day;
        }
        if(!$this->compare_date($start_day, $end_day)){
        	$error_end_day = '(*) 無効!';
        	$error['error_end_day'] = $error_end_day;
        }
        if(empty($end_day)){
            $error_end_day = '(*) 必須!';
            $error['error_end_day'] = $error_end_day;
        }
        if(!empty($error)){
            return $error;
        }else{
            return false;
        }
    }

    function compare_date($dateA, $dateB){
	    return (strtotime($dateB) > strtotime($dateA));
    }

    function return_day($start_day,$end_day,$month,$year,$number_in_week){
        $arr = array();
        for($i = $start_day; $i < ($end_day + 1); $i++){
            $date = $i. '-' . $month . '-' . $year;
            array_push($arr,$date);
        }

        $deadline;
        foreach($arr as $key => $val){
            if(date('w', strtotime($val)) == $number_in_week){
                $deadline = $val;
            } 
        }
        return $deadline;
    }

    function return_dead_day($start_day,$end_day,$month,$year,$number_in_week, $last_day_of_month){
        $dead_month = ($month + 1) > 12 ? 1 : ($month + 1);
        $dead_year = ($month + 1) > 12 ? ($year + 1) : $year ;

        $deadline = $this->return_day($start_day,$end_day,$month,$year,$number_in_week);
        $dead_day = explode('-', $deadline)[0]; //date of current week
        $dead_day_next_week = $dead_day + 7; //date of next week

        if($start_day == 22 && ($dead_day_next_week > $last_day_of_month)){ //week 4 
            $dead_day_next_week = $dead_day_next_week - $last_day_of_month;
            $dead_month;
            $dead_year;
            return array($dead_day_next_week, $dead_month, $dead_year);
        } else if($start_day == 29){ //week 5
            $dead_day_next_week = $this->return_day(1, 7, $dead_month, $dead_year, $number_in_week);
            $dead_day_next_week = explode('-', $dead_day_next_week)[0];
            $dead_month;
            $dead_year;
            return array($dead_day_next_week, $dead_month, $dead_year);
        } else {
            $dead_day_next_week;
            $month;
            $year;
            return array($dead_day_next_week, $month, $year);
        }
    }

    function return_start_end_day($week, $last_day_of_month){
        switch($week){
            case 1:
            $start_day = 1;
            $end_day = 7;
            break;
            case 2:
            $start_day = 8;
            $end_day = 14;
            break;
            case 3:
            $start_day = 15;
            $end_day = 21;
            break;
            case 4:
            $start_day = 22;
            $end_day = 28;
            break;
            case 5:
            $start_day = 29;
            $end_day = $last_day_of_month;
            break;
        }
        return array($start_day, $end_day);
    }

    function return_week_current($day){
        if($day >=1 && $day <= 7){
            $week_current = 1;
        } else if($day >=8 && $day <= 14){
            $week_current = 2;
        } else if ($day >=15 && $day <= 21){
            $week_current = 3;
        } else if ($day >=22 && $day <= 28){
            $week_current = 4;
        } else {
            $week_current = 5;
        }
        return $week_current;
    }

    function format_ymd($string){
        $x = explode('/',$string);
        $y = $x[2].$x[0].$x[1];
        return $y;
    }

    function arr_contract($arr){
        $arr_contract = array();
        foreach($arr['start_day'] as $id => $start_day)
        {
            $end_day    = $arr['end_day'][$id];   
            $plan       = $arr['plan'][$id];  
            $price      = $arr['price'][$id]; 
            $id         = $arr['SsmContract_id'][$id];
            $arr_contract[] = [
                'start_day' => $start_day, 
                'end_day'   => $end_day, 
                'plan_id'   => $plan, 
                'price'     => $price, 
                'SsmContract_id' => $id
            ];
        }
        return $arr_contract;
    }

    function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function validate_contract_server($arr_contract, $validate_contract){
        for($ci = 0; $ci < count($arr_contract); $ci ++){

            if( !($this->validateDate($arr_contract[$ci]['start_day'], "Y/m/d"))
                || !($this->validateDate($arr_contract[$ci]['end_day'], "Y/m/d"))
            ){
                return $validate_contract = true;
                break;
            }

            if ($arr_contract[$ci]['start_day'] >= $arr_contract[$ci]['end_day']){
                return $validate_contract = true;
                break;
            }
            if ($ci < (count($arr_contract) - 1)){
                for($cj = $ci + 1; $cj < count($arr_contract); $cj ++){
                    //start_day
                    if (($arr_contract[$ci]['start_day'] >= $arr_contract[$cj]['start_day']) &&
                        ($arr_contract[$ci]['start_day'] <= $arr_contract[$cj]['end_day'])){
                        return $validate_contract = true;
                        break;
                    }
                    //end_day
                    if( ($arr_contract[$ci]['end_day'] >= $arr_contract[$cj]['start_day']) &&
                        ($arr_contract[$ci]['end_day'] <= $arr_contract[$cj]['end_day']) ){
                        return $validate_contract = true;
                        break;
                    }
                }
            }
        }
    }

    function validate_chatwork($cw_api, $chatwork_id){
        $error_chatwork;

        if( !isset($cw_api) || $cw_api == "" ){
            return $error_chatwork = 'api';
        }

        $opt_check = array(
            CURLOPT_URL => "https://api.chatwork.com/v2/rooms",
            CURLOPT_HTTPHEADER => array( 'X-ChatWorkToken: ' . $cw_api ),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_POST => FALSE,
            );

        $ch_check = curl_init();
        curl_setopt_array( $ch_check, $opt_check );
        $res_check = curl_exec( $ch_check );
        curl_close( $ch_check );

        $convert_json = json_decode($res_check);

        if( isset($convert_json->errors[0]) == 'Invalid API Token'){
            return $error_chatwork = 'api';
        } else {
            if( !isset($chatwork_id) || $chatwork_id == "" ){
                return $error_chatwork = 'room_id_empty';
            } else {
                $room_id = array();
                foreach($convert_json as $key => $room){
                   $room_id[$key] = $room->room_id;
                }

                if(!in_array($chatwork_id, $room_id)){
                    return $error_chatwork = 'room_id_wrong';
                } else {
                    return $error_chatwork = 'room_id_true';
                }
            }
        }      
    }

    function have_error_site_price($price,$price_per_hour){
        if($price =="" && $price_per_hour == ""){
            return false;
        }
        
        if((is_numeric($price) && $price >= 0 && is_numeric($price_per_hour) && $price_per_hour >= 0)){
            return false;
        }else{
            return true;
        }
    }
}

?>