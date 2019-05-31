<?php

$queried_object = get_queried_object();
$taxonomy = $queried_object->taxonomy;
$term_id = $queried_object->term_id;
$att_id = get_field('tax_afb', $taxonomy . '_' . $term_id);

$tax_afb = wp_get_attachment_image($att_id, 'bovenaan_art');

$zijbalk_pos = ($tax_afb && $tax_afb !== '') ? "zijbalk-laag" : "";

get_header();

set_query_var('klassen_bij_primary', "cat $zijbalk_pos");
get_template_part('/sja/open-main');


if ( have_posts() ) : ?>

	<header class="page-header">

		<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			echo $tax_afb;
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
		?>
	</header><!-- .page-header -->
	<div class='niet-volle-breedte'>

		<?php
		while ( have_posts() ) : the_post();

				$art = new Article_c(
					array(
						'class' => "in-lijst blok",
						'h_type' => 2,
						'exc_lim' => 300
					),
				$post);

				$art->print();

		endwhile;

		paginering();

else :
	echo "<p>Dit archief is leeg</p>";
endif; ?>
</div>

<?php
get_template_part('/sja/sluit-main');
get_footer();
