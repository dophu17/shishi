<div class="mainContents nofooter">
    <div class="">
        <div class="row reportTopSection">
            <div class="col-md-10 col-md-offset-1 clearfix">
                <div>
                    <h2 class="commonh2 center" style="float:left">クライアント詳細情報</h2>
                    <div class="projectIndexHeaderBtns">
                        <a class="btn btn-default" href="<?php echo $this->Html->url('/SsmClient/edit/site_id:' . $site_id); ?>">クライアント情報編集</a>
                        <a class="btn btn-default" href="<?php echo $this->Html->url('/OttUser/admin/site_id:' . $site_id); ?>">権限設定</a>
                        <a class="btn btn-default" href="<?php echo $this->Html->url('/SsmClient/'); ?>">一覧に戻る</a>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-md-offset-1">
                <div class="clearfix">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#infos" aria-controls="allTasks" role="tab" data-toggle="tab">基本情報</a>
                        </li>
                        <li role="presentation">
                            <a href="#contract" aria-controls="allTasks" role="tab" data-toggle="tab">契約情報</a>
                        </li>
                        <li role="presentation">
                            <a href="#account_infos" aria-controls="allTasks" role="tab" data-toggle="tab">外部連携情報</a>
                        </li>
                        <li role="presentation">
                            <a href="#others" aria-controls="allTasks" role="tab" data-toggle="tab">その他</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="infos" style="padding: 20px;">
                        <h4 class="projectShowh4">サイト情報</h4>
                        <div class="projectShowContents">
                            <dl class="basicInfoDl">
                                <dt>サイト名</dt>
                                <dd>
                                    <?php echo $site_name ;?>
                                </dd>
                                <dt>サイトURL</dt>
                                <dd>
                                    <?php echo $site_url ;?>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="contract" style="padding: 20px;">
                        <h4 class="projectShowh4">契約情報</h4>
                        <div class="name_over">
                            <div class="projectShowContents name_contract">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>期間</th>
                                        <th>システム利用期間</th>
                                        <th>プラン</th>
                                        <th>月額利用料金</th>
                                    </tr>
                                    <?php foreach($info_contract as $contract){
                                    ?>

                                    <tr>
                                        <td>
                                            <span><?php echo $this->shishimai->show_date($contract['SsmContract']['start_day']); ?></span> 〜 <span><?php echo $this->shishimai->show_date($contract['SsmContract']['end_day']); ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $this->shishimai->show_date($contract['SsmContract']['start_day']); ?></span> 〜 <span><?php echo $this->shishimai->show_date($contract['SsmContract']['end_day']); ?></span>
                                        </td>
                                        <td>
                                            <?php echo $contract['SsmPlan']['name']; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($contract['SsmContract']['is_customize_price']){
                                                echo $this->shishimai->displayValue($contract['SsmContract']['plan_price'], 'number', '¥', '', 0);
                                            }else{
                                                echo $this->shishimai->displayValue($contract['SsmPlan']['price'], 'number', '¥', '', 0);
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <?php
                                    }; ?>

                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>

                    <!-- 外部連携情報tab -->
                    <div role="tabpanel" class="tab-pane" id="account_infos" style="padding: 20px;">
                        <h4 class="projectShowh4">GoogleAnalytics</h4>
                        <div class="projectShowContents">
                            <dl class="basicInfoDl">
                                <dt>Google Analytics ビューID</dt>
                                <dd><?php echo $ga_view_id?></dd>
                                <dt>
                                    <?php echo $ga_analytics?>
                                    <br/> e-commerce設定:&nbsp;
                                    <?php if($ga_ecommerce ==1 )
                                    {
                                    echo 'あり';
                                    }
                                    else {
                                    echo '無し';
                                    };
                                    ?>
                                </dt>
                            </dl>
                        </div>
                        <h4 class="projectShowh4">ChatWork</h4>
                        <div class="projectShowContents">
                            <dl class="basicInfoDl">
                                <dt>連携先ルームID</dt>
                                <dd>
                                    <?php echo $chatwork_id?>
                                </dd>
                            </dl>
                        </div>
                        <h4 class="projectShowh4">GoogleAdwords</h4>
                        <div class="projectShowContents">
                            <div class="well">
                                todo
                            </div>
                        </div>
                        <h4 class="projectShowh4">Yahoo!スポンサードサーチ</h4>
                        <div class="projectShowContents">
                            <div class="well">
                                todo
                            </div>
                        </div>
                    </div>
                    <!-- その他 -->
                    <div role="tabpanel" class="tab-pane" id="others" style="padding: 20px;">
                        <h4 class="projectShowh4">その他</h4>
                        <div class="projectShowContents" style="padding-left: 15px;">
                            <dl class="basicInfoDl">
                                <!-- <dt>満足度</dt>
                                <dd>
                                    <?php echo $cf_site_satisfaction[$site_satisfaction]['title']; ?>
                                </dd> -->
                                <dt>その他メモ</dt>
                                <dd>
                                    <div class="well">
                                        <?php echo $site_note; ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</body>

</html>