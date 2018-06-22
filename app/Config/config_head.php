<?php

/**
 * ページのtitle,meta,description,keyowrdをConfigで処理
 *
 * @author ersj2
 */

# Titleタグの後ろに入るサイト名（例、<title>基本情報登録 - TEAM WORK</title>）
define('SITE_NAME', 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］');



# CONFIG設定
$config = array(
	# ページタイトル
	'config_title' => array(
		# class名
		'pages'	=> 'TEAMWORKERS（チームワーカーズ）',
		'workers'	=> 'ワーカー',
		'clients'	=> 'クライアント',
	),

	# portal
	'config_pages'   => array(
		# action名
		'about' => array(
			'title'         => 'TEAMWORKERSとは？',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'message' => array(
			'title'         => 'メッセージ ',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'contact' => array(
			'title'         => 'お問い合わせ ',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'privacy' => array(
			'title'         => 'プライバシーポリシー ',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'agreement' => array(
			'title'         => 'ご利用規約 ',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'order' => array(
			'title'         => '特定商取引に関する法律に基づく表記 ',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'client_index' => array(
			'title'         => 'クライアント',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'client_function' => array(
			'title'         => '機能紹介',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'client_flow' => array(
			'title'         => '仕事の依頼の流れ',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'client_site' => array(
			'title'         => 'サイト診断',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'worker_index' => array(
			'title'         => 'ワーカー',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),
		'worker_how' => array(
			'title'         => 'チームワークでできること',
			'keywords'      => '在宅ワーク,SOHOワーク,テレワーク,ウェブ戦略,Webサイト運営,Webデザイン,サイト運営代行,ネットショップ運営,作業代行,クラウドソーシング,ノマドマーク,ライター募集,デザイナー仕事,副業,プチ起業,起業家支援',
			'description'   => 'ウェブサイトの運営に特化した、月額固定のお仕事発注サイト［TEAMWORKERS（チームワーカーズ）］'
		),

	),

	# ワーカー
	'config_workers'   => array(
		# action名
		'entry' => array(
			'title'         => 'ワーカー登録',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'entry_complete' => array(
			'title'         => 'ワーカー登録',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'entry_login' => array(
			'title'         => 'ワーカー登録',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'profile_entry' => array(
			'title'         => 'ワーカー登録',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'profile_skill' => array(
			'title'         => 'ワーカー登録',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'profile_complete' => array(
			'title'         => 'ワーカー登録',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
	),

	# 企業
	'config_clients'   => array(
		# action名
		'register1' => array(
			'title'         => 'クライアント登録',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'register2_mail' => array(
			'title'         => '手続きのご案内メール',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'register3_complete' => array(
			'title'         => 'クライアント登録完成',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'register4_site' => array(
			'title'         => 'サイト登録',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'register5_shindan' => array(
			'title'         => '診断テスト',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'register6_shindan_end' => array(
			'title'         => '診断テスト終了',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'register7_shindan_final' => array(
			'title'         => '診断結果',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'shindan_history_list' => array(
			'title'         => '診断詳細',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'shindan_site_list' => array(
			'title'         => '診断サイト一覧',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'edit_site' => array(
			'title'         => 'サイト情報変更',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'login' => array(
			'title'         => 'ログイン',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'mypage_login' => array(
			'title'         => 'ダッシュボード',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'forgot_password' => array(
			'title'         => 'パスワード忘れ',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
		'forgot_complete' => array(
			'title'         => 'メール送りました',
			'keywords'      => 'サイト診断,運営方法,コンサル',
			'description'   => 'あなたのサイトの弱点を診断します。サイト運営がうまくいかないけどどこが悪いのか分からない。そんなサイト弱点診断サービス'
		),
	)
);

















