<?php
function pre_dump($a){
	echo "<pre>";
	var_dump($a);
	echo "</pre>";
}

//DYSFUNCTIONEEL @TODO
function lege_p_filter( $html ) {
    do {
        $tmp = $html;
        $html = preg_replace('/<p[^>]*>\s*<\\/p[^>]*>/', '', $html );
    } while ( $html !== $tmp );

    return $html;
}

function maak_excerpt($post, $lim = 300){

	if (property_exists($post, 'post_excerpt') and $post->post_excerpt !== "") {
		return beperk_woordental($lim, $post->post_excerpt);
	} else if (property_exists($post, 'post_content')) {
		return strip_tags(beperk_woordental($lim, $post->post_content));
	} else if (property_exists($post, 'description')) {
		return strip_tags(beperk_woordental($lim, $post->description));
	} else {
		return '';
	}
}

function beperk_woordental($lim = 300, $tekst = ''){

	$charlength = $lim;
	$r = "";

	if ( mb_strlen( $tekst ) > $charlength ) {
		$subex = mb_substr( $tekst, 0, $charlength - 3 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$r.= mb_substr( $subex, 0, $excut );
		} else {
			$r.= $subex;
		}
		$r = rtrim($r);
		$r.= '...';

		return $r;
	} else {
		return $tekst;
	}
}

function appendChildBefore($orig, $child) {
	//werk alleen bij HTML één niveau diep.
    $expl = explode('>', $orig);
    $tag_naam = substr($expl[0], 1);
	return $expl[0] . ">$child</$tag_naam>";
}

function array_naar_queryvars($ar = array()) {
	foreach ($ar as $naam => $waarde) {
		set_query_var($naam, $waarde);
	}
}

function pak_template_naam() {
	$n = get_page_template();
	return str_replace('.php', '', str_replace(THEME_DIR . "/", '', $n));
}

function underscore_naar_spatie_met_hoofdletter($str) {
	$r = str_replace('_', " ", $str);
	return ucfirst($r);
}

function cp_bestaat_niet_leeg ($eigenschap, $klas) {
	if (!property_exists($klas, $eigenschap)) {
		return false;
	} else if (empty($klas->$eigenschap)) {
		return false;
	} else {
		return true;
	}
}

function apostrophe_weg ($a) {
	$r = str_replace("'", '&apos;', $a);
	$r = str_replace('"', '&apos;', $r);
	return $r;
}

function voeg_attr_in ($orig='', $invoeging='') {

	$e = explode(' ', $orig);
	$e[0] = $e[0] . " " . $invoeging;
	return implode(' ', $e);
}

function mooie_cpt_url($url){
	$e = explode('?', $url);
	$e2 = explode('=', $e[1]);
	$post_type = $e2[0];

	return str_replace("?$post_type=", "$post_type/", $url);
}

function haal_img_uit_tekst($tekst = '') {

	$capt_eind_ex = explode('[/caption]', $tekst);

	//is er een caption?
	if (count($capt_eind_ex) > 1) {
		$capt_start_ex = explode(']', $capt_eind_ex[0]);
		$img = $capt_start_ex[1];
	} else {

		$img_e = explode('<img', $tekst);

		if (count($img_e) < 2) {
			//er is geen afbeelding.
			return array(false, false, false);
		}
		$img_eind_e = explode('/>', $img_e[1]);
		$img = "<img " .$img_eind_e[0] . "/>";

	}

	$doc = new DOMDocument();
	$doc->loadHTML($img);
	$xpath = new DOMXPath($doc);
	$img_src = $xpath->evaluate("string(//img/@src)");
	$img_alt = $xpath->evaluate("string(//img/@alt)");
	$img_title = $xpath->evaluate("string(//img/@title)");
	$img_width = $xpath->evaluate("string(//img/@width)");
	$img_height = $xpath->evaluate("string(//img/@height)");

	//nu nog functionerende afbeelding er uit halen.

	//als het een titel attribuut heeft met daarin \d\d\d-\d\d\d dan is het geupload als reeds gecropte afbeelding.
	$is_crop = preg_match('/\d\d\dx\d\d\d/', $img_title);
	$src_res = '';

	if (!$is_crop) {
		$img_src_e = explode('-', $img_src);

		$img_src_e_c = count($img_src_e);

		$src_laatste = $img_src_e[$img_src_e_c - 1];

		$src_laatste2 = preg_replace('/\d\d\dx\d\d\d/i', '', $src_laatste);

		$img_src_e[$img_src_e_c - 1] = $src_laatste2;

		$src_impl = implode('-', $img_src_e);

		$src_impl = str_replace('-.jpg', '.jpg', $src_impl);
		$src_impl = str_replace('-.jpeg', '.jpeg', $src_impl);
		$src_impl = str_replace('-.png', '.png', $src_impl);

		$src_res = $src_impl;
	} else {
		$src_res = $img_src;
	}

	if ($src_res && $src_res !== '') {
		$img = "<img src='$src_res' alt='$img_alt' title='$img_title' width='$img_width' height='$img_height' />";
	} else {
		$img = false;
	}

	$console = array(
		'title'		=> $img_title,
	);

	return array($img, $src_res, $console);
}