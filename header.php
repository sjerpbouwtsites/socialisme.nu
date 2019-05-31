<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<style>
	.screen-reader-text {
	  border: 0;
	  clip: rect(1px, 1px, 1px, 1px);
	  clip-path: inset(50%);
	  height: 1px;
	  margin: -1px;
	  overflow: hidden;
	  padding: 0;
	  position: absolute;
	  width: 1px;
	  word-wrap: normal !important;
	}
	.stek-kop-links .custom-logo {
			width: 279px;
			display: block;
			transition: ease-out 0.2s;
			opacity: 1;
	}
	.stek-kop-links .custom-logo.nog-geen-data {
		display: none;
		opacity: 0;
	}

	.stek-kop-links .custom-logo.ga-maar-laden {
		opacity: 0;
	}


</style>
<link href="https://fonts.googleapis.com/css?family=Asap:400,400i,500,700|Merriweather" rel="stylesheet">
<link href="<?=THEME_URI?>/lettertypen/materialdesignicons.css" media="all" rel="stylesheet" type="text/css" />
<?php wp_head(); ?>
<link rel="shortcut icon" type="image/png" href="<?=IMG_URI?>/ster-klein.png"/>
<link rel="stylesheet" href="<?=THEME_URI?>/style.min.css">
<meta name="format-detection" content="telephone=no"/>
<!-- <script src='<?=THEME_URI?>/js/all.js'></script> -->

</head>

<body <?php body_class('schakel'); ?> data-anti='.schakel'>
<header id='stek-kop' class='klaar' role='banner'>
	<div class='rel'>
		<div class='verpakking'>
			<div class='stek-kop-links'>

				<a
					id='<?=is_front_page() ? '' : 'print-het-logo-in-mij'?>'
					href="<?=site_url()?>"
					class="custom-logo-link"
					rel="home"
					itemprop="url">
					<?=is_front_page() ? '<h1 id="print-het-logo-in-mij"></h1>' : ''?>
				</a>
				<a class='screen-reader-text skip-link' href='#main'>Ga naar inhoud</a>
			</div><!--koplinks-->

			<div class='stek-kop-rechts flex'>

				<div id='zoekveld'>

					<form role="search" method="get" class="search-form" action="<?=SITE_URI?>">
						<label>
							<span class="screen-reader-text">Zoeken naar:</span>
							<input class="search-field" placeholder="Zoeken …" value="" name="s" type="search">
						</label>
						<label for='kop-zoekveld'>
							<input id='kop-zoekveld' class="search-submit" value="Zoeken" type="submit">
							<i class='mdi mdi-arrow-right-thick'></i>
						</label>
					</form>

				</div>

				<div class='stek-kop-knoppen'>
					<button
						id='zoekveldschakel'
						href='#'
						class='schakel'
						data-toon='#zoekveld'
						data-anti='#toegankelijkheidsschakel, #menuschakel'
						aria-label='Zoeken'
						aria-haspopup="true"
						aria-expanded='false'
						data-f='focusZoekveld'>
						<i class="mdi mdi-magnify"></i>
						<i class="mdi mdi-window-close"></i>

					</button>
					<button
						id='menuschakel'
						href='#'
						aria-label='open menu'
						class='schakel'
						aria-haspopup="true"
						data-toon='#stek-nav'
						aria-expanded='false'
						data-anti='#toegankelijkheidsschakel, #zoekveldschakel'>
						<i class="mdi mdi-menu"></i>
						<i class="mdi mdi-window-close"></i>
						<span class='screen-reader-text'>Open menu</span>
					</button>
				</div>

			</div>

		</div><!--verpakking-->

		<nav id='stek-nav' class='stek-nav' role='navigation'>
			<div class='verpakking'>

				<?php

					wp_nav_menu(array('theme_location'=>"kop"));

					echo "<p class='menu-stuk hieronder-lijn'><a
						id='toegankelijkheidsschakel'
						href='#'
						class='schakel'
						data-toon='#toegankelijkheid'
						aria-controls='toegankelijkheidslabel'
						aria-haspopup='true'
						aria-expanded='false'
						role='button'
						aria-label='visuele toegankelijkheidsopties zoals contrast, lettergrootte en lettertype'
						tabindex='-1'
						data-anti='#zoekveldschakel, #menuschakel'>Visuele toegankelijkheid</i>
					</a></p>";

					echo "<p class='menu-stuk'><a href='#' class='schakel' data-doorschakel='#menuschakel'>Sluiten</a></p>";

				?>

			</div>
		</nav>

		<nav id='toegankelijkheid' class='stek-nav' aria-label='visuele toegankelijkheidsopties'>
			<div class='verpakking'>
				<div class='toegankelijkheid-flex'>

					<div class='toegankelijkheid-intro'>
						<p
							class='toegankelijkheid-intro-tekst'
							id='toegankelijkheidslabel'
							aria-live='polite'
							role='alert'
							data-tekst='Hier kan je grotere letters, meer contrast of dyslexie-vriendelijke letters kiezen.'
						></p>
					</div>

					<ul class='toegankelijkheid-lijst'>

						<li class='hieronder-lijn'>
							<fieldset>
								<legend><strong>Contrast</strong></legend>
								<ul>
									<li>
										<button class='contrast meer' >Veel <span class='screen-reader-text'>contrast</span><i class='mdi mdi-contrast-circle'></i></button>
									</li>
									<li>
										<button class='contrast herstel' >Normaal <span class='screen-reader-text'>contrast</span> <i class='mdi mdi-undo-variant'></i></button>
									</li>
								</ul>
							</fieldset>
						</li>

						<li class='hieronder-lijn'>
							<fieldset>
								<legend><strong>Dyslexie</strong></legend>
								<ul>
									<li>
										<button class='dyslexie meer' >Prettige letter <i class='mdi mdi-pen'></i></button>
									</li>
									<li>
										<button class='dyslexie herstel' >Gewone letter <i class='mdi mdi-undo-variant'></i></button>
									</li>
								</ul>
							</fieldset>
						</li>

						<li class='hieronder-lijn'>
							<fieldset>
								<legend><strong>Grootte</strong></legend>
								<ul>
									<li>
										<button class='grootte meer' >groter <i class='mdi mdi-plus'></i></button>
									</li>
									<li>
										<button class='grootte minder' >kleiner <i class='mdi mdi-minus'></i></button>
									</li>
								</ul>
							</fieldset>
						</li>

						<li>
							<fieldset>
								<li><button class='schakel' data-doorschakel='#toegankelijkheidsschakel' >Sluiten </buttom></li>
							</fieldset>
						</li>

					</ul>
				</div>
			</div>
		</nav>
	</div>
</header>


