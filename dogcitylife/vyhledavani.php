<?php include "header.php"; ?>

	<div class="container">
		<?php
			if(isset($_GET['page']) && !empty($_GET['page'])){
				$paged = $_GET['page'];
			}else{
				$paged = 1;
			}

			$per_page = 10;

			$typ = false;
			if(isset($_GET['typ']) && !empty($_GET['typ'])){
				$typ = $_GET['typ'];
			}

			$benefit = false;
			if(isset($_GET['benefit']) && !empty($_GET['benefit'])){
				$benefit = $_GET['benefit'];
			}

			$typ_string = "";
			$benefit_string = "";

			if($typ){
				$typ = array_unique($typ);
				$typ_string .= "AND (";
				$c = 0;
				foreach($typ as $val){
					$c++;
					if($c == 1){
						$typ_string .= "" . $val . "=1 ";
					}else{
						$typ_string .= " OR " . $val . "=1 ";
					}

				}
				$typ_string .= ") ";
			}

			if($benefit){
				foreach($benefit as $val){
					$benefit_string .= " AND " . $val . "=1 ";
				}
			}

			$vzdalenost = (float) 100;
			if(isset($_GET['vzdalenost']) && !empty($_GET['vzdalenost'])){
				$vzdalenost = (float) $_GET['vzdalenost'];
			}

			$hodnoceni = 0;
			if(isset($_GET['hodnoceni']) && !empty($_GET['hodnoceni'])){
				$hodnoceni = $_GET['hodnoceni'];
			}


			//DOPORUCENE!!!!
			$doporucene = $db->fetch_all("SELECT * FROM zarizeni WHERE doporucujeme=1 AND active=1 AND lang LIKE '" . get_current_lang() . "' " . $typ_string . $benefit_string);

			if($doporucene){

				foreach ($doporucene as $key => $value) {
					$hodnoceni_avg = $db->fetch("SELECT avg(obsluha),avg(dog_friendly),avg(jidlo),avg(prostredi) FROM reviews WHERE zarizeni_ID=" . $value['ID']);
					$all_hodnoceni = $db->fetch("SELECT count(ID) FROM reviews WHERE zarizeni_ID=" . $value['ID']);
					if($all_hodnoceni){
						$all_hodnoceni = $all_hodnoceni['count(ID)'];
					}else{
						$all_hodnoceni = 0;
					}

					$doporucene[$key]["all_hodnoceni"] = $all_hodnoceni;

					$average = 0;
					if($hodnoceni_avg){
						foreach ($hodnoceni_avg as $value) {
							$average += $value;
						}

						$average = round($average/count($hodnoceni_avg));
					}

					$doporucene[$key]["average"] = $average;
				}

				if($lat && $lng){
					foreach ($doporucene as $key => $value) {
						$doporucene[$key]["distance"] = distance($lat, $lng, $value['lat'], $value['lng']);
					}
				}
			}

			if($doporucene){
				if($lat && $lng){
					//distance
					usort($doporucene, function($a, $b) {
					    $result = 0;
				        if ($a['distance'] > $b['distance']) {
				            $result = 1;
				        } else if ($a['distance'] < $b['distance']) {
				            $result = -1;
				        }
				        return $result;
					});
				}else{
					//rating
					usort($doporucene, function($a, $b) {
					    return $a['average'] - $b['average'];
					});
				}
			}

			if($doporucene){
				$doporucene = array_slice($doporucene, 0, 1);
				$doporucene = reset($doporucene);
			}
			//END DOPORUCENE

			$zarizeni_all = $db->fetch_all("SELECT * FROM zarizeni WHERE active=1 AND lang LIKE '" . get_current_lang() . "' " . $typ_string . $benefit_string);
			if($lat && $lng){
				if($zarizeni_all){
					foreach ($zarizeni_all as $key => $value) {
						$zarizeni_all[$key]["distance"] = (float) distance($lat, $lng, $value['lat'], $value['lng']);
						if($zarizeni_all[$key]["distance"] > $vzdalenost){
							unset($zarizeni_all[$key]);
						}
					}
				}

			}
			if($zarizeni_all){
				foreach ($zarizeni_all as $key => $value) {
					$hodnoceni_avg = $db->fetch("SELECT avg(obsluha),avg(dog_friendly),avg(jidlo),avg(prostredi) FROM reviews WHERE zarizeni_ID=" . $value['ID']);
					$all_hodnoceni = $db->fetch("SELECT count(ID) FROM reviews WHERE zarizeni_ID=" . $value['ID']);
					if($all_hodnoceni){
						$all_hodnoceni = $all_hodnoceni['count(ID)'];
					}else{
						$all_hodnoceni = 0;
					}

					$zarizeni_all[$key]["all_hodnoceni"] = $all_hodnoceni;

					$average = 0;
					if($hodnoceni_avg){
						foreach ($hodnoceni_avg as $value) {
							$average += $value;
						}

						$average = round($average/count($hodnoceni_avg));
					}

					$zarizeni_all[$key]["average"] = $average;
					if($zarizeni_all[$key]["average"] < $hodnoceni){
						unset($zarizeni_all[$key]);
					}

					if(isset($doporucene) && isset($zarizeni_all[$key]) && $zarizeni_all[$key]['ID'] == $doporucene['ID']){
						unset($zarizeni_all[$key]);
					}
				}
			}

			$all = 0;
			if($zarizeni_all){
				$all = count($zarizeni_all);
			}

			$total_pages = ceil($all/$per_page);

			if($paged > 1){
				$offset = ($paged-1) * $per_page;
			}else{
				$offset = 0;
			}


			//ORDER ARRAY
			if($zarizeni_all){
				if($lat && $lng){
					//distance
					$test = usort($zarizeni_all, function($a, $b) {
						$result = 0;
				        if ($a['distance'] > $b['distance']) {
				            $result = 1;
				        } else if ($a['distance'] < $b['distance']) {
				            $result = -1;
				        }
				        return $result;
					    //return $a['distance'] - $b['distance'];
					});
				}else{
					//rating
					usort($zarizeni_all, function($a, $b) {
					    return $a['average'] - $b['average'];
					});
				}
			}




			if($zarizeni_all){
				$zarizeni_limited = array_slice($zarizeni_all, $offset, $per_page);
			}


			$pagination = array();

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

			$new = array();
			if(isset($zarizeni_limited))
				$new['zarizeni'] = $zarizeni_limited;
			$new['pagination'] = $pagination;

			$all_count = $all;
			if($doporucene)
				$all_count = $all + 1;

			$type_places_string =  'Dog friendly ';
			if(\strpos($typ_string, 'kavarna') !== false){
				$type_places_string .= __('Kavárny');
			}
			else if(\strpos($typ_string, 'restaurace') !== false){
				$type_places_string .= __('Restaurace');
			}
			else if(\strpos($typ_string, 'cvicak') !== false){
				$type_places_string = __('Cvičáky');
			}
			else if(\strpos($typ_string, 'hotel') !== false){
				$type_places_string .= __('Hotely');
			}
			else if(\strpos($typ_string, 'potreby') !== false){
				$type_places_string = __('Psí potřeby');
			}
			else if(\strpos($typ_string, 'hriste') !== false){
				$type_places_string = __('Psí hřiště');
			}

		?>
		<div class="search_line">
			<h2 class="title"><?php echo $type_places_string; ?> <span><?php echo $address; ?></span></h2>
			<div class="results">
			(<span><?php echo $all_count; ?></span>

			<?php
			if($all_count == 0 || $all_count >= 5){
				_e('výsledků');
			}elseif($all_count==1){
				_e('výsledek');
			}else{
				_e('výsledky');
			}

			?>)
			</div>
			<div class="clear"></div>
		</div>
	    <div class="clear"></div>
	    <div class="sidebar">
	    	<form id="sidebar_form">
		    	<div class="title"><?php _e('Upřesnit hledání'); ?></div>
		    	<div class="inner">
			    	<div class="rating_cont">
			    		<div class="subtitle"><?php _e('Hodnocení'); ?></div>
			    		<div class="rating_input">
				    		<div class="rating rating<?php echo $hodnoceni; ?>">
								<div class="full"></div>
								<div class="point" data-val="1"></div>
								<div class="point" data-val="2"></div>
								<div class="point" data-val="3"></div>
								<div class="point" data-val="4"></div>
								<div class="point" data-val="5"></div>
							</div>
							<input required type="hidden" name="hodnoceni" value="<?php echo $hodnoceni; ?>" id="hodnoceni">
						</div>
			    	</div>
		    	</div>
		    	<div class="inner">
			    	<div class="slider_cont">
			    		<div class="subtitle"><?php _e('Vzdálenost od místa'); ?></div>
			    		<input type="hidden" id="vzdalenost" name="vzdalenost" value="<?php if(isset($_GET['vzdalenost']) && !empty($_GET['vzdalenost'])){echo $_GET['vzdalenost'];}else{echo 100;} ?>" />
			    		<div class="vzdalenost_labels">
			    			<div class="from">0.1 km</div>
			    			<div class="to"><span>100</span> km</div>
			    			<div class="clear"></div>
			    		</div>
			    		<div id="vzdalenost_slider"></div>
			    		<div class="vzdalenost_text"><?php _e('Hledat v okruhu'); ?> (<span><?php if(isset($_GET['vzdalenost']) && !empty($_GET['vzdalenost'])){echo $_GET['vzdalenost'];}else{echo 100;} ?></span> km)</div>
			    	</div>
		    	</div>
		    	<div class="inner">
			    	<div class="buttons">
			    		<div class="subtitle"><?php _e('Benefity'); ?></div>
			    		<div class="inner_buttons benefity">
			    			<select multiple name="benefit[]" id="benefit" style="display: none;">
								<option value="wifi" <?php if(isset($_GET['benefit']) && in_array("wifi", $_GET['benefit'])) echo 'selected'; ?>>Wifi</option>
								<option value="dog_friendly" <?php if(isset($_GET['benefit']) && in_array("dog_friendly", $_GET['benefit'])) echo 'selected'; ?>>Dog Friendly</option>
								<option value="krmivo" <?php if(isset($_GET['benefit']) && in_array("krmivo", $_GET['benefit'])) echo 'selected'; ?>>Žrádlo pro pejska</option>
								<option value="parkovani" <?php if(isset($_GET['benefit']) && in_array("parkovani", $_GET['benefit'])) echo 'selected'; ?>>Parkování zdarma</option>
								<option value="bezbarier" <?php if(isset($_GET['benefit']) && in_array("bezbarier", $_GET['benefit'])) echo 'selected'; ?>>Bezbariérový přístup</option>
								<option value="zahradka" <?php if(isset($_GET['benefit']) && in_array("zahradka", $_GET['benefit'])) echo 'selected'; ?>>Zahrádka</option>
							</select>
							<a class="select_box wifi tooltip <?php if(isset($_GET['benefit']) && in_array("wifi", $_GET['benefit'])) echo 'selected'; ?>" data-val="wifi" href="" title="<?php _e('Wifi'); ?>"></a>
							<a class="select_box dog_friendly tooltip <?php if(isset($_GET['benefit']) && in_array("dog_friendly", $_GET['benefit'])) echo 'selected'; ?>" data-val="dog_friendly" href="" title="<?php _e('Miska s vodou a pelíšek'); ?>"></a>
							<a class="select_box krmivo tooltip <?php if(isset($_GET['benefit']) && in_array("krmivo", $_GET['benefit'])) echo 'selected'; ?>" data-val="krmivo" href="" title="<?php _e('Žrádlo pro pejska'); ?>"></a>
							<a class="select_box parkovani tooltip <?php if(isset($_GET['benefit']) && in_array("parkovani", $_GET['benefit'])) echo 'selected'; ?>" data-val="parkovani" href="" title="<?php _e('Parkování zdarma'); ?>"></a>
							<a class="select_box bezbarier tooltip <?php if(isset($_GET['benefit']) && in_array("bezbarier", $_GET['benefit'])) echo 'selected'; ?>" data-val="bezbarier" href="" title="<?php _e('Bezbariérový přístup'); ?>"></a>
							<a class="select_box zahradka tooltip <?php if(isset($_GET['benefit']) && in_array("zahradka", $_GET['benefit'])) echo 'selected'; ?>" data-val="zahradka" href="" title="<?php _e('Zahrádka'); ?>"></a>
			    		</div>
			    	</div>
			    </div>
			    <div class="inner">
			    	<div class="buttons">
			    		<div class="subtitle"><?php _e('Zařízení'); ?></div>
			    		<div class="inner_buttons zarizeni">
			    			<select multiple name="typ[]" id="typ_side" style="display: none;">
								<option value="kavarna" <?php if(isset($_GET['typ']) && in_array("kavarna", $_GET['typ'])) echo 'selected'; ?>>Hledat kavárny</option>
								<option value="restaurace" <?php if(isset($_GET['typ']) && in_array("restaurace", $_GET['typ'])) echo 'selected'; ?>>Hledat restaurace</option>
								<option value="hotel" <?php if(isset($_GET['typ']) && in_array("hotel", $_GET['typ'])) echo 'selected'; ?>>Hledat hotely</option>
								<option value="potreby" <?php if(isset($_GET['typ']) && in_array("potreby", $_GET['typ'])) echo 'selected'; ?>>Hledat psí potřeby</option>
								<option value="cvicak" <?php if(isset($_GET['typ']) && in_array("cvicak", $_GET['typ'])) echo 'selected'; ?>>Hledat cvičáky</option>
								<option value="hriste" <?php if(isset($_GET['typ']) && in_array("hriste", $_GET['typ'])) echo 'selected'; ?>>Psí hříště</option>
							</select>
							<a class="select_box kavarna tooltip <?php if(isset($_GET['typ']) && in_array("kavarna", $_GET['typ'])) echo 'selected'; ?>" data-val="kavarna" href="" title="<?php _e('Hledat kavárny'); ?>"></a>
							<a class="select_box restaurace tooltip <?php if(isset($_GET['typ']) && in_array("restaurace", $_GET['typ'])) echo 'selected'; ?>" data-val="restaurace" href="" title="<?php _e('Hledat restaurace'); ?>"></a>
							<a class="select_box hotel tooltip <?php if(isset($_GET['typ']) && in_array("hotel", $_GET['typ'])) echo 'selected'; ?>" data-val="hotel" href="" title="<?php _e('Hledat hotely'); ?>"></a>
							<a class="select_box potreby tooltip <?php if(isset($_GET['typ']) && in_array("potreby", $_GET['typ'])) echo 'selected'; ?>" data-val="potreby" href="" title="<?php _e('Hledat psí potřeby'); ?>"></a>
							<a class="select_box cvicak tooltip <?php if(isset($_GET['typ']) && in_array("cvicak", $_GET['typ'])) echo 'selected'; ?>" data-val="cvicak" href="" title="<?php _e('Hledat cvičáky'); ?>"></a>
							<a class="select_box hriste tooltip <?php if(isset($_GET['typ']) && in_array("hriste", $_GET['typ'])) echo 'selected'; ?>" data-val="hriste" href="" title="<?php _e('Hledat psí hřiště'); ?>"></a>
			    		</div>
			    	</div>
			    </div>
			    <div class="inner">
			    	<a class="remove_filters" href=""><?php _e('Zrušit všechny filtry'); ?></a>
			    </div>
			</form>
	    </div>
	    <div class="right_side">
	    	<div id="search_cont">
	    		<?php if($doporucene && $paged == 1): ?>
	    			<?php $zarizeni = $doporucene; ?>
	    			<div class="doporucene">
		    			<?php include("templates/zarizeni_vypis.php"); ?>
		    		</div>
	    			<div class="end_doporucene"><?php _e('Výsledky'); ?></div>
	    		<?php endif; ?>
		    	<?php if(isset($new['zarizeni']) && !empty($new['zarizeni'])):  ?>

						<?php foreach($new['zarizeni'] as $zarizeni): ?>
							<div class=<?php if($zarizeni['pecet'] == 1) echo "pecet"; ?>>
								<?php include("templates/zarizeni_vypis.php"); ?>
							</div>
		    		<?php endforeach; ?>
		    		<?php if(!empty($new['pagination'])): ?>
		    			<?php
		    				$prev = false;
		    				$next = false;
		    				if($paged > 1){
		    					$prev = $paged - 1;
		    				}
		    				if($paged < count($new['pagination'])){
		    					$next = $paged + 1;
		    				}
		    			?>
		    			<div class="pagination">
			    			<?php if($prev): ?>
			    				<a href="" data-page="<?php echo $prev; ?>" class="prev"><?php _e('Předchozí'); ?></a>
			    			<?php else: ?>
			    				<span class="prev"><?php _e('Předchozí'); ?></span>
			    			<?php endif; ?>
							<ul class="pagination">
								<?php foreach($new['pagination'] as $link): ?>
									<li><a data-page="<?php echo $link['page']; ?>" class="<?php if($paged == $link['page']) echo 'active'; ?>" href="<?php echo get_permalink($zarizeni['permalink']). "/" . $link['link']; ?>"><?php echo $link['page']; ?></a></li>
								<?php endforeach; ?>
							</ul>
							<?php if($next): ?>
			    				<a href="" data-page="<?php echo $next; ?>" class="next"><?php _e('Další'); ?></a>
			    			<?php else: ?>
			    				<span class="next"><?php _e('Další'); ?></span>
			    			<?php endif; ?>
			    			<div class="clear"></div>
		    			</div>
					<?php endif; ?>
		    	<?php endif; ?>
		    </div>
	    </div>
	    <div class="clear"></div>
	</div>
<?php include "footer.php"; ?>