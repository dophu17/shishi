<!DOCTYPE html>
<html>
<head>
  <title>Otetsudai</title>

  <link rel="stylesheet" media="all" href="//netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" data-turbolinks-track="true" />

  <meta name="csrf-param" content="authenticity_token" />
  <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-152-precomposed.png">
  <meta name="msapplication-TileColor" content="#FFFFFF">
  <meta name="msapplication-TileImage" content="/mstile-144.png">
</head>
<body>
  <div class="baseConainer">
    <nav class="navbar navbar-fixed-top">
  <div>
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-6" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- logo -->
      <h1 class="logo">
        <a href="">
          <img alt="shishimai" class="logo__logoimg" src="<?php echo $this->Html->url('/ott/img/logo.png')?>" />
</a>      </h1>
    </div>
    <div class="collapse navbar-collapse headerNavMenu">


        <!--ul class="nav navbar-nav pull-right" id="main-menu-right">
          <li>
            <a class="btn btn-success" href="">ログイン</a>
          </li>
        </ul-->
    </div>
  </div>
</nav>
    <div class="mainContents ">
      <div class="container">

  <div class="signinLogoWrapper">
    <img alt="shishimai" class="logo__logoimg" src="<?php echo $this->Html->url('/ott/img/logo.png')?>" />
  </div>
  <?php echo $this->Session->flash(); ?>
  <div class="signinFormWrapper">
    <?php echo $this->Form->create('SsmUser', array('role' => 'form')); ?>
      <div class="clearfix">
        <div class="top_log1">
          <?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => 'ログインID','label' => 'ログインID'));?>
        </div>
        <div class="top_log2">
          <?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => 'パスワード','label' => 'パスワード'));?>
        </div>
        <div class="top_log3">
          <input type="submit" name="commit" value="ログイン" class="btn-primary btn btn-black" />
        </div>
      </div>
      <!--div class="newUserRegistartionLnkBox">
        <a href="/users/password/new">パスワード再発行</a>
      </div-->
    <?php echo $this->Form->end() ?>
    </div>
</div>
    </div>
      <footer style="margin-top: 300px">
  <div class="footer-links" >
    <ul class="clearfix">
      <li><a href="http://www.henobu.com/" target="_blank">運営会社</a></li>
      <li><a target="_blank" href="http://www.henobu.com/privacy_policy/">プライバシーポリシー</a></li>
      <li><a target="_blank" href="https://www.henobu.com/contact_other/">お問い合わせ</a></li>
    </ul>
    <address>Copyright © 2017 henobufactory All Rights Reserved.</address>
  </div>
</footer>

<div class="modal fade modal_warning" tabindex="-1" role="dialog" aria-hidden="true" id="mi-modal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Caution</h4>
      </div> -->
      <div class="modal-body">
        <p style="text-align:center">現在契約期限が切れております。<a href='https://www.henobu.com/contact_other/'>「お問い合わせ」</a>よりご連絡ください。</p>
      </div>
      <div class="modal-footer">
        <!--a href="" class="btn btn-primary" id="modal-btn-si">Đi đang ký hd</a-->
        <button type="button" class="btn btn-default" onClick="$('.modal_warning').modal('hide');">キャンセル</button>    
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
  var show_warning = "<?php echo $show_warning?>";
  if(show_warning){
    $('.modal_warning').modal('show');
  }
});
</script>
</body>
</html>



