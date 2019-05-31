<?php
$thema_ondersteuning = array(
	'post-thumbnails',
	'automatic-feed-links',
	'title-tag',
	'html5' => array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', ),
	'custom-logo' => array(
		   'height'      => 100,
		   'width'       => 465,
		   'flex-width' => true,
		)
);

if ( ! function_exists( 'thema_ondersteuning' ) ) :
function zet_thema_ondersteuning(){
	global $thema_ondersteuning;
	if (count($thema_ondersteuning) > 0) {
		foreach ($thema_ondersteuning as $s=>$w) {
			if (is_array($w)) {
				add_theme_support($s, $w);
			} else {
				add_theme_support($w);
			}
		}
	}
}
endif;

if ( ! function_exists( 'sjerpbouwtsites_setup' ) ) :
	function sjerpbouwtsites_setup() {
		//load_theme_textdomain( 'sjerpbouwtsites', get_template_directory() . '/languages' );
		zet_thema_ondersteuning();
		register_nav_menus( array('kop' => esc_html__( 'kop', 'sjerpbouwtsites' ),) );
		register_nav_menus( array('voet' => esc_html__( 'voet', 'sjerpbouwtsites' ),) );
	}

endif;

add_action( 'after_setup_theme', 'sjerpbouwtsites_setup' );

if ( ! isset( $content_width ) ) { 	$content_width = 830; }