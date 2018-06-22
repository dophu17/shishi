<?php
App::uses('AppHelper', 'View/Helper');

class ShishimaiHelper extends AppHelper {
	public $helpers = array('Html');

    public function changeParamUrl($url,$key,$value) {
        $ex = explode('?',$url);
        $ex[1] = str_replace('amp;','',$ex[1]);
        parse_str($ex[1], $param);
        $param[$key] = $value;
        return $ex[0] ."?".http_build_query($param);
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

    public function displayValueTarget($value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        $value = trim($value);
        if($value == "-" || $value == "0" || $value == 0){
            return "-";
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }


    public function displayValue($value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        $value = trim($value);

        if($value == "-"){
            return "-";
        }elseif($value == 0){
            if($type == 'number'){
                return $pre_char.number_format(0,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",0).$sub_char;
            }else{
                return $pre_char."0".$sub_char;
            }
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }

    	/*if($value == "-" || $value == "0" || $value == 0){
    		return "-";
    	}else{
    		if($type == 'number'){
    			return $pre_char.number_format($value,$decimal_number).$sub_char;
    		}elseif($type == 'time'){
    			return $pre_char.gmdate("i:s",$value).$sub_char;
    		}else{
    			return $pre_char.$value.$sub_char;
    		}
    	}*/
    }

    public function displayActualKPIpage($key_api,$value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        $value = trim($value);

        if(in_array($key_api,array('pageviewsPerSession','avgSessionDuration','transactionsPerSession_1','transactionsPerSession','revenuePerTransaction_1','revenuePerTransaction','bounceRate','topBounceRate','percentNewSessions'))){
            if($value == 0){
                return "-";
            }
        }

        if($value == "-"){
            return "-";
        }elseif($value == 0){
            if($type == 'number'){
                return $pre_char.number_format(0,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",0).$sub_char;
            }else{
                return $pre_char."0".$sub_char;
            }
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }

    //Function display actual value of revenuePerTransaction_1,revenuePerTransaction ,transactionsPerSession_1 ,transactionsPerSession
    public function displayActualKPIpageV2($key_kpi,$data,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        if($key_kpi == 'transactionsPerSession_1'){
            if($data['sessions'] == 0 || $data['sessions'] == '0'){
                return '-';
            }else{
                if($data['transactions_1'] == 0 || $data['transactions_1'] == '0'){
                    return number_format(0,$decimal_number).$sub_char;
                }else{
                    return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
                }
            }
        }elseif($key_kpi == 'transactionsPerSession'){
            if($data['sessions'] == 0 || $data['sessions'] == '0'){
                return '-';
            }else{
                if($data['transactions'] == 0 || $data['transactions'] == '0'){
                    return number_format(0,$decimal_number).$sub_char;
                }else{
                    return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
                }
            }
        }elseif($key_kpi == 'revenuePerTransaction_1'){
            if($data['transactions_1'] == 0 || $data['transactions_1'] == '0'){
                return '-';
            }else{
                if($data['transactionRevenue'] == 0 || $data['transactionRevenue'] == '0'){
                    return number_format(0,$decimal_number).$sub_char;
                }else{
                    return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
                }
            }
        }elseif($key_kpi == 'revenuePerTransaction'){
            if($data['transactions'] == 0 || $data['transactions'] == '0'){
                return '-';
            }else{
                if($data['transactionRevenue'] == 0 || $data['transactionRevenue'] == '0'){
                    return number_format(0,$decimal_number).$sub_char;
                }else{
                    return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
                }
            }
        }else{
            return $this->displayActualKPIpage($key_kpi,$data[$key_kpi],$type,$pre_char,$sub_char,$decimal_number);
        }
    }

    public function displayZeroDashTargetHome($value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        $value = trim($value);

        if($value == "-"){
            return "-";
        }elseif($value == 0){
            return "-";
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }

    public function displayZeroDashTargetReport($value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        $value = trim($value);

        if($value == "-"){
            return "-";
        }elseif($value == 0){
            return "-";
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }

    public function displayValueBigNumberReport($value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){

        if($value == "-" || $value == "0" || $value == 0){
            return "0";
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }

    //Function use in home
    public function displayZeroDash($value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        $value = trim($value);

        if($value == "-"){
            return "-";
        }elseif($value == 0){
            if($type == 'number'){
                return $pre_char.number_format(0,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",0).$sub_char;
            }else{
                return $pre_char."0".$sub_char;
            }
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }

    }

    public function displayValueBigNumber($value,$type='number',$pre_char = "",$sub_char="",$decimal_number = 2){
        if($value == "-" || $value == "0" || $value == 0){
            return "0";
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value,$decimal_number).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }

    public function displayValueExport($value,$type='number',$pre_char = "",$sub_char=""){
        if($value == "-"){
            return $value;
        }else{
            if($type == 'number'){
                return $pre_char.number_format($value).$sub_char;
            }elseif($type == 'time'){
                return $pre_char.gmdate("i:s",$value).$sub_char;
            }else{
                return $pre_char.$value.$sub_char;
            }
        }
    }


    public function showTime($value,$sub_fix=array('y'=>'年','m'=>'月','d'=>'日')){
        $ex = explode(" ",$value);
        $ex1 = explode('-',$ex[0]);
        return $ex1[0].$sub_fix['y'].$ex1[1].$sub_fix['m'].$ex1[2].$sub_fix['d'];
    }

    function show_data($string,$default_value=""){
        if(CakeRequest::data($string)){
            return CakeRequest::data($string);
        }else{
            return $default_value;
        }
    }

    function show_date($value){
       $val = str_replace('-','/',$value);
       return $val;
    }

    function return_link($string){
        preg_match_all('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $string, $match);

        $match_link = array_unique($match[0]);
        foreach($match_link as $key => $url){
            $new_text_url = "<a href=".$url." target='_blank'>".$url."</a>" ;
            $string = str_replace($url, $new_text_url, $string);
        }
        return $string;
    }

    function rmTag($string){
       return strip_tags($string,'<p><i><br>');
    }

    function add_zero_before_day($day){
        if(intval($day) < 10){
            return 0 . $day ;
        } else {
            return $day;
        }
    }

    function getOptionYear($default_value = null){
        $now_y = date('Y');
        if(!$default_value) $default_value = $now_y;
        $return = "";
        for($i = 2010;$i < ($now_y + 5);$i++) {
            if($i == $default_value){
                $selected = 'selected="true"';
            }else{
                $selected = '';
            }
            $return .= '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
        }
        return $return;
    }

    function getOptionMonth($default_value = null){
        $now_m = date('m');
        if(!$default_value) $default_value = $now_m;
        $return = "";
        for($i = 1;$i < 13;$i++) {
            if($i == $default_value){
                $selected = 'selected="true"';
            }else{
                $selected = '';
            }
            $return .= '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
        }
        return $return;
    }

    function buildOptionSite($listSiteIDInfo,$site_id){
        $return = "";
        if(!empty($listSiteIDInfo)){
            foreach ($listSiteIDInfo as $value) {
                if($value['id'] == $site_id){
                    $selected = 'selected="true"';
                }else{
                    $selected = '';
                }

                $return .= '<option value="'.$value['id'].'" '.$selected.'>'.$value['site_name'].'</option>';
            }
        }
        return $return;
    }
}

?>