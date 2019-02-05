

<?php include "header.php"; ?>
<?php 
$db = new Db();

if(isset($_POST['delete_user']) && !empty($_POST['delete_user'])){
	$db->db_delete("users", array("ID" => $_POST['delete_user']));
}

$paged = 1;
if(isset($_GET['paged']) && !empty($_GET['paged'])){
	$paged = $_GET['paged'];
}

$search = "";
if(isset($_GET['search_name']) && !empty($_GET['search_name'])){
	$search = $_GET['search_name'];
}

$address = "";
if(isset($_GET['search_address']) && !empty($_GET['search_address'])){
	$address = $_GET['search_address'];
}

$date = "";
if(isset($_GET['search_date']) && !empty($_GET['search_date'])){
	$date = $_GET['search_date'];
}

$typ = "";

$zarizeni = $db->get_uzivatele_filter("", $typ, $search, $address, $date, $paged);
$all = $db->get_uzivatele_filter_all($search, $typ, $address, $date);
?>
<form method="GET" action="" id="filter_form">
	<div class="type_filter">		
			<div class="total">Celkem <?php echo $all; ?> zařízení</div>
			<div class="clear"></div>		
	</div>	
	<div class="search_filter">
		<input type="text" name="search_name" value="<?php if(isset($_GET['search_name']) && !empty($_GET['search_name'])) echo $_GET['search_name']; ?>" placeholder="Uživatelské jméno">
		<input type="text" name="search_address" value="<?php if(isset($_GET['search_address']) && !empty($_GET['search_address'])) echo $_GET['search_address']; ?>" placeholder="E-mail uživatele">
		<input type="text" name="search_date" value="<?php if(isset($_GET['search_date']) && !empty($_GET['search_date'])) echo $_GET['search_date']; ?>" placeholder="Datum vytvoření" class="datepicker">
		<button type="submit">Hledat</button>
		<a class="button fright" href="<?php echo get_admin_url() . "pridat_zarizeni"; ?>">Přidat zařízení</a>
	</div>
</form>
	<div class="list">

		<?php if(!empty($zarizeni['zarizeni'])): ?>
			<form method="post" action="" id="admin_users">
				<table>
					<input type="hidden" name="delete_user" id="delete_user" value="">
					<?php foreach($zarizeni['zarizeni'] as $val): ?>
						<tr>
							<td><a target="_blank" href="<?php echo get_front_url() . "profil?id=" . $val['ID']; ?>"><?php echo $val['login']; ?></a></td>
							<td><?php echo $val['email']; ?></td>
							<td><?php echo $val['register_date']; ?></td>
							<td> <button type="submit" class="delete_user" data-id="<?php echo $val['ID']; ?>" href="">Smazat</button></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</form>
			<?php if(!empty($zarizeni['pagination'])): ?>
				<ul class="pagination">
					<?php foreach($zarizeni['pagination'] as $link): ?>
						<li><a class="<?php if($paged == $link['page']) echo 'active'; ?>" href="<?php echo get_admin_url() . "uzivatele" . "" . $link['link']; ?>"><?php echo $link['page']; ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php endif; ?>
	</div>

<?php include "footer.php"; ?>