<div class="baseConainer">
    <div class="mainContents nofooter">
        <div class="reportContainer readOnly">
            <div class="reportHeaderBtns">
                <button class="btn btn-default" id="send-google-slide">Send to Google Slide</button>
                <?php if($user_role == 'admin'){
                    echo $this->Html->link("編集", array("action" => "edit_month", 'report_id' => $report_id) ,array('class'=>'btn btn-default'));
                }
                ?>
            </div>
             <?php 
            if($slide['SsmReportSlide']['id'] ){
            }
            ?>
            
            <!--Loop slile-->
            <?php
            foreach($slides as $slide){

                //Begin Slide
                if($slide['SsmReportSlide']['options'] == 'title_slide'){                   
                    ?>

                <div class="box" report_slide_id="<?php echo $slide['SsmReportSlide']['id'];?>" options="<?php echo $slide['SsmReportSlide']['options']; ?>" orderNumber="<?php echo $slide['SsmReportSlide']['order_num']; ?>" >
                    <section class="reportSection">
                        <div class="reportSectionFormWrapper">
                            <form accept-charset="UTF-8" method="post">
                                <h4 class="reportCoverTitle">
                                    <p class="reportKeyMessage editableItem"><?php echo $this->Shishimai->return_link(nl2br($slide['SsmReportSlide']['title'])) ?></p>

                                   <textarea class="form-control editableField edit_title" rows="5" ><?php
                                       echo $slide['SsmReportSlide']['title'];
                                    ?></textarea>

                                    </h4>
                            </form>

                        </div>
                        <div class="reportSection__footer clearfix">
                            <div class="reportSection__footer__right">
                                <a target="_blank" style="color: #555;" href="<?php echo $site_name['SsmSite']['site_url']; ?>"><?php echo $site_name['SsmSite']['site_url']; ?></a>
                            </div>
                        </div>
                    </section>
                </div>

                <?php
               
                }elseif($slide['SsmReportSlide']['options'] == 'chart_slide'){
                    //Chart
                    ?>

                    <div class="box" report_slide_id="<?php echo $slide['SsmReportSlide']['id'];?>" options="<?php echo $slide['SsmReportSlide']['options']; ?>" orderNumber="<?php echo $slide['SsmReportSlide']['order_num']; ?>" >
                        <section class="reportSection">
                            <div class="reportSectionFormWrapper">
                                <form action="" accept-charset="UTF-8" method="post">
                                    <input name="utf8" type="hidden" value="&#x2713;" />
                                    <h4 class="projectShowh4">
                    <span class="editableItem"><?php echo $this->Shishimai->return_link($slide['SsmReportSlide']['title']);?></span>
                    <input class="form-control editableField edit_title" value="<?php echo $slide['SsmReportSlide']['title'];?>" />
                  </h4>
                                    <p class="reportKeyMessage editableItem"><?php echo $this->Shishimai->return_link(nl2br($slide['SsmReportSlide']['description']));?></p>
                                    <textarea class="form-control editableField edit_description" rows="5"><?php echo $slide['SsmReportSlide']['description'];?></textarea>
                                </form>
                            </div>
                            <div class="chart_image" month="<?php echo $report_info['SsmReport']['month']; ?>" year="<?php echo $report_info['SsmReport']['year']; ?>" style="position: relative; min-height: 150px">
                                <!--Chart display here-->
                                <div id="loader"></div>
                            </div>
                            <div class="reportSection__footer clearfix">
                                <div class="reportSection__footer__right">
                                    <a target="_blank" style="color: #555;" href="<?php echo $site_name['SsmSite']['site_url']; ?>"><?php echo $site_name['SsmSite']['site_url']; ?></a>
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
                                        <h4 class="projectShowh4">
                    <span class="editableItem"><?php echo $this->Shishimai->return_link($slide['SsmReportSlide']['title']);?></span>
                    <input class="form-control editableField edit_title" type="text" value="<?php echo $slide['SsmReportSlide']['title'];?>" name="" />
                  </h4>
                                        <p class="reportKeyMessage editableItem"><?php echo $this->Shishimai->return_link(nl2br($slide['SsmReportSlide']['description']));?></p>
                                        <textarea class="form-control editableField edit_description" rows="5"><?php echo $slide['SsmReportSlide']['description'];?></textarea>
                                    </form>
                                </div>
                                <div class="box_image" month="<?php echo $report_info['SsmReport']['month']; ?>" year="<?php echo $report_info['SsmReport']['year']; ?>" style="position: relative; min-height: 150px">
                                <!--Box display here-->
                                <div id="loader"></div>
                                </div>
                                <div class="reportSection__footer clearfix">
                                    <div class="reportSection__footer__right">
                                        <a target="_blank" style="color: #555;" href="<?php echo $site_name['SsmSite']['site_url']; ?>"><?php echo $site_name['SsmSite']['site_url']; ?></a>
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
                                        <h4 class="projectShowh4">
                    <span class="editableItem"><?php echo $slide['SsmReportSlide']['title'];?></span>
                    <input class="form-control editableField edit_title" type="text" value="<?php echo $slide['SsmReportSlide']['title'];?>" name="" />
                  </h4>
                                        <p class="reportKeyMessage editableItem"><?php echo $this->Shishimai->return_link(nl2br($slide['SsmReportSlide']['description']));?></p>
                                        <textarea class="form-control editableField edit_description" rows="5"><?php echo $slide['SsmReportSlide']['description'];?></textarea>
                                    </form>
                                </div>

                                <div class="file_img" style="width: 100%; text-align: center">
                                <?php if(trim($slide['SsmReportSlide']['data']) != ""){
                                    ?>
                                    <img style="max-width:100%" src="<?php echo $this->Html->url('/uploads/ott/report/' . $slide['SsmReportSlide']['data']);?>  " >
                                    <?php
                                }
                                ?>
                                </div>

                                <div class="reportSection__footer clearfix">
                                    <div class="reportSection__footer__right">
                                        <a target="_blank" style="color: #555;" href="<?php echo $site_name['SsmSite']['site_url']; ?>"><?php echo $site_name['SsmSite']['site_url']; ?></a>
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
                                    <form method="post">
                                        <input name="utf8" type="hidden" value="&#x2713;" />
                                        <h4 class="projectShowh4">
                    <span class="editableItem"><?php echo $this->Shishimai->return_link($slide['SsmReportSlide']['title']);?></span>
                    <input class="form-control editableField edit_title" type="text" value="<?php echo $slide['SsmReportSlide']['title'];?>" name="" />
                  </h4>
                                    </form>
                                </div>

                                <div class="file_img" style="width: 100%; text-align: center">
                                <?php if(trim($slide['SsmReportSlide']['data']) != ""){
                                    ?>
                                    <img style="max-width:100%" src="<?php echo $this->Html->url('/uploads/ott/report/' . $slide['SsmReportSlide']['data']);?>  " >
                                    <?php
                                    }
                                ?>
                                </div>
                                <div class="reportSection__footer clearfix">
                                    <div class="reportSection__footer__right">
                                        <a target="_blank" style="color: #555;" href="<?php echo $site_name['SsmSite']['site_url']; ?>"><?php echo $site_name['SsmSite']['site_url']; ?></a>
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
                </div>
              <!--   <div class="reportSection__footer clearfix">
                    <div class="reportSection__footer__right">
                        <a target="_blank" style="color: #555;" href="<?php echo $site_name['SsmSite']['site_url']; ?>"><?php echo $site_name['SsmSite']['site_url']; ?></a>
                    </div>
                </div> -->
            </section>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo $this->Html->url('/ott/')?>css/style_ottreport.css">
<script type="text/javascript">
    var loadBoxUrl = "<?php echo $this->Html->url('/ShishimaiApi/getkpibox'); ?>";
    var loadCharUrl = "<?php echo $this->Html->url('/ShishimaiApi/getchartmonth'); ?>";
    var month_chart = $('.chart_image').attr('month');
    var year_chart = $('.chart_image').attr('year');
    var month_box = $('.box_image').attr('month');
    var year_box = $('.box_image').attr('year');
    var site_id = "<?php echo $site_id?>";
    var cv_key = "<?php echo $report_info['SsmReport']['cv_key']?>";
    var site_target_key = "<?php echo $report_info['SsmReport']['site_target_key']?>";
    var kpi_list = "<?php echo $report_info['SsmReport']['kpi_list']?>";
    var report_id = "<?php echo $report_id ?>";
</script>
<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
<script src="<?php echo $this->Html->url('/ott/')?>js/ajaxEditMonth.js"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
</body>

</html>