<?php include "header.php"; ?>
<?php 
;$id = 0;
if(isset($_GET['id']) && !empty($_GET['id'])){
	//test ID

	$id = $_GET['id'];
	$db = new Db();
	$cms = $db->fetch("SELECT * FROM objednavky WHERE ID=" . $db->db_escape($id, $db->conn));
	if(!$cms){
		redirect("nenalezeno");
	}
	if($cms['admin_read'] == 0){
		$db->update("objednavky", array("ID" => $cms['ID']), array("admin_read" => 1));
	}
}


$id = 0;
if(isset($_GET['id']) && !empty($_GET['id'])){
	//test ID

	$id = $_GET['id'];
	$db = new Db();
	$cms = $db->fetch("SELECT * FROM objednavky WHERE ID=" . $db->db_escape($id, $db->conn));
	if(!$cms){
		redirect("nenalezeno");
	}

	
}


if(isset($_POST['objednavka_send'])){
	require_once "phpmailer/PHPMailerAutoload.php";

	$db = new Db();

	$data = array(
		"objednatel" => $_POST['nazev'],
		"adresa" => $_POST['adresa'],
		"ic" => $_POST['ic'],
		"dic" => $_POST['dic'],
		"jmeno" => $_POST['jmeno'],
		"prijmeni" => $_POST['prijmeni'],
		"email" => $_POST['email'],
		"telefon" => $_POST['telefon'],
		"info" => $_POST['podnik_info'],
		"provozni_doba" => $_POST['open_hours'],
		"kontakt" => $_POST['kontakt'],
		"propagace" => $_POST['balicek']
	);

	if(isset($_POST['benefity']) && !empty($_POST['benefity'])){
		foreach($_POST['benefity'] as $value){
			$data[$value] = 1;
		}
	}

	$db->update("objednavky", array("ID" => $cms['ID']), $data);

	$cms = $db->fetch("SELECT * FROM objednavky WHERE ID=" . $db->db_escape($id, $db->conn));

	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->setFrom('info@dogcitylife.cz', 'Dogcitylife.cz');
	$mail->addAddress($cms['email'], $cms['jmeno']);     // Add a recipient
	$mail->AddBCC('spoluprace@dogcitylife.cz', 'Dogcitylife.cz');

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Rekapitulace objednávky z webu Dog City Life';

	$mail_content = file_get_contents("emails/objednavka.html");

	$benefity = array();

	if($cms['wifi'] == 1){
		$benefity[] = "Free Wifi";
	}
	if($cms['dog_friendly'] == 1){
		$benefity[] = "Miska s vodou a pelíšek";
	}
	if($cms['jidlo'] == 1){
		$benefity[] = "Žrádlo pro pejska";
	}
	if($cms['parkovani'] == 1){
		$benefity[] = "Parkování";
	}
	if($cms['bezbarier'] == 1){
		$benefity[] = "Bezbariérový přístup";
	}
	if($cms['zahradka'] == 1){
		$benefity[] = "Zahrádka";
	}

	$propagace = "";
	if($cms['propagace'] == 3){
		$propagace = "3 měsíce";
	}elseif($cms['propagace'] == 1){
		$propagace = "1 měsíc";
	}else{
		$propagace = $cms['propagace'] . " měsíců";
	}

	$replace_data = array(
		"{objednatel}" => $cms['objednatel'],
		"{adresa}" => $cms['adresa'],
		"{ic}" => $cms['ic'],
		"{dic}" => $cms['dic'],
		"{kontaktni_osoba}" => $cms['jmeno'],
		"{email}" => $cms['email'],
		"{o_podniku}" => $cms['info'],
		"{provozni}" => $cms['provozni_doba'],
		"{kontakt}" => $cms['kontakt'],
		"{benefity}" => implode(", ", $benefity),
		"{propagace}" => $propagace,
		"{html_text}" => $_POST['content_cms'],
	);

	foreach ($replace_data as $key => $value) {
		$mail_content = str_replace($key, $value, $mail_content);
	}

	$mail->Body = $mail_content;

	if(isset($_FILES['content_file']) && !empty($_FILES['content_file']) && $_FILES['content_file']['size'] != 0){
		$mail->AddAttachment($_FILES['content_file']['tmp_name'], $name = $_FILES['content_file']['name'],  $encoding = 'base64', $type = $_FILES['content_file']['type']);
	}else{
		$mail->AddAttachment('VOP.pdf', $name = 'VOP.pdf',  $encoding = 'base64', $type = 'application/pdf');
	}
	

	if(!$mail->send()) {
	    
	} else {
		if($cms['admin_send'] == 0){
			$db->update("objednavky", array("ID" => $cms['ID']), array("admin_send" => 1));
		}
	    $success = true;
	}
}

if(isset($_POST['object_delete'])){
	$db = new Db();

	$db->db_delete("objednavky", array("ID" => $cms['ID']));
	redirect(get_admin_url() . "objednavky2");
}

?>
<form method="POST" action="" id="cms" enctype="multipart/form-data">
	<?php if(isset($success)): ?>
		<div class="message"><?php _e('Objednávka byla úspěšně odeslána'); ?></div>
	<?php endif; ?>
	<div class="actions_edit">
		<h2>Objednávka č.: <?php echo $cms['ID']; ?></h2>
		<div class="clear"></div>
	</div>
	<button type="submit" name="object_delete">Smazat objednávku</button>
	<p>Datum přidání: <?php echo date("d.m.Y H:i",strtotime($cms['date'])); ?></p>
	<div class="form_block">
		<div class="col">
			<div class="input">
				<label>Název objednatele</label>
				<input type="text"  name="nazev" value="<?php echo $cms['objednatel']; ?>"  id="nazev" placeholder="<?php _e('Název objednatele'); ?> *">
			</div>
			<div class="input">
				<label>Adresa</label>
				<input type="text"  name="adresa" value="<?php echo $cms['adresa']; ?>"  id="adresa" placeholder="<?php _e('Adresa'); ?> *">
			</div>
			<div class="input">
				<label>IČ</label>
				<input type="text"  name="ic" id="ic" value="<?php echo $cms['ic']; ?>" placeholder="<?php _e('IČ'); ?> *">
			</div>
			<div class="input">
				<label>DIČ</label>
				<input type="text"  name="dic" value="<?php echo $cms['dic']; ?>" id="dic" placeholder="<?php _e('DIČ'); ?>">
			</div>		
			<div class="clear"></div>			
		</div>
	</div>
	<div class="form_block">
		<div class="col">
			<div class="input">
				<label>Jméno</label>
				<input type="text"  name="jmeno" id="jmeno" value="<?php echo $cms['jmeno']; ?>" placeholder="<?php _e('Jméno'); ?> *">
			</div>
			<div class="input">
				<label>Příjmení</label>
				<input type="text"  name="prijmeni" id="prijmeni" value="<?php echo $cms['prijmeni']; ?>" placeholder="<?php _e('Příjmení'); ?> *">
			</div>
			<div class="input">
				<label>Email</label>
				<input type="email"  name="email" id="email" value="<?php echo $cms['email']; ?>"  placeholder="<?php _e('E-mail'); ?> *">
			</div>	
			<div class="input">
				<label>Telefon</label>
				<input type="text"  name="telefon" id="telefon" value="<?php echo $cms['telefon']; ?>" placeholder="<?php _e('Telefon'); ?> *">
			</div>
			<div class="clear"></div>
		</div>		
	</div>
	<div class="form_block">
		<div class="col">
			<div class="input textarea">
				<label>Informace o podniku</label>
				<textarea  name="podnik_info" id="podnik_info" placeholder="<?php _e('Informace o podniku'); ?>"><?php echo $cms['info']; ?></textarea>
			</div>
			<div class="input textarea">
				<label>Provozní doba</label>
				<textarea  name="open_hours" id="open_hours" placeholder="<?php _e('Provozní doba'); ?>"><?php echo $cms['provozni_doba']; ?></textarea>
			</div>	
			<div class="clear"></div>
			<div class="input">
				<label>Kontakt</label>
				<input type="text"  name="kontakt" id="kontakt" value="<?php echo $cms['kontakt']; ?>" placeholder="<?php _e('Kontakt'); ?>">
			</div>
			<div class="clear"></div>
		</div>		
	</div>
	<div class="form_block">
		<div class="label"><?php _e('Benefity:'); ?></div>
		<div class="col">
			<div class="multiple">
				<div class="inner checkboxradio">
					<label for="wifi"><?php _e('Free WIFI'); ?></label>
					<input type="checkbox" <?php if($cms['wifi']) echo 'checked'; ?>  name="benefity[]" id="wifi" value="wifi">

					<label for="dog_friendly"><?php _e('Miska s vodou a pelíšek'); ?></label>
					<input type="checkbox" <?php if($cms['dog_friendly']) echo 'checked'; ?>  name="benefity[]" id="dog_friendly" value="dog_friendly">

					<label for="zradlo"><?php _e('Žrádlo pro pejska'); ?></label>
					<input type="checkbox" <?php if($cms['jidlo']) echo 'checked'; ?>  name="benefity[]" id="zradlo" value="jidlo">

					<label for="parkovani"><?php _e('Parkování'); ?></label>
					<input type="checkbox" <?php if($cms['parkovani']) echo 'checked'; ?>  name="benefity[]" id="parkovani" value="parkovani">

					<label for="bezbarier"><?php _e('Bezbariérový přístup'); ?></label>
					<input type="checkbox" <?php if($cms['bezbarier']) echo 'checked'; ?>  name="benefity[]" id="bezbarier" value="bezbarier">

					<label for="zahradka"><?php _e('Zahrádka'); ?></label>
					<input type="checkbox" <?php if($cms['zahradka']) echo 'checked'; ?>  name="benefity[]" id="zahradka" value="zahradka">
				</div>
			</div>
			<div class="clear"></div>
		</div>		
	</div>
	<div class="form_block">
		<div class="label"><?php _e('Propagační balíček:'); ?></div>
		<div class="col">
			<div class="input">
				<select required  name="balicek" id="balicek">
					<option <?php if($cms['propagace'] == 0) echo 'selected'; ?> value="0"><?php _e('0 měsíců'); ?></option>
					<option <?php if($cms['propagace'] == 1) echo 'selected'; ?> value="1"><?php _e('1 měsíc'); ?></option>
					<option <?php if($cms['propagace'] == 3) echo 'selected'; ?> value="3"><?php _e('3 měsíce'); ?></option>
					<option <?php if($cms['propagace'] == 6) echo 'selected'; ?> value="6"><?php _e('6 měsíců'); ?></option>
					<option <?php if($cms['propagace'] == 9) echo 'selected'; ?> value="9"><?php _e('9 měsíců'); ?></option>
					<option <?php if($cms['propagace'] == 12) echo 'selected'; ?> value="12"><?php _e('12 měsíců'); ?></option>
				</select>
			</div>
			<div class="clear"></div>
		</div>		
	</div>
	<h3>Zpráva pro žadatele</h3>
	<textarea class="tinymce" name="content_cms"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim venia fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </p></textarea>
	<p>Přiložené soubory:</p>
	<input type="file" name="content_file">
	<div class="actions" style="margin-top: 30px;">
		<button type="submit" name="objednavka_send">Odeslat</button>
	</div>
</form>
<?php include "footer.php"; ?>