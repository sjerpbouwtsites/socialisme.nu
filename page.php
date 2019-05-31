<?php

get_header();
set_query_var('klassen_bij_primary', "page los-bericht zijbalk-laag");
get_template_part('/sja/open-main'); ?>

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

			single_content($post);

			single_footer($post);

		endwhile; // End of the loop.
		?>
	</div>
</article>

<?php
get_template_part('/sja/sluit-main');
get_footer();
