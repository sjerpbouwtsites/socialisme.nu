<?php
/*
// Callback function to insert 'styleselect' into the $buttons array
function my_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

function my_mce_before_init_insert_formats( $init_array ) {

    $style_formats = array(
        // Each array child is a format with it's own settings
        array(
            'title' => 'link CTA',
            'block' => 'span',
            'classes' => 'cta link-cta',
            'wrapper' => true,
        ),
        array(
            'title' => 'scroll CTA',
            'block' => 'span',
            'classes' => 'cta scroll-cta',
            'wrapper' => true,
        )

   );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );

    return $init_array;

}

add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );*/

/*
add_filter('wp_handle_upload_prefilter','tc_handle_upload_prefilter');
function tc_handle_upload_prefilter($file)
{

    $img=getimagesize($file['tmp_name']);
    $minimum = array('width' => '500', 'height' => '280');
    $width= $img[0];
    $height =$img[1];

    if ($width < $minimum['width'] )
        return array("error"=>"He slimpie, graag ff goede afbeeldingen erin zetten anders lijkt t straks nergens op. Minimum breedte is {$minimum['width']}px. Je kwam met $width px");

    elseif ($height <  $minimum['height'])
        return array("error"=>"He slimpie, graag ff goede afbeeldingen erin zetten anders lijkt t straks nergens op. Minimum hoogte is {$minimum['width']}px. Je kwam met $width px");
    else
        return $file;
}*/