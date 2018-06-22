<div class="baseConainer">
    <div class="mainContents nofooter">
        <div class="">
            <div class="row reportTopSection">
                <div class="col-md-8 clearfix">
                    <h2 class="commonh2" style="float: left;margin-right: 20px">クライアント一覧</h2> 

                    <?php if($allow_action['task_showuser']){?>
                    <form id="form_user" target='_blank' action="<?php echo $this->Html->url('/OttTask/user')?>" method='GET'>
                    <?php if(!empty($list_user_option)){ ?>
                        <select name="user_id" class="form-control select-user-id" style="">
                            <option value='0'> 担当者をご選択ください </option>
                            <?php foreach($list_user_option as $op_user){ ?>
                                <option value='<?php echo $op_user['SsmUser']['id']?>'><?php echo $op_user['SsmUser']['first_name'].$op_user['SsmUser']['last_name']."【".$op_user['SsmUser']['username']."】";?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name='year' value="<?php echo $year;?>" >
                        <input type="hidden" name='month' value="<?php echo $month;?>" >
                        <?php
                    }
                    ?>
                    <script>
                        var is_chrome = !!window.chrome && !is_opera;
                        var is_explorer= typeof document !== 'undefined' && !!document.documentMode && !isEdge;
                        var is_firefox = typeof window.InstallTrigger !== 'undefined';
                        var is_safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
                        var is_opera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;


                        $(document).ready(function(){
                            if(is_safari){
                                $('input[type=submit]').css('display','block');
                                $('#form_user').attr('target','');
                            }

                            $('select[name=user_id]').change(function(){
                                if($(this).val() != '0'){
                                    if(!is_safari){
                                        
                                        var url = "<?php echo $this->Html->url('/OttTask/user')?>?year=<?php echo $year;?>&month=<?php echo $month;?>&user_id="+$(this).val();
                                        window.open(url);
                                    }else{
                                        $('#form_user').submit();
                                    }                                 
                                }   
                                if(!is_safari){
                                    $('select[name=user_id] > option[value=0]').prop('selected',true);      
                                }                       
                            });
                        });
                    </script>
                    </form>

                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <h2 class="commonh2" style="text-align:right">
                        <a class="show_ld" href="<?php echo $this->Html->url($prev_link);?>">
                            <i class="fa fa-caret-square-o-left"></i>
                        </a>
                        <span>
                        &nbsp;
                        <?php echo $year?>年 <?php echo $month?>月          &nbsp;
                        </span>
                        <a class="show_ld" href="<?php echo $this->Html->url($next_link);?>" style="float:right">
                            <i class="fa fa-caret-square-o-right"></i>
                        </a>
                    </h2>
                </div>
                <div class="table_wrap">
                    <table class="table reportTable clientTable table-striped">
                        <thead>
                        <tr>
                            <!--th width="250" class="t_left">クライアント名</th-->
                            <th width="250" class="t_left">サイト名</th>
                            <th width="100" class="t_center">一ヶ月工数</th>
                            <th width="100" class="t_center">使用工数
                            </th>
                            <?php
                            foreach($label_option as $label_id=>$label){
                                ?>
                                <th width="100" class="t_center"><?php echo $label;?></th>
                                <?php
                            }
                            ?>

                        </tr>
                        </thead>
                        <tbody id="list_content">
                        <tr>
                            <td>合計</td>
                            <td><b><?php echo number_format($total_data['total_site_hour_calculate'], 2, '.', '');?></b></td>
                            <td><b><?php echo number_format($total_data['total_all_label'], 2, '.', '');?></b></td>
                            <?php
                            foreach($label_option as $label_id=>$label){
                                ?>
                                <td><b><?php echo ($total_data['group_by_label'][$label_id] ? number_format($total_data['group_by_label'][$label_id], 2, '.', '') : '0.00');?></b></td>
                                <?php
                            }
                            ?>
                        </tr>

                        <?php
                        if(!empty($list)){
                            foreach($list as $site_task){

                                $task_html = "";
                                foreach($label_option as $label_id=>$label){
                                    $task_html .= "<td>".($site_task['task'][$label_id] ? number_format($site_task['task'][$label_id], 2, '.', '') : '0.00')."</td>";
                                }

                                ?>
                                <tr>
                                    <!--td> <?php echo $site_task['users'];?></td-->
                                    <td class="t_left"> <?php echo $site_task['site_name'];?></td>

                                    <td> <?php echo number_format($site_task['site_hour_calculate'], 2, '.', '');?></td>

                                    <td> <?php echo number_format($site_task['task_total'], 2, '.', '');?></td>

                                    <?php echo $task_html;?>
                                </tr>
                                <?php
                            }


                            ?>
                            <?php

                        }else{
                            ?>
                            <tr><td colspan="<?php echo count($label_option)+3;?>"> 今月はデータがございません。 </td></tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
@media(max-width: 1200px){
    .table_wrap{
        overflow-y: hidden;
        overflow-x: scroll;
        -webkit-overflow-x: scroll;
        -webkit-overflow-scrolling: touch;
    }
}

.t_left{
    text-align: left !important;
}
.t_right{
    text-align: right !important;
}
.t_center{
    text-align: center !important;
}
</style>

