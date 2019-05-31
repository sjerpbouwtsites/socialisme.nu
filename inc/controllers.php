<?php

function paginering() {

	$m = paginering_model();
	if (!$m) {
        return; //zie model
    } else {
        array_naar_queryvars($m);
        get_template_part('sja/paginering');
    }

}

function single_content($post, $alt_art_meta = '') {

	$m = single_content_model($post, $alt_art_meta);

	echo $m['print'];

	//onder intro
	if (count($m['tot_eind_intro_expl']) > 1) {
		echo apply_filters('the_content', $m['rest_content']);
	} else {
		echo apply_filters('the_content', $post->post_content);
	}
}

function single_footer ($post){

	if (!$post) return;
	$ID = $post->ID;

	echo "<footer class='single-footer'>";

	$bijlagen = get_field('bijlagen', $ID);

	if ($bijlagen and count($bijlagen)) :
		set_query_var('bijlagen', $bijlagen);
		get_template_part('sja/single-footer/bijlagen');
	endif; //bijlagen

	$conversie = get_field('conversies', $ID);

	if ($post->post_type === 'agenda') {

		$conv_m_a = conversie_moment_model($ID, $post);
		array_naar_queryvars($conv_m_a);
		get_template_part('sja/single-footer/moment');

	}

	if ($conversie and count($conversie)) : foreach ($conversie as $con) :

		switch ($con) {

			case 'moment':

				$conv_m_a = conversie_moment_model($ID, $post);

				array_naar_queryvars($conv_m_a);
				get_template_part('sja/single-footer/moment');

				break;

			case 'nieuwsbrief':
				get_template_part('sja/single-footer/nieuwsbrief');
				break;

			case 'aansluiten':
				array_naar_queryvars(array(
					'wld_id'	=> 'conv-wordt-lid',
					'wld_slug'	=> 'wordt_lid',
					'wld_alt_slug'	=> 'aansluiten',
				));
				get_template_part('sja/single-footer/wordt-lid-doneer');
				break;

			case 'doneren':
				array_naar_queryvars(array(
					'wld_id'	=> 'conv-doneren',
					'wld_slug'	=> 'doneer',
					'wld_alt_slug'	=> 'doneer',
				));
				get_template_part('sja/single-footer/wordt-lid-doneer');
				break;

			case 'krant_abo':
				if ( is_active_sidebar( 'voorpagina_midden' ) ) {dynamic_sidebar('voorpagina_midden');}
				break;

			default:
				//
				break;
		}


	endforeach; endif; //als conversie, iedere conversie.



	?>

	</footer>

<?php }

function print_cats() {
	echo cats_tags_model();
}

function agenda_filter_controller($posts_gevonden = true) {

	$m = agenda_filter_model();

	array_naar_queryvars($m);

	set_query_var('posts_gevonden', $posts_gevonden);

	get_template_part('sja/agenda-filter');

	return $m;

}

