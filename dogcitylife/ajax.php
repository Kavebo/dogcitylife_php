<?php 
require_once "admin/functions.php";
require_once "admin/db.php";

if(!empty($_REQUEST) && isset($_REQUEST)){
	foreach($_REQUEST as $key => $value){
		if(function_exists($key)){
			switch ($value) {
				case 'get':
					call_user_func($key, $_GET);
				break;

				case 'post':
					call_user_func($key, $_POST);
				break;
				
				default:
					call_user_func($key, $_POST);
				break;
			}
		}
	}
}

function ajax_check_email($data){
	$email = $data['email'];
	$db = new Db();
	$result = $db->fetch("SELECT * FROM users WHERE email LIKE '" . $email . "'");

	if($result){
		$result = false;
	}else{
		$result = true;
	}

	echo json_encode($result);

	die();
}

function ajax_check_login($data){
	$user = login_front($data['email'], $data['password']);
	if($user){
		echo 'true';
	}else{
		_e('Špatné jméno nebo heslo');
	}
	die();
}

function ajax_add_review($data){
	$db = new Db();

	$result = $db->insert("reviews", array("user_ID" => is_user_logged_in_front(), "zarizeni_ID" => $data['zarizeni_ID'], "datum" => date('Y-m-d H:i:s'), "popis" => $data['popis'], "obsluha" => $data['obsluha'], "dog_friendly" => $data['dog_friendly'], "jidlo" => $data['jidlo'], "prostredi" => $data['prostredi']));

	if($result){
		echo "true";
	}

	die();
}

function ajax_add_favorite($data){
	$db = new Db();

	$result = $db->insert("favorite", array("user_ID" => is_user_logged_in_front(), "zarizeni_ID" => $data['zarizeni_ID']));

	if($result){
		echo "true";
	}

	die();
}

function ajax_remove_favorite($data){
	$db = new Db();

	$SQL = "DELETE FROM favorite WHERE user_ID=" . is_user_logged_in_front() . " AND zarizeni_ID=" . $data['zarizeni_ID'];
	$result = mysqli_query($db->conn, $SQL);

	if($result){
		echo "true";
	}

	die();
}

function ajax_remove_newsletter($data){
	$db = new Db();

	$db->update("users", array("ID" => is_user_logged_in_front()), array("newsletter" => 0));

	die();
}

function ajax_activate_newsletter($data){
	$db = new Db();

	$db->update("users", array("ID" => is_user_logged_in_front()), array("newsletter" => 1));

	die();
}

function ajax_remove_account($data){
	$db = new Db();

	$db->db_delete("users", array("ID" => is_user_logged_in_front()));

	logout_front();

	die();
}

function ajax_lost_pass($data){
	$db = new Db();

	$user = $db->fetch("SELECT * FROM users WHERE email LIKE '" . $db->db_escape(trim($data['email']), $db->conn) . "'");

	$result = "false";

	if($user){
		$password = randomPassword();
		$salt = randomPassword();

		$data = array(
			"password" => hash('sha512', $password . $salt),
			"salt" => $salt,
		);

		$db->update("users", array("ID" => $user['ID']), $data);

		//send email
		require_once "admin/phpmailer/PHPMailerAutoload.php";
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->setFrom('info@dogcitylife.cz', 'Dogcitylife.cz');
		$mail->addAddress($user['email']);     // Add a recipient

		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Obnova zapomenutého hesla';
		$mail->Body    = 'Zasíláme vám vaše nové heslo.<br>Nové heslo: ' . $password . "<br><br>Toto heslo doporučujeme po přihlášení změnit.";
		
		if(!$mail->send()) {
		    
		} else {
		    $result = __('Nové heslo bylo posláno na váš email');
		}
	}

	echo $result;

	die();
}

?>