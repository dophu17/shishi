<?php
App::uses('AppHelper', 'View/Helper');

class JapaneseHelper extends AppHelper {
/**
*
* Format input value to japanese money format
*
* @param string/int $number
* @return string
*/
    public function moneyFormat($number) {
        $tenHundred = intval($number / 10000);
        if ($tenHundred > 0) {
            $overbalance = $number % 10000;
            $result = $tenHundred . '万';
            $result .= $overbalance? number_format($overbalance): '';
        } else {
            $result = $number;
        }

        $result .= '円';
        return $result;
    }
}