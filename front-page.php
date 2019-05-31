<?php
$vp_posts = voorpagina_posts();


get_header();
set_query_var('klassen_bij_primary', "voorpagina zijbalk-laag");
get_template_part('/sja/open-main');


/*
echo "<pre>";
var_dump($theorie_onderwerpen);
echo "</pre>";

die();*/


?>
	<article class='bericht eerste-bericht' itemscope itemtype='http://schema.org/BlogPosting'>

		<span class='meta-niet-tonen'>
				<?php
					$toon_auteur = art_meta_model($vp_posts[0]);
					if ($toon_auteur) {
						$auteur = get_userdata( $vp_posts[0]->post_author );
						echo "<meta itemprop='author' content='".$auteur->data->display_name."'>";
					} else {
						echo "<meta itemprop='author' content='Redactie socialisme.nu'>";
					}
				?>

			<meta itemprop='datePublished' content='<?=$vp_posts[0]->post_date?>'>
			<meta itemprop='dateModified' content='<?=$vp_posts[0]->post_modified?>'>
			<span itemprop='publisher' itemscope itemtype="http://schema.org/Organization">
				<meta itemprop='name' content='Internationale Socialisten'>
			</span>
		</span>

		<a itemprop='mainEntityOfPage' class='art-link' href='<?php the_permalink($vp_posts[0]->ID);?>'>

			<?php

				$tpt = get_the_post_thumbnail($vp_posts[0], 'bovenaan_art');
				if ($tpt && $tpt !== '') {
					echo voeg_attr_in($tpt, "itemprop='image'");
				}


			?>

			<h2 itemprop='headline'><?=$vp_posts[0]->post_title?></h2>
			<p><?php echo maak_excerpt($vp_posts[0]); ?> <span class='lees-meer'>Lees meer. </span></p>

		</a>
	</article>

	<div class='volgende-drie-artikelen niet-volle-breedte'>

	<?php

		for ($i = 1; $i < 4; $i++) {
			$art = new Article_c(
				array(
					'class' => "in-lijst blok",
					'exc_lim' => 500
				),
			$vp_posts[$i]);

			$art->print();
		}

	?>
	</div>

	<?php if ( is_active_sidebar( 'voorpagina_midden' ) )dynamic_sidebar('voorpagina_midden'); ?>

	<section class='afb-met-titel'>
		<h2 class='streep-eronder'>Eerder nieuws</h2>
		<div class='flex'>
		<?php

			for ($i = 4; $i < 10; $i++) {
				$art = new Article_c(
					array(
						'class' => "in-lijst blok",
						'exc_lim' => 200,
						//'gebruik_korte_titel' => true
					),
				$vp_posts[$i]);

				$art->print();
			}

		?>
		</div>
		<footer>
			<?php
				$alle_art = new Knop(array(
					'link' => get_permalink( get_option( 'page_for_posts' ) ),
					'tekst'=> 'Alle artikelen'
				));
				$alle_art->print();
			?>
		</footer>
	</section>

<?php
get_template_part('/sja/sluit-main');
get_footer();
