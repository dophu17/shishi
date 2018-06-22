<div class="mainContents nofooter">
    <div class="">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" style="text-align: center;">
                <h2 class="commonh2 center">権限設定</h2>
                <div class="clearfix">
                    <ul class="nav nav-tabs">
                        <li >
                            <a href="<?php echo $this->Html->url('/OttUser/admin')?>" >管理者</a>
                        </li>
                        <li class="active">
                            <a href="<?php echo $this->Html->url('/OttUser/client')?>">クライアント</a>
                        </li>
                        <!--li>
                            <a href="<?php echo $this->Html->url('/OttUser/partner')?>">代理店</a>
                        </li-->
                    </ul>
                    <div class="tab-content">

                        <!-- client -->
                        <div role="tabpanel" class="tab-pane active" id="project_client" style="padding: 20px;">

                            <?php
                            if(!empty($list_site)){
                                $i = 1;
                                foreach($list_site as $site){
                                ?>
                                <h3 class="joinsProjectSection" id="project_client_section_70">
                                <?php echo $i.".".$site['SsmSite']['site_description'];?>
                                </h3>
                                <div class="joinsProjectContents">
                                    <table class="table joinsTable">
                                        <tbody>

                                            <?php
                                            if(!empty($list_site_user[$site['SsmSite']['id']])){
                                                $j=1;
                                                foreach($list_site_user[$site['SsmSite']['id']] as $siteuser){
                                                ?>
                                                <tr>
                                                    <td width="10%"><?php echo $j?></td>
                                                    <td width="30%" style="text-align:left">
                                                        <?php
                                                        if($siteuser['SsmUser']['role'] == 'partner'){
                                                            echo "【代理店】";
                                                        }elseif($siteuser['SsmUser']['role'] == 'worker'){
                                                            echo "【ワーカー】";
                                                        }else{
                                                            echo "【クライアント】";
                                                        }
                                                        ?>

                                                        <?php
                                                        if($siteuser['SsmUser']['status'] == '2'){
                                                            echo '（招待中）';
                                                        }else{
                                                            echo $siteuser['SsmUser']['first_name']." ".$siteuser['SsmUser']['last_name'];
                                                        }
                                                        ?>


                                                    </td>
                                                    <td width="30%" style="text-align:left"><?php echo $siteuser['SsmUser']['username'];?></td>
                                                    <td>
                                                        <a style="float:right" class="btn btn-default btn-xs confrimedLnk" onClick="return confirm('このユーザーをクライアントから除外します。よろしいですか？')" href="<?php echo $this->Html->url('/OttUser/delete?user_id='.$siteuser['SsmUser']['id'].'&site_id='.$site['SsmSite']['id'])?>">
                                                            <i class="fa fa-trash-o"></i>削除
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $j++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="addJoinBtnBox">
                                        <a class="btn btn-primary" style="width: 150px;" href="<?php echo $this->Html->url('/OttUser/inviteuser/?site_id='.$site['SsmSite']['id'])?>">追加</a>
                                    </div>
                                </div>
                                <?php
                                $i++;
                                }
                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="loading-layer">
    <?php echo $this->Html->image("view_3/loading.gif"); ?>
</div>

</body>

</html>