<?php include "header.php"; ?>
	<?php
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$db = new Db();
		$zarizeni = $db->fetch("SELECT * FROM zarizeni WHERE permalink LIKE '" . $db->db_escape($_GET['id'], $db->conn) . "' AND lang LIKE '" . get_current_lang() . "'");
	}

	//IF DISTANCE
	$distance = "";
	if(isset($_COOKIE['lat'])){
		$distance = distance($_COOKIE['lat'], $_COOKIE['lng'], $zarizeni['lat'], $zarizeni['lng']);
	}

	$typ = "";
	if($zarizeni['kavarna'] == 1){
		$typ = "kavarna";
	}elseif($zarizeni['restaurace'] == 1){
		$typ = "restaurace";
	}elseif($zarizeni['cvicak'] == 1){
		$typ = "cvicak";
	}elseif($zarizeni['hotel'] == 1){
		$typ = "hotel";
	}elseif($zarizeni['potreby'] == 1){
		$typ = "sluzby";
	}elseif($zarizeni['hriste'] == 1){
		$typ = "hriste";
	}

	?>
	<div class="container">
		<div class="detail_title">
			<div class="title_cont">
				<h1><?php echo $zarizeni['name']; ?></h1><img height="32px" src="<?php echo get_front_url() . "img/" . $typ . ".svg"; ?>">
				<div class="clear"></div>
				<div class="address"><?php echo $zarizeni['address']; ?><?php if($distance): ?><span class="distance"> | <?php echo $distance . " km"; ?></span><?php endif; ?></div>
			</div>
			<div class="rating_cont">
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
				<div class="clear"></div>
				<?php
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

				$hodnoceni_text = "";

				switch($average){
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
				<?php if($typ != "hriste"): ?>
					<div class="rating rating<?php echo $average; ?>"><div></div></div>
					<div class="rating_text"><?php echo $hodnoceni_text; ?> (<?php echo $all_hodnoceni; ?> <?php _e('hodnocení'); ?>)</div>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="detail_gallery">
			<?php $imgs = $db->fetch_all("SELECT * FROM imgs WHERE zarizeni_ID=" . $zarizeni['ID'] . " AND is_main=0");  ?>
			<?php $c = 0; foreach($imgs as $img): $c++; ?>
				<?php if($c == 1): ?>
					<a href="<?php echo get_img_src($img['ID'], "front_big"); ?>" class="fancybox num<?php echo $c; ?>" rel="group"><img src="<?php echo get_img_src($img['ID'], "front_detail"); ?>"></a>
				<?php elseif($c == 4): ?>
					<a href="<?php echo get_img_src($img['ID'], "front_big"); ?>" rel="group" class="fancybox num<?php echo $c; ?>"><div class="overlay"><span><?php _e_printf("+ Zobrazit všechny fotografie (%d)", count($imgs)); ?></span></div><img src="<?php echo get_img_src($img['ID'], "front_gallery"); ?>"></a>
				<?php elseif($c > 1 && $c < 4): ?>
					<a href="<?php echo get_img_src($img['ID'], "front_big"); ?>" rel="group" class="fancybox num<?php echo $c; ?>"><img src="<?php echo get_img_src($img['ID'], "front_gallery"); ?>"></a>
				<?php else: ?>
					<a style="display:none;" href="<?php echo get_img_src($img['ID'], "front_big"); ?>" rel="group" class="fancybox num<?php echo $c; ?>"><img src="<?php echo get_img_src($img['ID'], "front_gallery"); ?>"></a>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php /*
			<div class="flexslider mobile_gallery">
				<div class="flex_counter"><span>1</span>/<?php echo count($imgs); ?></div>
				<ul class="slides">
					<?php $c = 0; foreach($imgs as $img): $c++; ?>
						<li><img src="<?php echo get_img_src($img['ID'], "front_detail"); ?>"></li>
					<?php endforeach; ?>
				</ul>
			</div>
			*/ ?>
			<div class="flexslider mobile_gallery">
				<div class="flex_counter"><span>1</span>/<?php echo count($imgs); ?></div>
				<div class="slides_js">
					<?php $c = 0; foreach($imgs as $img): $c++; ?>
						<img src="<?php echo get_img_src($img['ID'], "front_detail"); ?>">
					<?php endforeach; ?>
				</div>
			</div>
			<a data-lat="<?php echo $zarizeni['lat']; ?>" data-lng="<?php echo $zarizeni['lng']; ?>" href="" class="num5 navigate">
			<div class="map_overlay"><?php _e("Ukaž trasu")?></div>
			<div class="map_cont"><div id="map"></div></div>
			</a>
			<script>
		      function initMap() {
		        var myLatLng = {lat: <?php echo $zarizeni['lat']; ?>, lng: <?php echo $zarizeni['lng']; ?>};

		        var map = new google.maps.Map(document.getElementById('map'), {
		          zoom: 15,
		          center: myLatLng
		        });
		        map.setOptions({draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: true});

		        var marker = new google.maps.Marker({
		          position: myLatLng,
		          map: map,
		          title: ''
		        });
		      }
		    </script>
			<script async defer
		    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuZvB0tp3hkkhWgXSVx_8B2nIIWIWwyqY&callback=initMap">
		    </script>
			<div class="clear"></div>
		</div>
		<div class="detail_info benefity">
			<div class="title"><?php _e('Benefity'); ?></div>
			<div class="content">
				<?php if($zarizeni['wifi'] == 1): ?>
					<div  class="wifi"><span class="tooltip" title="<?php _e('Wifi'); ?>"></span><?php _e('Free WiFi'); ?></div>
				<?php endif; ?>
				<?php if($zarizeni['dog_friendly'] == 1): ?>
					<div class="dog_friendly"><span class="tooltip" title="<?php _e('Miska s vodou a pelíšek'); ?>"></span><?php _e('Miska s vodou a pelíšek'); ?></div>
				<?php endif; ?>
				<?php if($zarizeni['krmivo'] == 1): ?>
					<div  class="krmivo"><span class="tooltip" title="<?php _e('Žrádlo pro pejska'); ?>"></span><?php _e('Žrádlo pro pejska'); ?></div>
				<?php endif; ?>
				<?php if($zarizeni['parkovani'] == 1): ?>
					<div  class="parkovani"><span class="tooltip" title="<?php _e('Parkování zdarma'); ?>"></span><?php _e('Parkování zdarma'); ?></div>
				<?php endif; ?>
				<?php if($zarizeni['bezbarier'] == 1): ?>
					<div  class="bezbarier"><span class="tooltip" title="<?php _e('Bezbariérový přístup'); ?>"></span><?php _e('Bezbariérový přístup'); ?></div>
				<?php endif; ?>
				<?php if($zarizeni['zahradka'] == 1): ?>
					<div  class="zahradka"><span class="tooltip" title="<?php _e('Zahrádka'); ?>"></span><?php _e('Zahrádka'); ?></div>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="detail_info about">
			<div class="wider">
				<div class="title"><?php _e('O zařízení'); ?></div>
				<div class="content popis">
					<?php echo $zarizeni['popis']; ?>
				</div>
				<div class="clear mbottom"></div>
			</div>
			<?php if($zarizeni['doba_show']): ?>
			<div class="shorter provozni">
					<div class="title"><?php _e('Provozní doba'); ?></div>
					<div class="content">
						<?php echo $zarizeni['provozni_doba']; ?>
					</div>

				<div class="clear mbottom"></div>
			</div>
			<div class="clear"></div>
			<?php endif; ?>

			<?php if($zarizeni['download']): ?>
			<div class="wider">

					<div class="title"><?php _e('Menu podniku'); ?></div>
					<div class="content">
						<a class="download" href="<?php echo get_front_url() . "admin/uploads/" . $zarizeni['download']; ?>"><?php _e('Náhled menu zde'); ?></a>
					</div>

			</div>
			<?php endif; ?>
			<?php if($zarizeni['contact_show']): ?>
				<div class="shorter">

						<div class="title"><?php _e('Kontakt'); ?></div>
						<div class="content">
							<?php echo $zarizeni['contact']; ?>
						</div>

				</div>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
		<?php if($zarizeni['metro_show'] || $zarizeni['tramvaj_show'] || $zarizeni['autobus_show']): ?>
			<div class="detail_info spojeni">
				<div class="title"><?php _e('Spojení'); ?></div>
				<div class="content">
					<?php if($zarizeni['metro'] && $zarizeni['metro_show']): ?>
						<div class="metro" ><span class="tooltip" title="<?php _e('Metro'); ?>"></span><?php echo $zarizeni['metro']; ?></div>
					<?php endif; ?>
					<?php if($zarizeni['tramvaj'] && $zarizeni['tramvaj_show']): ?>
						<div class="tramvaj"><span class="tooltip" title="<?php _e('Tramvaj'); ?>"></span><?php echo $zarizeni['tramvaj']; ?></div>
					<?php endif; ?>
					<?php if($zarizeni['autobus'] && $zarizeni['autobus_show']): ?>
						<div class="autobus"><span class="tooltip" title="<?php _e('Autobus'); ?>"></span><?php echo $zarizeni['autobus']; ?></div>
					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
		<div class="recenze">
			<div class="title_cont">
				<?php $all_reviews = $db->fetch_all("SELECT ID FROM reviews WHERE zarizeni_ID=" . $zarizeni['ID']); if(empty($all_reviews)){$all_reviews = 0;}else{$all_reviews = count($all_reviews);} ?>
				<span><?php _e("Uživatelské recenze"); ?> (<?php echo $all_reviews; ?>)</span>
				<select name="order" id="order">
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

				$users = $db->fetch_all("SELECT reviews.datum, reviews.popis, reviews.obsluha, reviews.dog_friendly, reviews.jidlo, reviews.prostredi, users.login, users.ID FROM reviews INNER JOIN users ON reviews.user_ID=users.ID WHERE zarizeni_ID=" . $zarizeni['ID'] . " " . $filter_string . " " . $pagination_string);
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
								<li><a class="<?php if($paged == $link['page']) echo 'active'; ?>" href="<?php echo get_permalink($zarizeni['permalink']). "/" . $link['link']; ?>"><?php echo $link['page']; ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				<?php else: ?>
					<p><?php _e('Zatím nebyla přidána žádná recenze'); ?></p>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
			<div class="add_review">
				<?php if(is_user_logged_in_front()): ?>
					<form method="post" action="" class="review_form">
						<h3><?php _e('Napsat recenzi'); ?></h3>
						<div class="clear"></div>
						<div class="textarea">
							<textarea required name="review_text" id="review_text" placeholder="<?php _e('Vaše slovní hodnocení'); ?>"></textarea>
						</div>
						<?php if($typ == "hriste"): ?>
							<div class="clear"></div>
						<?php endif; ?>
						<div class="<?php if($typ != "hriste"): ?>right_inputs<?php endif; ?>">
							<?php if($typ != "hriste"): ?>
								<div class="rating_input">
									<span><?php _e('Obsluha'); ?></span>
									<div class="rating rating0">
										<div class="full"></div>
										<div class="point" data-val="1"></div>
										<div class="point" data-val="2"></div>
										<div class="point" data-val="3"></div>
										<div class="point" data-val="4"></div>
										<div class="point" data-val="5"></div>
									</div>
									<input required type="hidden" name="obsluha" value="" id="obsluha">
									<div class="clear"></div>
								</div>
								<div class="rating_input">
									<span><?php _e('Dog Friendly'); ?></span>
									<div class="rating rating0">
										<div class="full"></div>
										<div class="point" data-val="1"></div>
										<div class="point" data-val="2"></div>
										<div class="point" data-val="3"></div>
										<div class="point" data-val="4"></div>
										<div class="point" data-val="5"></div>
									</div>
									<input required type="hidden" name="dog_friendly" value="" id="dog_friendly">
									<div class="clear"></div>
								</div>
								<div class="rating_input">
									<span><?php _e('Jídlo a pití'); ?></span>
									<div class="rating rating0">
										<div class="full"></div>
										<div class="point" data-val="1"></div>
										<div class="point" data-val="2"></div>
										<div class="point" data-val="3"></div>
										<div class="point" data-val="4"></div>
										<div class="point" data-val="5"></div>
									</div>
									<input required type="hidden" name="jidlo" value="" id="jidlo">
									<div class="clear"></div>
								</div>
								<div class="rating_input">
									<span><?php _e('Prostředí'); ?></span>
									<div class="rating rating0">
										<div class="full"></div>
										<div class="point" data-val="1"></div>
										<div class="point" data-val="2"></div>
										<div class="point" data-val="3"></div>
										<div class="point" data-val="4"></div>
										<div class="point" data-val="5"></div>
									</div>
									<input required type="hidden" name="prostredi" value="" id="prostredi">
									<div class="clear"></div>
								</div>
							<?php else: ?>
								<input required type="hidden" name="obsluha" value="0" id="obsluha">
								<input required type="hidden" name="dog_friendly" value="0" id="dog_friendly">
								<input required type="hidden" name="jidlo" value="0" id="jidlo">
								<input required type="hidden" name="prostredi" value="0" id="prostredi">
							<?php endif; ?>
							<div class="clear"></div>
							<input type="hidden" name="zarizeni_ID" id="zarizeni_ID" value="<?php echo $zarizeni['ID']; ?>">
							<button type="submit" class=""><?php _e('Odeslat'); ?></button>
						</div>
						<div class="clear"></div>
					</form>
				<?php else: ?>
					<h3><?php _e('Napsat recenzi'); ?></h3>
					<span><?php _e('Pro přidání recenze musíte být přihlášeni'); ?></span><a class="btn login_fancybox" href="#login"><?php _e('Přihlásit'); ?></a>
				<?php endif; ?>
			</div>
		</div>
	    <div class="clear"></div>
	</div>
<?php include "footer.php"; ?>