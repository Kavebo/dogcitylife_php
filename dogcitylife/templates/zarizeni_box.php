<?php 

$typ = "";
if($zarizeni['kavarna'] == 1){
	$typ = "kavarna";
}elseif($zarizeni['restaurace'] == 1){
	$typ = "restaurace";
}elseif($zarizeni['bar'] == 1){
	$typ = "bar";
}elseif($zarizeni['hotel'] == 1){
	$typ = "hotel";
}elseif($zarizeni['potreby'] == 1){
	$typ = "potreby";
}elseif($zarizeni['hriste'] == 1){
	$typ = "hriste";
}

$hodnoceni = $db->fetch("SELECT avg(obsluha),avg(dog_friendly),avg(jidlo),avg(prostredi) FROM reviews WHERE zarizeni_ID=" . $zarizeni['ID']);
$all_hodnoceni = $db->fetch("SELECT count(ID) FROM reviews WHERE zarizeni_ID=" . $zarizeni['ID']);
if($all_hodnoceni){
	$all_hodnoceni = $all_hodnoceni['count(ID)'];
}else{
	$all_hodnoceni = 0;
}

$average = 0;
if($hodnoceni){
	foreach ($hodnoceni as $value) {
		$average += $value;
	}

	$average = round($average/count($hodnoceni));
}

?>
<div class="zarizeni_box <?php echo $typ; ?>">
	<?php $img = $db->fetch("SELECT * FROM imgs WHERE zarizeni_ID=" . $zarizeni['ID'] . " AND is_main=1"); ?>
	<?php if($img): ?>
		<a href="<?php echo get_permalink($zarizeni['permalink']); ?>"><img src="<?php echo get_img_src($img['ID'], "front_small"); ?>"></a>
	<?php endif; ?>
		<div class="inner">
			<a href="<?php echo get_permalink($zarizeni['permalink']); ?>" class="title"><?php echo $zarizeni['name']; ?></a>
			<div class="address"><?php echo $zarizeni['address']; ?></div>
			<?php if($typ != "hriste"): ?>
				<div class="rating rating<?php echo $average; ?>"><div></div></div>	
				<div class="rating_text">(<?php echo $all_hodnoceni; ?> <?php _e('hodnocení'); ?>)</div>
			<?php endif; ?>
		</div>
	
</div>