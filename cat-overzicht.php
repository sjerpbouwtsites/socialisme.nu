<?php

/*
template name: cat overzicht */

get_header();
set_query_var('klassen_bij_primary', "cats");
set_query_var('titel_hoog', "<h1>".$post->post_title."</h1>");
get_template_part('/sja/open-main');

$cats = get_categories();
//echo "<script>console.dir(".json_encode($cats).")</script>";

foreach ($cats as $c) {
	if ($c->term_id === 1 || $c->count === 0) continue;

	//maskeren als post
	$a = new Article_c(array(
		'is_categorie' 	=> true,
		'class' 		=> 'blok in-lijst',
		'htype'			=> 2,
	), $c);

	$a->print();

}

get_template_part('/sja/sluit-main');
get_footer();
