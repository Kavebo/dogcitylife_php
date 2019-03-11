<?php

require_once "db.php";



global $LANG_FILE;

$LANG_FILE = false;

$LANG_FILE = file_get_contents(get_admin_url() . "locale/" . get_current_lang() . "/LC_MESSAGES/" . get_current_lang() . ".po");

if($LANG_FILE){

	preg_match_all ('/msgid "(.+)?"\nmsgstr "(.+)?"/', $LANG_FILE, $LANG_FILE);

}





function get_admin_url(){

	if($_SERVER['SERVER_NAME'] == "localhost"){

		$admin_url = "http://localhost/dogcitylife/admin/";

	}else{

		$admin_url = "https://dogcitylife.cz/admin/";

	}

	return $admin_url;

}



function get_front_url(){

	if($_SERVER['SERVER_NAME'] == "localhost"){

		$admin_url = "http://localhost/dogcitylife/";

	}else{

		$admin_url = "https://dogcitylife.cz/";

	}

	return $admin_url;

}



function get_logout_url(){

	return get_admin_url() . "logout";

}



function getcurrentURL(){

	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {

		$url = "https://";

	}else{

		$url = "http://";

	}



	$url .= $_SERVER['SERVER_NAME'];





	$url .= $_SERVER["REQUEST_URI"];

	$url = strtok($url, '?');

	return $url;

}



function redirect($location){

	header("Location: " . $location);

	die();

}



function is_user_logged_in(){

	//session_start();

	if(isset($_SESSION['login_admin'])){

		return $_SESSION['login_admin'];

	}else{

		return false;

	}

}



function is_user_logged_in_front(){

	//session_start();

	if(isset($_SESSION['login_front'])){

		return $_SESSION['login_front'];

	}else{

		return false;

	}

}



function is_user_authorized(){

	$file = explode("/", $_SERVER["PHP_SELF"]);

	$file = end($file);



	if($user_id = is_user_logged_in()){



		$capability = true;

		$_404 = false;



		//get file and test GET parameters and file





		switch ($file) {





		}



		if($_404){

			redirect('nenalezeno');

		}

		if(!$capability){

			redirect("permission_denied");

		}



	}else{

		if($file != "login.php")

			redirect("login");

	}

}



function is_404(){

	$file = explode("/", $_SERVER["PHP_SELF"]);

	$file = end($file);



	$_404 = false;



	switch ($file) {

		case 'zarizeni.php':

			if(isset($_GET['id']) && !empty($_GET['id'])){

				$db = new Db();

				$zarizeni = $db->fetch("SELECT * FROM zarizeni WHERE permalink LIKE '" . $db->db_escape($_GET['id'], $db->conn) . "'");

				if(!$zarizeni){

					$_404 = true;

				}

			}else{

				$_404 = true;

			}

		break;

	}



	if($_404){

		redirect('nenalezeno');

	}

}



function randomPassword() {

    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";

    $pass = array(); //remember to declare $pass as an array

    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

    for ($i = 0; $i < 8; $i++) {

        $n = rand(0, $alphaLength);

        $pass[] = $alphabet[$n];

    }

    return implode($pass); //turn the array into a string

}



function the_title(){
	$cur_lang = get_current_lang();
	$title = false;
	$title_first = "DOG CITY LIFE - ";
	$title_second = " | dogcitylife.cz";

	$file = explode("/", $_SERVER["PHP_SELF"]);
	$file = end($file);
	switch($file){
		case 'vyhledavani.php':
			if($cur_lang == "cs"){
				$title = $title_first . "Vyhledávání" . $title_second;
			}elseif($cur_lang == "en"){
				$title = $title_first . "Search" . $title_second;
			}
		break;

		case 'profil.php':
			if($cur_lang == "cs"){
				$title = $title_first . "Profil" . $title_second;
			}elseif($cur_lang == "en"){
				$title = $title_first . "Profile" . $title_second;
			}
		break;

		case 'kontakt.php':
			$title = $title_first . "Kontakt" . $title_second;
		break;

		case 'contact.php':
			$title = $title_first . "Contact" . $title_second;
		break;

		case 'o-nas.php':
			$title = $title_first . "O nás" . $title_second;
		break;

		case 'about.php':
			$title = $title_first . "About" . $title_second;
		break;

		case 'spoluprace.php':
			$title = $title_first . "Spolupráce" . $title_second;
		break;

		case 'cooperation.php':
			$title = $title_first . "Cooperation" . $title_second;
		break;

		case 'obchodni-podminky.php':
			$title = $title_first . "Obchodní podmínky" . $title_second;
		break;

		case 'terms-of-service.php':
			$title = $title_first . "Terms of service" . $title_second;
		break;
	}

	if(!$title){
		if($cur_lang == "cs"){
			$title = $title_first . "Průvodce podniky a místy pro pejskaře";
		}elseif($cur_lang == "en"){
			$title = $title_first . "Guide of dog friendly places";
		}
	}

	echo $title;

}



function the_admin_css(){

	$css = "";

	$css .= '<link rel="stylesheet" type="text/css" href="' . get_admin_url() . "css/style.css?" . mt_rand() . '">';

	$css .= '<link rel="stylesheet" type="text/css" href="' . get_admin_url() . "css/jquery-ui.structure.min.css" . '">';

	$css .= '<link rel="stylesheet" type="text/css" href="' . get_admin_url() . "css/jquery-ui.theme.min.css" . '">';

	echo $css;

}



function the_admin_js(){

	$js = "";

	$js .= '<script src="' . get_admin_url() . "js/jquery-3.2.1.min.js" . '"></script>';

	$js .= '<script src="' . get_admin_url() . "js/jquery-ui.min.js" . '"></script>';

	$js .= '<script src="' . get_admin_url() . "js/jquery.validate.min.js" . '"></script>';

	$js .= '<script src="' . get_admin_url() . "js/messages_cs.min.js" . '"></script>';

	$js .= '<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=fmi82rfa4rrmmxf5s5yhkw8qjohmgmge49bzw5qrsfjbz6l7"></script>';

	$js .= '<script src="' . get_admin_url() . "js/scripts.js" . '"></script>';

	echo $js;

}



function the_front_css(){

	$css = "";

	$css .= '<link rel="stylesheet" type="text/css" href="' . get_front_url() . "css/jquery-ui.structure.min.css" . '">';

	$css .= '<link rel="stylesheet" type="text/css" href="' . get_front_url() . "css/jquery.fancybox.css" . '">';

	$css .= '<link rel="stylesheet" type="text/css" href="' . get_front_url() . "css/jquery.fancybox-thumbs.css" . '">';

	$css .= '<link rel="stylesheet" type="text/css" href="' . get_front_url() . "css/style.css" . '">';

	echo $css;

}



function the_front_js(){

	$js = "";

	$js .= '<script src="' . get_front_url() . "js/jquery-3.2.1.min.js" . '"></script>';

	$js .= '<script src="' . get_front_url() . "js/jquery-ui.min.js" . '"></script>';

	$js .= '<script src="' . get_front_url() . "js/jquery.ui.touch-punch.min.js" . '"></script>';

	$js .= '<script src="' . get_front_url() . "js/jquery.fancybox.pack.js" . '"></script>';

	$js .= '<script src="' . get_front_url() . "js/jquery.fancybox-thumbs.js" . '"></script>';

	$js .= '<script src="' . get_front_url() . "js/jquery.validate.min.js" . '"></script>';

	if(get_current_lang() == "cs"){

		$js .= '<script src="' . get_front_url() . "js/messages_cs.js" . '"></script>';

	}

	$js .= '<script src="' . get_front_url() . "js/jquery.flexslider-min.js" . '"></script>';

	$js .= '<script src="' . get_front_url() . "js/jquery.slides.min.js" . '"></script>';

	$js .= '<script src="' . get_front_url() . "js/scripts.js" . '"></script>';

	echo $js;

}



function login_admin($login, $password){

	$db = new Db();

	$salt = $db->fetch("SELECT salt FROM admins WHERE login LIKE '" . $db->db_escape(trim($login),$db->conn) . "' LIMIT 1");

	$password_md5 = hash('sha512', $password . $salt['salt']);

	$user = $db->fetch("SELECT ID FROM admins WHERE login LIKE '" . $db->db_escape(trim($login),$db->conn) . "' AND password LIKE '" . trim($password_md5) . "' LIMIT 1");

	if($user){

		$_SESSION["login_admin"] = $user['ID'];

	}

	return $user;

}



function logout_admin(){

	if(isset($_SESSION['login_admin'])){

		unset($_SESSION['login_admin']);

	}



	//redirect to login page

	redirect("login");

}



function login_front($login, $password){

	$db = new Db();

	$salt = $db->fetch("SELECT salt FROM users WHERE email LIKE '" . $db->db_escape(trim($login),$db->conn) . "' LIMIT 1");

	$password_md5 = hash('sha512', $password . $salt['salt']);

	$user = $db->fetch("SELECT ID FROM users WHERE email LIKE '" . $db->db_escape(trim($login),$db->conn) . "' AND password LIKE '" . trim($password_md5) . "' LIMIT 1");

	if($user){

		$_SESSION["login_front"] = $user['ID'];

	}

	return $user;

}



function login_front_fb($FBID){

	$db = new Db();

	$user = $db->fetch("SELECT ID FROM users WHERE FBID LIKE '" . trim($FBID) . "' LIMIT 1");

	if($user){

		$_SESSION["login_front"] = $user['ID'];

	}

	return $user;

}



function logout_front(){

	if(isset($_SESSION['login_front'])){

		unset($_SESSION['login_front']);

	}



	//redirect to login page

	redirect(get_front_url_lang());

}



function get_menu_items(){

	$menu = array(

		array("title" => "Zařízení", "link" => get_admin_url(), "urls" => array("", "pridat_zarizeni")),

		array("title" => "Uživatelé", "link" => get_admin_url() . "uzivatele", "urls" => array("uzivatele")),

		array("title" => "Stránky", "link" => get_admin_url() . "stranky", "urls" => array("stranky", "cms")),

		array("title" => "Recenze", "link" => get_admin_url() . "recenze", "urls" => array("recenze")),

		array("title" => "Objednávky", "link" => get_admin_url() . "objednavky2", "urls" => array("objednavky2", "objednavka"))

		);

	$current = getcurrentURL();

	$add = false;

	foreach ($menu as $key => $value) {

		foreach($value['urls'] as $url){

			if(get_admin_url() . $url == $current){

				$add = true;

			}

		}

		if($add){

			$menu[$key]['active'] = 1;

			$add = false;

		}else{

			$menu[$key]['active'] = 0;

		}

	}



	return $menu;

}



function get_front_menu_items(){

	$menu = array(

		array("title" => __("Kavárny"), "link" => get_front_url_lang() . "vyhledavani?typ[]=kavarna", "urls" => array("")),

		array("title" => __("Restaurace"), "link" => get_front_url_lang() . "vyhledavani?typ[]=restaurace", "urls" => array("")),

		array("title" => __("Hotely"), "link" => get_front_url_lang() . "vyhledavani?typ[]=hotel", "urls" => array("")),

		array("title" => __("Psí potřeby"), "link" => get_front_url_lang() . "vyhledavani?typ[]=potreby", "urls" => array("")),

		array("title" => __("Cvičáky"), "link" => get_front_url_lang() . "vyhledavani?typ[]=cvicak", "urls" => array("")),

		array("title" => __("Psí hřiště"), "link" => get_front_url_lang() . "vyhledavani?typ[]=hriste", "urls" => array("")),

		);

	$current = getcurrentURL();

	$add = false;

	foreach ($menu as $key => $value) {

		foreach($value['urls'] as $url){

			if(get_admin_url() . $url == $current){

				$add = true;

			}

		}

		if($add){

			$menu[$key]['active'] = 1;

			$add = false;

		}else{

			$menu[$key]['active'] = 0;

		}

	}



	return $menu;

}



function get_langs(){

	$langs = array("cs" => "Čeština", "en" => "Angličtina");

	return $langs;

}



function get_image_sizes(){

	$sizes = array(

		"admin_small" => array("width" => 130, "height" => 130),

		"front_small" => array("width" => 361, "height" => 200),

		"front_vypis" => array("width" => 282, "height" => 241),

		"front_detail" => array("width" => 584, "height" => 333),

		"front_gallery" => array("width" => 282, "height" => 160),

		"front_big" => array("width" => 880, "height" => 585),

		"lightbox_small" => array("width" => 122, "height" => 80),

		"profile_gallery" => array("width" => 181, "height" => 181),

		"profile_main" => array("width" => 127, "height" => 158),

		"profile_small" => array("width" => 38, "height" => 38),

	);

	return $sizes;

}



function permalink($str, $replace=array(), $delimiter='-') {

	if( !empty($replace) ) {

		$str = str_replace((array)$replace, ' ', $str);

	}



	$diacritics = array("ě","š","č","ř","ž","ý","á","í","é");

	$replaces = array("e","s","c","r","z","y","a","i","e");



	$str = str_replace((array)$diacritics, (array)$replaces, $str);



	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);

	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);

	$clean = strtolower(trim($clean, '-'));

	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);



	return $clean;

}



function get_permalink($permalink){

	return get_front_url_lang() . "zarizeni/" . $permalink;

}



function diverse_array($vector) {

    $result = array();

    foreach($vector as $key1 => $value1)

        foreach($value1 as $key2 => $value2)

            $result[$key2][$key1] = $value2;

    return $result;

}



function get_img_src($ID, $size){

	$db = new Db();

	$img = $db->fetch("SELECT * FROM imgs WHERE ID=" . $ID);

	$sizes = get_image_sizes();

	$size = $sizes[$size];

	$src = get_admin_url() . "uploads/" . $ID . "/" . $size['width'] . "x" . $size['height'] . "-" . $img['filename'];

	return $src;

}



function get_current_lang(){

	if(isset($_GET['lang']) && !empty($_GET['lang'])){

		$lang = $_GET['lang'];

	}else{

		$lang = "cs";

	}



	return $lang;

}



function __($s){

	//translate

	global $LANG_FILE;

	if($LANG_FILE){

		$result = $LANG_FILE[2][array_search($s, $LANG_FILE[1])];

		if(!$result){

			$result = $s;

		}

	}else{

		$result = $s;

	}

	return $result;

}



function _e($s){

	echo __($s);

}



function _e_printf($s, $var){

	$s = __($s);



	return printf($s, $var);

}







function get_front_url_lang($l = ""){

	if(!isset($l) || empty($l)){

		$lang = get_current_lang();

	}else{

		$lang = $l;

	}



	if($lang == "cs"){

		return get_front_url();

	}else{

		return get_front_url() . $lang . "/";

	}

}



function distance($lat1, $lng1, $lat2, $lng2)

{

	$pi80 = M_PI / 180;

	$lat1 *= $pi80;

	$lng1 *= $pi80;

	$lat2 *= $pi80;

	$lng2 *= $pi80;



	$r = 6372.797; // mean radius of Earth in km

	$dlat = $lat2 - $lat1;

	$dlng = $lng2 - $lng1;

	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);

	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

	$km = $r * $c;



	return round($km,2);

}



?>