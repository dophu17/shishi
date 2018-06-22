<div class="baseConainer">

    <div class="mainContents nofooter">
        <div class="">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="commonh2 center">スライド作成</h2>
                    <form class="new_oni_slide" id="new_oni_slide" enctype="multipart/form-data" action="#" accept-charset="UTF-8" method="post">
                        
                        <div class="form-group">
                            <label>タイトル</label>
                            
                            <?php echo $this->Form->input('title', array('label' => false, 'name' => 'title','type' => 'text','class' => 'form-control')); ?>
                            <p style="color:#f50904;"><?php echo $error; ?></p>
                        </div>
                        <div class="form-group description">
                            <label>キーメッセージ</label>
                            <?php echo $this->Form->textarea('description', array('label' => false, 'name' => 'description','class' => 'form-control')); ?>                       
                        </div>
                        <!--choose 1-->
                         <input type="hidden" name="type_image" class="image_slide_title" value="image_slide" /> 

                        <!--choose 2-->
                        <!-- <div class="form-group">
                            <div>
                                <label>
                                    <input type="radio" value="image_slide_title" checked="checked" name="type_image" class="image_slide_title" /> 画像ファイルのみ
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="radio" value="image_slide" name="type_image" class="image_slide"/> 画像ファイル＋みんなでコメントを入れる
                                </label>
                            </div>
                        </div> -->
                        <div id="modify">
                            <label id="modifyM">ファイル</label>
                            <?php echo $this->Form->input("info_image", array("type" => "file")); ?>
                        </div>
                        <div style="text-align: center; margin-bottom: 50px;">
                            <input type="submit" name="commit" value="作成" class="btn btn-lg btn-primary" />
                        </div>
                    </form>
                
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<script type="text/javascript">
$(document).ready(function(){
    $('#modify .input label').remove();
//     $(".description").hide();
//     $('input[type=radio]').change(function(){
//          $(".description").toggle();
//     });

});

</script>

</html>