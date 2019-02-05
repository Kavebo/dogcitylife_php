<?php 
require_once "admin/functions.php";
require_once "admin/db.php";

$db = new Db();

$cms = $db->fetch("SELECT * FROM cms WHERE ID=4");

if(get_current_lang() != "en" && !$cms){
	if($_404){
		redirect('nenalezeno');
	}
}
$success = false;
if(isset($_POST['email_submit'])){
	require_once "admin/phpmailer/PHPMailerAutoload.php";

	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->setFrom($_POST['email_email'], $_POST['email_name'] . " " . $_POST['email_surname']);
	$mail->addAddress('info@dogcitylife.cz', 'Dogcitylife.cz');     // Add a recipient

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Zpráva z kontaktního formuláře Dog City Life';
	$mail->Body    = 'Zpráva z kontaktního formuláře:<br><br>Jméno: ' . $_POST['email_name'] . "<br>Příjmení: " . $_POST['email_surname'] . "<br>Email: " . $_POST['email_email'] . "<br>Zpráva: " . $_POST['email_text'];

	if(!$mail->send()) {
	    
	} else {
	    $success = true;
	}
}

?>
<?php include "header.php"; ?>
	<div class="container cms_page kontakt_page">
		<h1><?php echo $cms['name']; ?></h1>
		<div class="cms_content">
			<div class="cms_podminky">
			<div class="left_side"><?php _e('Napište nám'); ?></div>
			<div class="right_content">
				<form method="post" action="">
					<?php if($success): ?>
						<div class="message"><?php _e('Email byl úspěšně odeslán.'); ?></div>
					<?php endif; ?>
					<div class="form_third">
						<div class="input">
							<input type="text" name="email_name" required placeholder="<?php _e('Jméno *'); ?>">
						</div>
					</div>
					<div class="form_third">
						<div class="input">
							<input type="text" name="email_surname" placeholder="<?php _e('Příjmení'); ?>">
						</div>
					</div>
					<div class="form_third last">
						<div class="input">
							<input type="email" name="email_email" required placeholder="<?php _e('E-mail *'); ?>">
						</div>
					</div>
					<div class="clear"></div>
					<div class="input">
						<textarea name="email_text" required placeholder="<?php _e('Vaše zpráva *'); ?>"></textarea>
					</div>
					<p class="req"><?php _e('Všechny údaje označené * jsou povinné'); ?></p>
					<div class="clear"></div>
					<button type="submit" name="email_submit"><?php _e('Odeslat'); ?></button>
					<div class="clear"></div>
				</form>
			</div>
			<div class="clear">&nbsp;</div>
			</div>
			<?php echo $cms['content']; ?>
		</div>
	    <div class="clear"></div>
	</div>
<?php include "footer.php"; ?>