<?php
$typ_zarizeni = "";
if($zarizeni['kavarna'] == 1){
	$typ_zarizeni = "kavarna";
}elseif($zarizeni['restaurace'] == 1){
	$typ_zarizeni = "restaurace";
}elseif($zarizeni['cvicak'] == 1){
	$typ_zarizeni = "cvicak";
}elseif($zarizeni['hotel'] == 1){
	$typ_zarizeni = "hotel";
}elseif($zarizeni['potreby'] == 1){
	$typ_zarizeni = "sluzby";
}elseif($zarizeni['hriste'] == 1){
	$typ_zarizeni = "hriste";
}
$hodnoceni_text = "";

switch($zarizeni['average']){
	case 1:
		$hodnoceni_text = __('Velmi špatné');
	break;
	case 2:
		$hodnoceni_text = __('Špatné');
	break;
	case 3:
		$hodnoceni_text = __('Průměrné');
	break;
	case 4:
		$hodnoceni_text = __('Dobré');
	break;
	case 5:
		$hodnoceni_text = __('Vynikající');
	break;
}
?>
<div class="zarizeni_long">
	<?php $img = $db->fetch("SELECT * FROM imgs WHERE zarizeni_ID=" . $zarizeni['ID'] . " AND is_main=1"); ?>
	<?php if($img): ?>
		<a class="img" href="<?php echo get_permalink($zarizeni['permalink']); ?>"><img src="<?php echo get_img_src($img['ID'], "front_vypis"); ?>"></a>
	<?php endif; ?>
	<div class="inner">
		<?php
			$class = "login_fancybox";
			$href = "#login";

			if(is_user_logged_in_front()){
				$href = "";
				$check = $db->fetch("SELECT * FROM favorite WHERE user_ID=" . is_user_logged_in_front() . " AND zarizeni_ID=" . $zarizeni['default_ID']);
				if($check){
					$class = "active ajax_remove_favorite";
				}else{
					$class = "ajax_add_favorite";
				}
			}

		?>
		<a class="favorite <?php echo $class; ?>" data-id="<?php echo $zarizeni['ID']; ?>" href="<?php echo $href; ?>"></a>
		<a class="title" href="<?php echo get_permalink($zarizeni['permalink']); ?>"><span><?php echo $zarizeni['name']; ?></span><img height="32px" src="<?php echo get_front_url() . "img/" . $typ_zarizeni . ".svg"; ?>"></a>
		<div class="address"><?php echo $zarizeni['address']; ?><?php if(isset($zarizeni['distance'])): ?><span class="distance"> | <?php echo $zarizeni['distance'] . " km"; ?></span><?php endif; ?></div>
		<?php if($typ_zarizeni != "hriste"): ?>
			<div class="rating_cont">
				<div class="rating rating<?php echo $zarizeni['average']; ?>"><div></div></div>
				<div class="rating_text"><?php echo $hodnoceni_text; ?> (<?php echo $zarizeni['all_hodnoceni']; ?> <?php _e('hodnocení'); ?>)</div>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
		<div class="popis">
			<?php if($img): ?>
			<a class="mobile_img" href="<?php echo get_permalink($zarizeni['permalink']); ?>"><img src="<?php echo get_img_src($img['ID'], "front_vypis"); ?>"></a>
		<?php endif; ?>
		<span><?php echo mb_substr($zarizeni['popis'], 0, 200) . "..."; ?></span><div class="clear"></div></div>
		<div class="benefity">
			<?php if($zarizeni['wifi'] == 1): ?>
				<img title="<?php _e('Wifi'); ?>" class="tooltip" src="<?php echo get_front_url() . "img/benefit_wifi.svg"; ?>">
			<?php endif; ?>
			<?php if($zarizeni['dog_friendly'] == 1): ?>
				<img title="<?php _e('Miska s vodou a pelíšek'); ?>" class="tooltip" src="<?php echo get_front_url() . "img/benefit_dogfriendly.svg"; ?>">
			<?php endif; ?>
			<?php if($zarizeni['krmivo'] == 1): ?>
				<img title="<?php _e('Žrádlo pro pejska'); ?>" class="tooltip" src="<?php echo get_front_url() . "img/benefit_kost.svg"; ?>">
			<?php endif; ?>
			<?php if($zarizeni['parkovani'] == 1): ?>
				<img title="<?php _e('Parkování zdarma'); ?>" class="tooltip" src="<?php echo get_front_url() . "img/benefit_parkovani.svg"; ?>">
			<?php endif; ?>
			<?php if($zarizeni['bezbarier'] == 1): ?>
				<img title="<?php _e('Bezbariérový přístup'); ?>" class="tooltip" src="<?php echo get_front_url() . "img/benefit_bezbarier.svg"; ?>">
			<?php endif; ?>
			<?php if($zarizeni['zahradka'] == 1): ?>
				<img title="<?php _e('Zahrádka'); ?>" class="tooltip" src="<?php echo get_front_url() . "img/benefit_zahradka.svg"; ?>">
			<?php endif; ?>
		</div>
	</div>
	<div class="clear"></div>
</div>