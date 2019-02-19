</div>
	<?php
		$cur_lang = pll_current_language();
	?>
	<?php if($cur_lang == "cs"): ?>
		<div class="footer">
			<div class="container">
				<a class="up" href=""></a>
				<div class="footer_menu">
					<div class="title">Zařízení</div>
					<ul>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=kavarna">Kavárny</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=restaurace">Restaurace</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=hotel">Hotely</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=potreby">Psí potřeby</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=cvicak">Cvičáky</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=hriste">Psí hřiště</a></li>
					</ul>
				</div>
				<div class="footer_menu">
					<div class="title">Top zařízení</div>
					<ul>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=kavarna&amp;hodnoceni=5">Kavárny</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=restaurace&amp;hodnoceni=5">Restaurace</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=hotel&amp;hodnoceni=5">Hotely</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=potreby&amp;hodnoceni=5">Psí potřeby</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=cvicak&amp;hodnoceni=5">Cvičáky</a></li>
						<li><a href="https://dogcitylife.cz/vyhledavani?typ[]=hriste">Psí hřiště</a></li>
					</ul>
				</div>
				<div class="footer_menu">
					<div class="title">Dog City Life</div>
					<ul>
						<li><a href="https://dogcitylife.cz/o-nas">O nás</a></li>
						<li><a href="https://www.instagram.com/dogcitylife_cz/">Instagram</a></li>
						<li><a href="https://dogcitylife.cz/kontakt">Kontakt</a></li>
						<li><a href="https://dogcitylife.cz/spoluprace">Spolupráce</a></li>
						<li><a href="https://dogcitylife.cz/obchodni-podminky">Obchodní podmínky</a></li>
						<li><a href="<?php bloginfo('url'); ?>" class="blog">Blog</a></li>
					</ul>
				</div>
				<div class="copyright">Copyright 2018 © Dog City Life</div>
				<div class="clear"></div>
			</div>
			<div class="copyright_mobile"><div class="container">Copyright 2018 © Dog City Life</div></div>
		</div>
	<?php elseif($cur_lang = "en"): ?>
		<div class="footer">
			<div class="container">
				<a class="up" href=""></a>
				<div class="footer_menu">
					<div class="title">Places</div>
					<ul>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=kavarna">Cafe</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=restaurace">Restaurant</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=hotel">Hotel</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=potreby">Dog needs</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=cvicak">Agility</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=hriste">Dog playground</a></li>
					</ul>
				</div>
				<div class="footer_menu">
					<div class="title">Top places</div>
					<ul>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=kavarna&amp;hodnoceni=5">Cafe</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=restaurace&amp;hodnoceni=5">Restaurant</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=hotel&amp;hodnoceni=5">Hotel</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=potreby&amp;hodnoceni=5">Dog needs</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=cvicak&amp;hodnoceni=5">Agility</a></li>
						<li><a href="https://dogcitylife.cz/en/vyhledavani?typ[]=hriste">Dog playground</a></li>
					</ul>
				</div>
				<div class="footer_menu">
					<div class="title">Dog City Life</div>
					<ul>
						<li><a href="https://dogcitylife.cz/en/about">About us</a></li>
						<li><a href="https://www.instagram.com/dogcitylife_cz/">Instagram</a></li>
						<li><a href="https://dogcitylife.cz/en/contact">Contact</a></li>
						<li><a href="https://dogcitylife.cz/en/cooperation">Cooperation</a></li>
						<li><a href="https://dogcitylife.cz/en/terms-of-service">Terms and Conditions</a></li>
						<li><a href="<?php bloginfo('url'); ?>" class="blog">Blog</a></li>
					</ul>
				</div>
				<div class="copyright">Copyright 2018 © Dog City Life</div>
				<div class="clear"></div>
			</div>
		<div class="copyright_mobile"><div class="container">Copyright 2018 © Dog City Life</div></div>
	</div>
	<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>