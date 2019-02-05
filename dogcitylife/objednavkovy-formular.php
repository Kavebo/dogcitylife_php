<?php 
require_once "admin/functions.php";
require_once "admin/db.php";

$db = new Db();

if(get_current_lang() != "cs"){
	if($_404){
		redirect('nenalezeno');
	}
}

if(isset($_POST['objednavka_submit'])){
	//handle submit


	$data = array(
		"date" => date("Y-m-d H:i:s"),
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

	$result = $db->insert("objednavky", $data);

	require_once "admin/phpmailer/PHPMailerAutoload.php";

	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->setFrom($_POST['email'], $_POST['nazev']);
	$mail->addAddress('spoluprace@dogcitylife.cz', 'Dogcitylife.cz');

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Nová objednávka na Dog City Life';
	$mail->Body    = 'Nová objednávka propagace DogCityLife - <a href="' . get_admin_url() . "objednavka?id=" . $result . '">více v administraci</a>';

	if(!$mail->send()) {
	    
	} else {
	    $success = true;
	}

}

?>
<?php include "header.php"; ?>
	<div class="container cms_page">
		<h1><?php _e('Objednávkový formulář'); ?></h1>
		<div class="cms_content objednavkovy_form">
			<form method="post" action="">
				<?php if(isset($success)): ?>
					<div class="message"><?php _e('Objednávka byla úspěšně odeslána'); ?></div>
				<?php endif; ?>
				<div class="form_block">
					<div class="label"><?php _e('Objednatel:'); ?></div>
					<div class="col">
						<div class="input">
							<input type="text" name="nazev" required id="nazev" placeholder="<?php _e('Název objednatele'); ?> *">
						</div>
						<div class="input">
							<input type="text" name="adresa" required id="adresa" placeholder="<?php _e('Adresa'); ?> *">
						</div>
						<div class="input">
							<input type="text" name="ic" id="ic" required placeholder="<?php _e('IČ'); ?> *">
						</div>
						<div class="input">
							<input type="text" name="dic" id="dic" placeholder="<?php _e('DIČ'); ?>">
						</div>		
						<div class="clear"></div>			
					</div>
				</div>
				<div class="form_block">
					<div class="label"><?php _e('Kontaktní osoba:'); ?></div>
					<div class="col">
						<div class="input">
							<input type="text" name="jmeno" id="jmeno" required placeholder="<?php _e('Jméno'); ?> *">
						</div>
						<div class="input">
							<input type="text" name="prijmeni" id="prijmeni" required placeholder="<?php _e('Příjmení'); ?> *">
						</div>	
						<div class="input">
							<input type="email" name="email" id="email" required placeholder="<?php _e('E-mail'); ?> *">
						</div>
						<div class="input">
							<input type="text" name="telefon" id="telefon" required placeholder="<?php _e('Telefon'); ?> *">
						</div>							
						<div class="clear"></div>
					</div>	
				</div>
				<div class="form_block">
					<div class="label"><?php _e('O podniku:'); ?></div>
					<div class="col">
						<div class="input textarea">
							<textarea name="podnik_info" id="podnik_info" placeholder="<?php _e('Informace o podniku (všechny důležité informace, které chcete zveřejnit a uvidí je uživatel webu v detailu Vašeho podniku)'); ?>"></textarea>
						</div>
						<div class="input textarea">
							<textarea name="open_hours" id="open_hours" placeholder="<?php _e('Provozní doba'); ?>"></textarea>
						</div>	
						<div class="clear"></div>
						<div class="input contact_input">
							<input type="text" name="kontakt" id="kontakt" placeholder="<?php _e('Kontakt pro zákazníky (instagram, FB, e-mail, telefon...)'); ?>">
						</div>
						<div class="clear"></div>
					</div>		
				</div>
				<div class="form_block">
					<div class="label"><?php _e('Benefity:'); ?></div>
					<div class="col">
						<div class="multiple">
							<div class="inner">
								<label for="wifi"><?php _e('Free WIFI'); ?></label>
								<input type="checkbox" name="benefity[]" id="wifi" value="wifi">

								<label for="dog_friendly"><?php _e('Miska s vodou a pelíšek'); ?></label>
								<input type="checkbox" name="benefity[]" id="dog_friendly" value="dog_friendly">

								<label for="zradlo"><?php _e('Žrádlo pro pejska'); ?></label>
								<input type="checkbox" name="benefity[]" id="zradlo" value="jidlo">
								<div class="clear"></div>
								<label for="parkovani"><?php _e('Parkování'); ?></label>
								<input type="checkbox" name="benefity[]" id="parkovani" value="parkovani">

								<label for="bezbarier"><?php _e('Bezbariérový přístup'); ?></label>
								<input type="checkbox" name="benefity[]" id="bezbarier" value="bezbarier">

								<label for="zahradka"><?php _e('Zahrádka'); ?></label>
								<input type="checkbox" name="benefity[]" id="zahradka" value="zahradka">
							</div>
						</div>
						<div class="clear"></div>
					</div>		
				</div>
				<div class="form_block">
					<div class="label"><?php _e('Propagační balíček:'); ?></div>
					<div class="col">
						<div class="input">
							<select required name="balicek" id="balicek">
								<option value="0"><?php _e('0 měsíců'); ?></option>
								<option value="1"><?php _e('1 měsíc'); ?></option>
								<option value="3"><?php _e('3 měsíce'); ?></option>
								<option value="6"><?php _e('6 měsíců'); ?></option>
								<option value="9"><?php _e('9 měsíců'); ?></option>
								<option value="12"><?php _e('12 měsíců'); ?></option>
							</select>
						</div>
						<div class="clear"></div>
					</div>		
				</div>
				<div class="clear"></div>
				<button type="submit" name="objednavka_submit"><?php _e('Odeslat'); ?></button>
			</form>
		</div>
	    <div class="clear"></div>
	</div>
<?php include "footer.php"; ?>