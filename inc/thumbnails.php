<?php

//was eerst iets heel anders, laten staan ivm mogelijk nog gebruiken.

/**********************************************************************************************
 *      photon hack.
 *      photon weigert te croppen maar gaat alleen schalen. Aldus.
 *
 */





/***********************************************************************************************
 *    thumbnail formaat instellingen
 *
 */

function mk_tmb_frm($naam, $breedte, $hoogte, $crop){
    return array(
        'naam'             => $naam,
        'breedte'          => $breedte,
        'hoogte'           => $hoogte,
        'crop'             => $crop,
    );
}

//thumbnailformaten normaal alleen opvraagbaar via query. Dit maakt ze beschikbaar.
//naam //breedte //hoogte //crop
$thumbnail_formaten = array(
    'bovenaan_art'              => mk_tmb_frm( 'bovenaan_art', 870, 300, true),
    'art_in_lijst'              => mk_tmb_frm( 'art_in_lijst', 560, 300, true),
    'krant'                     => mk_tmb_frm( 'krant', 200, 273, true),
    'gallery'                   => mk_tmb_frm( 'gallery', 445, 245, true ),           //NODIG VOOR GALLERY.
);

function thumbnail_init() {

    global $thumbnail_formaten;

    foreach ($thumbnail_formaten as $tf) {

        add_image_size($tf['naam'], $tf['breedte'], $tf['hoogte'], $tf['crop']);
    }

};


/***********************************************************************************************
 *    verbouwd src naar src van formaat.
 *
 */


function thumb_in_formaat($orig_src, $formaten) {

    if (!is_array($formaten)) { //ingevoerd als string ipv als formaten array?

        global $thumbnail_formaten;
        $formaten = $thumbnail_formaten[$formaten];

    }

    if (array_key_exists('breedte', $formaten) ) {
        //nieuwe wijze
        return rtrim($orig_src, '.jpg') . '-'.$formaten['breedte'].'x'.$formaten['hoogte'].'.jpg';
    } else {
        //legacy
        return rtrim($orig_src, '.jpg') . '-'.$formaten['width'].'x'.$formaten['height'].'.jpg';
    }

}




/***********************************************************************************************
 *    slak maken.
 *
 */

function acc_to_slug($str){
    return strtolower(preg_replace('/[^A-Za-z0-9]/', "", $str));
}

add_action( 'after_setup_theme', 'thumbnail_init' );

/***********************************************************************************************
 *    paginatype en paginainstellingen en schermbreedte afhankelijke featured images.
 *
 */


function print_thumb($args = array()){

    global $thumbnail_formaten;
    $opm = '';

    $hoogte = array_key_exists('hoogte', $args) ? $args['hoogte'] : "vol";
    $hoogte = $hoogte === "vol" || $hoogte === "half" ? $hoogte : "vol";
    $alt = array_key_exists('alt', $args) ? $args['alt'] : get_the_title();

    $opm .= $hoogte;

    if (!array_key_exists('afbs', $args)) {

        if (is_singular() || is_search() || is_front_page()) {

            if (has_post_thumbnail()) {
                $post_id = get_the_id();
            } else {
                //terugvallen op voorpagina.
                $post_id = get_option('page_on_front');
                $opm .= ' geen-afb-terugval';
            }

            $srcs = pak_srcs($post_id, $hoogte);
            $opm .= ' enkele-pagina';

        } else { //dus is cat

            $hui_cat = get_queried_object();
            $opm .= ' cat-pag';

            $args = array(
                'post_type' => 'attachment',
                'post_status' => 'inherit',
                'category_name' => 'bovenaan-'.$hui_cat->slug,
            );

            $thumb_query = new WP_Query( $args );

            if($thumb_query->post_count > 0){

                $df = $thumbnail_formaten['header-desktop-'.$hoogte];
                $tf = $thumbnail_formaten['header-tablet-'.$hoogte];
                $mf = $thumbnail_formaten['header-mobiel'];

                $srcs = array(
                    'desktop'       => thumb_in_formaat($thumb_query->post->guid, $df),
                    'tablet'        => thumb_in_formaat($thumb_query->post->guid, $tf),
                    'mobiel'        => thumb_in_formaat($thumb_query->post->guid, $mf),
                );

            } else {

                //terugvallen op voorpagina
                $post_id = get_option('page_on_front');
                $srcs = pak_srcs($post_id, $hoogte);

                $opm .= ' cat-terugval';

            }

            wp_reset_postdata();

        } //eind als cat

        //als de afbeelding is geupload kleiner dan de desktopvariant staat die 'vanille' er in.
        $gesloopt_pad =  str_replace(THEME_URI . '/uploads/', '', $srcs['desktop']);
        $upload_dir = wp_upload_dir();

        $controleer_bestand = $upload_dir['basedir'] . '/' . $gesloopt_pad;

        if( file_exists($controleer_bestand)) {
            //niets aan de hand
        } else {

          //dan weer desktop formaat eruit slopen. al bekend?
          $df = $thumbnail_formaten['header-desktop-vol'];
          $srcs['desktop'] = str_replace('-'.$df['breedte'] . 'x' . $df['hoogte'], '', $srcs['desktop']);

        }

        //opmerkingen srcs meenemen
        $opm .= $srcs['opm'];

    } else { // heeft wel afbs gedefineerd

        $srcs = array(
            'desktop'       => $args['afbs']['header-desktop-'.$hoogte],
            'tablet'        => $args['afbs']['header-tablet-'.$hoogte],
            'mobiel'        => $args['afbs']['header-mobiel'],
        );

        $opm .= " van-params";

    }

    //word hardhandig wat overschreven?
    if (array_key_exists("overschrijf", $args)) {

        foreach ($args['overschrijf'] as $k=>$v) {
            $srcs[$k] = $v;
        }

    }


    $mob_vanaf = $thumbnail_formaten['header-mobiel']['breedte'];
    $tab_vanaf = $thumbnail_formaten['header-tablet-vol']['breedte'];


    echo "<picture class='".trim($opm)."'>
        <source srcset='{$srcs['mobiel']}' media='(max-width: {$mob_vanaf}px)'>
        <source srcset='{$srcs['tablet']}' media='(max-width: {$tab_vanaf}px)'>
        <img class='attachment-header object-fit size-header wp-post-image' srcset='{$srcs['desktop']}' alt='$alt'>
    </picture>";

}

/***********************************************************************************************
 *    hulpfunctie voor print_thumb.
 *
 */

function pak_srcs($id, $hoogte = "vol"){

    global $thumbnail_formaten;
    $opm = '';

    $tb_str = 'header-tablet-'.$hoogte;
    $mb_str = 'header-mobiel';

    $tf = $thumbnail_formaten[$tb_str];
    $mf = $thumbnail_formaten[$mb_str];

    if (get_field('breedte_specifieke_afbeeldingen', $id)) {

        $tablet_afb = get_field('tablet_afbeelding', $id);
        $mobiel_afb = get_field('mobiel_afbeelding', $id);

        if (!empty($tablet_afb)) {
            $tablet_ar = array(thumb_in_formaat($tablet_afb, $tf));
        } else {
            $tablet_ar = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $tb_str );
            $opm .= ' geen-tablet';
        }

        if (!empty($mobiel_afb)) {
            $mobiel_ar = array(thumb_in_formaat($mobiel_afb, $mf));
        } else {
            $mobiel_ar = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $mb_str );
            $opm .= ' geen-mobiel';
        }

    } else {

        $tablet_ar = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $tb_str );
        $mobiel_ar = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $mb_str );
    }

    $desktop_ar = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'header-desktop-'.$hoogte );

    return array(
        'desktop'       => $desktop_ar[0],
        'tablet'        => $tablet_ar[0],
        'mobiel'        => $mobiel_ar[0],
        'opm'           => $opm
    );
}