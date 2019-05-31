<?php

//doet precies wat je kan vermoeden... gebruiken ism ajax knoppen klasse in HTML, zie relevante JS, zie inc/view/ajax-knop.php

add_action("wp_ajax_ajaxPosts", "ajax_posts");
add_action("wp_ajax_nopriv_ajaxPosts", "ajax_posts");

function std_ajax_query($data){

	$pt = (array_key_exists('post_type', $data) ? $data['post_type'] : "post");
	$stat = (array_key_exists('status', $data) ? $data['status'] : "publish");
	$ppp = (array_key_exists('posts_per_pagina', $data) ? $data['posts_per_pagina'] : "publish");
	$offset = (array_key_exists('offset', $data) ? $data['offset'] : "3");

	return get_posts(array(
	  	'post_type' 		=> $pt,
	  	'post_status' 		=> $stat,
	  	'posts_per_page' 	=> $ppp,
	  	'offset'			=> $offset,
	));
}

function ajax_posts(){

	$posts = std_ajax_query($_GET['ajaxData']);

	include VIEW_DIR . '/posts_std.php';

	die();

}
