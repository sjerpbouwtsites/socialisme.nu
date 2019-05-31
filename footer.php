
<div id='stek-voet' class='verpakking' role="contentinfo">
	<div class='blok'>
		<div class='flex'>
			<section class='sectie-wit'>
				<h3><?php the_field('kolom_1_titel', 'option');?></h3>
				<?php the_field('kolom_1_tekst', 'option');	?>

				<div id='footer-share-kopie'></div>

				<footer>
					<?php
					$fb_knop = new Knop(array(
						'class' => 'in-wit',
						'link' => get_field('footer_facebook_link', 'option'),
						'tekst'=> 'Vind ons op facebook'
						));
					$fb_knop->print();
					?>
				</footer>
			</section>
			<section class='sectie-wit'>
				<h3><?php the_field('kolom_2_titel', 'option');?></h3>
				<?php echo wpautop(do_shortcode(get_field('kolom_2_tekst', 'option'))); ?>
			</section>
			<section class='sectie-wit'>
 				<h3>Menu</h3>
				<?php
					wp_nav_menu(array(
						'theme_location'	=> "voet",
						'menu_class'		=> 'stek-nav'
					));
				?>
				<p class='menu-stuk'><a href='#' target='-1' class='schakel' data-doorschakel='#toegankelijkheidsschakel' aria-label='visuele toegankelijkheidsopties zoals contrast, lettergrootte en lettertype'>Toegankelijkheid</a></p>
			</section>
		</div>
		<div class='colofon'>
			<?php the_field('footer_colofon', 'option'); ?>
		</div>
	</div>
</div>


<?php wp_footer();

?>
<script>




/*var img = document.querySelector('img')

function loaded() {
  alert('loaded')
}

if (img.complete) {
  loaded()
} else {
  img.addEventListener('load', loaded)
  img.addEventListener('error', function() {
      alert('error')
  })
}

*/
console.log('git test');


var BASE_URL = "<?php echo get_site_url(); ?>",
	TEMPLATE_URL = "<?php echo get_template_directory_uri(); ?>",
	IMG_URL = TEMPLATE_URL + "/afb/",
	AJAX_URL = BASE_URL + "/wp-admin/admin-ajax.php";

toonHetLogo();

// hoeft slechts te wachten tot HTML er is.
function toonHetLogo() {

	var printHier = document.getElementById('print-het-logo-in-mij');

	var hetLogo = document.createElement('img');
	hetLogo.setAttribute('width', '465');
	hetLogo.setAttribute('height', '100');
	hetLogo.classList.add('custom-logo');
	hetLogo.classList.add('ben-nog-aan-het-laden');
	hetLogo.classList.add('nog-geen-data');

	hetLogo.setAttribute('src', TEMPLATE_URL + '/images/socialisme-nu-logo.png');

	if (!hetLogo.complete) {

		hetLogo.addEventListener('load', function(){
			printHier.appendChild(hetLogo);
			hetLogo.setAttribute('alt', 'Socialisme.nu | Nieuws, verslagen, theorie van de Internationale Socialisten');
			hetLogo.classList.remove('nog-geen-data');
			setTimeout(function(){
				hetLogo.classList.remove('ga-maar-laden');
			}, 25);

		});

		hetLogo.addEventListener('error', function(){
			var s = document.createElement('span');
			s.textContent = 'logo laadde niet';
			printHier.appendChild(s);
		});

	} else {

		printHier.appendChild(hetLogo);
		hetLogo.classList.remove('nog-geen-data');
		setTimeout(function(){
			hetLogo.classList.remove('ga-maar-laden');
		}, 25);

	}





}


</script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js">
  </script>

<script src='<?=get_template_directory_uri() . "/js/all.js"?>'></script>
</body>
</html>
