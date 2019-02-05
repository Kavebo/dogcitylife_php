<?php include "header.php"; ?>
<?php 
$db = new Db();
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
if(isset($_GET['typ_zarizeni']) && !empty($_GET['typ_zarizeni'])){
	$typ = $_GET['typ_zarizeni'];
}

$zarizeni = $db->get_zarizeni_filter("", $typ, $search, $address, $date, $paged);
$all = $db->get_zarizeni_filter_all($search, $typ, $address, $date);

?>
<form method="GET" action="" id="filter_form">
	<div class="type_filter">		
			<?php 
			$current = "0";
			if(isset($_GET['typ_zarizeni']) && !empty($_GET['typ_zarizeni'])){
				$current = $_GET['typ_zarizeni'];
			}

			?>
			<select name="typ_zarizeni" style="display: none;">
				<option <?php if("0" == $current) echo "current"; ?> value="0">Vše</option>
				<option <?php if("kavarna" == $current) echo "current"; ?> value="kavarna">Kavárny</option>
				<option <?php if("restaurace" == $current) echo "current"; ?> value="restaurace">Restaurace</option>
				<option <?php if("bar" == $current) echo "current"; ?> value="bar">Bary</option>
				<option <?php if("hotel" == $current) echo "current"; ?> value="hotel">Hotely</option>
				<option <?php if("potreby" == $current) echo "current"; ?> value="potreby">Psí potřeby</option>
				<option <?php if("hriste" == $current) echo "current"; ?> value="hriste">Psí hřiště</option>
			</select>
			<ul>
				<li><a class="<?php if("0" == $current) echo "current"; ?>" href="0">Vše</a></li>
				<li><a class="<?php if("kavarna" == $current) echo "current"; ?>" href="kavarna">Kavárny</a></li>
				<li><a class="<?php if("restaurace" == $current) echo "current"; ?>" href="restaurace">Restaurace</a></li>
				<li><a class="<?php if("bar" == $current) echo "current"; ?>" href="bar">Bary</a></li>
				<li><a class="<?php if("hotel" == $current) echo "current"; ?>" href="hotel">Hotely</a></li>
				<li><a class="<?php if("potreby" == $current) echo "current"; ?>" href="potreby">Psí potřeby</a></li>
				<li><a class="<?php if("hriste" == $current) echo "current"; ?>" href="hriste">Psí hřiště</a></li>
			</ul>
			<div class="total">Celkem <?php echo $all; ?> zařízení</div>
			<div class="clear"></div>		
	</div>
	<div class="search_filter">
		<input type="text" name="search_name" value="<?php if(isset($_GET['search_name']) && !empty($_GET['search_name'])) echo $_GET['search_name']; ?>" placeholder="Hledat název">
		<input type="text" name="search_address" value="<?php if(isset($_GET['search_address']) && !empty($_GET['search_address'])) echo $_GET['search_address']; ?>" placeholder="Hledat adresu">
		<input type="text" name="search_date" value="<?php if(isset($_GET['search_date']) && !empty($_GET['search_date'])) echo $_GET['search_date']; ?>" placeholder="Datum vložení" class="datepicker">
		<button type="submit">Hledat</button>
		<a class="button fright" href="<?php echo get_admin_url() . "pridat_zarizeni"; ?>">Přidat zařízení</a>
	</div>
	<div class="list">

		<?php if(!empty($zarizeni['zarizeni'])): ?>
			<table>
				<?php foreach($zarizeni['zarizeni'] as $val): ?>
					<tr>
						<td><?php echo $val['name']; ?></td>
						<td><?php echo $val['address']; ?></td>
						<td><?php echo $val['inserted']; ?></td>
						<td><a href="<?php echo get_front_url() . "zarizeni/" . $val['permalink']; ?>">Odkaz</a></td>
						<td class="<?php if($val['active'] == 0) echo 'not_active'; ?>">Aktivní</td>
						<td class="<?php if($val['doporucujeme'] == 0) echo 'not_active'; ?>">Doporučujeme</td>
						<td><a href="<?php echo get_admin_url() . "pridat_zarizeni?id=" . $val['ID']; ?>">Upravit</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php if(!empty($zarizeni['pagination'])): ?>
				<ul class="pagination">
					<?php foreach($zarizeni['pagination'] as $link): ?>
						<li><a class="<?php if($paged == $link['page']) echo 'active'; ?>" href="<?php echo get_admin_url() . "" . "" . $link['link']; ?>"><?php echo $link['page']; ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</form>
<?php include "footer.php"; ?>