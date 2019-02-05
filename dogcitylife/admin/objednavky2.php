<?php include "header.php"; ?>
<?php 
$db = new Db();
$paged = 1;
if(isset($_GET['paged']) && !empty($_GET['paged'])){
	$paged = $_GET['paged'];
}
$zarizeni = $db->get_objednavky_filter($paged);
//$all = $db->get_zarizeni_filter_all($search, $typ, $address, $date);
?>
	<div class="under_header"></div>
	<div class="list">

		<?php if(!empty($zarizeni['zarizeni'])): ?>
			<table>
				<?php foreach($zarizeni['zarizeni'] as $val): ?>
					<tr>
						<td><?php echo $val['objednatel']; ?></td>
						<td><?php echo $val['adresa']; ?></td>
						<td><?php echo date("d.m.Y H:i",strtotime($val['date'])); ?></td>
						<td><?php if($val['admin_read'] == 0){echo "Nepřečteno";}else{echo "Přečteno";} ?></td>
						<td><?php if($val['admin_send'] == 0){echo "K vyřízení";}else{echo "Hotovo";} ?></td>
						<td><a href="<?php echo get_admin_url() . 'objednavka?id=' . $val['ID']; ?>">Detail</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php if(!empty($zarizeni['pagination'])): ?>
				<ul class="pagination">
					<?php foreach($zarizeni['pagination'] as $link): ?>
						<li><a class="<?php if($paged == $link['page']) echo 'active'; ?>" href="<?php echo get_admin_url() . "objednavky2" . "" . $link['link']; ?>"><?php echo $link['page']; ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php endif; ?>
	</div>
<?php include "footer.php"; ?>