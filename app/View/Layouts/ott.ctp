<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="このツールはウェブお手伝いさんサービスを受けている人が活用できるウェブサイトの効果検証・レポーティングツールです">
    <meta name="keywords" content="ウェブお手伝いさん,効果検証,レポーティング,売上管理,売上シミュレーション,解析アドバイス,">

    <link rel="icon" href="<?php echo $this->Html->url('/ott')?>/favicon/favicon2.ico?v=2" type="image/x-icon" />
    <title>
        ウェブ解析お手伝いさん【効果検証・レポーティングツール】
    </title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $this->Html->url('/ott')?>/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $this->Html->url('/ott')?>/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="<?php echo $this->Html->url('/ott')?>/css/jqueryui.css" rel="stylesheet">
    <link href="<?php echo $this->Html->url('/ott')?>/css/select2.min.css" rel="stylesheet">
    <link href="<?php echo $this->Html->url('/ott')?>/css/bootstrap-editable.css" rel="stylesheet">
    <link href="<?php echo $this->Html->url('/ott')?>/css/task.css" rel="stylesheet">
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
        <h1 class="logo show_ld">
            <a href="<?php echo $this->Html->url('/OttHome'.(CakeSession::read('active_site_id')? "?site_id=".CakeSession::read('active_site_id') : "" ))?>">
                <img alt="shishimai" class="logo__logoimg" src="<?php echo $this->Html->url('/ott/img/logo.png')?>">
            </a>
        </h1>
    </div>
    <span data-toggle="collapse" data-target="#link-s" class="visible-xs-inline-block ham">
    <i class="fa fa-bars" aria-hidden="true"></i>
    </span>
    <div class="collapse navbar-collapse headerNavMenu" id="link-s">
        <?php
        if(!empty($listSiteIDName) && CakeSession::read('active_site_id') != 0){
        ?>
        <ul class="nav navbar-nav headerNavMenu__ul" id="main-menu-left">
            <li>
              <a class="show_ld" href="<?php echo $this->Html->url('/OttHome'.(CakeSession::read('active_site_id')? "?site_id=".CakeSession::read('active_site_id') : "" ));?>">運営状況サマリ</a>
            </li>
            <li class="divider-arrow"></li>
            <li>
              <a class="show_ld" href="<?php echo $this->Html->url('/OttKpi'.(CakeSession::read('active_site_id')? "?site_id=".CakeSession::read('active_site_id') : "" ))?>">目標と実績</a>
            </li>
            <li class="divider-arrow"></li>
            <li>
              <a class="show_ld" href="<?php echo $this->Html->url('/OttReport'.(CakeSession::read('active_site_id')? "?site_id=".CakeSession::read('active_site_id') : "" ))?>">レポーティング</a>
            </li>
        </ul>
        <?php
        }
        ?>

        <?php
        $op_active  = "-----";
        $op_html    = "";
        if(!empty($listSiteIDName)){
            $i = 1;
            foreach($listSiteIDName as $sid=>$sname){

                if($i == 1){
                    $op_active  = $sname;
                }
                if(CakeSession::read('active_site_id') == $sid){ 
                    $op_active  = $sname;
                    $class = 'active';
                }else{
                    $class = '';
                }
                $url = $this->Html->url('/OttHome/activesite?site_id='.$sid);

                if(!($user_role == 'admin' && $listSiteIDInfo[$sid]['show_on_menu'] == 0)){
                    $op_html .= '<li class="'.$class.' show_ld"><a href="'.$url.'">'.$sname.'</a></li>';
                }
                $i++;
            }
        }
        ?>

        <ul class="nav navbar-nav pull-right" id="main-menu-right">
          <?php
            if (!empty($expiredContracts)) {
          ?>
              <li>
                <!-- <a href="#" data-toggle="popover" data-placement="bottom"  data-content="<?php //echo $this->ExpiredContracts->createBootstrapPopoverDataContent($expiredContracts) ?>"> 契約期間が切れているサイトがあります。</a> -->
                <a style="color: #f00" href="<?php echo $this->Html->url('/SsmClient');?>">契約期間が切れているサイトがあります。</a>
              </li>
              <li class="divider-vertical"></li>
          <?php
            }
          ?>
          <li class="dropdown" id="preview-menu-shop">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="fa fa-home"></i>
              <?php echo $op_active;?>
              <b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" id="list_site" style="overflow-y: scroll; overflow-x: hidden;">
                <?php //echo $op_html;?>
                <input type="text" id="input-list-site">
                <div id="list-site">
                    <?php echo $op_html;?>
                </div>
            </ul>
          </li>

          <li class="divider-vertical"></li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="fa fa-gear"></i>設定
              <b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" id="swatch-menu">

            <?php
            if($user_role == 'admin'){
            ?>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/SsmClient');?>">
                    クライアント管理
                    </a>
                </li>

                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttTask/my');?>">
                    日報
                    </a>
                </li>
            
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttTask');?>">
                    工数一覧
                    </a>
                </li>

                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttTaskLabel');?>">
                    ジャンル一覧
                    </a>
                </li>

                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/SsmClient/man_hours_rate_setting') ?>">
                    割合工数
                    </a>
                </li>

                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttPlan');?>">
                    プラン設定
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttSetting');?>">
                    CW通知文面管理
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttThank/send');?>">
                    アリガトウ
                    </a>
                </li>

                <li class="divider"></li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/myaccount');?>">
                      <i class="fa fa-user"></i>ユーザー情報変更
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/changepassword');?>">
                      <i class="fa fa-key"></i>パスワード変更
                    </a>
                </li>

                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/editchatwork');?>">
                      <i class="fa fa-comment-o"></i>ChatWork連携設定
                    </a>
                </li>
                <li>
                    <a rel="nofollow" data-method="delete" href="<?php echo $this->Html->url('/OttUser/logout');?>">
                      <i class="fa fa-sign-out"></i>ログアウト
                    </a>
                </li>
            <?php
            }elseif($user_role == 'partner'){
            ?>
                <!--li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttTask/my');?>">
                    日報
                    </a>
                </li-->

                <!--li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttTask');?>">
                    工数一覧
                    </a>
                </li-->                
                
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/myaccount');?>">
                      <i class="fa fa-user"></i>ユーザー情報変更
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/changepassword');?>">
                      <i class="fa fa-key"></i>パスワード変更
                    </a>
                </li>
                <li>
                    <a rel="nofollow" data-method="delete" href="<?php echo $this->Html->url('/OttUser/logout');?>">
                      <i class="fa fa-sign-out"></i>ログアウト
                    </a>
                </li>
            <?php
            }elseif($user_role == 'worker'){
            ?>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttTask');?>">
                    工数一覧
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttTask/my');?>">
                    日報
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttThank/send');?>">
                    アリガトウ
                    </a>
                </li>
                <li class="divider"></li>
                
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/myaccount');?>">
                      <i class="fa fa-user"></i>ユーザー情報変更
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/changepassword');?>">
                      <i class="fa fa-key"></i>パスワード変更
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/editchatwork');?>">
                      <i class="fa fa-comment-o"></i>ChatWork連携設定
                    </a>
                </li>
                <li>
                    <a rel="nofollow" data-method="delete" href="<?php echo $this->Html->url('/OttUser/logout');?>">
                      <i class="fa fa-sign-out"></i>ログアウト
                    </a>
                </li>
            <?php
            }else{
            ?>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttTask');?>">
                    工数一覧
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/myaccount');?>">
                      <i class="fa fa-user"></i>ユーザー情報変更
                    </a>
                </li>
                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/changepassword');?>">
                      <i class="fa fa-key"></i>パスワード変更
                    </a>
                </li>

                <li>
                    <a class="show_ld" href="<?php echo $this->Html->url('/OttUser/editchatwork');?>">
                      <i class="fa fa-comment-o"></i>ChatWork連携設定
                    </a>
                </li>
                <li>
                    <a rel="nofollow" data-method="delete" href="<?php echo $this->Html->url('/OttUser/logout');?>">
                      <i class="fa fa-sign-out"></i>ログアウト
                    </a>
                </li>
            <?php
            }
            ?>
            </ul>
          </li>
        </ul>
    </div>
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
<!--    <script src="--><?php //echo $this->Html->url('/ott')?><!--/js/jquery-2.0.3.min.js"></script>-->
    <script src="<?php echo $this->Html->url('/ott')?>/js/bootstrap.min.js"></script>
    <script src="<?php echo $this->Html->url('/ott')?>/js/select2.min.js"></script>
    <script src="<?php echo $this->Html->url('/ott')?>/js/bootstrap-editable.min.js"></script>
    <script src="<?php echo $this->Html->url('/ott')?>/js/jquery-ui.js"></script>
    <div id="loading-layer" style="display: none;">
        <!--img src="<?php echo $this->Html->url('/ott')?>/img/loading.gif" alt="Loading"-->
    </div>
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
var getSiteAutocomplete 	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'getSiteAutocomplete']);?>";
var changeSort 	= "<?php echo Router::url(['controller' => 'ShishimaiApi', 'action' => 'changeSort']);?>";
var baseUrl = '<?php echo $this->webroot ?>';
$(document).ready(function(){
    $('.show_ld').click(function(){
        $('#loading-layer').show();
    });

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

    //add autocomplete to list site top menu
    $("#input-list-site").keyup(function(){
        var siteNameActive = '<?php echo $op_active ?>';
        var siteName = $(this).val();
        $.ajax({
            method: "POST",
            url: getSiteAutocomplete,
            data: { siteName: siteName }
        }).done(function( results ) {
            var res = JSON.parse(results);
            console.log(res);
            $('#list-site').html('');
            if ( res.data.length == 0 ) {
                $('#list-site').append('<li class="show_ld"><a>データがございません</a></li>');
                return false;
            }
            $( res.data ).each(function( index, value ) {
                if ( siteNameActive == value.site_name ) {
                    $('#list-site').append('<li class="show_ld active"><a href="' + baseUrl + 'OttHome/activesite?site_id=' + value.id + '">' + value.site_name + '</a></li>');
                } else {
                    $('#list-site').append('<li class="show_ld"><a href="' + baseUrl + 'OttHome/activesite?site_id=' + value.id + '">' + value.site_name + '</a></li>');
                }
            });
        });
    });
    //end add autocomplete to list site top menu

    //sorttable
    $( "#defined_task" ).sortable({
        start : function(event, ui) {
            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);
        },
        change : function(event, ui) {
            var start_pos = ui.item.data('start_pos');
            var index = ui.placeholder.index();
        },
        update : function(event, ui) {
            var fromIndex = ui.item.data('start_pos');
            var toIndex = ui.item.index();
            var taskId = ui.item.attr("task_id");
            $.ajax({
                method: "POST",
                url: changeSort,
                data: { taskId: taskId, fromIndex: fromIndex, toIndex: toIndex }
            }).done(function( res ) {
                var results = JSON.parse(res);
                console.log(results);
            });
        },
        axis : 'y'
    });
    $( "#defined_task" ).disableSelection();
    //end sorttable

    //sorttable list today
    $( "#list-task-today" ).sortable();
    $( "#list-task-today" ).disableSelection();
    //end sorttable list today

    /*$(document).ready(function(){
        $('[data-toggle="popover"]').popover({
            html: true,
        });
    });*/
});
</script>
<!--end dktran-->
</html>



