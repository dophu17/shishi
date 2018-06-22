<div class="mainContents nofooter">
    <div class="">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" style="text-align: center;">
                <h2 class="commonh2 center">権限設定</h2>
                <div class="clearfix">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="<?php echo $this->Html->url('/OttUser/admin')?>" >管理者</a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Html->url('/OttUser/client')?>">クライアント</a>
                        </li>
                        <!--li>
                            <a href="<?php echo $this->Html->url('/OttUser/partner')?>">代理店</a>
                        </li-->
                    </ul>
                    <div class="tab-content">
                        <!-- owner -->
                        <div role="tabpanel" class="tab-pane active" id="space_owner" style="padding: 20px;">
                            <h3 class="joinsProjectSection">全サイト管理者</h3>
                            <div class="joinsProjectContents">
                                <table class="table joinsTable table_client">
                                    <tbody>
                                        <?php
                                        if(!empty($list)){
                                            $i= 1;
                                            foreach($list as $item){
                                            ?>
                                            <tr>
                                                <td><?php echo $i?></td>
                                                <td>
                                                    <?php 
                                                    if($item['SsmUser']['status'] == 2){ 
                                                        echo '（招待中）';
                                                    }else{
                                                        echo $item['SsmUser']['first_name']." ".$item['SsmUser']['last_name'];
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $item['SsmUser']['username']?></td>
                                                <td>
                                                    <a style="float:right" class="btn btn-default btn-xs confrimedLnk" onClick="return confirm('このユーザーを管理者から除外します。よろしいですか？')" href="<?php echo $this->Html->url('/OttUser/delete/?user_id='.$item['SsmUser']['id'])?>">
                                                        <i class="fa fa-trash-o"></i>削除
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                                $i++;
                                            }
                                        }else{

                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="addJoinBtnBox">
                                    <a class="btn btn-primary" style="width: 150px;" href="<?php echo $this->Html->url('/OttUser/inviteadmin')?>">追加</a>
                                </div>
                            </div>
                        </div>
                        <!-- worker -->
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