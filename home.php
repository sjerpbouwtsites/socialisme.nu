<?php


$pagtekst = '';
if (array_key_exists('paged', $_GET)) {
	$pagtekst = " - pagina ".$_GET['paged'];
}
ob_start();
single_post_title();
$titel = ob_get_clean();

get_header();
set_query_var('klassen_bij_primary', "index");
set_query_var('titel_hoog', "<h1>".$titel.$pagtekst."</h1>");
get_template_part('/sja/open-main');
?>

<div class='niet-volle-breedte'>
<?php
if ( have_posts() ) :

	while ( have_posts() ) : the_post();

		$art = new Article_c(
			array(
				'class' => "in-lijst blok",
				'htype' => 2,
				'exc_lim' => 300
			),
		$post);

		$art->print();

	endwhile;

	paginering();

else :

	echo "geen posts.";

endif; ?>
</div>

<?php
get_template_part('/sja/sluit-main');
get_footer();
