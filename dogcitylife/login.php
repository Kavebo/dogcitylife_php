<?php 
require_once "admin/functions.php";
require_once "admin/db.php";
$db = new Db();
//handle login/registration
if(isset($_POST['email'])){

	if(isset($_POST['username'])){
		//REGISTER
		$password = $_POST['password'];
		$salt = randomPassword();

		$data = array(
			"email" => $_POST['email'],
			"login" => $_POST['username'],
			"password" => hash('sha512', $password . $salt),
			"salt" => $salt,
			"register_date" => date("Y-m-d H:i:s")
		);

		$user = $db->insert("users", $data);

		if($user){
			login_front($_POST['email'], $password);
			
		}
		redirect(get_front_url_lang());
	}else{
		//LOGIN
		
	}

}else{
	redirect(get_front_url_lang());
}

?>