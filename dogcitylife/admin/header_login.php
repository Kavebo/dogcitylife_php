<?php 
require_once "db.php";
require_once "functions.php";
is_user_authorized();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php the_title(); ?></title>
	<meta charset="utf-8">
	<?php the_admin_css(); the_admin_js(); ?>
</head>
<body>