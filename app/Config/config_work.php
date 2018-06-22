<?php

/**
 * Workの項目はDBを使わずにConfigで処理
 *
 * @author ersj4
 */
define('JOB_POINT_OTHER', 18);

//*add 20141225
define('EC_KIND', 14);
define('MAX_ANSWER_POINT', 5);
//*add 20141225

# CONFIG設定
$config = array(

	# プロフィール登録 register4_profile
	'profile'	=> array(
			# 性別
			'sex' => array(
					1	=> '女性',
					2	=> '男性',
			),
			# 現在の職業
			'job' => array(
					1	=> 'Webディレクター',
					2	=> 'Webエンジニア',
					3	=> 'その他プログラマ・エンジニア',
					4	=> 'HTMLコーダー',
					5	=> 'サーバ・インフラエンジニア',
					6	=> 'クリエイティブディレクター',
					7	=> 'Webデザイナー',
					8	=> 'グラフィックデザイナー',
					9	=> 'イラストレーター',
					10	=> 'その他デザイナー',
					11	=> 'その他Web系専門職',
					12	=> '映像クリエイター',
					13	=> '音楽クリエイター',
					14	=> 'カメラマン',
					15	=> '声優・ナレーター',
					16	=> 'エディター',
					17	=> 'ライター',
					18	=> '翻訳家',
					19	=> '秘書・事務',
					20	=> '営業・企画',
					21	=> '会計・財務・経理',
					22	=> 'マーケティング',
					23	=> '広報・PR',
					24	=> '人事・労務',
					25	=> '法務',
					26	=> 'その他専門職',
					27	=> '学生',
					28	=> '主婦',
					29	=> '無職',
					30	=> 'その他',
			),
			# お住まいの地域
			'area' => array(
					1	=> '北海道',
					2	=> '青森県',
				 	3	=> '岩手県',
				 	4	=> '宮城県',
					5	=> '秋田県',
					6	=> '山形県',
					7	=> '福島県',
					8	=> '茨城県',
					9	=> '栃木県',
					10	=> '群馬県',
					11	=> '埼玉県',
					12	=> '千葉県',
					13	=> '東京都',
					14	=> '神奈川県',
					15	=> '新潟県',
					16	=> '富山県',
					17	=> '石川県',
					18	=> '福井県',
					19	=> '山梨県',
					20	=> '長野県',
					21	=> '岐阜県',
					22	=> '静岡県',
					23	=> '愛知県',
					24	=> '三重県',
					25	=> '滋賀県',
					26	=> '京都府',
					27	=> '大阪府',
					28	=> '兵庫県',
					29	=> '奈良県',
					30	=> '和歌山県',
					31	=> '鳥取県',
					32	=> '島根県',
					33	=> '岡山県',
					34	=> '広島県',
					35	=> '山口県',
					36	=> '徳島県',
					37	=> '香川県',
					38	=> '愛媛県',
					39	=> '高知県',
					40	=> '福岡県',
					41	=> '佐賀県',
					42	=> '長崎県',
					43	=> '熊本県',
					44	=> '大分県',
					45	=> '宮崎県',
					46	=> '鹿児島県',
					47	=> '沖縄県',
			),
			# ご住所
			'address' => null,
			# ご結婚
			'marriage' => array(
					1	=> '既婚',
					2	=> '未婚',
			),
			# お子様の人数
			'child' => array(
					1	=> '0名',
					2	=> '1名',
					3	=> '2名',
					4	=> '3名以上',
			),
			# お子様の年齢1
			'child_age1' => null,
			# お子様の年齢2
			'child_age2' => null,
			# お子様の年齢3
			'child_age3' => null,
			# お子様の年齢4
			'child_age4' => null,
			# お子様の年齢5
			'child_age5' => null,
			# 現在の状況
			'situation' => array(
					1 => '正社員',
					2 => '契約社員',
					3 => '派遣社員',
					4 => 'アルバイト・パート（シフト制）',
					5 => 'SOHO・フリーランス',
					6 => '法人',
					7 => '主婦（フルタイム希望）',
					8 => '主婦（パートタイム希望）',
					9 => '主婦（フリーランス・独立希望）',
					10 => '学生（フルタイム希望）',
					11 => '学生（アルバイト希望）',
					12 => '学生（フリーランス・独立希望）',
					13 => 'その他'

			),
			# お仕事選びのポイント
			'job_point' => array(
					1 	=> '在宅で働ける仕事であること',
					2	=> '時間を選んでできる仕事であること',
					3	=> '自分のしたい仕事であること',
					4	=> '自分の得意な仕事であること',
					5	=> 'お金をたくさん稼げる仕事であること',
					6	=> 'カンタンな仕事であること',
					7	=> 'やりがいのある仕事であること',
					8	=> 'スキルアップになる仕事であること',
					9	=> '仲間がいて楽しい仕事であること',
					10	=> 'じっくり長期的に働けること',
					11	=> '責任の軽い仕事であること',
					12	=> '単調ではない仕事であること',
					13	=> '頑張れば頑張った分成果が上がる仕事',
					14	=> '手に職がつく仕事であること',
					15	=> 'キャリアアップできる仕事であること',
					16	=> '再就職に活かせる仕事であること',
					17	=> 'イチから学べる仕事であること',
					18	=> 'そのほか（フリー記入）',
			),
			# お仕事選びのポイントそのほか（フリー記入）
			'job_point_etc' => null,
			# 仕事ができる時間
			'job_time' => null,
			# 仕事ができる曜日
			'job_week' => array(
					1	=> '月',
					2	=> '火',
					3	=> '水',
					4	=> '木',
					5	=> '金',
					6	=> '土',
					7	=> '日',
					8	=> '不定',
			),
			# 仕事ができる日数
			'job_day' => null,
			# 理想の報酬
			'monthly_salary' => null,
			# 時給
			'hour_salary' => null,
			# 理想の働き方
			'work' => null,
			# 専属契約の希望
			'contract' => array(
					1	=> '積極的に契約したい（優先的にご紹介します）',
					2	=> '希望の条件であれば契約したい',
					3	=> '今のところ考えていない',
					4	=> '専属契約は考えていない',
			),
			# 成果報酬型の希望
			'result_reward' => array(
					1	=> '積極的にチャレンジしたい（優先的にご紹介します）',
					2	=> '希望の条件であればチャレンジしたい',
					3	=> '今のところ希望しない',
					4	=> '成果報酬型は希望しない',
			),
			# 交流会・情報交換の希望
			'meeting' => array(
					1	=> '積極的に情報交換したい（メールを送付します）',
					2	=> '欲しい情報であれば情報交換したい',
					3	=> '今のところ希望しない',
					4	=> '情報交換は希望しない',
			),
	),

	# プロフィールのタイトル
	'profile_title' => array(
			'sex' 				=> '性別',
			'job'				=> '現在の職業',
			'area' 				=> 'お住まいの地域',
			'address' 			=> 'ご住所',
			'marriage' 			=> 'ご結婚',
			'child' 			=> 'お子様の人数',
			'child_age1' 		=> 'お子様の年齢1',
			'child_age2' 		=> 'お子様の年齢2',
			'child_age3' 		=> 'お子様の年齢3',
			'child_age4' 		=> 'お子様の年齢4',
			'child_age5' 		=> 'お子様の年齢5',
			'situation' 		=> '現在の状況',
			'job_point' 		=> 'お仕事選びのポイント',
			'job_point_etc' 	=> '仕事ができる時間',
			'job_time' 			=> '仕事ができる曜日',
			'job_week' 			=> '仕事ができる曜日',
			'job_day' 			=> '仕事ができる曜日',
			'monthly_salary' 	=> '理想の報酬',
			'hour_salary' 		=> '時給',
			'work' 				=> '理想の働き方',
			'contract' 			=> '専属契約の希望',
			'result_reward' 	=> '成果報酬型の希望',
			'meeting' 			=> '交流会・情報交換の希望',
	),

	# ワークスタイル&スキル登録 register5_work
	'workstyle'	=> array(
			# 技術系(システム・ネットワーク)
			'experience1' => array(
					1	=> 'システム開発（オープン・WEB・モバイル）',
					2	=> 'システム開発（汎用）',
					3	=> 'システム開発（制御・組み込み）',
					4	=> 'プロジェクトマネージャー・リーダー',
					5	=> 'パッケージ開発',
					6	=> 'ネットワーク・サーバ設計・構築',
					7	=> '運用・保守・監視・技術サポート',
					8	=> 'ITコンサルタント・プリセールス',
					9	=> '社内情報システム ',
					10	=> 'その他システム関連',
			),
			# クリエイティブ系(Web)
			'experience2' => array(
					1	=> 'Webプロデューサー・Webディレクター',
					2	=> 'Webデザイナー・HTMLコーダー',
					3	=> 'Webコンテンツ企画・Web編集',
			),
			# クリエイティブ系(ゲーム・マルチメディア)
			'experience3' => array(
					1	=> 'プロデューサー・ディレクター・プランナー ',
					2	=> 'ゲームプログラマー ',
					3	=> 'CGデザイナー',
					4	=> 'その他ゲーム・マルチメディア関連',

			),
			# クリエイティブ系(広告・アパレル・その他)
			'experience4' => array(
					1	=> '広告・出版・印刷関連クリエイティブ職  ',
					2	=> 'アパレル関連クリエイティブ職 ',

			),
			# 営業系
			'experience5' => array(
					1	=> '営業・企画営業（法人対象）',
					2	=> '営業・企画営業（個人対象）',
					3	=> 'ルートセールス・内勤営業 ',
					4	=> '海外営業',
					5	=> 'セールスエンジニア・技術営業 ',
					6	=> 'MR、MS',
					7	=> '営業マネージャー・営業企画',
					8	=> 'テレマーケティング・コールセンター関連',
					9	=> 'その他営業関連  ',
			),
			# 企画・マーケティング系
			'experience6' => array(
					1	=> '経営企画・事業企画・経営管理職',
					2	=> 'マーケ・販促・商品企画・宣伝・PR',
					3	=> 'Webマーケティング・SEO・SEM',
					4	=> 'カスタマーサポート・ユーザーサポート',
			),
			# 管理系
			'experience7' => array(
					1	=> '広報・IR ',
					2	=> '財務・会計・経理',
					3	=> '人事・総務 ',
					4	=> '特許・知的財産・法務',
					5	=> '購買・資材調達・物流・貿易事務',
			),
			# 事務・アシスタント系
			'experience8' => array(
					1	=> '一般事務・営業事務・秘書・通訳・翻訳 ',
					2	=> 'その他受付・企画・事務関連 ',
			),
			# サービス系人材/店舗/医療・福祉、他
			'experience9' => array(
					1	=> 'キャリアコンサルタント・コーディネイター',
					2	=> 'MD・バイヤー・店舗開発・SV',
					3	=> '店長・販売・店舗管理',
					4	=> '医療・福祉・介護関連',
					5	=> 'その他サービス関連 ',
			),
			# 専門系金融/不動産/コンサル/士業、他
			'experience10' => array(
					1	=> '金融関連営業職',
					2	=> '金融関連専門職',
					3	=> '不動産関連専門職',
					4	=> 'コンサルタント・その他専門職',
			),
			# 技術系(電気・電子・機械・半導体)
			'experience11' => array(
					1	=> '回路・システム・半導体設計 ',
					2	=> '組込み・制御設計電子機器・精密機器',
					3	=> '組込み・制御設計自動車・各種機械',
					4	=> '機械・機構設計電子機器・精密機器',
					5	=> '機械・機構設計自動車・各種機械',
					6	=> '生産技術・プロセス開発 ',
					7	=> '品質・生産管理電気・電子・機械・半導体',
					8	=> 'その他電気・電子・機械・半導体技術関連',
			),
			# 技術系(医療・化学・食品・化粧品)
			'experience12' => array(
					1	=> '医療・医薬系関連',
					2	=> '化粧品・食品・化学関連 ',
			),
			# 建築・土木・プラント系
			'experience13' => array(
					1	=> '施工管理',
					2	=> '測量・設計・積算',
					3	=> 'その他建築・土木・プラント関連',
			),
			# 以下からあてはまる項目をいくつでもお選びください。
			'condition' => array(
					1	=> '扶養枠内',
					2	=> '時間や仕事が選べる',
					3	=> '単発・短期',
					4	=> 'ブランクがあっても可能 ',
					5	=> '未経験でも可能',
					6	=> '正社員雇用あり',
					7	=> '月額固定収入',
					8	=> 'スキルアップできる（有料でも） ',
					9	=> '資格が取れる（有料でも）',
					10	=> '在宅ワーカーのネットワークが作れる',
					11	=> '在宅と通勤が日によって選べる',
					12	=> '研修制度あり ',
					13	=> '昇級・昇格あり',
					14	=> '打ち合わせなどの外出が無い（少ない）',
					15	=> '電話対応が無い（少ない）',
					16	=> 'Skypeやクラウドでやりとりできる ',
					17	=> 'そのほか（フリー記入）',
			),
			# そのほか（フリー記入）
			'condition_etc' => null,
			# パソコン
			'skill1_year' => null,
			'skill1_technical' => array(
					0	=> '経験なし',
					1	=> '初級（基本的な操作ができる）',
					2	=> '中級（様々なアプリケーションを問題なく扱える）',
					3	=> '上級（人に教えられるぐらい様々な機能を問題なく扱える）',
			),
			# オフィス系ソフト（WORD、EXCEL等
			'skill2_year' => null,
			'skill2_technical' => array(
					0	=> '経験なし',
					1	=> '初級（基本的な入力、編集、加工ができる）',
					2	=> '中級（表や図を挿入したり、計算式を入れたり、グラフが作成できる）',
					3	=> '上級（人に教えられるぐらい様々な機能を問題なく扱える）',
			),
			# デザイン系（photoshop、illustrator、fireworks等）アプリケーション
			'skill3_year' => null,
			'skill3_technical' => array(
					0	=> '経験なし',
					1	=> '初級（基本的なデザイン制作、編集、加工ができる）',
					2	=> '中級（思ったようなデザインができる）',
					3	=> '上級（ほとんどの機能を活用してデザインができる）',
			),
			# 技術系（html、CSS、プログラミング等）
			'skill4_year' => null,
			'skill4_technical' => array(
					0	=> '経験なし',
					1	=> '初級（基本的なコーディング、編集、加工ができる）',
					2	=> '中級（思ったようなコーディング、プログラミングができる）',
					3	=> '上級（ほとんどの技術を用いてコーディング、プログラミングができる）',
			),
			# マーケティング系（集客、販促等）
			'skill5_year' => null,
			'skill5_technical' => array(
					0	=> '経験なし',
					1	=> '初級（目的に応じて基本的な企画、作業、提案ができる）',
					2	=> '中級（目的に応じて多角的な企画、作業、提案ができる）',
					3	=> '上級（様々なマーケティング戦略を用いて高度な企画、作業、提案ができる）',
			),
			# アクセス解析系（データ分析、レポート報告等）
			'skill6_year' => null,
			'skill6_technical' => array(
					0	=> '経験なし',
					1	=> '初級（目的に応じて基本的な企画、作業、提案ができる）',
					2	=> '中級（目的に応じて多角的な企画、作業、提案ができる）',
					3	=> '上級（様々なマーケティング戦略を用いて高度な企画、作業、提案ができる）',
			),
			# コミュニケーション系（進行管理、提案、仲介、連携等）
			'skill7_year' => null,
			'skill7_technical' => array(
					0	=> '経験なし',
					1	=> '初級（目的に応じて基本的なコミュニケーションが取れる）',
					2	=> '中級（目的に応じて多角的なコミュニケーションが取れる）',
					3	=> '上級（総合的な状況判断のもと、リーダーとして的確なコミュニケーションが取れる）',
			),
			# コンサルティング系（提案、アドバイス、コンサルティング等）
			'skill8_year' => null,
			'skill8_technical' => array(
					0	=> '経験なし',
					1	=> '初級（目的に応じて基本的な提案、アドバイスができる）',
					2	=> '中級（目的に応じて多角的な提案、アドバイスができるほか、先々を見据えた対応ができる）',
					3	=> '上級（総合的な状況分析のもと、専門的な提案、アドバイス、コンサルティングが行なえる）',
			),
			# 語学系（英語、中国語、フランス語等）
			'skill9_year' => null,
			'skill9_technical' => array(
					0	=> '経験なし',
					1	=> '初級（基本的な会話や読解ができる）',
					2	=> '中級（目的に応じて語学力を活かすことができる）',
					3	=> '上級（ネイティブレベルで語学力を活かすことができる）',
			),
			# 自己PR
			'pr' => null,
	),

	# ワークスタイルのタイトル
	'workstyle_title' => array(
			'experience1' => '技術系(システム・ネットワーク)',
			'experience2' => 'クリエイティブ系(Web)',
			'experience3' => 'クリエイティブ系(ゲーム・マルチメディア)',
			'experience4' => 'クリエイティブ系(広告・アパレル・その他)',
			'experience5' => '営業系',
			'experience6' => '企画・マーケティング系',
			'experience7' => '管理系',
			'experience8' => '事務・アシスタント系',
			'experience9' => 'サービス系人材/店舗/医療・福祉、他',
			'experience10' => '専門系金融/不動産/コンサル/士業、他',
			'experience11' => '技術系(電気・電子・機械・半導体)',
			'experience12' => '技術系(医療・化学・食品・化粧品)',
			'experience13' => '建築・土木・プラント系',
			'condition' => '以下からあてはまる項目をいくつでもお選びください。',
			'skill1_technical' => 'パソコン',
			'skill2_technical' => 'オフィス系ソフト（WORD、EXCEL等）',
			'skill3_technical' => 'デザイン系（photoshop、illustrator、fireworks等）アプリケーション',
			'skill4_technical' => '技術系（html、CSS、プログラミング等）',
			'skill5_technical' => 'マーケティング系（集客、販促等）',
			'skill6_technical' => 'アクセス解析系（データ分析、レポート報告等）',
			'skill7_technical' => 'コミュニケーション系（進行管理、提案、仲介、連携等）',
			'skill8_technical' => 'コンサルティング系（提案、アドバイス、コンサルティング等）',
			'skill9_technical' => ' 語学系（英語、中国語、フランス語等）',
	),

	// サイト登録
	'style' => array(
		's_name'	=> false,
		'url'		=> false,
		'target'	=> array(
			1	=> 'B to B',
			2	=> 'B to C',
			3	=> '両方・どちらでもない',
		),
	),
);


