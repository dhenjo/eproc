<!DOCTYPE html>
<?php
//print_r($data['captchaImg']);
//die;
?>
<html>
<head>
	<title>Captcha implement in CodeIgniter by CodexWorld</title>
	<style type="text/css">
		#captImg{float:left;}
		.refreshCaptcha {position:relative;top:27px;}
		form{float:left;width:100%;}
	</style>
	
	<script src="<?php echo base_url(); ?>themes/lte/assets/js/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$('.refreshCaptcha').on('click', function(){
				$.get('<?php echo base_url().'captcha/refresh'; ?>', function(data){
					$('#captImg').html(data);
				});
			});
		});
	</script>
</head>
<body>
	<p>Submit the word you see below:</p>
	<p id="captImg"><?php echo $data['captchaImg']; ?></p>
	<a href="javascript:void(0);" class="refreshCaptcha" ><img src="<?php echo base_url().'themes/lte/assets/images/refresh.png'; ?>"/></a>
	<form method="post">
		<input type="text" name="captcha" value=""/>
		<input type="submit" name="submit" value="SUBMIT"/>
	</form>
</body>
</html>