<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
    <?php if(!empty($htmlhead[$this->params['action']]['title'])): ?>
         <?php echo $htmlhead[$this->params['action']]['title']." | "; ?>
 	<?php endif; ?>
        <?php if(!empty($pagetitle['clients'])){ echo $pagetitle['clients']." | "; } ?>
        <?php echo SITE_NAME ?>
    </title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('reset');
		echo $this->Html->css('client.styles');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->Html->script('jquery-1.10.2.min');
		echo $this->Html->script('rollover');
		echo $this->Html->script('lazy.load');
		echo $this->Html->script('lazyloadscript');
		echo $this->fetch('script');
	?>
</head>
<body>
	<!-- ▼ヘッダー▼ -->
	<div id="header_a">

		<div id="container">

			<div id="logo_a">
				<?php echo $this->Html->image('client_img/a_logo.jpg', array('alt' => 'TEAM WORK','url' => '/')); ?></a>
			</div>
			<!-- /logo_a -->

		</div>
		<!-- /container -->

	</div>
	<!-- ▲ヘッダー▲ -->

	<!-- ▼メインコンテンツ▼ -->
	<div id="main-contents">
		<?php echo $this->fetch('content'); ?>
	</div>
	<!-- /main-contents -->
	<!-- ▲メインコンテンツ▲ -->

	<!-- ▼フッター▼ -->
	<div id="footer">
		<div id="container">

			<ul class="footer01">
				<li><a href="<?php echo $this->webroot; ?>client/" class="over">HOME</a></li>
				<li><a href="<?php echo $this->webroot; ?>about/" class="over">TEAMWORKERSとは？</a></li>
				<li><a href="<?php echo $this->webroot; ?>client/function/" class="over">機能紹介</a></li>
				<li><a href="<?php echo $this->webroot; ?>client/price/" class="over">ご利用料金</a></li>
				<li><a href="<?php echo $this->webroot; ?>message/" class="over">メッセージ</a></li>
				</ul>

				<ul class="footer02">
				<li><a href="https://www.teamworkers.jp/contact/">お問い合わせ</a></li>
				<li><a href="<?php echo $this->webroot; ?>privacy/" class="over">プライバシーポリシー</a></li>
				<li><a href="<?php echo $this->webroot; ?>agreement/" class="over">利用規約</a></li>
				<li><a href="http://www.henobu.com/" target="_blank" class="over">運営会社</a></li>
				<li><a href="<?php echo $this->webroot; ?>order/" class="over">特定商取引</a></li>
			</ul>

<div style="margin-bottom:20px;"><a href="http://www.henobu.com/tool/products/list.php?category_id=23" target="_blank"><img src="https://www.teamworkers.jp/wp/wp-content/themes/twentytwelve/img/common/e_run_bnr.jpg" width="490"></a></div>
			<div class="footer_fb">
			<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FTeamwork%25E3%2583%2597%25E3%2583%25A9%25E3%2583%2583%25E3%2583%2588%25E3%2583%2595%25E3%2582%25A9%25E3%2583%25BC%25E3%2583%25A0%2F779752068724553&amp;width=550&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=true&amp;appId=472402386170318" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:550px; height:258px;" allowTransparency="true"></iframe>
			</div>



		</div><!-- /container -->
	</div><!-- /footer -->

	<div class="copy">
		<div id="container">
			Copyright © TEAMWORK All Rights Reserved.
		</div><!-- /container -->
	</div><!-- /copy -->
	<!-- ▲フッター▲ -->
    <?php echo $this->element('google_analytics'); ?>

</body>
</html>
