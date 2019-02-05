<?php include "header.php"; ?>
<?php 
$db = new Db();

if(isset($_POST['delete_review']) && !empty($_POST['delete_review'])){
	$db->db_delete("reviews", array("ID" => $_POST['delete_review']));
}

$paged = 1;
if(isset($_GET['paged']) && !empty($_GET['paged'])){
	$paged = (int) $_GET['paged'];
}


$pagination_string = "";

$per_page = 15;

$all = $db->fetch_all("SELECT reviews.ID, reviews.popis, zarizeni.name, zarizeni.permalink, users.login FROM reviews INNER JOIN zarizeni ON zarizeni.ID=reviews.zarizeni_ID INNER JOIN users ON users.ID=reviews.user_ID");

$total_pages = ceil(count($all)/$per_page);

if($paged > 1){
	$offset = ($paged-1) * $per_page;
}else{
	$offset = 0;
}

$pagination_string = "LIMIT " . $per_page . " OFFSET " . $offset;

$pagination = array();

if($total_pages > 1){
	for ($i=1; $i < $total_pages+1; $i++) { 

		$link = "?paged=" . $i;

		$pagination[] = array(
			'page' => $i,
			'link' => $link
			);
	}
}

$stranky = $db->fetch_all("SELECT reviews.ID, reviews.popis, zarizeni.name, zarizeni.permalink, users.login FROM reviews INNER JOIN zarizeni ON zarizeni.ID=reviews.zarizeni_ID INNER JOIN users ON users.ID=reviews.user_ID " . $pagination_string);
?>
<div class="under_header"></div>
	<div class="list">

		<?php if(!empty($stranky)): ?>
			<form method="post" action="" id="admin_reviews">
				<table>
					<input type="hidden" name="delete_review" id="delete_review" value="">
					<?php foreach($stranky as $val): ?>
						<tr>
							<td><?php echo $val['login']; ?></td>
							<td><a href="<?php echo get_front_url() . "zarizeni/" . $val['permalink']; ?>"><?php echo $val['name']; ?></a></td>
							<td><?php echo $val['popis']; ?></td>
							<td> <button type="submit" class="delete_review" data-id="<?php echo $val['ID']; ?>" href="">Smazat</button></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</form>
			<?php if(!empty($pagination)): ?>
				<ul class="pagination">
					<?php foreach($pagination as $link): ?>
						<li><a class="<?php if($paged == $link['page']) echo 'active'; ?>" href="<?php echo get_admin_url() . "recenze" . "" . $link['link']; ?>"><?php echo $link['page']; ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php endif; ?>
	</div>

<?php include "footer.php"; ?>