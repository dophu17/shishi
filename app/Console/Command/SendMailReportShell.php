<?php
App::uses('Shell', 'Console');
App::uses('CakeEmail', 'Network/Email');
class SendMailReportShell extends Shell {
    public $uses = array('Client','OrderInfo','SsmReportWeek','SsmReport');
    public function main() {
        Configure::load('config_shishimai');
        $email_send = Configure::read('email_send');
        // */ * * * * * /home/centos/public_html/projects/shishimai_dev/app/Console/cake send_mail_report
        // date_default_timezone_set('Asia/Ho_Chi_Minh'); 
        // date_default_timezone_set('Asia/Tokyo'); 
        $now_year   = date('Y');
        $now_month  = intval(date('m'));
        $now_day    = intval(date('d'));
        $now_hour   = intval(date('H'));

        $this->out("Current : $now_year / $now_month / $now_day : $now_hour");
        //ssm_sites.id = 12 AND

        //Send mail to manage site user
        /*$sql = '
            SELECT week.year, week.month, week.day, week.hour, week.report_id, ssm_users.username, ssm_users.first_name, ssm_users.last_name, ssm_reports.mail_sended, ssm_sites.site_name
            FROM ssm_report_weeks AS week
            JOIN ssm_reports ON ssm_reports.id = week.report_id
            JOIN ssm_sites ON ssm_sites.id = ssm_reports.site_id
            JOIN ssm_users ON ssm_users.id = ssm_sites.site_manage_user
            WHERE
            week.status = 0 AND ssm_reports.mail_sended = 0 AND
            (
                week.year < '.$now_year.' OR
                (week.year = '.$now_year.' AND week.month < '.$now_month.') OR
                (week.year = '.$now_year.' AND week.month = '.$now_month.' AND week.day < '.$now_day.') OR
                (week.year = '.$now_year.' AND week.month = '.$now_month.' AND week.day = '.$now_day.' AND week.hour <= '.$now_hour.')
            )
        ';*/

        //Send mail to report assigned user
        $sql_2 = '
            SELECT week.year, week.month, week.day, week.hour, week.report_id, week.user_id
                , ssm_users.username, ssm_users.first_name, ssm_users.last_name
                , ssm_sites.site_name, ssm_sites.chatwork_id, ssm_sites.chatwork_api

                , ssm_reports.mail_sended,ssm_reports.year as title_year,ssm_reports.month as title_month, ssm_reports.week as title_week
            FROM ssm_report_weeks AS week 
            JOIN ssm_reports ON ssm_reports.id = week.report_id 
            JOIN ssm_sites ON ssm_sites.id = ssm_reports.site_id
            JOIN ssm_users ON ssm_users.id = week.user_id
            WHERE 
                week.status = 0 AND ssm_reports.mail_sended = 0 AND 
                week.year = '.$now_year.' AND week.month = '.$now_month.' AND week.day = '.$now_day.' AND week.hour = '.$now_hour.'
            ';

        $this->out($sql_2);

        $info_deadline_sql = $this->SsmReportWeek->query($sql_2);

        $info_deadline = [];

        foreach($info_deadline_sql as $key => $deadline){
            $year              = $deadline['week']['year'];
            $month             = $deadline['week']['month'];
            $day               = $deadline['week']['day'];
            $hour              = $deadline['week']['hour'];
            $report_id_of_site = $deadline['week']['report_id'];
            $username          = $deadline['ssm_users']['username'];
            $first_name        = $deadline['ssm_users']['first_name'];
            $last_name         = $deadline['ssm_users']['last_name'];
            $mail_sended       = $deadline['ssm_reports']['mail_sended'];
            $site_name         = $deadline['ssm_sites']['site_name'];
            $chatwork_id       = $deadline['ssm_sites']['chatwork_id'];
            $chatwork_api      = $deadline['ssm_sites']['chatwork_api'];
            $title_year        = $deadline['ssm_reports']['title_year'];
            $title_month       = $deadline['ssm_reports']['title_month'];
            $title_week        = $deadline['ssm_reports']['title_week'];
            $title_day         = ($title_week * 7) - 6 ;

            $info_deadline[$key] = array(
                'month'             => $month,
                'day'               => $day,
                'hour'              => $hour,
                'mail'              => $username,
                'report_id_of_site' => $report_id_of_site,
                'first_name'        => $first_name,
                'last_name'         => $last_name,
                'mail_sended'       => $mail_sended,
                'chatwork_id'       => $chatwork_id,
                'chatwork_api'      => $chatwork_api,
                'site_name'         => $site_name,
                'title_year'        => $title_year,
                'title_month'       => $title_month,
                'title_week'        => $title_week,
                'title_day'         => $title_day
            );

            $this->out("$year/$month/$day : $hour ,Email : $username ,Reprot_id : $report_id_of_site,Name: $first_name $last_name,Site name : $site_name");
        }

        foreach($info_deadline as $key => $val){
            //Send mail
            $url = "https://web-otetsudai.jp/report/OttReport/edit_week/report_id:" . $val['report_id_of_site'];
            $Email = new CakeEmail();
            $Email->config('default');
            $Email
            ->subject('【Otetsudai】')
            ->emailFormat('html')
            ->template('alert_deadline_report')
            ->to($val['mail'])
            ->from($email_send)
            ->viewVars(array(
                'email'         => $val['mail'],
                'link'          => $url,
                'first_name'    => $val['first_name'],
                'last_name'     => $val['last_name'],
                'month'         => $val['month'],
                'day'           => $val['day'],
                'hour'          => $val['hour'],
                'site_name'     => $val['site_name'],
                'title_day'     => $val['title_day'],
                'title_month'   => $val['title_month'],
            ));
            if ($Email->send ()) {
                $this->SsmReport->id = $val['report_id_of_site'];
                $update_mail_sended = array(
                    'mail_sended' => 1
                );
                $this->SsmReport->set($update_mail_sended);
                $this->SsmReport->save();
            }
            unset($Email);

            //Send Chatwork
            $val['link'] = $url;
            $this->postChatwork($val);
        }
        $this->out('Done.');
    }

    public function postChatwork($info) {
        Configure::load('config_shishimai');
        // Only run on product server (prd cw group : 81441222)
        $chatwork_id  = Configure::read('cw_alert_deadline_group_id');
        $chatwork_api = $info['chatwork_api'];
        //check chatword_id in [room_id]
        $opt_check = array(
            CURLOPT_URL => "https://api.chatwork.com/v2/rooms",
            CURLOPT_HTTPHEADER => array('X-ChatWorkToken: ' . $chatwork_api),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_POST => FALSE,
        );
        $ch_check = curl_init();
        curl_setopt_array($ch_check, $opt_check);
        $res_check = curl_exec($ch_check);
        curl_close($ch_check);
        $convert_json = json_decode($res_check);
        $room_id = array();
        foreach ($convert_json as $key => $room) {
            $room_id[$key] = $room->room_id;
        }
        if (in_array($chatwork_id, $room_id)) {
            //Get content of message
            $content = $this->prepareChatworkContent($info);
            if ($content) {
                // Input value after 'rid' on url that you want send file
                $rid   = $chatwork_id;
                //Input API key
                $token = $chatwork_api;
                $data = array(
                    'body' => $content
                );
                $opt = array(
                    CURLOPT_URL => "https://api.chatwork.com/v2/rooms/{$rid}/messages",
                    CURLOPT_HTTPHEADER => array('X-ChatWorkToken: ' . $token),
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_POST => TRUE,
                    CURLOPT_POSTFIELDS => http_build_query($data, '', '&')
                );
                $ch = curl_init();
                curl_setopt_array($ch, $opt);
                curl_exec($ch);
                curl_close($ch);
                $this->out('Send to chatwork successfully, report_id = ' . $info['report_id_of_site']);
            } else {
                $this->out('Error: report_id = ' . $info['report_id_of_site']);
            }
        } else {
            $this->out('Error: error_room_id');
        }
    }

    public function prepareChatworkContent($info) {
        $content = 'アカウント　' . $info['first_name'] . ' ' . $info['last_name'] . '　様';
        $content .= "\n";
        $content .= '以下のレポートの入力期限が過ぎております。ご確認ください。';
        $content .= "\n";

        $content .= '●' . $info['site_name'] . ' ' . $info['link'] . ' ' . $info['title_month'] . '月' . $info['title_day'] . '日週レポート'; //ママワークス
        return $content;
    }
}