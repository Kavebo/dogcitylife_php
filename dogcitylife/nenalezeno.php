<?php include "header.php"; ?>
<div class="not_found">
	<div class="container">
		<h1>404</h1>
		<p><?php _e_printf('Ehm.. ehm.. tuto stránku jste asi neočekávali.<br>
Zkuste se vrátit na <a href="%s">homepage.</a>', get_front_url_lang()); ?></p>
		<img src="<?php echo get_front_url() . "img/404.png"; ?>">
	    <div class="clear"></div>
	</div>
</div>
<?php include "footer.php"; ?>