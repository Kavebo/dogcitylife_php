<?php include "header.php"; ?>
<?php 
$db = new Db();
$stranky = $db->fetch_all("SELECT * FROM cms WHERE lang LIKE 'cs'");

?>
<div class="under_header"></div>
	<div class="list">

		<?php if(!empty($stranky)): ?>
			<table>
				<?php foreach($stranky as $val): ?>
					<tr>
						<td><?php echo $val['name']; ?></td>
						<td><a href="<?php echo get_front_url() . "" . $val['permalink']; ?>">Odkaz</a></td>
						<td><a href="<?php echo get_admin_url() . "cms?id=" . $val['ID']; ?>">Upravit</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>

<?php include "footer.php"; ?>