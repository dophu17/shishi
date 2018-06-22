
<div class="row">
    <div class="col-md-8 col-md-offset-2">
      <h2 class="commonh2 center">ChatWork連携設定</h2>

      <div class="well">
        <p>
          ChatWorkと連携することで、タスクやレポートに関する通知をChatWorkに送信することができます。<br>
          <br>
          まずはChatWorkの画面よりChatWork APIトークンを取得してください。<br>
          （※&nbsp;数営業日かかります。）          
        </p>
        <label>---&nbsp;APIトークン取得方法&nbsp;---</label>
        <ol>
          <li><a target="_blank" href="https://www.chatwork.com/">ChatWork</a>にユーザー登録・ログインする</li>
          <li><a target="_blank" href="https://www.chatwork.com/service/packages/chatwork/subpackages/api/apply_beta.php">API利用申請</a>のページから申し込みを行う</li>
          <li>数営業日待つと、ChatWorkよりAPIトークン発行完了通知が届きます</li>
          <li><a target="_blank" href="https://www.chatwork.com/">ChatWork</a>にログインし、「右上のユーザー名」 &gt; 「動作設定」 &gt; 「API発行」タブを開いてパスワードを入力してAPIトークンを表示する</li>
        </ol>
        <p>
          上記で確認したAPIトークンを下記フォームに入力、保存してください。
        </p>
      </div>

      <form class="edit_user" action="#" accept-charset="UTF-8" method="post">
        <div class="form-group">
          <label>ChatWorkAPIトークン</label>
          <div class="form-group">
            <input class="form-control" type="password" class="chatwork_api" name="chatwork_api" onchange="$(this).val($(this).val().trim());">
          </div>
        </div>

        <div class="actions" style="text-align: center; padding: 50px 0;">
          <span class="btn btn-lg btn-primary submit">保存</span>
        </div>
</form>    </div>
  </div>


<style type="text/css">
.note {
background-color: #5cb85c;
border-color: #d6e9c6;
color: #fcf8e3;
padding: 15px;
position: fixed;
bottom: 0;
left: 0;
width: 100%;
margin-bottom: 0;
z-index: 1000;
}

.error {
background-color: #ce3131;
border-color: #faebcc;
color: #f5f5f5;
padding: 15px;
position: fixed;
bottom: 0;
left: 0;
width: 100%;
margin-bottom: 0;
z-index: 1000;
}
</style>
<p class="error"></p>
<p class="note"></p>
<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
<script type="text/javascript">
var editchatworkUrl = "<?php echo $this->Html->url('/OttUser/editchatwork');?>"

$('.error').hide();
$('.note').hide();

  $('.submit').click(function(){
    var chatwork_api = $("input[name=chatwork_api]").val();
    $.post(
      editchatworkUrl,
      {
        chatwork_api: chatwork_api
      },
      function(res){
        if(res.trim() == 'success'){
          setTimeout(function(){
              $('.note').text('キーチャットワークは保存されました。').fadeIn();
          }, 100);
          setTimeout(function(){
              $('.note').fadeOut();
          }, 2500);
         
        } else if(res.trim() == 'empty'){
          setTimeout(function(){
              $('.error').text('キーチャットワークは空いてます。キーを記入してください').fadeIn();
          }, 100);
          setTimeout(function(){
              $('.error').fadeOut();
          }, 2500);
        } else{
          setTimeout(function(){
              $('.error').text('Chatworkとの連携と失敗しました。APIトークンをご確認ください。').fadeIn();
          }, 100);
          setTimeout(function(){
              $('.error').fadeOut();
          }, 2500);
        }
      }
      );
  });

</script>
