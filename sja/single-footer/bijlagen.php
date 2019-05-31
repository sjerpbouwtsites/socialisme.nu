<section class='bijlages blok'>
	<h3>Bij dit artikel</h2>
	<div class='knoppen-doos'>
	<?php
		foreach ($bijlagen as $b) :

			$b_knop = new Knop(array(
				'class'	=> 'in-wit ikoon-links',
				'ikoon' => 'cloud-download',
				'link' 	=> $b['bijlage'],
				'tekst'	=> $b['bijlage_naam'],
				'extern'=> true
			));
			$b_knop->print();

		endforeach;

	?>
	</div>
</section>