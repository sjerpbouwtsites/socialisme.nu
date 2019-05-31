<?php

get_header();
set_query_var('klassen_bij_primary', "error-404 not-found");
get_template_part('/sja/open-main'); ?>

	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Oeps!', 'sjerpbouwtsites' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<p><?php esc_html_e( 'Niets hier! Probeer eens onderstaand zoekformulier.', 'sjerpbouwtsites' ); ?></p>

		<?php
			get_search_form();

		?>

	</div><!-- .page-content -->

<?php
get_template_part('/sja/sluit-main');
get_footer();
