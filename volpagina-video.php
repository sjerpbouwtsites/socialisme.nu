<?php

/*
template name: volpagina video
*/


get_header(); set_query_var('klassen_bij_primary', "page los-bericht");


if ($vid = get_field('youtube_video')) : ?>


<div class="vid-verp autoplay">
	<div class="video-background">
		<div class="video-foreground">

			<p><iframe src="https://www.youtube.com/embed/<?=$vid?>?feature=oembed&amp;autoplay=1&amp;loop=1&amp&amp;rel=0" allowfullscreen="" width="200" height="113" frameborder="0"></iframe></p>

		</div>
	</div>
	<div class='vid-blok'>
		<?php
			$sluit_je_aan = new Knop();
			$sluit_je_aan->class = 'scroll ';
			$sluit_je_aan->tekst = get_field('scroll_knop_tekst');
			$sluit_je_aan->link = "#h1";
			$sluit_je_aan->print();
		?>
	</div>
</div>
<?php endif;


get_template_part('/sja/open-main'); ?>

<article class='bericht'>
	<h1><?php the_title();?></h1>
	<?php if (!$vid) : ?>
	<div class='uitgelichte-afbeelding-buiten'>

		<?php
			get_template_part('sja/post-afb-met-desc');
		?>


	</div>
	<?php endif;?>
	<div class='niet-volle-breedte'>

		<?php

		while ( have_posts() ) : the_post();

			single_content($post);

			if ( is_active_sidebar( 'onder_lidworden' ) )dynamic_sidebar('onder_lidworden');

			single_footer($post);

		endwhile; // End of the loop.
		?>
	</div>
</article>




<?php get_template_part('/sja/sluit-main');
get_footer();
