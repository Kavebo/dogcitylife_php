<?php
require_once "db.php";
require_once "functions.php";
is_user_authorized();
include('SimpleImage.php'); ?>
<?php
$id = 0;
if(isset($_GET['id']) && !empty($_GET['id'])){
	//test ID

	$id = $_GET['id'];
	$db = new Db();
	$zarizeni = $db->fetch("SELECT * FROM zarizeni WHERE ID=" . $db->db_escape($id, $db->conn));
	if(!$zarizeni){
		redirect("nenalezeno");
	}
}
$error = false;
if(isset($_POST['object_save'])){
	$db = new Db();

	if($id){
		//SAVE
		$doporucujeme = 0;
		$active = 0;
		if(isset($_POST['object_active'])){
			$active = 1;
		}
		if(isset($_POST['object_doporucujeme'])){
			$doporucujeme = 1;
		}

		$data = array(
			"name" => $_POST['object_name'],
			"permalink" => permalink($_POST['object_name']),
			"address" => $_POST['object_address'],
			"contact_person" => $_POST['object_contact_person'],
			"popis" => $_POST['object_popis'],
			"provozni_doba" => $_POST['object_provozni_doba'],
			"contact" => $_POST['object_contact'],
			"contact_show" => $_POST['contact_show'],
			"doba_show" => $_POST['doba_show'],
			"metro" => $_POST['object_metro'],
			"tramvaj" => $_POST['object_tramvaj'],
			"autobus" => $_POST['object_autobus'],
			"metro_show" => $_POST['metro_show'],
			"tramvaj_show" => $_POST['tramvaj_show'],
			"autobus_show" => $_POST['autobus_show'],
			"active" => $active,
			"doporucujeme" => $doporucujeme,
			"kavarna" => 0,
			"restaurace" => 0,
			"cvicak" => 0,
			"hotel" => 0,
			"potreby" => 0,
			"wifi" => 0,
			"dog_friendly" => 0,
			"krmivo" => 0,
			"parkovani" => 0,
			"bezbarier" => 0,
			"hriste" => 0,
			"zahradka" => 0,
		);



		if(isset($_POST['object_type'])){
			foreach ($_POST['object_type'] as $value) {
				$data[$value] = 1;
			}
		}

		if(isset($_POST['object_benefity'])){
			foreach ($_POST['object_benefity'] as $value) {
				$data[$value] = 1;
			}
		}

		$db->update("zarizeni", array("ID" => $zarizeni['ID']), $data);

		$db->update("zarizeni", array("default_ID" => $zarizeni['default_ID']), array("active" => $active,
			"doporucujeme" => $doporucujeme));

		//REMOVE IMAGES
		if(isset($_POST['delete_image']) && !empty($_POST['delete_image'])){
			foreach ($_POST['delete_image'] as $img_id) {
				$db->db_delete("imgs", array("ID" => $img_id));
			}
		}

		//REMOVE FILE
		if(isset($_POST['delete_download']) && !empty($_POST['delete_download'])){
			$db->update("zarizeni", array("ID" => $zarizeni['ID']), array("download" => ""));
		}

		//LOCATION
		if(!empty($_POST['object_address'])){
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($_POST['object_address']) . '&key=AIzaSyCa9hro3xB3Z5ciY7HDcLWC65HinFyIrWU';
			$data = @file_get_contents($url);

			$result = json_decode($data);
			if($result->status == 'OK'){
				$lat = $result->results[0]->geometry->location->lat;
				$lng = $result->results[0]->geometry->location->lng;
				$db->update("zarizeni", array("ID" => $zarizeni['ID']), array("lat" => $lat, "lng" => $lng));
			}
		}

		//REMOVE AND ADD IMAGES/UPLOADS
		if(isset($_FILES['object_photo']) && !empty($_FILES['object_photo']) && $_FILES['object_photo']['size'] != 0){
			//DELETE OLD
			$img_to_delete = $db->fetch("SELECT * FROM imgs WHERE zarizeni_ID=" . $zarizeni['ID'] . " AND is_main=1");
			$db->db_delete("imgs", array("ID" => $img_to_delete['ID']));
			//ADD NEW
			$file = $_FILES['object_photo'];
			$img = $db->insert("imgs", array("zarizeni_ID" => $zarizeni['ID'], "filename" => $file['name'], "is_main" => 1));
			if (!file_exists("uploads/" . $img . "/")) {
				mkdir("uploads/" . $img . "/");
			}
			$uploaddir = "uploads/" . $img . "/";
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

		if(isset($_FILES['object_morephoto']) && !empty($_FILES['object_morephoto'])){
				$files = diverse_array($_FILES['object_morephoto']);
				$c = 0;
				foreach ($files as $file) {
					if($file['size'] != 0){
						$c++;
						$img = $db->insert("imgs", array("zarizeni_ID" => $zarizeni['ID'], "filename" => $file['name'], "is_main" => 0));
						if (!file_exists("uploads/" . $img . "/")) {
							mkdir("uploads/" . $img . "/");
						}
						$uploaddir = "uploads/" . $img . "/";
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

			if(isset($_FILES['object_download']) && !empty($_FILES['object_download']) && isset($_FILES['object_download']['name']) && !empty($_FILES['object_download']['name'])){
				$file = $_FILES['object_download'];
				$img = $db->update("zarizeni", array("ID" => $zarizeni['ID']), array("download" => $zarizeni['ID'] . "-" . $file['name']));
				if (!file_exists("uploads/" . $img . "/")) {
					mkdir("uploads/" . $img . "/");
				}
				$uploaddir = "uploads/";
				$uploadfile = $uploaddir . $zarizeni['ID'] . "-" . basename($file['name']);

				if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
					$file_to_copy3 = $uploadfile;
				}
			}

	}else{
		//ADD NEW
		$doporucujeme = 0;
		$active = 0;
		if(isset($_POST['object_active'])){
			$active = 1;
		}
		if(isset($_POST['object_doporucujeme'])){
			$doporucujeme = 1;
		}

		$data = array(
			"name" => $_POST['object_name'],
			"permalink" => permalink($_POST['object_name']),
			"inserted" => date("Y-m-d H:i:s"),
			"address" => $_POST['object_address'],
			"contact_person" => $_POST['object_contact_person'],
			"popis" => $_POST['object_popis'],
			"provozni_doba" => $_POST['object_provozni_doba'],
			"contact" => $_POST['object_contact'],
			"contact_show" => $_POST['contact_show'],
			"doba_show" => $_POST['doba_show'],
			"metro" => $_POST['object_metro'],
			"tramvaj" => $_POST['object_tramvaj'],
			"autobus" => $_POST['object_autobus'],
			"metro_show" => $_POST['metro_show'],
			"tramvaj_show" => $_POST['tramvaj_show'],
			"autobus_show" => $_POST['autobus_show'],
			"active" => $active,
			"doporucujeme" => $doporucujeme,
		);

		if(isset($_POST['object_type'])){
			foreach ($_POST['object_type'] as $value) {
				$data[$value] = 1;
			}
		}

		if(isset($_POST['object_benefity'])){
			foreach ($_POST['object_benefity'] as $value) {
				$data[$value] = 1;
			}
		}



		$langs = get_langs();
		$default_id = 0;
		$file_to_copy = "";
		$files_to_copy = array();
		$file_to_copy3 = "";

		if(!empty($_POST['object_address'])){
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($_POST['object_address']) . '&key=AIzaSyCa9hro3xB3Z5ciY7HDcLWC65HinFyIrWU';
			$data2 = @file_get_contents($url);

			$result = json_decode($data2);
			if($result->status == 'OK'){
				$lat = $result->results[0]->geometry->location->lat;
				$lng = $result->results[0]->geometry->location->lng;
			}
		}

		foreach ($langs as $key => $value) {
			$data['lang'] = $key;
			$zarizeni_id = $db->insert("zarizeni", $data);
			if($key == "cs"){
				$default_id = $zarizeni_id;
			}
			$db->update("zarizeni", array("ID" => $zarizeni_id), array("default_ID" => $default_id));

			//LOCATION
			if(!empty($_POST['object_address']) && $result->status == 'OK'){
				$db->update("zarizeni", array("ID" => $zarizeni_id), array("lat" => $lat, "lng" => $lng));
			}

			//UPLOAD AND ADD IMAGES
			if(isset($_FILES['object_photo']) && !empty($_FILES['object_photo']) && $default_id == $zarizeni_id){
				$file = $_FILES['object_photo'];
				$img = $db->insert("imgs", array("zarizeni_ID" => $zarizeni_id, "filename" => $file['name'], "is_main" => 1));
				if (!file_exists("uploads/" . $img . "/")) {
					mkdir("uploads/" . $img . "/");
				}
				$uploaddir = "uploads/" . $img . "/";
				$uploadfile = $uploaddir . basename($file['name']);

				if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
					$file_to_copy = $uploadfile;
					$sizes = get_image_sizes();
					foreach($sizes as $size){
						$image = new abeautifulsite\SimpleImage($uploadfile);
						$image->thumbnail($size['width'],$size['height']);
						$image->save($uploaddir . $size['width'] . "x" . $size['height'] . "-" . basename($file['name']));
					}
				}
				/*if($default_id != $zarizeni_id && $file_to_copy){
					copy($file_to_copy, $uploadfile);
					$sizes = get_image_sizes();
					foreach($sizes as $size){
						$image = new abeautifulsite\SimpleImage($file_to_copy);
						$image->thumbnail($size['width'],$size['height']);
						$image->save($uploaddir . $size['width'] . "x" . $size['height'] . "-" . basename($file['name']));
					}
				}*/
			}

			if(isset($_FILES['object_morephoto']) && !empty($_FILES['object_morephoto'])  && $default_id == $zarizeni_id){
				$files = diverse_array($_FILES['object_morephoto']);
				$c = 0;
				foreach ($files as $file) {
					$c++;
					$img = $db->insert("imgs", array("zarizeni_ID" => $zarizeni_id, "filename" => $file['name'], "is_main" => 0));
					if (!file_exists("uploads/" . $img . "/")) {
						mkdir("uploads/" . $img . "/");
					}
					$uploaddir = "uploads/" . $img . "/";
					$uploadfile = $uploaddir . basename($file['name']);

					if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
						$files_to_copy[$c] = $uploadfile;
						$sizes = get_image_sizes();
						foreach($sizes as $size){
							$image = new abeautifulsite\SimpleImage($uploadfile);
							$image->thumbnail($size['width'],$size['height']);
							$image->save($uploaddir . $size['width'] . "x" . $size['height'] . "-" . basename($file['name']));
						}
					}
					/*if($default_id != $zarizeni_id && isset($files_to_copy[$c])){
						copy($files_to_copy[$c], $uploadfile);

						$sizes = get_image_sizes();
						foreach($sizes as $size){
							$image = new abeautifulsite\SimpleImage($files_to_copy[$c]);
							$image->thumbnail($size['width'],$size['height']);
							$image->save($uploaddir . $size['width'] . "x" . $size['height'] . "-" . basename($file['name']));
						}
					}*/
				}
			}

			if(isset($_FILES['object_download']) && !empty($_FILES['object_download']) && isset($_FILES['object_download']['name']) && !empty($_FILES['object_download']['name'])){
				$file = $_FILES['object_download'];
				$img = $db->update("zarizeni", array("ID" => $zarizeni_id), array("download" => $zarizeni_id . "-" . $file['name']));
				if (!file_exists("uploads/" . $img . "/")) {
					mkdir("uploads/" . $img . "/");
				}
				$uploaddir = "uploads/";
				$uploadfile = $uploaddir . $zarizeni_id . "-" . basename($file['name']);

				if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
					$file_to_copy3 = $uploadfile;
				}
				/*if($default_id != $zarizeni_id){
					copy($file_to_copy3, $uploadfile);
				}*/
			}
		}


		//REDIRECT TO EDIT
		redirect("pridat_zarizeni?id=" . $default_id);

	}
}

$id = 0;
if(isset($_GET['id']) && !empty($_GET['id'])){
	//test ID

	$id = $_GET['id'];
	$db = new Db();
	$zarizeni = $db->fetch("SELECT * FROM zarizeni WHERE ID=" . $db->db_escape($id, $db->conn));
	if(!$zarizeni){
		redirect("nenalezeno");
	}
}

if(isset($_POST['object_delete'])){
	$db = new Db();
	$db->db_delete("zarizeni", array("default_ID" => $zarizeni['default_ID']));
	redirect(get_admin_url());
}

include "header.php";
?>
<form method="POST" action="" id="zarizeni" enctype="multipart/form-data">
	<div class="actions_edit">
		<h2>Přidat nebo upravit zařízení</h2>
		<div class="actions">
			<button type="submit" name="object_save">Uložit změny</button>
		</div>
		<div class="clear"></div>
	</div>
	<?php if($id): ?>
		<div class="input checkboxradio">
			<label for="object_active">Aktivní</label>
			<input type="checkbox" name="object_active" id="object_active" value="1" <?php if(isset($zarizeni['active']) && $zarizeni['active'] == 1) echo "checked"; ?>>
		</div>
		<div class="clear"></div>
		<div class="input checkboxradio">
			<label for="object_doporucujeme">Doporučujeme</label>
			<input type="checkbox" name="object_doporucujeme" id="object_doporucujeme" value="1" <?php if(isset($zarizeni['doporucujeme']) && $zarizeni['doporucujeme'] == 1) echo "checked"; ?>>
		</div>
		<div class="clear"></div>
	<?php endif; ?>
	<?php if($id): ?>
		<div class="languages">
			<h3>Editace jazykových verzí</h3>
			<?php $langs = get_langs(); unset($langs[$zarizeni['lang']]); ?>
			<?php foreach($langs as $key => $lang): ?>
				<?php $lang_id = $db->fetch("SELECT * FROM zarizeni WHERE default_ID=" . $zarizeni['default_ID'] . " AND lang LIKE '" . $key . "'"); ?>
				<a href="<?php echo get_admin_url() . "pridat_zarizeni?id=" . $lang_id['ID']; ?>"><?php echo $lang; ?></a>
			<?php endforeach; ?>
		</div>
		<hr>
	<?php endif; ?>
	<div class="input">
		<label for="object_name">Název zařízení</label>
		<input type="text" name="object_name" id="object_name" required value="<?php if(isset($zarizeni['name'])) echo $zarizeni['name']; ?>">
	</div>
	<div class="input">
		<label for="object_address">Adresa</label>
		<input type="text" name="object_address" id="object_address" required value="<?php if(isset($zarizeni['address'])) echo $zarizeni['address']; ?>">
	</div>
	<div class="input">
		<label for="object_contact_person">Kontaktní osoba</label>
		<input type="text" name="object_contact_person" id="object_contact_person" value="<?php if(isset($zarizeni['contact_person'])) echo $zarizeni['contact_person']; ?>">
	</div>
	<div class="input checkboxradio">
		<label for="object_type">Typ zařízení</label>
		<label for="kavarna">Kavárna</label>
		<input type="checkbox" name="object_type[]" value="kavarna" id="kavarna" <?php if(isset($zarizeni['kavarna']) && $zarizeni['kavarna'] == "1") echo "checked"; ?>>
		<label for="restaurace">Restaurace</label>
		<input type="checkbox" name="object_type[]" value="restaurace" id="restaurace" <?php if(isset($zarizeni['restaurace']) && $zarizeni['restaurace'] == "1") echo "checked"; ?>>
		<label for="cvicak">Cvičák</label>
		<input type="checkbox" name="object_type[]" value="cvicak" id="cvicak" <?php if(isset($zarizeni['cvicak']) && $zarizeni['cvicak'] == "1") echo "checked"; ?>>
		<label for="hotel">Hotel</label>
		<input type="checkbox" name="object_type[]" value="hotel" id="hotel" <?php if(isset($zarizeni['hotel']) && $zarizeni['hotel'] == "1") echo "checked"; ?>>
		<label for="potreby">Psí potřeby</label>
		<input type="checkbox" name="object_type[]" value="potreby" id="potreby" <?php if(isset($zarizeni['potreby']) && $zarizeni['potreby'] == "1") echo "checked"; ?>>
		<label for="hriste">Psí hříště</label>
		<input type="checkbox" name="object_type[]" value="hriste" id="hriste" <?php if(isset($zarizeni['hriste']) && $zarizeni['hriste'] == 1) echo "checked"; ?>>
	</div>
	<div class="input">
		<label for="object_photo">Náhledové foto</label>
		<input type="file" name="object_photo" id="object_photo">
		<?php if($id): ?>
			<?php $img = $db->fetch("SELECT * FROM imgs WHERE zarizeni_ID=" . $zarizeni['ID'] . " AND is_main=1"); ?>
			<?php if($img): ?>
				<img src="<?php echo get_img_src($img['ID'], "admin_small"); ?>">
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php if($id): ?>
	<div class="input more_files">
		<label for="object_morephoto">Ostatní foto</label>
		<input type="file" name="object_morephoto[]" multiple="multiple" id="object_morephoto">
		<div class="clear"></div>
		<?php if($id): ?>
			<?php $imgs = $db->fetch_all("SELECT * FROM imgs WHERE zarizeni_ID=" . $zarizeni['ID'] . " AND is_main=0");  ?>
			<?php foreach($imgs as $img): ?>
				<img src="<?php echo get_img_src($img['ID'], "admin_small"); ?>">
				Smazat? <input type="checkbox" name="delete_image[]" value="<?php echo $img['ID']; ?>">
				<div class="clear"></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<div class="input checkboxradio">
		<label for="object_benefity">Benefity</label>
		<label for="wifi">Free wifi</label>
		<input type="checkbox" name="object_benefity[]" value="wifi" id="wifi" <?php if(isset($zarizeni['wifi']) && $zarizeni['wifi'] == 1) echo "checked"; ?>>
		<label for="dog_friendly">Miska s vodou a pelíšek</label>
		<input type="checkbox" name="object_benefity[]" value="dog_friendly" id="dog_friendly" <?php if(isset($zarizeni['dog_friendly']) && $zarizeni['dog_friendly'] == 1) echo "checked"; ?>>
		<label for="krmivo">Pamlsky</label>
		<input type="checkbox" name="object_benefity[]" value="krmivo" id="krmivo" <?php if(isset($zarizeni['krmivo']) && $zarizeni['krmivo'] == 1) echo "checked"; ?>>
		<label for="parkovani">Parkování</label>
		<input type="checkbox" name="object_benefity[]" value="parkovani" id="parkovani" <?php if(isset($zarizeni['parkovani']) && $zarizeni['parkovani'] == 1) echo "checked"; ?>>
		<label for="bezbarier">Bezbarérový přístup</label>
		<input type="checkbox" name="object_benefity[]" value="bezbarier" id="bezbarier" <?php if(isset($zarizeni['bezbarier']) && $zarizeni['bezbarier'] == 1) echo "checked"; ?>>
		<label for="zahradka">Zahrádka</label>
		<input type="checkbox" name="object_benefity[]" value="zahradka" id="zahradka" <?php if(isset($zarizeni['zahradka']) && $zarizeni['zahradka'] == 1) echo "checked"; ?>>
	</div>
	<div class="input">
		<label for="object_popis">Popis zařízení</label>
		<textarea name="object_popis" id="object_popis"><?php if(isset($zarizeni['popis'])) echo $zarizeni['popis']; ?></textarea>
	</div>
	<div class="input check">
		<label for="object_provozni_doba">Provozní doba</label>
		<input type="checkbox" name="doba_show" value="1" <?php if(!$id): ?>checked<?php elseif($zarizeni['doba_show'] == 1): ?>checked<?php endif; ?>>
		<textarea name="object_provozni_doba" id="object_provozni_doba"><?php if(isset($zarizeni['provozni_doba'])) echo $zarizeni['provozni_doba']; ?></textarea>
	</div>
	<div class="input check">
		<label for="object_contact">Kontakt</label>
		<input type="checkbox" name="contact_show" value="1" <?php if(!$id): ?>checked<?php elseif($zarizeni['contact_show'] == 1): ?>checked<?php endif; ?>>
		<textarea name="object_contact" id="object_contact"><?php if(isset($zarizeni['contact'])) echo $zarizeni['contact']; ?></textarea>
	</div>
	<div class="input check">
		<label for="object_download">Menu podniku</label>
		<input type="file" name="object_download" id="object_download">
		<?php if($id && $zarizeni['download']): ?>
			<a href="<?php echo get_admin_url() . "uploads/" . $zarizeni['download']; ?>"><?php echo $zarizeni['download']; ?></a>
			<div class="clear"></div>
			Smazat? <input type="checkbox" name="delete_download" value="<?php echo $zarizeni['download']; ?>">
				<div class="clear"></div>
		<?php endif; ?>
	</div>
	<div class="input">
		<label>Spojení MHD</label>
		<div class="third">
			<label for="object_metro">Metro</label>
			<input type="checkbox" name="metro_show" value="1" <?php if(!$id): ?>checked<?php elseif($zarizeni['metro_show'] == 1): ?>checked<?php endif; ?>>
			<textarea name="object_metro" id="object_metro"><?php if(isset($zarizeni['metro'])) echo $zarizeni['metro']; ?></textarea>
		</div>
		<div class="third">
			<label for="object_tramvaj">Tramvaj</label>
			<input type="checkbox" name="tramvaj_show" value="1" <?php if(!$id): ?>checked<?php elseif($zarizeni['tramvaj_show'] == 1): ?>checked<?php endif; ?>>
			<textarea name="object_tramvaj" id="object_tramvaj"><?php if(isset($zarizeni['tramvaj'])) echo $zarizeni['tramvaj']; ?></textarea>
		</div>
		<div class="third">
			<label for="object_autobus">Autobus</label>
			<input type="checkbox" name="autobus_show" value="1" <?php if(!$id): ?>checked<?php elseif($zarizeni['autobus_show'] == 1): ?>checked<?php endif; ?>>
			<textarea name="object_autobus" id="object_autobus"><?php if(isset($zarizeni['autobus'])) echo $zarizeni['autobus']; ?></textarea>
		</div>
		<div class="clear"></div>
	</div>
	<?php if($id && $zarizeni['lang'] == "cs"): ?>
		<button type="submit" name="object_delete">Smazat zařízení</button>
	<?php endif; ?>
</form>
<?php include "footer.php"; ?>