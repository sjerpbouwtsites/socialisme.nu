<?php

get_header('sectie');
set_query_var('klassen_bij_primary', "los-bericht zijbalk-laag");
get_template_part('/sja/open-main');

?>

<article class='bericht'>
 	<h1><?php the_title();?></h1>
	<div class='uitgelichte-afbeelding-buiten'>

		<?php
			get_template_part('sja/post-afb-met-desc');
		?>

	</div>
	<div class='niet-volle-breedte'>

		<?php

		while ( have_posts() ) : the_post();

			single_content($post, krant_art_meta());

			single_footer($post);

			if ( is_active_sidebar( 'onder_krant' ) )dynamic_sidebar('onder_krant');

			$terug = new Knop(array(
				'class' => 'in-wit ikoon-links',
				'link' => get_post_type_archive_link('krant'),
				'tekst' => 'Terug naar krantenoverzicht',
				'ikoon' => "arrow-left-thick"
			));

			$terug->print();


		endwhile; // End of the loop.
		?>
	</div>
</article>


<?php
get_template_part('/sja/sluit-main');
get_footer();
