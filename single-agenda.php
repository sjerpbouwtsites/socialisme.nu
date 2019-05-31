<?php

get_header('sectie');
set_query_var('klassen_bij_primary', "los-bericht agenda-los zijbalk-laag");
get_template_part('/sja/open-main');

?>

<article class='bericht' itemscope itemtype=http://schema.org/BlogPosting>
	<h1 itemprop="name"><?php the_title();?></h1>
	<div class='uitgelichte-afbeelding-buiten'>

		<?php
			get_template_part('sja/post-afb-met-desc');
		?>

	</div>
	<div class='niet-volle-breedte'>

		<?php

		while ( have_posts() ) : the_post();

			single_content($post, agenda_art_meta($post));

			$lat = get_field('lat');
			$long = str_replace(',', '.', get_field('long'));
			$zoom = str_replace(',', '.', get_field('zoom'));



			if ($lat && $long && $zoom) :

 				echo "<div
 						id='agenda-kaart'
 						data-lat='$lat'
 						data-long='$long'
 						data-zoom='$zoom'
 						data-titel='{$post->post_title}'
 						>
 					</div>";

			endif;

			single_footer($post);

			echo "<p class='terug-naar-agenda'>";

			$terug_naar_agenda = new Knop(array(
				'class' 	=> 'in-wit ikoon-links',
				'link' 		=> get_post_type_archive_link('agenda'),
				'tekst'		=> 'Terug naar de agenda',
				'ikoon'		=> 'arrow-left-thick'
			));

			$terug_naar_agenda->print();

			echo "</p>";

		endwhile; // End of the loop.
		?>
	</div>
</article>


<?php
get_template_part('/sja/sluit-main');
get_footer();
