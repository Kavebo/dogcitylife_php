<?php 
require_once "db.php";
require_once "functions.php";

if(isset($_SESSION['login_admin'])){
	redirect(get_admin_url());
}

if(isset($_POST['action_login'])){
	$errors = array();
	//EMPTY = ERROR
	if(empty($_POST['login'])){
		$errors[] = "Vyplňte email"; 
	}

	if(empty($_POST['password'])){
		$errors[] = "Vyplňte heslo"; 
	}

	$user = login_admin($_POST['login'], $_POST['password']);

	if(isset($user) && !empty($user) && $user){
		//var_dump($user);
		redirect(get_admin_url());
	}else{
		//THROW ERROR
		if(empty($errors))
			$errors[] = "Neexistující uživatel nebo nesprávné heslo";
	}

}
?>
<?php include "header_login.php"; ?>
	<div class="login_form">
		<?php if(!empty($errors)): ?>
			<ul class="errors">
				<?php foreach($errors as $error): ?>
					<li><?php echo $error; ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<img src="<?php echo get_admin_url() . "img/logo.png"; ?>">
		<form method="post" action="">
			<div class="input">
				<label for="login">Login:</label>
				<input class="" id="login" type="text" name="login" value="<?php if(isset($_POST['login'])) echo $_POST['login']; ?>">
			</div>
			<div class="clear"></div>
			<div class="hr"></div>
			<div class="input">
				<label for="password">Heslo:</label>
				<input class="" id="password" type="password" name="password" value="">
			</div>
			<div class="clear"></div>
			<div class="input">
				<button class="button" name="action_login" type="submit">Přihlásit</button>
			</div>
			<div class="clear"></div>
		</form>
	</div>
<?php include "footer_login.php"; ?>