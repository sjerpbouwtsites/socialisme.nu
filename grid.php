<?php

$mediaquery_breekpunten = array(
	'tablet'  => '1200px',
	'mobiel'  => '600px'
);

$breedtes = array(
	'desktop' => '1200px',
	'tablet'  => '900px',
	'mobiel'  => '100%'
);


$raster = array(
	'horizontaal' => array(
		'desktop' => array(
			'top: 60px'
		),
		'tablet' => array(),
		'mobiel'=> array(
			'top: 100px',
			'top: 30px'
		)
	),
	'verticaal' => array(
		'desktop' => array(
			'left: 20px',
			'right: 20px',
			'left: 50%',
			'left: 310px',
			'right: 310px',
		),
		'tablet' => array(
			'left: 20px',
			'right: 20px',
			'left: 50%'
		),
		'mobiel'=> array(

		)
	),
);


function geen_px($a) {
	return str_replace('px', '', $a);
}


$stijl = '';
$rastertel = 1;

echo "<div id='raster-verpakking'><div id='rv-binnen'>";

	foreach ($raster as $richting=>$v) {

		foreach ($v as $formaat=>$v2) {

			foreach ($v2 as $lijn) {
				echo "<div class='raster $richting $formaat' style='$lijn'></div>";
			}

		}

	}

echo "</div></div>";

echo "<button id='toon-raster'>RASTER</button>";
?>

<script>
	document.getElementsByTagName('body')[0].addEventListener('click', function(e){
		if (e.target.id === 'toon-raster') {
			document.getElementById('raster-verpakking').classList.toggle('open');
		}
	});
</script>

<style>


	#toon-raster {
		position: fixed;
		bottom: 0;
		right: 0;
		padding: 10px 15px;
		background-color: black;
		color: white;
		font-weight: bold;
		border: 0;
		border-radius: 0;
	}
		#toon-raster:hover {
			color: darkred;
		}



	#raster-verpakking {
		width: <?=$breedtes['desktop']?>;
		position: absolute;
		top: 0;
		<?php
			$db = geen_px($breedtes['desktop']);
			echo "left: calc(50% - ".($db/2)."px);";
		?>
		height: 1200vh;
		box-sizing: border-box;
		display: none;
	}
		#raster-verpakking.open {
			display: block;
		}

	@media(max-width: <?=$mediaquery_breekpunten['tablet']?>) {
		#raster-verpakking {
			width: <?=$breedtes['tablet']?>;
			<?php
			$tb = geen_px($breedtes['tablet']);
				echo "left: calc(50% - ".($tb/2)."px);";
			?>
		}
	}

	<?php if ($mediaquery_breekpunten['tablet'] !== $breedtes['tablet']) {

		echo "@media(max-width: ".$breedtes['tablet'].") {
			#raster-verpakking {
				width: 100%;
				left: 0;
			}
		}";

	} ?>

	@media(max-width: <?=$mediaquery_breekpunten['mobiel']?>) {
		#raster-verpakking {
			width: <?=$breedtes['mobiel']?>;
			left: 0;

		}
	}

		#rv-binnen {
			position: relative;
			height: 100%;
		}
		.raster {
			position: absolute;
			display: none;
		}
			.raster.horizontaal {
				border-bottom: 1px solid #5fe1e1;;
				width: 100%;
				left: 0;
				margin-top: -0.5px;
			}
			.raster.verticaal {
				border-left: 1px solid #5fe1e1;;
				height: 100%;
				top: 0;
				margin-right: -0.5px;
			}

			.raster.desktop {
				display: block;
			}


			@media (max-width: <?=$mediaquery_breekpunten['tablet']?>) {
				.raster.desktop {
					display: none;
				}
				.raster.tablet {
					display: block;
				}
			}

			@media (max-width: <?=$mediaquery_breekpunten['mobiel']?>) {
				.raster.tablet {
					display: none;
				}
				.raster.mobiel {
					display: block;
				}
			}

</style>
