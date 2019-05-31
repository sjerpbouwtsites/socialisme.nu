<?php

$aut = get_the_title($schrijver_id);

echo '<div class="artikel-meta blok">
	<span class="art-meta-links">
			'.get_the_post_thumbnail($schrijver_id, 'thumbnail').'
		</span>
		<span class="art-meta-rechts">
			<span class="auteur" itemprop="author">'.$aut.'</span>
			<meta itemprop="datePublished" content="'. (property_exists($post, 'post_published') ? $post->post_published : $post->post_modified).'">
			<meta itemprop="dateModified" content="'.$post->post_modified.'">
			<span class="datum">'.get_the_date().'</span>
		</span>
	</div>';

