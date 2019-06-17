<section id='conv-moment' class='blok conversie'>
		<h3>Kom naar:<br><em><?= $cvm_titel ?></em></h3>
		<p><?= $cvm_tekst ?></p>
		<footer>
			<div class='knoppen-doos'>
			<?php
   if (count($cvm_knoppen)):
     echo "<h4>Meer info</h4>";

     foreach ($cvm_knoppen as $knop_data):
       $k = new Knop($knop_data);
       $k->print();
     endforeach;
   endif;

   echo "<hr>";

   $knop_cal = new Knop(array(
     'class' => 'in-wit ikoon-links schakel uitgesproken',
     'ikoon' => 'calendar-plus',
     'link' => '#',
     'tekst' => 'Voeg aan je agenda toe',
     'schakel' => "data-toon='#kalender-data'"
   ));

   $knop_cal->print();

   //als knoppen dan alle knoppen

   echo "</div>"; //knoppen doos;

   echo "<div
					id='kalender-data'
					data-title='$cvm_titel'
					data-start='$cvm_datum'
					data-duration='$cvm_duur'
					data-address='$cvm_adres'
					data-description='$cvm_tekst'
				>
				<div id='kalender-print'></div>
					<p>Let op: winstgerichte bedrijven kammen je agenda door. Je data is onveilig.</p>
				</div>";
   ?>
		</footer>
	</section>