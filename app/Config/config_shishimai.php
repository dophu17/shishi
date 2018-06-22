<?php
$config['kpis'] = array(
	//1
	array(
		'key'			=>'transactionRevenue',
		'title'			=>'売上',
		'prospective'	=>1,
		'pre_char'		=>'¥',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-usd',
		'decimal'		=>0,
	),
	//2
	array(
		'key'			=>'pageviews',
		'title'			=>'PV数',
		'prospective'	=>1,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-eye',
		'decimal'		=>0,

	),
	//3
	array(
		'key'			=>'pageviewsPerSession',
		'title'			=>'一人あたりPV数',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-eye',
		'decimal'		=>2,
	),
	//4
	array(
		'key'			=>'sessions',
		'title'			=>'セッション数',
		'prospective'	=>1,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-user',
		'decimal'		=>0,
	),
	//5
	array(
		'key'			=>'avgSessionDuration',
		'title'			=>'平均セッション時間',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'time',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-clock-o',
		'decimal'		=>0,
	),
	//6
	array(
		'key'			=>'uniqueUsers',
		'title'			=>'UU数',
		'prospective'	=>1,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-user',
		'decimal'		=>0,
	),
	//7
	array(
		'key'			=>'transactions',
		'title'			=>'CV数',
		'prospective'	=>1,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-shopping-cart',
		'decimal'		=>0,
	),

	//8
	array(
		'key'			=>'transactionsPerSession',
		'title'			=>'CVR',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'%',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-shopping-cart',
		'decimal'		=>2,
	),

	//9
	array(
		'key'			=>'revenuePerTransaction',
		'title'			=>'客単価',
		'prospective'	=>0,
		'pre_char'		=>'¥',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-usd',
		'decimal'		=>0,
	),
	//10
	array(
		'key'			=>'bounceRate',
		'title'			=>'直帰率',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'%',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-sign-out',
		'decimal'		=>1,
	),
	//11
	array(
		'key'			=>'topBounceRate',
		'title'			=>'Top直帰率',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'%',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-sign-out',
		'decimal'		=>1,
	),
	//12
	array(
		'key'			=>'percentNewSessions',
		'title'			=>'新規セッション率',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'%',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-user',
		'decimal'		=>1,
	),


);


$config['kpis_1'] = array(
	//1
	array(
		'key'			=>'transactionRevenue',
		'title'			=>'売上',
		'prospective'	=>1,
		'pre_char'		=>'¥',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-usd',
		'decimal'		=>0,
	),
	//2
	array(
		'key'			=>'pageviews',
		'title'			=>'PV数',
		'prospective'	=>1,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-eye',
		'decimal'		=>0,

	),
	//3
	array(
		'key'			=>'pageviewsPerSession',
		'title'			=>'一人あたりPV数',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-eye',
		'decimal'		=>2,
	),
	//4
	array(
		'key'			=>'sessions',
		'title'			=>'セッション数',
		'prospective'	=>1,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-user',
		'decimal'		=>0,
	),
	//5
	array(
		'key'			=>'avgSessionDuration',
		'title'			=>'平均セッション時間',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'time',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-clock-o',
		'decimal'		=>0,
	),
	//6
	array(
		'key'			=>'uniqueUsers',
		'title'			=>'UU数',
		'prospective'	=>1,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-user',
		'decimal'		=>0,
	),
	//7
	array(
		'key'			=>'transactions_1',
		'title'			=>'CV数',
		'prospective'	=>1,
		'pre_char'		=>'',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>1,
		'icon'			=>'fa fa-shopping-cart',
		'decimal'		=>0,
	),

	//8
	array(
		'key'			=>'transactionsPerSession_1',
		'title'			=>'CVR',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'%',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-shopping-cart',
		'decimal'		=>2,
	),
	//9
	array(
		'key'			=>'revenuePerTransaction_1',
		'title'			=>'客単価',
		'prospective'	=>0,
		'pre_char'		=>'¥',
		'sub_char'		=>'',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-usd',
		'decimal'		=>0,
	),
	//10
	array(
		'key'			=>'bounceRate',
		'title'			=>'直帰率',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'%',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-sign-out',
		'decimal'		=>1,
	),
	//11
	array(
		'key'			=>'topBounceRate',
		'title'			=>'Top直帰率',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'%',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-sign-out',
		'decimal'		=>1,
	),
	//12
	array(
		'key'			=>'percentNewSessions',
		'title'			=>'新規セッション率',
		'prospective'	=>0,
		'pre_char'		=>'',
		'sub_char'		=>'%',
		'type_data'		=>'number',
		'allow_change_actual'=>0,
		'icon'			=>'fa fa-user',
		'decimal'		=>1,
	),
);

$config['slide_option'] = array(
	'1'=>'title_slide',
	'2'=>'chart_slide',
	'3'=>'kpi1_slide',
	'4'=>'image_slide',
	'5'=>'image_slide_title',
); 

$config['type_option'] = array(
	'm'=>'type_month',
	'w'=>'type_week',
);

$config['report_status'] = array(
	'0'=>'未公開',
	'1'=>'公開済み',
);

$config['site_satisfaction'] = array(
	'1'=>  array(
		'title' => '満足',
		'class' => 'icon-emojismile',
		'color' => '#d9b340'
	),
	'2'=>  array(
		'title' => '普通',
		'class' => 'icon-blankstare',
		'color' => '#6f7b83'
	),
	'3'=>  array(
		'title' => '最低',
		'class' => 'icon-sad',
		'color' => '#6f7b83'
	),
);

//report publish button
$config['report_publish_button'] = array(
	'0'=>'公開する',
	'1'=>'非公開する',
);

$config['upload_dir'] = array(
	'user'	=>'/uploads/ott/user',
	'report'=>'/uploads/ott/report',
);

$config['ga_email_service'] = "report@otetsudai-182102.iam.gserviceaccount.com";

//consfig user status
$config['user_status'] = array(
	'0'=>'InActive',
	'1'=>'Active',
	'2'=>'Requesting',
);

$config['day_in_week'] = array(
	'0'=>'日曜日',
	'1'=>'月曜日',
	'2'=>'火曜日',
	'3'=>'水曜日',
	'4'=>'木曜日',
	'5'=>'金曜日',
	'6'=>'土曜日'
);

$config['email_send'] = 'contact@web-otetsudai.jp';

//Advice Key In home and menu 2
$config['advice_note'] = array(
	'advice_note_1'=>'イベントカレンダー',
	'advice_note_3'=>'ミーティング日程',
	'advice_note_2'=>'販売計画',
	'advice_note_4'=>'目標（売上・KPI）',
	'advice_note_6'=>'更新／改善',
	'advice_note_7'=>'施策／開発',
	'advice_note_9'=>'認知／集客対策',
	'advice_note_5'=>'内部・外部対策',
	'advice_note_12'=>'ウェブ以外の施策',
	'advice_note_11'=>'役割分担',
	'advice_note_8'=>'施策／開発にかかるコスト',
	'advice_note_10'=>'認知／集客対策にかかるコスト',
	'advice_note_13'=>'自由記述１',
	'advice_note_14'=>'自由記述２',
	'advice_note_15'=>'自由記述３'
);
$config['advice_note_caculate'] = array(
    'advice_sessions'=>'セッション数',
    'advice_transactionsPerSession'=>'CVR',
    'advice_revenuePerTransaction'=>'客単価'
);

$config['cw_thank_group_id'] = '111476336';//Product : 98840465  Test : 98420955 //now:11111

$config['cw_alert_deadline_group_id'] = '111476336';//Product : 81441222 Test : 98420955 //now:111111

//Task module (cw group send msg to)
$config['cw_task_group_id'] = '111476336';//Product : 39317588 Test : 98420955 //now:98420955

