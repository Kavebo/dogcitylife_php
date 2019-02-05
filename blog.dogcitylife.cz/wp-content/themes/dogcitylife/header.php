<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="" />
<meta name="designer" content="" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="robots" content="noodp" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=0.8, minimum-scale=0.8">
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php wp_head(); ?>
</head>
<body>
<?php
require_once "db.php";
$db = new Db();
$cur_user = false;
if(isset($_SESSION['login_front'])){

	$cur_user = $db->fetch("SELECT * FROM users WHERE ID=". $_SESSION['login_front']);
}
?>
<div class="wrapper">
	<div class="header">
		<div class="container">
			<div class="top_header">
				<a class="logo" href="<?php bloginfo('url'); ?>"><img width="230" src="<?php echo get_template_directory_uri() . "/img/DCL_logo_blog.svg"; ?>"></a>
				<div class="menu">
					<?php
						$cur_lang = pll_current_language();
					?>
					<?php if($cur_lang == "cs"): ?>
					<ul>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=kavarna" class="">Kavárny</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=restaurace" class="">Restaurace</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=bar" class="">Bary</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=hotel" class="">Hotely</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=potreby" class="">Psí potřeby</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=hriste" class="">Psí hřiště</a></li>
					</ul>
					<?php elseif($cur_lang = "en"): ?>
						<ul>
							<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=kavarna" class="">Cafe</a></li>
							<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=restaurace" class="">Restaurant</a></li>
							<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=bar" class="">Bar</a></li>
							<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=hotel" class="">Hotel</a></li>
							<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=potreby" class="">Dog needs</a></li>
							<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=hriste" class="">Agility</a></li>
						</ul>
					<?php endif; ?>
				</div>
				<div class="user_menu">
					<?php if($cur_lang == "cs"): ?>
						<a href="https://blog.dogcitylife.cz/en/"><img width="38px" src="https://dogcitylife.cz/img/english.svg"></a>
						<?php if($cur_user): ?>
							<div class="user_header">
								<span><?php echo $cur_user['login']; ?></span>
								<div class="img">
									<?php
										$img = $db->fetch("SELECT * FROM imgs WHERE is_main=1 AND user_ID=" . $cur_user['ID']);
									?>
									<?php if($img): ?>
										<img src="https://dogcitylife.cz/admin/uploads/<?php echo $img['ID']; ?>/38x38-<?php echo $img['filename']; ?>">
									<?php else: ?>
										<img src="https://dogcitylife.cz/img/user_default_small.png">
									<?php endif; ?>
								</div>
								<div class="dropdown_menu">
									<ul>
										<li><a href="https://dogcitylife.cz/profil?id=<?php echo $cur_user['ID']; ?>">Můj profil</a></li>
										<li><a href="https://dogcitylife.cz/nastaveni">Nastavení</a></li>
										<li><a href="https://dogcitylife.cz/logout">Odhlásit</a></li>
									</ul>
								</div>
							</div>
						<?php else: ?>
							<a class="btn login_fancybox" href="https://dogcitylife.cz/#login">Přihlásit</a>
						<?php endif; ?>

					<?php elseif($cur_lang = "en"): ?>
						<a href="https://blog.dogcitylife.cz/"><img width="38px" src="https://dogcitylife.cz/img/czech.svg"></a>
						<?php if($cur_user): ?>
							<div class="user_header">
								<span><?php echo $cur_user['login']; ?></span>
								<div class="img">
									<?php
										$img = $db->fetch("SELECT * FROM imgs WHERE is_main=1 AND user_ID=" . $cur_user['ID']);
									?>
									<?php if($img): ?>
										<img src="https://dogcitylife.cz/admin/uploads/<?php echo $img['ID']; ?>/38x38-<?php echo $img['filename']; ?><?php echo get_img_src($img['ID'], "profile_small"); ?>">
									<?php else: ?>
										<img src="https://dogcitylife.cz/img/user_default_small.png">
									<?php endif; ?>
								</div>
								<div class="dropdown_menu">
									<ul>
										<li><a href="https://dogcitylife.cz/en/profil?id=<?php echo $cur_user['ID']; ?>">My profile</a></li>
										<li><a href="https://dogcitylife.cz/en/nastaveni">Settings</a></li>
										<li><a href="https://dogcitylife.cz/en/logout">Log out</a></li>
									</ul>
								</div>
							</div>
						<?php else: ?>
							<a class="btn login_fancybox" href="https://dogcitylife.cz/en/#login">Login</a>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="mobile_menu">
					<a class="hamburger" href=""></a>
					<div class="dropdown_menu">
						<?php if($cur_lang == "cs"): ?>
						<ul>
							<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=kavarna" class=" normal">Kavárny</a></li>
							<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=restaurace" class=" normal">Restaurace</a></li>
							<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=bar" class=" normal">Bary</a></li>
							<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=hotel" class=" normal">Hotely</a></li>
							<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=potreby" class=" normal">Psí potřeby</a></li>
							<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=hriste" class=" normal">Psí hřiště</a></li>
							<li class="line"></li>
							<li><a class="login_fancybox" href="https://dogcitylife.cz/#login">Přihlásit</a></li>
							<li><a href="https://dogcitylife.cz/en/"><img width="38px" src="https://dogcitylife.cz/img/english.svg"></a></li>
						</ul>
						<?php elseif($cur_lang = "en"): ?>
							<ul>
								<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=kavarna" class=" normal">Cafe</a></li>
								<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=restaurace" class=" normal">Restaurant</a></li>
								<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=bar" class=" normal">Bar</a></li>
								<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=hotel" class=" normal">Hotel</a></li>
								<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=potreby" class=" normal">Dog needs</a></li>
								<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=hriste" class=" normal">Agility</a></li>
								<li class="line"></li>
								<li><a class="login_fancybox" href="https://dogcitylife.cz/en/#login">Log in</a></li>
								<li><a href="https://dogcitylife.cz/"><img width="38px" src="https://dogcitylife.cz/img/czech.svg"></a></li>
							</ul>
						<?php endif; ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>