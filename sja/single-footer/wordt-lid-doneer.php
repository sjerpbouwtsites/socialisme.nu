	<section id='<?=$wld_id?>' class='blok conversie'>

		<h3><?php the_field($wld_alt_slug.'_titel', 'option'); ?></h3>
		<?php echo wpautop(get_field($wld_alt_slug.'_tekst', 'option')); ?>

		<footer>
		<?php

			$knop = new Knop(array(
				'class' => 'knop in-wit',
				'link' => get_field($wld_slug.'_pagina', 'option'),
				'tekst'=> get_field($wld_alt_slug.'_knop_tekst', 'option')
			));

			$knop->print();

		?>
		</footer>
	</section>