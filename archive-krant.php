<?php


get_header();
set_query_var('klassen_bij_primary', "krant");
get_template_part('/sja/open-main');

if ( have_posts() ) : ?>

	<header class="page-header">

		<h1>De Socialist</h1>
		<?php
			echo wp_get_attachment_image(get_field('krant_foto', 'option'), 'bovenaan_art');
			echo apply_filters('the_content', get_field('krant_tekst', 'option'));
		?>
	</header><!-- .page-header -->
	<div class='niet-volle-breedte'>

		<?php
		while ( have_posts() ) : the_post();

				$art = new Article_c(
					array(
						'class' => "in-lijst blok",
						'h_type' => 2,
						'exc_lim' => 300,
						'titel_overschrijving' => 	str_replace(',', '', (	explode(' ', get_field('editie', $post->ID))[0]	))	. ' - ' . $post->post_title// bv #301 blabla
					),
				$post);

				$art->print();

		endwhile;

		paginering();

	echo "</div>";

else :

	echo "geen kranten gevonden";

endif; ?>

<?php

get_template_part('/sja/sluit-main');
get_footer();
