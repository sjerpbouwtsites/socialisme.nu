<?php

$req_uri = $_SERVER['REQUEST_URI'];

if (strpos($req_uri, 'blog/nieuws/')) {
	wp_redirect( str_replace('blog/nieuws/', '', $req_uri), '301');
	exit;
}

if (strpos($req_uri, 'blog/')) {
	wp_redirect( str_replace('blog/', '', $req_uri), '301');
	exit;
}




/******************************
 vrij descriptieve namen en simpele functies, geen documentatie
******************************/

///////////////////////////////////////////////////////////

define('SITE_URI', get_site_url());

define('THEME_DIR', get_template_directory());
define('THEME_URI', get_template_directory_uri());

define('INC_DIR', THEME_DIR . "/inc");
define('INC_URI', THEME_URI . "/inc");

define('VIEW_DIR', INC_DIR . "/view");
define('VIEW_URI', INC_URI . "/view");

define('IMG_DIR', THEME_DIR . "/afb");
define('IMG_URI', THEME_URI . "/afb");

define('JS_DIR', INC_DIR . "/js");
define('JS_URI', INC_URI . "/js");

///////////////////////////////////////////////////////////

$include_funcs = array(
	"acf",
	"klassen",
	'thumbnails',
	'widgets',
	'posttypes',
	'thema-config',
	'gereedschap',
	'edit',
	'controllers',
	'models',
	'strip_scripts',
);

$include_funcs_length = count($include_funcs);
for ($i = 0; $i < $include_funcs_length; $i++) {
	include INC_DIR . "/" . $include_funcs[$i] . ".php";
}


///////////////////////////////////////////////////////////

add_filter( 'get_the_archive_title', function ( $title ) {

    if( is_category() ) {
        $title = single_cat_title( 'Thema: ', false );
    }
    return $title;
});

///////////////////////////////////////////////////////////

add_action( 'admin_menu', 'remove_menu_pages' );
function remove_menu_pages() {

	remove_menu_page( 'edit.php?post_type=feedback' );
	remove_menu_page( 'edit-comments.php' );

}

///////////////////////////////////////////////////////////

//min upload bij afb voor niet-beheerder + meer mime types support

function my_myme_types($mime_types){
    $mime_types['svg'] = 'image/svg+xml'; //Adding svg extension
    $mime_types['psd'] = 'image/vnd.adobe.photoshop'; //Adding photoshop files
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);




if( !current_user_can( 'administrator') ) {
	add_filter('wp_handle_upload_prefilter','tc_handle_upload_prefilter');
	function tc_handle_upload_prefilter($file)
	{

	    $img=getimagesize($file['tmp_name']);

	    //als geen img, dan sizeAr leeg.
	    if (!$img or count($img) < 2) {
	    	return $file;
	    }

	    $minimum = array('width' => '870', 'height' => '300');
	    $width= $img[0];
	    $height =$img[1];

	    if ($width < $minimum['width'] )
	        return array("error"=>"Minimaal 870px breed. Als het echt niet anders kan vraag dan een beheerder de upload te doen");

	    elseif ($height <  $minimum['height'])
	        return array("error"=>"Minimaal 300px hoog. Als het echt niet anders kan vraag dan een beheerder de upload te doen");
	    else
	        return $file;


	}
}

//////////////////////////////////////////////////////////

//alleen opslaan als...
function js_admin_aanpassing() {

	wp_register_script( 'admin-aanpassing', get_template_directory_uri() . '/js/admin-aanpassing.js' );
	wp_enqueue_script( 'admin-aanpassing' );
} //

add_action( 'admin_enqueue_scripts', 'js_admin_aanpassing' );

///////////////////////////////////////////////////////////


function css_admin_aanpassing() {
	wp_register_style( 'admin-css-aanpassing', THEME_URI . '/admin-aanpassing.css');
	wp_enqueue_style( 'admin-css-aanpassing');

} //

add_action('admin_init', 'css_admin_aanpassing' );


