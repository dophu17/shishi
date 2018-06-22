<div class="col-md-6 col-md-offset-3">
      <h2 class="commonh2 center">CSVファイルのインポート情報</h2>

      <form action="" novalidate="novalidate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
      	<div style="display:none;"><input type="hidden" name="_method" value="PUT"></div>

        <div class="form-group">
          <div class="input text"><label for="SsmUserDepartment">年</label>
          	<select name="year" class="form-control">
          		<?php
          		for($i = 2015;$i <= (date('Y'));$i++){
          			?>
          			<option value="<?php echo $i?>" <?php echo ($i == date('Y')? 'selected':"")?> ><?php echo $i?></option>

          			<?php
          		}
          		?>
			</select>
          </div>
        </div>

        <div class="form-group">
			<label>ファイルを選択</label>
			<div class="input file"><input type="file" name="file" class="form-control"></div>
        </div>


        <div class="actions" style="text-align: center;">
			<input type="submit" name="commit" value="入力" class="btn btn-lg btn-primary">
        </div>
    </form>
</div>

