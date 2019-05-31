<?php
echo '<div class="artikel-meta geen-auteur">
	<span class="datum">'.get_the_date().'</span>
	<meta itemprop="datePublished" content="'. (property_exists($post, 'post_published') ? $post->post_published : $post->post_modified).'">
	<meta itemprop="dateModified" content="'.$post->post_modified.'">
	<meta itemprop="author" content="Redactie socialisme.nu">
</div>';