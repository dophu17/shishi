<div class="baseConainer">

    <div class="mainContents nofooter">
        
        <div class="reportContainer">
            <div class="reportHeaderBtns">
                <?php
                if($allow_action['create_slide']){
                ?>
                <a class="btn btn-default show_ld" href="<?php echo $this->Html->url('/OttReport/new_slide/report_id:'.$report_id . '/site_id:' . $site_id); ?>">スライドを追加</a>
                <?php } ?>

                <?php
                if($allow_action['sort_slide']){
                ?>
                <a class="btn btn-default" id="reorderSlideBtn" data-target="#reorderSlidesModal" data-toggle="modal">並び替え</a>
                <?php } ?>


                <a class="btn btn-default notAuthorized" href="">初期化</a>

                <?php
                if($allow_action['public_report']){
                ?>
                <p class="btn btn-default public"><?php echo $button_array['report_publish_button']; ?></p>
                <?php } ?>
                <?php if ($allow_action['edit_report_month'] && !empty($report_revisions)) { ?>
                  <a class="btn btn-default" data-target="#revision_modal" data-toggle="modal">レストア</a>
                <?php } ?>
            </div>
            <?php
            if($slide['SsmReportSlide']['id'] ){
            }
            ?>

            <!--Loop slile-->
            <?php
            $i=1;
            foreach($slides as $slide){

                //Begin Slide
                if($slide['SsmReportSlide']['options'] == 'title_slide'){
                    ?>

                <div class="box" report_slide_id="<?php echo $slide['SsmReportSlide']['id'];?>" options="<?php echo $slide['SsmReportSlide']['options']; ?>" orderNumber="<?php echo $slide['SsmReportSlide']['order_num']; ?>" >
                    <section class="reportSection">
                        <div class="reportSectionFormWrapper">
                            <form accept-charset="UTF-8" method="post">
                                <span class="reportSection__slideNumber">スライド<?php echo $i ?></span>
                                <div class="reportSection__btns">
                                    <span class="editReportItemsBtn"><i class="fa fa-pencil-square-o"></i>&nbsp;編集</span>
                                    <p name="button" class="btn btn-link saveReportItemsBtn">
                                        <i class="fa fa fa-check-square-o"></i>&nbsp;保存
                                    </p>
                                    <a class="btn btn-link deleteSlideBtn confrimedLnk" href="" >
                                        <i class="fa fa-trash-o"></i>&nbsp;削除
                                    </a>
                                </div>
                                <h4 class="reportCoverTitle">
                                    <div class="reportKeyMessage editableItem">
                                        <?php echo $this->Shishimai->return_link(nl2br($slide['SsmReportSlide']['title'])); ?>
                                   </div>

                                   <textarea class="form-control editableField edit_title" rows="5" ><?php echo $slide['SsmReportSlide']['title']; ?></textarea>

                                    </h4>
                            </form>

                        </div>
                        <div class="reportSection__footer clearfix">
                            <div class="reportSection__footer__right">
                                <a target="_blank" style="color: #555;" href="<?php echo $info_site['SsmSite']['site_url']; ?>"><?php echo $info_site['SsmSite']['site_url']; ?></a>
                            </div>
                        </div>
                    </section>
                </div>

                <?php

                }elseif($slide['SsmReportSlide']['options'] == 'chart_slide'){
                    //Chart
                    ?>

                    <div class="box" report_slide_id="<?php echo $slide['SsmReportSlide']['id'];?>" options="<?php echo $slide['SsmReportSlide']['options']; ?>" orderNumber="<?php echo $slide['SsmReportSlide']['order_num']; ?>">
                        <section class="reportSection">
                            <div class="reportSectionFormWrapper">
                                <form action="" accept-charset="UTF-8" method="post">
                                    <input name="utf8" type="hidden" value="&#x2713;" />
                                    <span class="reportSection__slideNumber">スライド<?php echo $i ?></span>
                                    <div class="reportSection__btns">
                                        <span class="editReportItemsBtn"><i class="fa fa-pencil-square-o"></i>&nbsp;編集</span>
                                        <button name="button" class="btn btn-link saveReportItemsBtn">
                                            <i class="fa fa fa-check-square-o"></i>&nbsp;保存
                                        </button>
                                        <a class="btn btn-link deleteSlideBtn confrimedLnk" href="" >
                                            <i class="fa fa-trash-o"></i>&nbsp;削除
                                        </a>
                                    </div>
                                    <h4 class="projectShowh4">
                                        <span class="editableItem"><?php echo $this->Shishimai->return_link($slide['SsmReportSlide']['title']);?></span>
                                        <input class="form-control editableField edit_title" value="<?php echo $slide['SsmReportSlide']['title'];?>" />
                                    </h4>
                                    <p class="reportKeyMessage editableItem edit_description_view"><?php echo $this->Shishimai->return_link(nl2br($slide['SsmReportSlide']['description']));?></p>
                                    <textarea class="form-control editableField edit_description" rows="5"><?php echo $slide['SsmReportSlide']['description'];?></textarea>
                                </form>
                            </div>
                            <div class="chart_image" month="<?php echo $report_info['SsmReport']['month']; ?>" year="<?php echo $report_info['SsmReport']['year']; ?>" style="position: relative;">
                                <!--Chart display here-->
                                <div style="position: relative; min-height: 150px">
                                <div id="loader"></div>
                                </div>
                            </div>
                            <div class="reportSection__footer clearfix">
                                <div class="reportSection__footer__right">
                                    <a target="_blank" style="color: #555;" href="<?php echo $info_site['SsmSite']['site_url']; ?>"><?php echo $info_site['SsmSite']['site_url']; ?></a>
                                </div>
                            </div>
                        </section>
                    </div>
                    <?php
                }elseif($slide['SsmReportSlide']['options'] == 'kpi1_slide'){
                    //Kpi1
                    ?>

                        <div class="box" report_slide_id="<?php echo $slide['SsmReportSlide']['id'];?>" options="<?php echo $slide['SsmReportSlide']['options']; ?>" orderNumber="<?php echo $slide['SsmReportSlide']['order_num']; ?>">
                            <section class="reportSection">
                                <div class="reportSectionFormWrapper">
                                    <form method="post">
                                        <input name="utf8" type="hidden" value="&#x2713;" />
                                        <span class="reportSection__slideNumber">スライド<?php echo $i ?></span>
                                        <div class="reportSection__btns">
                                            <span class="editReportItemsBtn"><i class="fa fa-pencil-square-o"></i>&nbsp;編集</span>
                                            <button name="button" class="btn btn-link saveReportItemsBtn">
                                                <i class="fa fa fa-check-square-o"></i>&nbsp;保存
                                            </button>
                                            <a class="btn btn-link deleteSlideBtn confrimedLnk" rel="nofollow" data-method="delete" href="">
                                                <i class="fa fa-trash-o"></i>&nbsp;削除
                                            </a>
                                        </div>
                                        <h4 class="projectShowh4">
                    <span class="editableItem"><?php echo $this->Shishimai->return_link($slide['SsmReportSlide']['title']);?></span>
                    <input class="form-control editableField edit_title" type="text" value="<?php echo $slide['SsmReportSlide']['title'] ;?>" name="" />
                  </h4>
                                        <p class="reportKeyMessage editableItem edit_description_view"><?php echo $this->Shishimai->return_link(nl2br($slide['SsmReportSlide']['description']));?></p>
                                        <textarea class="form-control editableField edit_description" rows="5"><?php echo $slide['SsmReportSlide']['description'] ;?></textarea>
                                    </form>
                                </div>
                                <div class="box_image" month="<?php echo $report_info['SsmReport']['month']; ?>" year="<?php echo $report_info['SsmReport']['year']; ?>" style="position: relative;">
                                <!--Box display here-->
                                    <div style="position: relative; min-height: 150px">
                                        <div id="loader"></div>
                                    </div>
                                </div>
                                <div class="reportSection__footer clearfix">
                                    <div class="reportSection__footer__right">
                                        <a target="_blank" style="color: #555;" href="<?php echo $info_site['SsmSite']['site_url']; ?>"><?php echo $info_site['SsmSite']['site_url']; ?></a>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <?php
                }elseif($slide['SsmReportSlide']['options'] == 'image_slide'){
                    //Image
                    ?>
                     <div class="box" report_slide_id="<?php echo $slide['SsmReportSlide']['id'];?>" options="<?php echo $slide['SsmReportSlide']['options']; ?>" orderNumber="<?php echo $slide['SsmReportSlide']['order_num']; ?>">
                            <section class="reportSection">
                                <div class="reportSectionFormWrapper">
                                    <form method="post">
                                        <input name="utf8" type="hidden" value="&#x2713;" />
                                        <span class="reportSection__slideNumber">スライド<?php echo $i ?></span>
                                        <div class="reportSection__btns">
                                            <span class="editReportItemsBtn"><i class="fa fa-pencil-square-o"></i>&nbsp;編集</span>
                                            <button name="button" class="btn btn-link saveReportItemsBtn ">
                                                <i class="fa fa fa-check-square-o"></i>&nbsp;保存
                                            </button>
                                            <a class="btn btn-link deleteSlideBtn confrimedLnk" rel="nofollow" data-method="delete" href="">
                                                <i class="fa fa-trash-o"></i>&nbsp;削除
                                            </a>
                                        </div>
                                        <h4 class="projectShowh4">
                    <span class="editableItem"><?php echo $this->Shishimai->return_link($slide['SsmReportSlide']['title']);?></span>
                    <input class="form-control editableField edit_title" type="text" value="<?php echo $slide['SsmReportSlide']['title'] ;?>" name="" />
                  </h4>
                                        <p class="reportKeyMessage editableItem edit_description_view"><?php echo $this->Shishimai->return_link(nl2br($slide['SsmReportSlide']['description']));?></p>
                                        <textarea class="form-control editableField edit_description" rows="5"><?php echo $slide['SsmReportSlide']['description'] ;?></textarea>
                                    </form>
                                </div>

                                        <form class="uploadimage" action="" method="post" enctype="multipart/form-data">
                                            <input type="file" name="file" class="file"/>
                                            <input type="submit" value="submit" class="submit_inform" style="display:none">
                                            <input type="hidden" name="slide_id" value="<?php echo $slide['SsmReportSlide']['id'];?>">
                                        </form>

                                <div>
                                    <div class="file_img" style="width: 100%; text-align: center">
                                        <div class="file_img_before"></div>
                                        <?php if(isset($slide['SsmReportSlide']['data']) && $slide['SsmReportSlide']['data'] != "" ){
                                        ?>
                                            <img style="max-width:100%" src="<?php echo $this->Html->url('/uploads/ott/report/' . $slide['SsmReportSlide']['data']);?>  " >
                                        <?php
                                        }?>
                                    </div>
                                </div>

                                <div class="reportSection__footer clearfix">
                                    <div class="reportSection__footer__right">
                                        <a target="_blank" style="color: #555;" href="<?php echo $info_site['SsmSite']['site_url']; ?>"><?php echo $info_site['SsmSite']['site_url']; ?></a>
                                    </div>
                                </div>
                            </section>
                        </div>

                         <?php
                }elseif($slide['SsmReportSlide']['options'] == 'image_slide_title'){
                    //Image
                    ?>
                     <div class="box" report_slide_id="<?php echo $slide['SsmReportSlide']['id'];?>" options="<?php echo $slide['SsmReportSlide']['options']; ?>" orderNumber="<?php echo $slide['SsmReportSlide']['order_num']; ?>">
                            <section class="reportSection">
                                <div class="reportSectionFormWrapper">
                                    <form>

                                        <span class="reportSection__slideNumber">スライド<?php echo $i ?></span>
                                        <div class="reportSection__btns">
                                            <span class="editReportItemsBtn"><i class="fa fa-pencil-square-o"></i>&nbsp;編集</span>
                                            <button name="button" class="btn btn-link saveReportItemsBtn ">
                                                <i class="fa fa fa-check-square-o"></i>&nbsp;保存
                                            </button>
                                            <a class="btn btn-link deleteSlideBtn confrimedLnk" rel="nofollow" data-method="delete" href="">
                                                <i class="fa fa-trash-o"></i>&nbsp;削除
                                            </a>
                                        </div>
                                        <h4 class="projectShowh4">
                                            <span class="editableItem"><?php echo $this->Shishimai->return_link($slide['SsmReportSlide']['title']);?></span>
                                            <input class="form-control editableField edit_title" type="text" value="<?php echo $slide['SsmReportSlide']['title'] ;?>" name="" />
                                        </h4>
                                    </form>
                                </div>
                                <div class="file_img">
                                    <div class="file_img_before"></div>
                                    <?php if(isset($slide['SsmReportSlide']['data']) && $slide['SsmReportSlide']['data'] != ""){
                                    ?>
                                        <img style="max-width:100%" src="<?php echo $this->Html->url('/uploads/ott/report/' . $slide['SsmReportSlide']['data']);?>  " >
                                    <?php
                                    }?>
                                </div>

                                <div class="reportSection__footer clearfix">
                                    <div class="reportSection__footer__right">
                                        <a target="_blank" style="color: #555;" href="<?php echo $info_site['SsmSite']['site_url']; ?>"><?php echo $info_site['SsmSite']['site_url']; ?></a>
                                    </div>
                                </div>
                            </section>
                        </div>
                                <?php
                }
                //End Slide
                $i++;
            }
            ?>
<!--End loop slide-->

                                                <!-- todo merge -->
                                                <div class="modal fade" id="taskDetailModal">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title taskDetailModal__title"></h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="taskDetailModal__body"></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="reportSection__footer clearfix">
                                                <div class="reportSection__footer__right">
                                                    <a target="_blank" style="color: #555;" href="<?php echo $info_site['SsmSite']['site_url']; ?>"><?php echo $info_site['SsmSite']['site_url']; ?></a>
                                                </div>
                                            </div> -->
                                        </section>
                                    </div>
                                    <section class="addSlideSection">
                                        <a class="btn btn-lg btn-default addSlideBtn" href="<?php echo $this->Html->url('/OttReport/new_slide/report_id:'.$report_id . '/site_id:' . $site_id); ?>">
                                            <i class="fa fa-plus-circle"></i>&nbsp; スライドを追加
                                        </a>
                                    </section>
        </div>
        <div class="modal fade" id="reorderSlidesModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">スライド並び替え</h4>
                    </div>
                    <form accept-charset="UTF-8" method="post">
                        <input name="utf8" type="hidden" value="&#x2713;" />
                        <div class="modal-body">
                            <table class="table">
                                <tbody id="reorderTbody">
                                <tr>
                                        <th style="width: 30px;">#</th>
                                        <th style="text-align: center;">タイトル</th>
                                        <th style="width: 140px;text-align: center;">順番並び替え</th>
                                    </tr>
                                 <?php

                                foreach($slides as $slide){
                                ?>
                                    <tr class="reorderTr" slide_id="<?php echo $slide['SsmReportSlide']['id']?>">
                                        <td>
                                            <span class="oni_slides_number_output"></span>

                                        </td>
                                        <td><?php echo $slide['SsmReportSlide']['title'];?></td>
                                        <td>
                                            <a class="btn btn-default btn-xs upListBtn" href="#">
                                                <i class="fa fa-caret-square-o-up"></i>&nbsp;上へ
                                            </a>
                                            <a class="btn btn-default btn-xs downListBtn" href="#">
                                                <i class="fa fa-caret-square-o-down"></i>&nbsp;下へ
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <input type="button" name="commit" id="commit" value="保存" class="btn btn-primary" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
    </div>
</div>


<!--Modal report revision data-->
<?php if ($allow_action['edit_report_month'] && !empty($report_revisions)) { ?>
  <div class="modal fade" id="revision_modal">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">復元</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" id="form_copy">
            <div class="row">
              <div class="form-group">
                <label class="col-sm-3 control-label">復元時刻</label>
                <div class="col-sm-9">
                  <select class="form-control" name="revision">
                      <?php
                      foreach ($report_revisions as $revision) {
                          $selected = ($revision['is_active']) ? 'selected' : '';
                          print '<option value="' . $revision['id'] . '" ' . $selected . '>' . $revision['created_at'] . '</option>';
                      }
                      ?>
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <span class="copy_msg warning clearfix" style="margin: auto; margin-bottom: 10px"></span>
          <div class="confirm_button" style="display:none">
            <button type="button" class="btn btn-primary btn_report_revision_confirmed" data-dismiss="modal">はい</button>
            <button type="button" class="btn btn-default btn_report_revision_cancelled" data-dismiss="modal">いいえ
            </button>
          </div>
          <div class="normal_button">

            <button type="button" class="btn btn-primary btn_report_revision">レストア</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<!--End modal report revision data-->

<p class="note"></p>
<p class="error"></p>

<link rel="stylesheet" type="text/css" href="<?php echo $this->Html->url('/ott/')?>css/style_ottreport.css">

<script type="text/javascript">
var report_id       = <?php echo $report_id;?>;
var report_year     = <?php echo $report_info['SsmReport']['year'];?>;
var report_month    = <?php echo $report_info['SsmReport']['month'];?>;
var editReportSlide_url     = "<?php echo $this->Html->url('/ShishimaiApi/editReportSlide')?>";
var deleteReportSlide_url   = "<?php echo $this->Html->url('/ShishimaiApi/deleteReportSlide')?>";
var publicReportMonthUrl    = "<?php echo $this->Html->url('/ShishimaiApi/publicReportMonth')?>";
var reportIndexUrl          = "<?php echo $this->Html->url('/OttReport/index')?>";
var site_id         = "<?php echo $site_id; ?>";
var loadBoxUrl      = "<?php echo $this->Html->url('/ShishimaiApi/getkpibox'); ?>";
var loadCharUrl     = "<?php echo $this->Html->url('/ShishimaiApi/getchartmonth'); ?>";
var reportRevisionUrl       = "<?php echo $this->Html->url('/ShishimaiApi/restoreReportRevision'); ?>";
var month_chart     = $('.chart_image').attr('month');
var year_chart      = $('.chart_image').attr('year');
var month_box       = $('.box_image').attr('month');
var year_box        = $('.box_image').attr('year');
var editOrderNum    = "<?php echo $this->Html->url('/ShishimaiApi/editOrderNum')?>";

var updateImageUrl  = "<?php echo $this->Html->url('/ShishimaiApi/uploadfile/report_id:'. $report_id)?>";
var cv_key          = "<?php echo $report_info['SsmReport']['cv_key']?>";
var site_target_key = "<?php echo $report_info['SsmReport']['site_target_key']?>";
var kpi_list        = "<?php echo $report_info['SsmReport']['kpi_list']?>";

$(document).ready(function(e){
    $('.uploadimage').hide();
});

</script>
<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
<script src="<?php echo $this->Html->url('/ott/')?>js/ajaxEditMonth.js"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
</body>

</html>