<?php

$filter_text = "Je kan de agenda doorzoeken met (een combinatie van) onderstaande filters.";


if ( count($_POST) and
	(array_key_exists('momentsoort', $_POST) and $_POST['momentsoort'] !== '') ||
	(array_key_exists('onderwerp', $_POST) and $_POST['onderwerp'] !== '') ||
	(array_key_exists('plek', $_POST) and $_POST['plek'] !== '') ) {


	$filter_t_ar = array();

	if (array_key_exists('momentsoort', $_POST) and $_POST['momentsoort'] !== '') {
		$filter_t_ar[] = $_POST['momentsoort'];
	}

	if (array_key_exists('onderwerp', $_POST) and $_POST['onderwerp'] !== '') {
		$filter_t_ar[] = $_POST['onderwerp'];
	}

	if (array_key_exists('plek', $_POST) and $_POST['plek'] !== '') {
		$filter_t_ar[] = $_POST['plek'];
	}

	if ($posts_gevonden) {
		$filter_text = "Je zocht op ". implode('en ', $filter_t_ar) . ".";
	} else {
		$filter_text = "We konden helaas niets voor je vinden. Probeer een minder specifieke zoekopdracht. Je zocht op ". implode('en ', $filter_t_ar) . ".";
	}



}

?>



<p><?=$filter_text?></p>

<form class='doos' id='agenda-filter' action='<?php echo get_post_type_archive_link('agenda'); ?>' method='POST'>
	<div class='flex'>

		<?php

		if (array_key_exists('archief', $_GET) || array_key_exists('archief', $_POST)) {
			echo "<input type='hidden' name='archief' value='ja'>";
		}

		$teller = 0;

		foreach ($filters_inst as $tax_naam => $opts) {
			$prio = false;
			$teller++;

			echo "<section class='flex'>";
			echo "<h3 id='select-teller-$teller'>".$tax_naam."</h3>";

			if (array_key_exists($tax_naam, $_POST)) {
				$prio = $_POST[$tax_naam];
				$prio_naam = '';
				foreach ($opts as $o) {
					if ($o['slug'] === $prio) {
						$prio_naam = $o['name'] ;
						break;
					}
				}
			}

			echo "<select aria-labelledby='select-teller-$teller' class='agenda-filters ".($prio ? "geklikt" : "")."' name='$tax_naam'>";

			if ($prio) {
				echo "<option value='$prio'>$prio_naam</option>";
			}
				echo "<option value=''>geen keuze</option>";



			foreach ($opts as $o) {
				if ($o['slug'] === $prio) continue;

				$count_print = $filters_actief ? "" : "(".$o['count'].")";

				echo "<option value='".$o['slug']."'>".$o['name']."$count_print</option>";
			}

			echo "</select>";
			echo "</section>";

		}?>

		<input type='submit' value='Verstuur filter'>
	</div>

	<!--WEG IN PRODUCTIE -->
	<input type='hidden' name='pag' value='agenda'>
</form>