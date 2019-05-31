<?php
//

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page();

}


function my_acf_google_map_api( $api ){

	$api['key'] = 'AIzaSyBDW__wSO7mbHOr5VkRoqNR01dXY2exje0';

	return $api;

}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');