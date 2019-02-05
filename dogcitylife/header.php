<?php
require_once "admin/functions.php";
require_once "admin/db.php";
is_404();
$langs = get_langs();
$default = "cs";
$current = get_current_lang();

$db = new Db();

//IF COORDINATES GET ADDRESS
if(isset($_GET['search'])){
	$address = $_GET['search'];
}else{
	$address = "";
}

$lat = "";
$lng = "";


if(isset($_GET['search']) && !empty($_GET['search'])){
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($_GET['search']) . '&key=AIzaSyCa9hro3xB3Z5ciY7HDcLWC65HinFyIrWU';
	$data = @file_get_contents($url);

	$result = json_decode($data);
	if($result->status == 'OK'){
		$lat = $result->results[0]->geometry->location->lat;
		$lng = $result->results[0]->geometry->location->lng;
	}
}elseif(isset($_GET['lat']) && !empty($_GET['lat'])){

	$lat = $_GET['lat'];
	$lng = $_GET['lng'];
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . urlencode($_GET['lat']) . "," . urlencode($_GET['lng']) . "&language=cs";
	$data = @file_get_contents($url);
	$result = json_decode($data);
	if($result->status == 'OK'){
		$address = $result->results[0]->formatted_address;
	}

}

//TEST FULLTEXT
if(isset($_GET['search']) && !empty($_GET['search'])){
	$fulltext = $db->fetch_all("SELECT * FROM zarizeni WHERE name LIKE '%" . $db->db_escape($_GET['search'], $db->conn) . "%'");
	if(isset($fulltext[0])){
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($fulltext[0]['address']) . '&key=AIzaSyCa9hro3xB3Z5ciY7HDcLWC65HinFyIrWU';
		$data = @file_get_contents($url);

		$result = json_decode($data);
		if($result->status == 'OK'){
			$lat = $result->results[0]->geometry->location->lat;
			$lng = $result->results[0]->geometry->location->lng;
		}
	}
}

if($lat && $lng){
	setcookie("lat",$lat,time()+43200);
	setcookie("lng",$lng,time()+43200);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php the_title(); ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=0.8, minimum-scale=0.8">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_front_url(); ?>apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_front_url(); ?>android-chrome-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_front_url(); ?>favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_front_url(); ?>favicon-16x16.png">
	<link rel="manifest" href="<?php echo get_front_url(); ?>manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo get_front_url(); ?>mstile-150x150.png">
	<meta name="theme-color" content="#ffffff">
	<meta name="description" content="Hledáte dog friendly místa v Praze i mimo ni? Objevte námi otestované kavárny, restaurace, bary, hotely, kde budete s Vaším psem vždy vítaní.">
	<?php /*<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>
	<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'> */ ?>
	<?php the_front_css(); the_front_js(); ?>
</head>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-104179178-1', 'auto');
  ga('send', 'pageview');

</script>
<input type="hidden" name="ajaxUrl" id="ajaxUrl" value="<?php echo get_front_url() . "ajax"; ?>">
<input type="hidden" name="email_message" id="email_message" value="<?php _e('Tento email je již zaregistrován'); ?>">
<input type="hidden" name="user_delete_message" id="user_delete_message" value="<?php _e('Vážně chcete smazat váš účet?'); ?>">
<div class="loader">
	<div class="spinner">
	  <div class="rect1"></div>
	  <div class="rect2"></div>
	  <div class="rect3"></div>
	  <div class="rect4"></div>
	  <div class="rect5"></div>
	</div>
</div>
<div class="popups" style="display: none;">
	<div id="lost_password">
		<div class="popup_inner">
			<div class="title"><?php _e('Zapomněli jste heslo?'); ?></div>
			<p><?php _e('Zadejte váš e-mail a my vám zašleme nové heslo'); ?></p>
			<div class="message" style="display: none;"><?php _e('Neplatný email') ?></div>
			<form method="post" action="<?php echo get_front_url() . "login"; ?>" class="lost_password_form">
				<div class="input">
					<label for="lost_email"><?php _e('E-mail'); ?></label>
					<input required type="email" name="email" id="lost_email">
				</div>
				<div class="clear"></div>
				<button type="submit"><?php _e('Resetovat heslo'); ?></button>
			</form>
			<div class="dog_lost"></div>
		</div>
	</div>
	<div id="login">
		<div class="popup_inner">
			<img src="<?php echo get_front_url() . "img/logo_popup.png"; ?>">
			<div class="title"><?php _e('Přihlásit se'); ?></div>
			<?php

			require_once 'Facebook/autoload.php';

			$fb = new Facebook\Facebook([
			  'app_id' => '325204007892526', // Replace {app-id} with your app id
			  'app_secret' => 'a6160af572a88a2e9ba66ab0727c1a6f',
			  'default_graph_version' => 'v2.2',
			  ]);

			$helper = $fb->getRedirectLoginHelper();

			$permissions = ['email', 'public_profile']; // Optional permissions
			$loginUrl = $helper->getLoginUrl(get_front_url() . 'fblogin', $permissions);

			?>
			<a class="fb_login disabled" href="<?php echo htmlspecialchars($loginUrl); ?>"><?php _e('Přihlásit se přes Facebook'); ?></a>
			<div class="input_checkbox">
				<label for="podminky_fb"><?php _e_printf('Přihlášením souhlasíte s <a href="%s">všeobecnými obchodními podmínkami</a> webu www.dogcitylife.cz', get_front_url_lang() . "obchodni-podminky"); ?></label>
				<input class="custom_checkbox" type="checkbox" required name="podminky_fb" id="podminky_fb">
				<div class="clear"></div>
			</div>
			<div class="or"><span><?php _e('nebo'); ?></span></div>
			<div class="message" style="display: none;"><?php _e('Zadali jste špatně email nebo heslo.'); ?></div>
			<form method="post" action="<?php echo get_front_url() . "login"; ?>" class="form_login">
				<div class="input">
					<label for="login_email"><?php _e('E-mail'); ?></label>
					<input type="text" name="email" id="login_email">
				</div>
				<div class="input">
					<label for="login_password"><?php _e('Heslo'); ?></label>
					<input type="password" name="password" id="login_password">
				</div>
				<a class="lost_password" href="#lost_password"><?php _e('Zapomněli jste heslo?'); ?></a>
				<div class="clear"></div>
				<button type="submit"><?php _e('Přihlásit'); ?></button>
			</form>
			<a class="register" href="#register"><?php _e('Registrovat se'); ?></a>
		</div>
	</div>
	<div id="register">
		<div class="popup_inner">
			<img src="<?php echo get_front_url() . "img/logo_popup.png"; ?>">
			<div class="title"><?php _e('Registrovat se'); ?></div>
			<form method="post" action="<?php echo get_front_url() . "login"; ?>" class="register_form">
				<div class="input">
					<label for="register_email"><?php _e('Zadejte váš e-mail'); ?></label>
					<input type="text" required name="email" id="register_email">
				</div>
				<div class="input">
					<label><?php _e('Uživatelské jméno'); ?></label>
					<input type="text" required name="username">
				</div>
				<div class="input">
					<label for="password"><?php _e('Vaše heslo'); ?></label>
					<input type="password" required id="password" name="password">
				</div>
				<div class="input">
					<label for="password_again"><?php _e('Ověření hesla'); ?></label>
					<input type="password" required id="password_again" name="password_again">
				</div>
				<div class="input_checkbox">
					<label for="podminky"><?php _e_printf('Registrací souhlasíte s <a href="%s">všeobecnými obchodními podmínkami</a> webu www.dogcitylife.cz', get_front_url_lang() . "obchodni-podminky"); ?></label>
					<input class="custom_checkbox" type="checkbox" required name="podminky" id="podminky">
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<button type="submit"><?php _e('Registrovat se'); ?></button>
			</form>
		</div>
	</div>
</div>
<div class="wrapper">
	<div class="header">
		<div class="container">
			<div class="top_header">
				<a class="logo" href="<?php echo get_front_url_lang(); ?>"><img width="172" src="<?php echo get_front_url() . "img/logo.svg"; ?>"></a>
				<div class="menu">
					<ul>
					<?php $menu = get_front_menu_items(); foreach($menu as $item): ?>
						<li><a href="<?php echo $item['link']; ?>" class="<?php if($item['active']) echo 'active'; ?>"><?php echo $item['title']; ?></a></li>
					<?php endforeach; ?>
					</ul>
				</div>
				<div class="user_menu">
					<?php if($current == $default): ?>
						<a href="<?php echo get_front_url_lang("en"); ?>"><img width="38px" src="<?php echo get_front_url() . "img/english.svg"; ?>"></a>
					<?php else: ?>
						<a href="<?php echo get_front_url_lang("cs"); ?>"><img width="38px" src="<?php echo get_front_url() . "img/czech.svg"; ?>"></a>
					<?php endif; ?>
					<a href="<?php if(get_current_lang() == "cs"){echo "https://blog.dogcitylife.cz/";}else{echo "https://blog.dogcitylife.cz/en/";} ?>" class="link"><?php echo 'Blog'; ?></a>
					<?php if(is_user_logged_in_front()): ?>
						<?php $user = $db->fetch("SELECT * FROM users WHERE ID=". is_user_logged_in_front()); ?>
						<div class="user_header">
							<span><?php echo $user['login']; ?></span>
							<div class="img">
								<?php
									$img = $db->fetch("SELECT * FROM imgs WHERE is_main=1 AND user_ID=" . $user['ID']);
								?>
								<?php if($img): ?>
									<img src="<?php echo get_img_src($img['ID'], "profile_small"); ?>">
								<?php else: ?>
									<img src="<?php echo get_front_url() . "img/user_default_small.png"; ?>">
								<?php endif; ?>
							</div>
							<div class="dropdown_menu">
								<ul>
									<li><a href="<?php echo get_front_url_lang() . "profil?id=" . is_user_logged_in_front(); ?>"><?php _e('Můj profil'); ?></a></li>
									<li><a href="<?php echo get_front_url_lang() . "nastaveni"; ?>"><?php _e('Nastavení'); ?></a></li>
									<li><a href="<?php echo get_front_url_lang() . "logout"; ?>"><?php _e('Odhlásit'); ?></a></li>
								</ul>
							</div>
						</div>
					<?php else: ?>
						<a class="btn login_fancybox" href="#login"><?php _e('Přihlásit'); ?></a>
					<?php endif; ?>
				</div>
				<div class="mobile_menu">
					<a class="hamburger" href=""></a>
					<div class="dropdown_menu">
						<ul>
							<?php $menu = get_front_menu_items(); foreach($menu as $item): ?>
								<li><a href="<?php echo $item['link']; ?>" class="<?php if($item['active']) echo 'active'; ?> normal"><?php echo $item['title']; ?></a></li>
							<?php endforeach; ?>
							<li class="line"></li>
							<li><a href="<?php if(get_current_lang() == "cs"){echo "https://blog.dogcitylife.cz/";}else{echo "https://blog.dogcitylife.cz/en/";} ?>" class="link"><?php echo 'Blog'; ?></a></li>
							<?php if(is_user_logged_in_front()): ?>
								<li><a href="<?php echo get_front_url_lang() . "profil?id=" . is_user_logged_in_front(); ?>"><?php _e('Můj profil'); ?></a></li>
								<li><a href="<?php echo get_front_url_lang() . "nastaveni"; ?>"><?php _e('Nastavení'); ?></a></li>
								<li><a href="<?php echo get_front_url_lang() . "logout"; ?>"><?php _e('Odhlásit'); ?></a></li>
							<?php else: ?>
								<li><a class="login_fancybox" href="#login"><?php _e('Přihlásit'); ?></a></li>
							<?php endif; ?>
							<?php if($current == $default): ?>
								<li><a href="<?php echo get_front_url_lang("en"); ?>"><img width="38px" src="<?php echo get_front_url() . "img/english.svg"; ?>"></a></li>
							<?php else: ?>
								<li><a href="<?php echo get_front_url_lang("cs"); ?>"><img width="38px" src="<?php echo get_front_url() . "img/czech.svg"; ?>"></a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="search_header">
				<form method="get" action="<?php echo get_front_url_lang() . "vyhledavani"; ?>">
					<div class="search_types">
						<select multiple name="typ[]" id="typ" style="display: none;">
							<option value="kavarna" <?php if(isset($_GET['typ']) && in_array("kavarna", $_GET['typ'])) echo 'selected'; ?>>Hledat kavárny</option>
							<option value="restaurace" <?php if(isset($_GET['typ']) && in_array("restaurace", $_GET['typ'])) echo 'selected'; ?>>Hledat restaurace</option>
							<option value="bar" <?php if(isset($_GET['typ']) && in_array("bar", $_GET['typ'])) echo 'selected'; ?>>Hledat bary</option>
							<option value="hotel" <?php if(isset($_GET['typ']) && in_array("hotel", $_GET['typ'])) echo 'selected'; ?>>Hledat hotely</option>
							<option value="potreby" <?php if(isset($_GET['typ']) && in_array("potreby", $_GET['typ'])) echo 'selected'; ?>>Hledat potreby</option>
							<option value="hriste" <?php if(isset($_GET['typ']) && in_array("hriste", $_GET['typ'])) echo 'selected'; ?>>Hledat psí hřiště</option>
						</select>
						<a class="select_box kavarna tooltip <?php if(isset($_GET['typ']) && in_array("kavarna", $_GET['typ'])) echo 'selected'; ?>" data-val="kavarna" href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=kavarna"; ?>" title="<?php _e('Hledat kavárny'); ?>"></a>
						<a class="select_box restaurace tooltip <?php if(isset($_GET['typ']) && in_array("restaurace", $_GET['typ'])) echo 'selected'; ?>" data-val="restaurace" href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=restaurace"; ?>" title="<?php _e('Hledat restaurace'); ?>"></a>
						<a class="select_box bar tooltip <?php if(isset($_GET['typ']) && in_array("bar", $_GET['typ'])) echo 'selected'; ?>" data-val="bar" href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=bar"; ?>" title="<?php _e('Hledat bary'); ?>"></a>
						<a class="select_box hotel tooltip <?php if(isset($_GET['typ']) && in_array("hotel", $_GET['typ'])) echo 'selected'; ?>" data-val="hotel" href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=hotel"; ?>" title="<?php _e('Hledat hotely'); ?>"></a>
						<a class="select_box potreby tooltip <?php if(isset($_GET['typ']) && in_array("potreby", $_GET['typ'])) echo 'selected'; ?>" data-val="potreby" href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=potreby"; ?>" title="<?php _e('Hledat psí potřeby'); ?>"></a>
						<a class="select_box hriste tooltip <?php if(isset($_GET['typ']) && in_array("hriste", $_GET['typ'])) echo 'selected'; ?>" data-val="hriste" href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=hriste"; ?>" title="<?php _e('Hledat psí hřiště'); ?>"></a>
					</div>
					<div class="search_bar">
						<input type="text" name="search" id="search" placeholder="<?php _e('Zde prosím napište Vaši adresu'); ?>" value="<?php echo $address; ?>">
						<a class="gps" href=""></a>
						<button type="submit"><?php _e('Hledat'); ?></button>
					</div>
					<input type="hidden" name="lat" id="lat" value="<?php echo $lat; ?>">
					<input type="hidden" name="lng" id="lng" value="<?php echo $lng; ?>">
					<span class="trygps"><?php _e('Zkuste funkci "najdi mou polohu"'); ?></span>
				</form>
			</div>
		</div>
	</div>
	<div class="main">