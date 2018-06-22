<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2 class="commonh2 center">アリガトウ送る</h2>

      <?php echo $this->Form->create('Thank', array('role' => 'form','type'=>'file', 'novalidate' => true)); ?>

        <div class="form-group">
          <?php echo $this->Form->input('to', array('class' => 'form-control', 'placeholder' => '','label' => 'To','options'=>$admin_option));?>
          <p style="color: red"><?php echo (isset($error_to) ? $error_to : '');?></p>
        </div>

        <div class="form-group" style="position: relative;">
          <img style="position: absolute;left: 59px;top: -3px;width: 25px;" src="<?php echo $this->Html->url('/ott/img/face.png')?>" class="emo">
          <?php echo $this->Form->input('msg', array('type'=>'textarea','class' => 'form-control', 'placeholder' => '','label' => 'コメント'));?>
          <p style="color: red"><?php echo (isset($error_msg) ? $error_msg : '');?></p>
        </div>

        <div class="actions" style="text-align: center;">
          <input type="submit" value="送信" class="btn btn-lg btn-primary">
        </div>
      <?php echo $this->Form->end() ?>
  </div>
</div>


<!--Modal emoji-->
<div class="modal fade" id="emo_modal">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-body">
            <ul id="_emoticonGallery" class="_emoticonGallery emoticonTooltip__emoticonGallery">
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_smile.gif" class="emoticonTooltip__emoticon" title="Smile" alt=":)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_sad.gif" class="emoticonTooltip__emoticon" title="Sad" alt=":(">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_more_smile.gif" class="emoticonTooltip__emoticon" title="Laugh" alt=":D">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_lucky.gif" class="emoticonTooltip__emoticon" title="Nice!" alt="8-)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_surprise.gif" class="emoticonTooltip__emoticon" title="Shocked!" alt=":o">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_wink.gif" class="emoticonTooltip__emoticon" title="Wink" alt=";)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_tears.gif" class="emoticonTooltip__emoticon" title="Cry" alt=";(">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_sweat.gif" class="emoticonTooltip__emoticon" title="Worry" alt="(sweat)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_mumu.gif" class="emoticonTooltip__emoticon" title="Hmm..." alt=":|">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_kiss.gif" class="emoticonTooltip__emoticon" title="Kiss!" alt=":*">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_tongueout.gif" class="emoticonTooltip__emoticon" title="Goofy" alt=":p">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_blush.gif" class="emoticonTooltip__emoticon" title="Blush" alt="(blush)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_wonder.gif" class="emoticonTooltip__emoticon" title="Whaaat?" alt=":^)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_snooze.gif" class="emoticonTooltip__emoticon" title="Sleeepy" alt="|-)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_love.gif" class="emoticonTooltip__emoticon" title="Love" alt="(inlove)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_grin.gif" class="emoticonTooltip__emoticon" title="Grin" alt="]:)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_talk.gif" class="emoticonTooltip__emoticon" title="Talk" alt="(talk)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_yawn.gif" class="emoticonTooltip__emoticon" title="Yawn" alt="(yawn)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_puke.gif" class="emoticonTooltip__emoticon" title="Bleh!" alt="(puke)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_ikemen.gif" class="emoticonTooltip__emoticon" title="Emo" alt="(emo)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_otaku.gif" class="emoticonTooltip__emoticon" title="Nerd" alt="8-|">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_ninmari.gif" class="emoticonTooltip__emoticon" title="Heh, heh, heh" alt=":#)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_nod.gif" class="emoticonTooltip__emoticon" title="Nod" alt="(nod)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_shake.gif" class="emoticonTooltip__emoticon" title="Shake" alt="(shake)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_wry_smile.gif" class="emoticonTooltip__emoticon" title="Wry Grin" alt="(^^;)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_whew.gif" class="emoticonTooltip__emoticon" title="Whew!" alt="(whew)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_clap.gif" class="emoticonTooltip__emoticon" title="Clap" alt="(clap)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_bow.gif" class="emoticonTooltip__emoticon" title="Bow" alt="(bow)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_roger.gif" class="emoticonTooltip__emoticon" title="Roger!" alt="(roger)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_muscle.gif" class="emoticonTooltip__emoticon" title="Muscle" alt="(flex)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_dance.gif" class="emoticonTooltip__emoticon" title="Dance!" alt="(dance)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_komanechi.gif" class="emoticonTooltip__emoticon" title="Rock!" alt="(:/)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_devil.gif" class="emoticonTooltip__emoticon" title="Devil" alt="(devil)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_star.gif" class="emoticonTooltip__emoticon" title="Star" alt="(*)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_heart.gif" class="emoticonTooltip__emoticon" title="Heart" alt="(h)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_flower.gif" class="emoticonTooltip__emoticon" title="Flower" alt="(F)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_cracker.gif" class="emoticonTooltip__emoticon" title="Firecracker" alt="(cracker)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_cake.gif" class="emoticonTooltip__emoticon" title="Cake" alt="(^)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_coffee.gif" class="emoticonTooltip__emoticon" title="Coffee" alt="(coffee)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_beer.gif" class="emoticonTooltip__emoticon" title="Beer" alt="(beer)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_handshake.gif" class="emoticonTooltip__emoticon" title="Handshake" alt="(handshake)">
                </li>
                <li class="emoticonTooltip__emoticonContainer">
                    <img src="https://assets.chatwork.com/images/emoticon2x/emo_yes.gif" class="emoticonTooltip__emoticon" title="Yes" alt="(y)">
                </li>
            </ul>
        </div>
    </div>
  </div>
</div>

<!--End modal emoji-->

<script>
$(document).ready(function(){
    $('.emo').click(function(){
      $('#emo_modal').modal('show');
    });

    $(".emoticonTooltip__emoticon").on('click', function() {
        insertAtCursor(document.getElementById('ThankMsg'), $(this).attr('alt'));
        $('#emo_modal').modal('hide');
        $("#ThankMsg").focus();
    });
});


function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    // Microsoft Edge
    else if(window.navigator.userAgent.indexOf("Edge") > -1) {
      var startPos = myField.selectionStart; 
      var endPos = myField.selectionEnd; 

      myField.value = myField.value.substring(0, startPos)+ myValue 
             + myField.value.substring(endPos, myField.value.length); 

      var pos = startPos + myValue.length;
      myField.focus();
      myField.setSelectionRange(pos, pos);
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}

</script>
<style>
#_emoticonGallery{
    display: flex;
    flex-wrap: wrap;
    padding: 5px;
    list-style: none;
}
.emoticonTooltip__emoticonContainer{
    border-radius: 3px;
    transition: border .2s linear 0s;
    box-sizing: border-box;
    display: -webkit-box;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    width: 37px;
    height: 33px;
    border: 1px solid transparent;
    cursor: pointer;
}
.emoticonTooltip__emoticon{
    width: 20px;
    height: 20px;
}
</style>