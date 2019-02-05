<?php 
require_once "admin/functions.php";
require_once "admin/db.php";
include('admin/SimpleImage.php');

$db = new Db();

if(isset($_GET['id']) && !empty($_GET['id'])){
	$user = $db->fetch("SELECT * FROM users WHERE ID=" . $db->db_escape($_GET['id'], $db->conn));
	if(!$user){
		redirect("nenalezeno");
	}
}else{
	redirect("nenalezeno");
}

$is_edit = false;
if($user['ID'] == is_user_logged_in_front()){
	$is_edit = true;
}

if(isset($_POST['user_popis']) && $is_edit){
	$db->update("users", array("ID" =>is_user_logged_in_front()), array("popis" => $_POST['user_popis']));

	if(isset($_POST['favorite'])){
		$oblibene = $db->fetch_all("SELECT favorite.ID FROM favorite WHERE favorite.user_ID=" . $user['ID'] . "");
		if($oblibene){
			$new_oblibene = array();
			foreach ($oblibene as $key => $value) {
				$new_oblibene[] = $value['ID'];
			}
			$diff = array_diff($new_oblibene, $_POST['favorite']);
			if($diff){
				foreach ($diff as $key => $value) {
					$SQL = "DELETE FROM favorite WHERE user_ID=" . $user['ID'] . " AND ID=" . $value;
					$result = mysqli_query($db->conn, $SQL);
				}
			}
		}
	}else{
		$db->db_delete("favorite", array("user_ID" => $user['ID']));
	}

	if(isset($_POST['profile_imgs'])){
		$oblibene = $db->fetch_all("SELECT imgs.ID FROM imgs WHERE imgs.user_ID=" . $user['ID'] . " AND is_main=0");
		if($oblibene){
			$new_oblibene = array();
			foreach ($oblibene as $key => $value) {
				$new_oblibene[] = $value['ID'];
			}
			$diff = array_diff($new_oblibene, $_POST['profile_imgs']);
			if($diff){
				foreach ($diff as $key => $value) {
					$SQL = "DELETE FROM imgs WHERE is_main=0 AND user_ID=" . $user['ID'] . " AND ID=" . $value;
					$result = mysqli_query($db->conn, $SQL);
				}
			}
		}
	}else{
		$SQL = "DELETE FROM imgs WHERE user_ID=" . $user['ID'] . " AND is_main=0";
		$result = mysqli_query($db->conn, $SQL);
	}


	if(isset($_FILES['user_imgs']) && !empty($_FILES['user_imgs'])){
		$files = diverse_array($_FILES['user_imgs']);
		$c = 0;
		foreach ($files as $file) {
			if($file['size'] != 0){
				$c++;
				$img = $db->insert("imgs", array("user_ID" => $user['ID'], "filename" => $file['name'], "is_main" => 0));
				if (!file_exists("amdin/uploads/" . $img . "/")) {
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
	}
	
}


?>
<?php include "header.php"; $user = $db->fetch("SELECT * FROM users WHERE ID=" . $db->db_escape($_GET['id'], $db->conn)); ?>
	<div class="container">
		<?php if($is_edit): ?>
			<form method="post" action="" enctype="multipart/form-data">
		<?php endif; ?>
				<div class="profile_title">
					<h1><?php _e('Profil uživatele:'); ?> <span><?php echo $user['login']; ?></span></h1>
					<?php if($is_edit): ?>
						<div class="actions">
							<a class="edit" href=""><?php _e('Upravit'); ?></a>
							<a class="save" style="display:none;" href=""><?php _e('Uložit změny'); ?></a>
							<a class="cancel" style="display:none;" href=""><?php _e('Zrušit změny'); ?></a>
						</div>
					<?php endif; ?>
					<div class="clear"></div>
				</div>
				<div class="profile_box profile_box_main">
					<div class="left_side">
						<?php 
							$img = $db->fetch("SELECT * FROM imgs WHERE is_main=1 AND user_ID=" . $user['ID']);
						?>
						<?php if($img): ?>
							<img src="<?php echo get_img_src($img['ID'], "profile_main"); ?>">
						<?php else: ?>
							<img src="<?php echo get_front_url() . "img/default_comment.png"; ?>">
						<?php endif; ?>
					</div>
					<div class="right_side">
						<textarea name="user_popis" disabled><?php echo $user['popis']; ?></textarea>
					</div>
					<div class="clear"></div>
				</div>
				<?php $oblibene = $db->fetch_all("SELECT favorite.ID as favorite_ID, zarizeni.name, zarizeni.address, zarizeni.permalink FROM favorite INNER JOIN zarizeni ON zarizeni.default_ID=favorite.zarizeni_ID WHERE favorite.user_ID=" . $user['ID'] . " AND zarizeni.lang LIKE'" . get_current_lang() . "'"); ?>
				<?php if($oblibene): ?>
					<div class="profile_box">
						<div class="left_side">
							<div class="title"><?php _e('Oblíbené'); ?></div>
						</div>
						<div class="right_side zarizeni">
							<?php foreach($oblibene as $zarizeni): ?>
								<a href="<?php echo get_permalink($zarizeni['permalink']); ?>">
									<div class="favorite_zarizeni">
										<div class="name"><?php echo $zarizeni['name']; ?></div>
										<div class="address"><?php echo $zarizeni['address']; ?></div>
										<?php if($is_edit): ?>
											<input type="hidden" name="favorite[]" value="<?php echo $zarizeni['favorite_ID']; ?>">
											<a class="remove tooltip" title="<?php _e('Odebrat z oblíbených'); ?>" style="display: none;" href=""></a>
										<?php endif; ?>
									</div>
								</a>
							<?php endforeach; ?>
						</div>
						<div class="clear"></div>
					</div>
				<?php endif; ?>
				<div class="profile_box">
					<div class="left_side">
						<div class="title"><?php _e('Fotky uživatele'); ?></div>
					</div>
					<div class="right_side">
					<?php 
							$imgs = $db->fetch_all("SELECT * FROM imgs WHERE is_main=0 AND user_ID=" . $user['ID']);
						?>
						<?php if($imgs): ?>
							<div class="detail_gallery">
								<?php foreach($imgs as $img): ?>
									<a href="<?php echo get_img_src($img['ID'], "front_big"); ?>" class="fancybox" rel="group"><img src="<?php echo get_img_src($img['ID'], "profile_gallery"); ?>"><?php if($is_edit): ?><input type="hidden" name="profile_imgs[]" value="<?php echo $img['ID'] ?>"><span class="remove tooltip" style="display:none;" title="<?php _e('Smazat'); ?>"></span><?php endif; ?></a>
									
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<div class="clear"></div>
						<?php if($is_edit): ?>
							<div class="file_upload" style="display: none;">
								<label for="user_imgs"><?php _e('Nahrát soubory z počítače'); ?></label>
								<input type="file" multiple name="user_imgs[]" id="user_imgs">
								<span></span>
							</div>
						<?php endif; ?>
					</div>
					<div class="clear"></div>
				</div>
				<?php if($is_edit): ?>
				</form>
			<?php endif; ?>
				<div class="recenze">
					<div class="title_cont">
						<?php $all_reviews = $db->fetch_all("SELECT reviews.ID FROM reviews INNER JOIN zarizeni ON zarizeni.ID=reviews.zarizeni_ID WHERE reviews.user_ID=" . $user['ID']); if(empty($all_reviews)){$all_reviews = 0;}else{$all_reviews = count($all_reviews);} ?>
						<span><?php _e("Recenze uživatele: "); ?> <span><?php echo $user['login']; ?></span> (<?php echo $all_reviews; ?>)</span>
						<select name="order" id="order" class="profile_review">
							<option value="DESC"><?php _e('Nejnovější'); ?></option>
							<option value="ASC"><?php _e('Nejstarší'); ?></option>
						</select>
						<div class="clear"></div>
					</div>
					<div class="list_review" id="list_review">
						<?php  

						if(isset($_GET['page']) && !empty($_GET['page'])){
							$paged = $_GET['page'];
						}else{
							$paged = 1;
						}

						if(isset($_GET['order']) && !empty($_GET['order'])){
							$filter = $_GET['order'];
						}else{
							$filter = "DESC";
						}

						$pagination_string = "";
						$per_page = 3;
						$all = $all_reviews;
						$total_pages = ceil($all/$per_page);

						if($paged > 1){
							$offset = ($paged-1) * $per_page;
						}else{
							$offset = 0;
						}

						$pagination_string = "LIMIT " . $per_page . " OFFSET " . $offset;
						$pagination = array();
						$filter_string = "";

						if(!empty($filter)){
							$filter_string = " ORDER BY reviews.datum " . $filter;
						}else{
							$filter_string = " ORDER BY reviews.datum DESC";
						}

						if($total_pages > 1){
							for ($i=1; $i < $total_pages+1; $i++) { 

								$link = "?page=" . $i;

								if(!empty($filter)){
									$link .= "&order=" . $filter;
								}

								$pagination[] = array(
									'page' => $i,
									'link' => $link
									);
							}
						}

						$users = $db->fetch_all("SELECT reviews.zarizeni_ID as review_ID, reviews.datum, reviews.popis, reviews.obsluha, reviews.dog_friendly, reviews.jidlo, reviews.prostredi, users.login, users.ID FROM reviews INNER JOIN users ON reviews.user_ID=users.ID INNER JOIN zarizeni ON zarizeni.ID=reviews.zarizeni_ID WHERE user_ID=" . $user['ID'] . " " . $filter_string . " " . $pagination_string);
						$new = array();
						$new['reviews'] = $users;
						$new['pagination'] = $pagination;
						?>
						<?php if(!empty($new['reviews'])): ?>
							<?php foreach($new['reviews'] as $review): ?>
								<?php include "templates/review.php"; ?>
							<?php endforeach; ?>
							<?php if(!empty($new['pagination'])): ?>
								<ul class="pagination">
									<?php foreach($new['pagination'] as $link): ?>
										<li><a class="<?php if($paged == $link['page']) echo 'active'; ?>" href="<?php echo get_front_url_lang() . "profil" . "" . $link['link'] . "&id=" . $user['ID']; ?>"><?php echo $link['page']; ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						<?php else: ?>
							<p><?php _e('Uživatel zatím nenapsal žádnou recenzi'); ?></p>
						<?php endif; ?>
					</div>
					<div class="clear"></div>
				</div>
			    <div class="clear"></div>
	</div>
<?php include "footer.php"; ?>