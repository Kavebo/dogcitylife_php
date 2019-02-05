<?php include "header.php"; include('SimpleImage.php'); ?>
<?php 
$id = 0;
if(isset($_GET['id']) && !empty($_GET['id'])){
	//test ID

	$id = $_GET['id'];
	$db = new Db();
	$cms = $db->fetch("SELECT * FROM cms WHERE ID=" . $db->db_escape($id, $db->conn));
	if(!$cms){
		redirect("nenalezeno");
	}
}
$error = false;
if(isset($_POST['object_save'])){
	$db = new Db();

	$db->update("cms", array("ID" => $cms['ID']), array("content" => $_POST['content_cms']));
}

$id = 0;
if(isset($_GET['id']) && !empty($_GET['id'])){
	//test ID

	$id = $_GET['id'];
	$db = new Db();
	$cms = $db->fetch("SELECT * FROM cms WHERE ID=" . $db->db_escape($id, $db->conn));
	if(!$cms){
		redirect("nenalezeno");
	}
}

?>
<form method="POST" action="" id="cms" enctype="multipart/form-data">
	<div class="actions_edit">
		<h2>Upravit CMS stránku: <?php echo $cms['name']; ?></h2>
		<div class="actions">
			<button type="submit" name="object_save">Uložit změny</button>
		</div>
		<div class="clear"></div>
	</div>
	<?php if($id): ?>
		<div class="languages">
			<h3>Editace jazykových verzí</h3>
			<?php $langs = get_langs(); unset($langs[$cms['lang']]); ?>
			<?php foreach($langs as $key => $lang): ?>
				<?php $lang_id = $db->fetch("SELECT * FROM cms WHERE default_ID=" . $cms['default_ID'] . " AND lang LIKE '" . $key . "'"); ?>
				<a href="<?php echo get_admin_url() . "cms?id=" . $lang_id['ID']; ?>"><?php echo $lang; ?></a>
			<?php endforeach; ?>
		</div>
		<hr>
	<?php endif; ?>
	<textarea class="tinymce" name="content_cms"><?php echo $cms['content']; ?></textarea>
</form>
<?php include "footer.php"; ?>