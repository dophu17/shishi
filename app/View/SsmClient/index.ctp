<style>
tr.expired td {
    background-color: #f6bfb1 !important;
}

tr.expired.suspend td {
    background-color: #b9b5b5 !important;
}
</style>
<div class="baseConainer">
    <div class="mainContents nofooter">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->Html->url('/ott'); ?>/css/whhg.css">

        <div class="">
            <div class="row reportTopSection">
                <div class="col-md-12 clearfix">
                    <h2 class="commonh2" style="float: left;">クライアント一覧</h2>
                    <div class="projectIndexHeaderBtns">

                    <?php
                    if($user_role == 'admin'){
                    ?>
                        <a class="btn btn-default" href="<?php echo $this->Html->url('/SsmClient/add')?>">クライアント追加</a>
                        <a class="btn btn-default" href="<?php echo $this->Html->url('/OttUser/admin')?>">権限設定</a>
                        <a class="btn btn-default" href="<?php echo $this->Html->url('/SsmClient/kpi')?>">クライアントのデータを表示</a>
                    <?php
                    }
                    ?>

                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table clientTable table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    サイト名
                                    <br/>
                                    <small style="font-weight: normal;">（クライアント名）</small>
                                </th>
                                <th style="width: 180px;">担当ディレクター</th>
                                <!-- <th style="width: 90px;">満足度<br/>ステータス</th> -->
                                <th style="width: 200px;">契約プラン</th>
                                <th style="width: 120px;">月額利用料金</th>
                                <th style="width: 80px;">&nbsp;</th>
                            </tr>
                            <?php $i = 1;?>
                            <?php foreach($info_site as $info){
                                $siteId   = $info['ssm_sites']['id'];
                                $contract = $contract_site[$siteId];
                            ?>

                            <tr class="contractStatus--current <?php echo $contract['SsmContract']['expired'] ? 'expired' : '' ?> <?php echo $info['ssm_sites']['suspend'] == 1 ? 'expired suspend' : '' ?> ">
                                <td style="text-align: left;padding-left: 10px;">
                                    <div style="font-size:14px;font-weight: bold;">
                                        <?php echo $i?>.<a target="_blank" style="color: #000;" href="<?php echo $this->Html->url('/OttHome?site_id=' . $info['ssm_sites']['id'] ); ?>"><?php echo $info['ssm_sites']['site_name']?></a>
                                        <?php if ($contract['SsmContract']['expired']): ?>
                                            <span style="color: #f00;">※</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php echo $info['ssm_sites']['site_description'] ?>
                                </td>
                                <td>
                                    <div style="float:left" class="name_img">
                                    <a target="_blank" href="<?php echo $this->Html->url('/SsmClient/edit_account/user_id:' . $info['ssm_sites']['site_manage_user']); ?>"><img style="width: 40px; height: auto; margin-right: 5px; float: left;" src="<?php 
                                    echo empty($info['ssm_users']['avatar']) 
                                    ? $this->Html->url('/uploads/ott/user/no_image.png')
                                    : $this->Html->url('/uploads/ott/user/' . $info['ssm_users']['avatar'])
                                    ; ?>" alt="No image" /></a>
                                    </div>
                                    <div style="">
                                        <span style="font-size: 14px;"><?php echo $info['ssm_users']['first_name'] . $info['ssm_users']['last_name']?></span><br/><a target="_blank" class="btn btn-default btn-xs" href="<?php echo $this->Html->url('/SsmClient/edit_account/user_id:' . $info['ssm_sites']['site_manage_user']); ?>">編集</a>
                                    </div>
                                </td>

                                <!--  <td style="font-size: 16px;">
                                        <i class="<?php echo $cf_site_satisfaction[$info['ssm_sites']['site_satisfaction']]['class']; ?> icon_face" style="color: <?php echo $cf_site_satisfaction[$info['ssm_sites']['site_satisfaction']]['color'];?>"></i>
                                        <span style="vertical-align: middle;"><?php echo $cf_site_satisfaction[$info['ssm_sites']['site_satisfaction']]['title']; ?></span>
                                </td> -->

                                <td>
                                    <p><?php echo $contract_site[$info['ssm_sites']['id']]['SsmPlan']['name']?></p>
                                    <div style="font-size: 12px;">
                                        <?php
                                        if(isset($contract_site[$info['ssm_sites']['id']])){
                                            echo $this->shishimai->showTime($contract_site[$info['ssm_sites']['id']]['SsmContract']['start_day'])."<br>".$this->shishimai->showTime($contract_site[$info['ssm_sites']['id']]['SsmContract']['end_day']);
                                        }else{
                                            echo "未設定";
                                        }
                                        ?>
                                    </div>

                                </td>
                                <td>
                                    <p>
                                    <?php 
                                    if($contract_site[$info['ssm_sites']['id']]['SsmContract']['is_customize_price']){
                                    	echo number_format($contract_site[$info['ssm_sites']['id']]['SsmContract']['plan_price']);
                                	}else{
                                		echo number_format($contract_site[$info['ssm_sites']['id']]['SsmPlan']['price']);
                                	}
                                    ?>円&nbsp;/&nbsp;月</p>
                                </td>
                                <td class="m10">
                                    <a class="btn btn-xs btn-default" href="<?php echo $this->Html->url('/SsmClient/detail/site_id:' . $info['ssm_sites']['id'] ); ?>" >詳細</a><br/>
                                    <a class="btn btn-xs btn-default" href="<?php echo $this->Html->url('/SsmClient/edit/site_id:' . $info['ssm_sites']['id'] ); ?>" >編集</a><br/>

                                    <?php if ( $info['ssm_sites']['suspend'] == 0 ): ?>
                                        <a class="btn btn-xs btn-default" href="<?php echo $this->Html->url('/SsmClient/suspend/site_id:' . $info['ssm_sites']['id'] ); ?>">契約完了</a>
                                    <?php else: ?>
                                        <a class="btn btn-xs btn-default" href="<?php echo $this->Html->url('/SsmClient/unSuspend/site_id:' . $info['ssm_sites']['id'] ); ?>">復旧</a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo $this->Html->url('/ott/css/style_ssmclient.css'); ?>">
</body>

</html>