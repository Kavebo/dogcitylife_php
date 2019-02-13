<div class="review">
	<?php
	$file = explode("/", $_SERVER["PHP_SELF"]);
	$file = end($file);

	$typ = "";
	if($zarizeni['kavarna'] == 1){
		$typ = "kavarna";
	}elseif($zarizeni['restaurace'] == 1){
		$typ = "restaurace";
	}elseif($zarizeni['cvicak'] == 1){
		$typ = "cvicak";
	}elseif($zarizeni['hotel'] == 1){
		$typ = "hotel";
	}elseif($zarizeni['sport'] == 1){
		$typ = "sport";
	}elseif($zarizeni['hriste'] == 1){
		$typ = "hriste";
	}
	?>
	<?php if($file == "profil.php"): ?>
		<?php $zarizeni = $db->fetch("SELECT * FROM zarizeni WHERE ID=" . $review['review_ID']); ?>
		<div class="user"><a href="<?php echo get_permalink($zarizeni['permalink']); ?>"><?php echo $zarizeni['name']; ?> </a> | <?php echo $zarizeni['address']; ?> | <span><?php echo date("d. m. Y", strtotime($review['datum'])); ?></span></div>
	<?php else: ?>
		<div class="user"><a href="<?php echo get_front_url_lang() . "profil?id=" . $review['ID']; ?>"><?php echo $review['login']; ?></a> | <?php echo date("d. m. Y", strtotime($review['datum'])); ?></div>
	<?php endif; ?>
	<div class="content">
		<div class="img">
			<?php
				$img = $db->fetch("SELECT * FROM imgs WHERE is_main=1 AND user_ID=" . $review['ID']);
			?>
			<?php if($img): ?>
				<img src="<?php echo get_img_src($img['ID'], "profile_main"); ?>">
			<?php else: ?>
				<img src="<?php echo get_front_url() . "img/default_comment.png"; ?>">
			<?php endif; ?>
		</div>
		<div class="popis"><?php echo $review['popis']; ?></div>
		<?php if($typ != "hriste"): ?>
			<div class="right_inputs">
				<div class="rating_input">
					<span><?php _e('Obsluha'); ?></span>
					<div class="rating rating<?php echo $review['obsluha']; ?>">
						<div class="full"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="rating_input">
					<span><?php _e('Dog Friendly'); ?></span>
					<div class="rating rating<?php echo $review['dog_friendly']; ?>">
						<div class="full"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="rating_input">
					<span><?php _e('Jídlo a pití'); ?></span>
					<div class="rating rating<?php echo $review['jidlo']; ?>">
						<div class="full"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="rating_input">
					<span><?php _e('Prostředí'); ?></span>
					<div class="rating rating<?php echo $review['prostredi']; ?>">
						<div class="full"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
</div>