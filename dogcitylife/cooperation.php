<?php 
require_once "admin/functions.php";
require_once "admin/db.php";

$db = new Db();

$cms = $db->fetch("SELECT * FROM cms WHERE ID=8");

if(get_current_lang() != "en" && !$cms){
	if($_404){
		redirect('nenalezeno');
	}
}
?>
<?php include "header.php"; ?>
	<div class="container cms_page">
		<h1><?php echo $cms['name']; ?></h1>
		<div class="cms_content">
			<?php echo $cms['content']; ?>
			<a class="btn" href="<?php if(get_current_lang() == "cs"){echo get_front_url_lang() . "objednavkovy-formular";}else{echo get_front_url_lang() . "order-form";} ?>"><?php _e('Objednávkový formulář'); ?></a>
		</div>
	    <div class="clear"></div>
	</div>
<?php include "footer.php"; ?>