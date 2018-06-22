<?php
class PaymentConstants {
	const PAYMENT_URL = 'https://secure.telecomcredit.co.jp/inetcredit/secure/order.pl';
	const PAYMENT_CONFIRM_SERVER = 'secure.telecomcredit.co.jp';
	//const CLIENT_IP = '47355';
	const CLIENT_IP = '00237';

	//*henobu add
	//会員を全員プレミアムへ
	/*
	const STATUS_FREE = 0;
	const STATUS_STANDARD = 1;
	const STATUS_PREMIUM = 2;
	*/
	const STATUS_FREE = 3;
	const STATUS_STANDARD = 1;
	const STATUS_PREMIUM = 0;
	//*henobu add

	const TITLE_CHANGE_FROM_STANDARD_TO_PREMIUM = '980円->4980円';
	const TITLE_CHANGE_FROM_PREMIUM_TO_STANDARD = '4980円->980円';

	const PLAN_STANDARD = 1;
	const PLAN_PREMIUM = 2;

	const PLAN_STANDARD_NAME = 'スタンダード会員';
	//const PLAN_STANDARD_NAME = '1日会員';
	const PLAN_PREMIUM_NAME = 'プレミアム会員';

	const PLAN_STANDARD_MONTHLY_FEE = 980;
	//const PLAN_STANDARD_MONTHLY_FEE = 500;
	const PLAN_PREMIUM_MONTHLY_FEE = 4980;

	const PLAN_STANDARD_REBILL_PARAM_ID = '1month980yen';
	//const PLAN_STANDARD_REBILL_PARAM_ID = '1day500yen';
	const PLAN_PREMIUM_REBILL_PARAM_ID = '1month4980yen';
}

class OrderInfoStatus {
	const PENDING = 0;
	const SUCCESS = 1;
	const FALSE = 2;
}

class PaymentRebillParam {
	const ONCE = 1;
	const MONTHLY = 2;
}

class UserType {
	const CLIENT = 1;
	const WORKER = 2;
}

class JobPaymentStatus {
	const UNPAID = 1;
	const PENDING = 2;
	const SUCCESS = 3;
}

$config = array(
	'userTables' => array(
		UserType::CLIENT => array(
			'type' => 'Client',
			'table_name' => 'clients',
			'primary_key' => 'client_id',
			'name' => 'c_name',
			'status' => 'c_status',
		),
		UserType::WORKER => array(
			'type' => 'Worker',
			'table_name' => 'workers',
			'primary_key' => 'id',
			'name' => 'w_name',
			'status' => 'w_status',
		),
	),
	'status' => array(
		OrderInfoStatus::PENDING => 'pending',
		OrderInfoStatus::SUCCESS => 'success',
		OrderInfoStatus::FALSE => 'false',
	),
	'henobuOptions' => array(
		1 => array(
			'id' => 1,
			'title' => 'クリエイティブディレクション代行（1ヶ月）',
			'comment' => 'お客様に替わってワーカーと連携し作業進行ディレクションをはじめ、より効果アップにつながるクリエイティブディレクションを行ないます。',
			'amount' => 15000,
		),
		2 => array(
			'id' => 2,
			'title' => '効果アップ戦略プランニング',
			'comment' => 'お客様のサイトを効果的に改善する戦略を立案します。通常100000円ほどかかる業務ですが、診断テストを受けて頂いている場合はこちらの費用で対応が可能です。',
			'amount' => 35000,
		),
		3 => array(
			'id' => 3,
			'title' => '年間シミュレーション戦略設計',
			'comment' => 'お客様のサイトの総合判定結果を元に、１年間で目標達成をするための施策立案、戦略設計、タスク設計、作業スタッフアサイン、運営代行プランの具現化を致します。Skypeミーティング、対面ミーティングを含みます。',
			'amount' => 75000,
		),
	),
	'seminarOptions' => array(
		1 => array(
			'id' => 1,
			'title' => '［デザイン］基本・初級課題の添削アドバイス',
			'comment' => '基本課題および初級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		2 => array(
			'id' => 2,
			'title' => '［デザイン］中級課題の添削アドバイス',
			'comment' => '中級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は10回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 25000,
		),
		3 => array(
			'id' => 3,
			'title' => '［デザイン］上級課題の添削アドバイス',
			'comment' => '上級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は20回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 50000,
		),
		4 => array(
			'id' => 4,
			'title' => '［デザイン］課題添削セット',
			'comment' => '基本・初級・中級・上級の４つの課題に対する添削アドバイスのeラーニングを受講できます。課題に対する添削は無制限です。eラーニング受講可能期間は3ヶ月です。（補修期間を別途設定できます。）',
			'amount' => 80000,
		),
		5 => array(
			'id' => 5,
			'title' => '［デザイン］補習eラーニング',
			'comment' => '一度添削アドバイスした課題に関して補習を行ないます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		6 => array(
			'id' => 6,
			'title' => '［ライティング］基本・初級課題の添削アドバイス',
			'comment' => '基本課題および初級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		7 => array(
			'id' => 7,
			'title' => '［ライティング］補習eラーニング',
			'comment' => '一度添削アドバイスした課題に関して補習を行ないます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		8 => array(
			'id' => 8,
			'title' => '［マーケティング］基本・初級課題の添削アドバイス',
			'comment' => '基本課題および初級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		9 => array(
			'id' => 9,
			'title' => '［マーケティング］中級課題の添削アドバイス',
			'comment' => '中級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は10回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 25000,
		),
		10 => array(
			'id' => 10,
			'title' => '［マーケティング］上級課題の添削アドバイス',
			'comment' => '上級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は20回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 50000,
		),
		11 => array(
			'id' => 11,
			'title' => '［マーケティング］課題添削セット',
			'comment' => '基本・初級・中級・上級の４つの課題に対する添削アドバイスのeラーニングを受講できます。課題に対する添削は無制限です。eラーニング受講可能期間は3ヶ月です。（補修期間を別途設定できます。）',
			'amount' => 80000,
		),
		12 => array(
			'id' => 12,
			'title' => '［マーケティング］補習eラーニング',
			'comment' => '一度添削アドバイスした課題に関して補習を行ないます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		13 => array(
			'id' => 13,
			'title' => '［アクセス解析］基本・初級課題の添削アドバイス',
			'comment' => '基本課題および初級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		14 => array(
			'id' => 14,
			'title' => '［アクセス解析］補習eラーニング',
			'comment' => '一度添削アドバイスした課題に関して補習を行ないます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		15 => array(
			'id' => 15,
			'title' => '［コーディング］基本・初級課題の添削アドバイス',
			'comment' => '基本課題および初級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		16 => array(
			'id' => 16,
			'title' => '［コーディング］補習eラーニング',
			'comment' => '一度添削アドバイスした課題に関して補習を行ないます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		17 => array(
			'id' => 17,
			'title' => '［ディレクション］基本・初級課題の添削アドバイス',
			'comment' => '基本課題および初級課題の添削アドバイスのeラーニングを受講できます。課題に対する添削は5回です。eラーニング受講可能期間は1ヶ月です。',
			'amount' => 12500,
		),
		18 => array(
			'id' => 18,
			'title' => 'ネットショップ運営方法',
			'comment' => 'ネットショップ運営に関するeラーニング動画を閲覧できます。受講可能期間は1ヶ月です。',
			'amount' => 12000,
		),
	),
	'site_limited' => array(
		PaymentConstants::STATUS_FREE => 1,
		PaymentConstants::STATUS_STANDARD => 5,
	),
	'status_job_payment' => array(
		JobPaymentStatus::UNPAID => 'unpaid',
		JobPaymentStatus::PENDING => 'pending',
		JobPaymentStatus::SUCCESS => 'success',
	),
);