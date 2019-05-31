<?php

get_header();

set_query_var('klassen_bij_primary', "agenda-pag");
get_template_part('/sja/open-main');

?>
<div class='niet-volle-breedte agenda'>
	<h1>Agenda</h1>

	<?php

	$afm = agenda_filter_controller(have_posts());

	if ( have_posts() ) :

		$agenda = new Agenda(array(
			'aantal' => 10,
			'omgeving' => 'pagina'
		));

		$agenda->print();

		?>

	<?php paginering();

	endif;


	echo "<div class='knoppen-doos'>";

		$archief = array_key_exists('archief', $_GET) || array_key_exists('archief', $_POST);
		$agenda_link = get_post_type_archive_link('agenda');

		$archief_knop = new Knop(array(
			'ikoon'=> ($archief ? "arrow-right-thick"  : "step-backward-2"),
			'class'=> 'in-kleur '.($archief ? ""  : "ikoon-links"),
			'link' => $agenda_link . ($archief ? ""  : "?archief=ja"),
			'tekst'=> $archief ? "normale agenda" : "agenda archief"
		));

		$archief_knop->print();

		//als filters actief knop terug naar begin.
		if ( $afm['filters_actief'] ) {

			$agenda_begin = new Knop(array(
				'ikoon' => 'replay',
				'class'=> 'in-wit',
				'link' => $agenda_link,
				'tekst'=> "Verwijder filters",
			));
			$agenda_begin->print();

		}

	echo "</div>";

	?>
</div>

<?php

get_template_part('/sja/sluit-main');
get_footer();
