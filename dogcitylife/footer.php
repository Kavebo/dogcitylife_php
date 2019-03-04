	</div>
</div>
<div class="footer">
	<div class="container">
		<a class="up" href=""></a>
		<div class="footer_menu">
			<div class="title"><?php _e('Zařízení'); ?></div>
			<ul>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=kavarna"; ?>"><?php _e('Kavárny'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=restaurace"; ?>"><?php _e('Restaurace'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=hotel"; ?>"><?php _e('Hotely'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=potreby"; ?>"><?php _e('Psí potřeby'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=cvicak"; ?>"><?php _e('Cvičáky'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=hriste"; ?>"><?php _e('Psí hřiště'); ?></a></li>
			</ul>
		</div>
		<div class="footer_menu">
			<div class="title"><?php _e('Top zařízení'); ?></div>
			<ul>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=kavarna&hodnoceni=5"; ?>"><?php _e('Kavárny'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=restaurace&hodnoceni=5"; ?>"><?php _e('Restaurace'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=hotel&hodnoceni=5"; ?>"><?php _e('Hotely'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=potreby&hodnoceni=5"; ?>"><?php _e('Psí potřeby'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=cvicak&hodnoceni=5"; ?>"><?php _e('Cvičáky'); ?></a></li>
				<li><a href="<?php echo get_front_url_lang() . "vyhledavani?typ[]=hriste"; ?>"><?php _e('Psí hřiště'); ?></a></li>
			</ul>
		</div>
		<div class="footer_menu">
			<div class="title"><?php _e('Dog City Life'); ?></div>
			<ul>
				<li><a href="<?php if(get_current_lang() == "cs"){echo get_front_url_lang() . "o-nas";}else{echo get_front_url_lang() . "about";} ?>"><?php _e('O nás'); ?></a></li>
				<li><a href="https://www.instagram.com/dogcitylife_cz/"><?php _e('Instagram'); ?></a></li>
				<li><a href="<?php if(get_current_lang() == "cs"){echo get_front_url_lang() . "kontakt";}else{echo get_front_url_lang() . "contact";} ?>"><?php _e('Kontakt'); ?></a></li>
				<li><a href="<?php if(get_current_lang() == "cs"){echo get_front_url_lang() . "spoluprace";}else{echo get_front_url_lang() . "cooperation";} ?>"><?php _e('Spolupráce'); ?></a></li>
				<li><a href="<?php if(get_current_lang() == "cs"){echo get_front_url_lang() . "obchodni-podminky";}else{echo get_front_url_lang() . "terms-of-service";} ?>"><?php _e('Obchodní podmínky'); ?></a></li>
				<li><a href="<?php if(get_current_lang() == "cs"){echo "https://blog.dogcitylife.cz/";}else{echo "https://blog.dogcitylife.cz/en/";} ?>" class="blog"><?php _e('Blog'); ?></a></li>
			</ul>
		</div>
		<div class="copyright">Copyright <?php echo date('Y'); ?> © Dog City Life</div>
		<div class="clear"></div>
	</div>
	<div class="copyright_mobile"><div class="container">Copyright <?php echo date('Y'); ?> © Dog City Life</div></div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?AIzaSyCa9hro3xB3Z5ciY7HDcLWC65HinFyIrWU&callback=myMap"></script>
</body>
</html>