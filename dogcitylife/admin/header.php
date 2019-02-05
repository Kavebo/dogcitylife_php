<?php 
require_once "db.php";
require_once "functions.php";
is_user_authorized();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dogcitylife administrace</title>
	<meta charset="utf-8">
	<?php the_admin_css(); the_admin_js(); ?>
</head>
<body>
<div class="wrapper">
	<div class="container">
		<div class="header">
			<a href="<?php echo get_admin_url(); ?>"><img src="<?php echo get_admin_url() . "img/logo.png"; ?>"></a>
			<div class="menu">
				<ul>
				<?php $menu = get_menu_items(); foreach($menu as $item): ?>
					<li><a href="<?php echo $item['link']; ?>" class="<?php if($item['active']) echo 'active'; ?>"><?php echo $item['title']; ?></a></li>
				<?php endforeach; ?>
				</ul>
			</div>
		</div>
	