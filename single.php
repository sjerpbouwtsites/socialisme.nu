<?php

get_header('sectie');
set_query_var('klassen_bij_primary', "los-bericht zijbalk-laag");
get_template_part('/sja/open-main');

?>

<article class='bericht' itemscope itemtype=http://schema.org/BlogPosting>
	<h1 itemprop="headline"><?php the_title();?></h1>
	<span class='meta-niet-tonen' itemprop='publisher' itemscope itemtype="http://schema.org/Organization">
		<meta itemprop='name' content='Internationale Socialisten'>
	</span>
	<meta itemprop='mainEntityOfPage' content='<?php the_permalink(); ?>'>
	<div class='uitgelichte-afbeelding-buiten'>

		<?php
			get_template_part('sja/post-afb-met-desc');
		?>

	</div>
	<div class='niet-volle-breedte'>

		<?php

		while ( have_posts() ) : the_post();

			single_content($post);

			print_cats();

			single_footer($post); ?>

		<?php endwhile; // End of the loop.
		?>
	</div>
</article>

<?php
get_template_part('/sja/sluit-main');
get_footer();
