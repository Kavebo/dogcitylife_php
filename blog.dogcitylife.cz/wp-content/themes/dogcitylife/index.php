<?php get_header(); ?>
	<?php global $wp_query; $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;  ?>

	<div class="container">
		<?php 
			$cats = get_categories(array('hide_empty' => false, 'exclude' => array(1,7)));
			$cur_cat = false;
			if(get_query_var( 'cat' ))
				$cur_cat = get_category( get_query_var( 'cat' ) );
		?>
		<div class="categories">
			<ul>
				<li><a href="<?php bloginfo('url'); ?>" class="<?php if(!is_archive()) echo 'active'; ?>"><?php _e('Vše', 'dogcitylife'); ?></a></li>
				<?php if(!empty($cats)): ?>
					<?php foreach($cats as $cat): ?>
						<li><a class="<?php if(is_archive() && $cat->term_id == $cur_cat->term_id) echo 'active'; ?>" href="<?php echo get_term_link( $cat, 'category' ); ?>"><?php echo $cat->name; ?></a></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
		<div class="clanky">
			<div id="ajax_load_container" data-next="<?php echo get_next_posts_page_link($wp_query->max_num_pages); ?>">
				<?php $c=0; while(have_posts()): the_post(); $c++; ?>
					<div class="clanek <?php if($c % 3 == 0 ) echo  'third '; if($c % 2 == 0 ) echo ' second '; ?>">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail("listing"); ?></a>
						<div class="inner">
							<a class="title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							<p><?php echo get_excerpt(100); ?></p>
							<div class="author_img"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><img src="<?php echo get_avatar_url(get_the_author_meta( 'ID' ), array('size' => 53)); ?>"></a></div>
						</div>
					</div>
					<?php if($c == 3 && $paged == 1): ?>
						<div class="registration_banner desktop">
							<?php
								$cur_lang = pll_current_language();
							?>
							<?php if($cur_lang == "cs"): ?>
								<?php dynamic_sidebar("banner_desktop_cz"); ?>
							<?php elseif($cur_lang = "en"): ?>
								<?php dynamic_sidebar("banner_desktop_en"); ?>
							<?php endif; ?>
						</div>
						<div class="registration_banner mobile smallest">
							<?php
								$cur_lang = pll_current_language();
							?>
							<?php if($cur_lang == "cs"): ?>
								<?php dynamic_sidebar("banner_mobile_cz"); ?>
							<?php elseif($cur_lang = "en"): ?>
								<?php dynamic_sidebar("banner_mobile_en"); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if($c == 4 && $paged == 1): ?>
						<div class="registration_banner mobile">
							<?php
								$cur_lang = pll_current_language();
							?>
							<?php if($cur_lang == "cs"): ?>
								<?php dynamic_sidebar("banner_desktop_cz"); ?>
							<?php elseif($cur_lang = "en"): ?>
								<?php dynamic_sidebar("banner_desktop_en"); ?>
							<?php endif; ?>
						</div>
						
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
			<div class="clear"></div>
			<?php  if(get_next_posts_page_link($wp_query->max_num_pages) && $wp_query->max_num_pages > 0): ?>
				<div class="center">
					<a class="ajax_load_more" href="">Načíst další články</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php get_footer(); ?>
