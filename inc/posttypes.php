<?php
/***********************************************************************************************
 *  REGISTREER DE VARIA POSTTYPES
 *
 *	VOORBEELD. zet in registreer_posttypes.
 *
 *	$project = new Posttype_voorb('project');
 *
 *	$project->pas_args_aan(array(
 *		'supports' =>
 *			array(
 *				'title',
 *				'editor',
 *				'thumbnail'
 *			),
 *	));
 *
 *	$project->registreer();
 *
 *	$project->maak_taxonomie('cms');
 *
 *	$project->maak_taxonomie('techniek');
 *
 */


function registreer_posttypes(){

	$kranten = new Posttype_voorb('krant', 'kranten');
	$kranten->pas_args_aan(array(
		'has_archive' => true,
		'public' => true,
		'show_in_nav_menus' => true,
		'menu_icon' => 'dashicons-media-document',
	));
	$kranten->registreer();



	$agenda = new Posttype_voorb('agenda', 'agenda');
	$agenda->pas_args_aan(array(
		'has_archive' => true,
		'public' => true,
		'show_in_nav_menus' => true,
		'menu_icon' => 'dashicons-calendar-alt',
	));
	$agenda->registreer();

	$agenda->maak_taxonomie('momentsoort', 'momentsoorten');
	$agenda->maak_taxonomie('plek', 'plekken');
	$agenda->maak_taxonomie('onderwerp', 'onderwerpen');


	$schrijver = new Posttype_voorb('schrijver', 'schrijvers');
	$schrijver->pas_args_aan(array(
		'has_archive' => false,
		'public' => false,
		'show_in_nav_menus' => false,
		'menu_icon' => 'dashicons-universal-access',
		'supports'	=> array('title', 'thumbnail')
	));
	$schrijver->registreer();

	register_taxonomy(
		'rubriek',
		'post',
		array(	'labels'=> array(
			'name' 				=> _x('Rubrieken', 'taxonomy general name'),
			'singular_name' 	=> _x('Rubriek', 'taxonomy singular name'),
			)
		)
	);

}



add_action('init', 'registreer_posttypes');


class Posttype_voorb {

	function __construct($enkelvoud, $meervoud = ''){

		if (empty($meervoud)) {
			$meervoud = $enkelvoud . 'en';
		}

		$this->enkelvoud = $enkelvoud;
		$this->meervoud = $meervoud;

		$this->termen = array(
			'name' 				=> _x(ucfirst($meervoud), 'post type general name'),
			'singular_name' 	=> _x(ucfirst($enkelvoud), 'post type singular name'),
			'add_new' 			=> _x('Voeg een nieuw '.$enkelvoud.' toe', $enkelvoud),
			'add_new_item' 		=> __('Nieuw '.$enkelvoud),
			'edit_item' 		=> __('Pas '.$enkelvoud.' aan'),
			'new_item' 			=> __('Nieuw '.$enkelvoud),
			'view_item' 		=> __('Bekijk '.$enkelvoud),
			'search_items' 		=> __('Zoek tussen '.$meervoud),
			'not_found' 		=>  __('Niets gevonden'),
			'not_found_in_trash' => __('Niet gevonden in de prullebak'),
			'parent_item_colon' => ''
		);

		$this->args = array(
			'labels' 			=> $this->termen,
			'description'		=> 'De ' . $this->meervoud . '.',
			//'public' 			=> true, dit overschrijft dus vele andere waarden.
			'exclude_from_search'=> false,
			'publicly_queryable' => true,
			'show_in_nav_menus'	=> false,
			'show_in_menu'		=> true,
			'menu_position'		=> 10,
			'show_ui' 			=> true,
			'rewrite' 			=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'public'			=> true,
			'supports' => 		array(
					'title',
					'editor',
					'excerpt',
					'thumbnail'
				),
		  );

	}

	function pas_args_aan($args){
		foreach ($args as $k=>$v) {
			$this->args[$k] = $v;
		}
	}

	function registreer(){
		register_post_type($this->enkelvoud, $this->args );
	}

	function maak_taxonomie($tax_enkelvoud = '', $tax_meervoud = ''){

		if (empty($tax_enkelvoud)) {
			return;
		}

		if (empty($tax_meervoud)) {
			$tax_meervoud = $tax_enkelvoud . 'en';
		}

		register_taxonomy(
			$tax_enkelvoud,
			$this->enkelvoud,
			array(	'labels'=> array(
				'name' 				=> _x($tax_meervoud, 'taxonomy general name'),
				'singular_name' 	=> _x($tax_enkelvoud, 'taxonomy singular name'),
				)
			)
		);
	}

}