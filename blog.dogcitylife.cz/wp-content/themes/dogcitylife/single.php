<?php get_header(); ?>
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
	</div>
	<div class="container container_mobile_full">
		<?php while(have_posts()): the_post(); ?>
			<div class="single_post">
				<div class="main_img"><?php the_post_thumbnail("single"); ?></div>
				<div class="inner">
					<div class="inner_cont">
						<h1><?php the_title(); ?></h1>
						<div class="date"><?php echo get_the_date(); ?></div>
						<div class="social">
							<a class="fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink($post->ID)); ?>"></a>
							<a class="copy_link" href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a>
						</div>
						<?php $content = get_extended( $post->post_content ); ?>
						<p class="excerpt"><?php echo $content['main']; ?></p>
						<div class="extended">
							<?php echo apply_filters( 'the_content', $content['extended'] ); ?>
							<div class="clear"></div>
						</div>
						<div class="single_author">
							<div class="author_img"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><img src="<?php echo get_avatar_url(get_the_author_meta( 'ID' ), array('size' => 53)); ?>"></a></div>
							<a class="author_name" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author(); ?></a>
						</div>
						<div id="disqus_thread"></div>
						<script>
						    
						    var disqus_config = function () {
						        // Replace PAGE_URL with your page's canonical URL variable
						        this.page.url = "<?php the_permalink(); ?>";  
						        
						        // Replace PAGE_IDENTIFIER with your page's unique identifier variable
						        this.page.identifier = <?php echo $post->ID; ?>; 
						    };
						    
						    
						    (function() {  // REQUIRED CONFIGURATION VARIABLE: EDIT THE SHORTNAME BELOW
						        var d = document, s = d.createElement('script');
						        
						        // IMPORTANT: Replace EXAMPLE with your forum shortname!
						        s.src = 'https://dogcitylifecz.disqus.com/embed.js';
						        
						        s.setAttribute('data-timestamp', +new Date());
						        (d.head || d.body).appendChild(s);
						    })();
						</script>
						<noscript>
						    Please enable JavaScript to view the 
						    <a href="https://disqus.com/?ref_noscript" rel="nofollow">
						        comments powered by Disqus.
						    </a>
						</noscript>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
<?php get_footer(); ?>
