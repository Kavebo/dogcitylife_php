<?php 
require_once "admin/functions.php";
require_once "admin/db.php";
include('admin/SimpleImage.php');

$db = new Db();
if(is_user_logged_in_front()){
	$user = $db->fetch("SELECT * FROM users WHERE ID=" . $db->db_escape(is_user_logged_in_front(), $db->conn));
	if(!$user){
		redirect(get_front_url_lang());
	}
}else{
	redirect(get_front_url_lang());
}

//SAVE
if(isset($_POST['user_name'])){
	$db->update("users", array("ID" => is_user_logged_in_front()), array("login" => $_POST['user_name']));

	if(isset($_POST['user_password']) && !empty($_POST['user_password'])){

		$password = $_POST['user_password'];
		$salt = randomPassword();

		$data = array(
			"password" => hash('sha512', $password . $salt),
			"salt" => $salt
		);
		$db->update("users", array("ID" => is_user_logged_in_front()), $data);
	}

	if(isset($_FILES['user_img']) && !empty($_FILES['user_img']) && $_FILES['user_img']['size'] != 0){
		//DELETE OLD
		$img_to_delete = $db->fetch("SELECT * FROM imgs WHERE user_ID=" . is_user_logged_in_front() . " AND is_main=1");
		$db->db_delete("imgs", array("ID" => $img_to_delete['ID']));
		//ADD NEW
		$file = $_FILES['user_img'];
		$img = $db->insert("imgs", array("user_ID" => is_user_logged_in_front(), "filename" => $file['name'], "is_main" => 1));
		if (!file_exists("admin/uploads/" . $img . "/")) {
			mkdir("admin/uploads/" . $img . "/");
		}
		$uploaddir = "admin/uploads/" . $img . "/";
		$uploadfile = $uploaddir . basename($file['name']);

		if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
			$sizes = get_image_sizes();
			foreach($sizes as $size){
				$image = new abeautifulsite\SimpleImage($uploadfile);
				$image->thumbnail($size['width'],$size['height']);
				$image->save($uploaddir . $size['width'] . "x" . $size['height'] . "-" . basename($file['name']));
			}					
		}
	}
}

$user = $db->fetch("SELECT * FROM users WHERE ID=" . $db->db_escape(is_user_logged_in_front(), $db->conn));
?>
<?php include "header.php"; ?>
	<div class="container">
		<form method="post" action="" enctype="multipart/form-data">
			<div class="profile_title">
				<h1><?php _e('Nastavení uživatele:'); ?> <span><?php echo $user['login']; ?></span></h1>
				<div class="actions">
					<a class="edit" href=""><?php _e('Upravit'); ?></a>
					<a class="save" style="display:none;" href=""><?php _e('Uložit změny'); ?></a>
					<a class="cancel" style="display:none;" href=""><?php _e('Zrušit změny'); ?></a>
				</div>
				<div class="clear"></div>
			</div>
			<div class="profile_box profile_img">
				<div class="left_side">
					<div class="title"><?php _e('Profilový obrázek'); ?></div>
				</div>
				<div class="right_side">
					<?php 
						$img = $db->fetch("SELECT * FROM imgs WHERE is_main=1 AND user_ID=" . $user['ID']);
					?>
					<?php if($img): ?>
						<img src="<?php echo get_img_src($img['ID'], "profile_main"); ?>">
					<?php else: ?>
						<img src="<?php echo get_front_url() . "img/default_comment.png"; ?>">
					<?php endif; ?>
					<div class="file_upload" style="display: none;">
						<label for="user_img"><?php _e('Nahrát soubory z počítače'); ?></label>
						<input type="file" name="user_img" id="user_img">
						<span></span>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="profile_box">
				<div class="left_side">
					<div class="title"><?php _e('Jméno'); ?></div>
				</div>
				<div class="right_side">
					<div class="input">
						<input type="text" name="user_name" value="<?php echo $user['login']; ?>" disabled>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="profile_box">
				<div class="left_side">
					<div class="title"><?php _e('Heslo'); ?></div>
				</div>
				<div class="right_side">
					<div class="input">
						<input type="password" name="user_password" value="" disabled>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="profile_box">
				<div class="left_side">
					<div class="title"><?php _e('Newsletter'); ?></div>
				</div>
				<div class="right_side">
					<p><?php _e('Mám zájem o zasílání speciálních nabídek a informačních e-mailů.'); ?></p>
					<?php if($user['newsletter'] == 1): ?>
						<a class="remove_newsletter" href=""><?php _e('Zrušit odběr novinek') ?></a>
						<a class="activate_newsletter" style="display: none;" href=""><?php _e('Aktivovat') ?></a>
					<?php else: ?>
						<a class="remove_newsletter" style="display:none;" href=""><?php _e('Zrušit odběr novinek') ?></a>
						<a class="activate_newsletter" href=""><?php _e('Aktivovat') ?></a>
					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="profile_box">
				<div class="left_side">
					<div class="title"><?php _e('Smazat účet'); ?></div>
				</div>
				<div class="right_side">
					<p><?php _e('Chci smazat svůj účet a s ním veškeré mé údaje uvedené na tomto webu.'); ?></p>
					<a class="remove_account" href=""><?php _e('Smazat účet'); ?></a>
				</div>
				<div class="clear"></div>
			</div>
		</form>
	</div>
<?php include "footer.php"; ?>