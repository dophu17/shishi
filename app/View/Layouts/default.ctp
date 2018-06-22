<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="このツールはウェブお手伝いさんサービスを受けている人が活用できるウェブサイトの効果検証・レポーティングツールです">
    <meta name="keywords" content="ウェブお手伝いさん,効果検証,レポーティング,売上管理,売上シミュレーション,解析アドバイス,">

    <title>
        ウェブ解析お手伝いさん【効果検証・レポーティングツール】
    </title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $this->Html->url('/ott')?>/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $this->Html->url('/ott')?>/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="<?php echo $this->Html->url('/ott')?>/css/jqueryui.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
    body {
        padding-top: 70px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery Version 1.11.1 -->
    <script src="<?php echo $this->Html->url('/ott')?>/js/jquery.js"></script>

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
            <a href="<?php echo $this->Html->url('/OttHome')?>">
                <img alt="shishimai" class="logo__logoimg" src="<?php echo $this->Html->url('/ott/img/logo.png')?>">
            </a>
        </h1>
    </div>
    <span data-toggle="collapse" data-target="#link-s" class="visible-xs-inline-block ham">
    <i class="fa fa-bars" aria-hidden="true"></i>
    </span>
    
  </div>
</nav>

</div>

    <!-- Page Content -->
    <div class="container">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>

    </div>
    <!-- /.container -->

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $this->Html->url('/ott')?>/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    window._mfq = window._mfq || [];
    (function() {
        var mf = document.createElement("script");
        mf.type = "text/javascript"; mf.async = true;
        mf.src = "//cdn.mouseflow.com/projects/784e5367-b3fe-4fd9-855f-646f84456c7d.js";
        document.getElementsByTagName("head")[0].appendChild(mf);
    })();
    </script>
</body>

<link rel="stylesheet" href="<?php echo $this->Html->url('/ott')?>/css/layout.css">
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117443997-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-117443997-2');
</script>
<script type="text/javascript">
$(document).ready(function(){
$(".ham i").click(function(){
    if($("i").hasClass("fa-bars")){
        $(this).removeClass("fa-bars").addClass("fa-times");
    } else {
        $(this).removeClass("fa-times").addClass("fa-bars");
    }
});

$('.error_per').click(function(){
    alert('こちらの操作を行う権限がありません。管理者までご連絡ください。');
});

});
</script>
<!--end dktran-->
</html>



