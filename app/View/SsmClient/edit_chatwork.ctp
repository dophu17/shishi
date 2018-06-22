<div class="mainContents nofooter">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2 class="commonh2 center">ChatWork連携設定</h2>

                <div class="well">
                    <p>
                        ChatWorkと連携することで、タスクやレポートに関する通知をChatWorkに送信することができます。
                        <br/>
                        <br/> まずはChatWorkの画面よりChatWork APIトークンを取得してください。
                        <br/> （※&nbsp;数営業日かかります。）
                    </p>
                    <label>---&nbsp;APIトークン取得方法&nbsp;---</label>
                    <ol>
                        <li><a target="_blank" href="https://www.chatwork.com/">ChatWork</a>にユーザー登録・ログインする</li>
                        <li><a target="_blank" href="https://www.chatwork.com/service/packages/chatwork/subpackages/api/apply_beta.php">API利用申請</a>のページから申し込みを行う</li>
                        <li>数営業日待つと、ChatWorkよりAPIトークン発行完了通知が届きます</li>
                        <li><a target="_blank" href="https://www.chatwork.com/">ChatWork</a>にログインし、「右上のユーザー名」 > 「動作設定」 > 「API発行」タブを開いてパスワードを入力してAPIトークンを表示する</li>
                    </ol>
                    <p>
                        上記で確認したAPIトークンを下記フォームに入力、保存してください。
                    </p>
                </div>

                <form class="edit_user" accept-charset="UTF-8" method="post">
                    <div class="form-group">
                        <label>ChatWorkAPIトークン</label>
                        <div class="form-group">
                            <input class="form-control" type="password" name="cw_api" onchange="$(this).val($(this).val().trim());" />
                        </div>
                    </div>
                    <input type="hidden" name="hidden_data" value='<?php echo $hidden_data; ?>' />
                    <div class="actions" style="text-align: center; padding: 50px 0;">
                        <input type="submit" name="commit" value="保存" class="btn btn-lg btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>
</body>
<script type="text/javascript">
var x = $('input[name=hidden_data]').val();
var n = x.includes("fromEdit");
var m = x.includes("fromAdd");
var ssmclientEditUrl = "<?php echo $this->Html->url('/SsmClient/edit/site_id:' . $site_id); ?>";

var ssmclientAddtUrl = "<?php echo $this->Html->url('/SsmClient/add'); ?>";
if(n){
    $('.edit_user').attr('action', ssmclientEditUrl);
} else if(m){
    $('.edit_user').attr('action', ssmclientAddtUrl);
}
</script>
</html>