<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="<?php echo $this->Html->url('/ott')?>/js/ott.js"></script>
<form id="uploadimage" action="" method="post" enctype="multipart/form-data"><input type="file" name="file" id="file" required /></form>
<button class="btn_upload">upload</button>

<script type="text/javascript">
$(document).ready(function(){
	$('.btn_upload').click(function(){
		//Xu lý title,des
		$.post('http://shishimai.dev/ShishimaiApi/xltitle',{},function(msg){
			if(msg.trim() == 'success'){
				$('#uploadimage').submit();

			}else{
				alert('xl title thất bại');
			}
		});
	});

	//Post ajax
	$('#uploadimage').on('submit',function(e){
		e.preventDefault();
		$.ajax({
		url: "http://shishimai.dev/ShishimaiApi/uploadfile", // Url to which the request is send
		type: "POST",             // Type of request to be send, called as method
		data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		contentType: false,       // The content type used when sending data to the server.
		cache: false,             // To unable request pages to be cached
		processData:false,        // To send DOMDocument or non processed data file it is set to false
		success: function(data)   // A function to be called if request succeeds
		{
			alert('success');
		}
		});
	});
});
</script>