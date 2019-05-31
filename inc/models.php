<?php

//nee het niet is niet echt mvc. Weet ik. thx. stfu. bai.
function soc_nu_logo() {
	ob_start();
	the_custom_logo();
	$logo = ob_get_clean();
	$logo = str_replace('</a>', '', $logo);

    $is_front = is_front_page();

    if ($is_front) $logo = "<h1>" . $logo;
	//$logo .= "<img src='".IMG_URI."/IS vuist.png' alt='socialisme.nu' width='145' height='204' /></a>";
    if ($is_front) $logo .= "</h1>";
    $logo .= "</a>";
	return $logo;
}

function voorpagina_posts(){

    // dit was eerst een functie die filterde op niet sticky
    // gebaseerde op foutieve interpretatie ontwerp
    // voorpagina posts zijn alle posts, ook sticky.

	$flinke_voorraad = get_posts(array(
		'posts_per_page' => 10,
        'post_status'    => 'publish',
        'post_type'      => 'post'
	));

    return $flinke_voorraad;

 //    $sticky_false = array_map(function($post){
 //        if (is_sticky($post->ID)) {
 //            return false;
 //        }
 //        return $post;
 //    }, $flinke_voorraad);

	// $zonder_sticky = array_filter($sticky_false, function($post){
 //        return !!$post;
 //    });

 //    return $zonder_sticky;
}

function paginering_model() {

    if( is_singular() && !is_search()) return false;
    global $wp_query;
    if( $wp_query->max_num_pages <= 1 ) return false;

    $m = array(
        'pagi_paged' => get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1,
        'pagi_max'  => intval( $wp_query->max_num_pages ),
        'pagi_links' => array()
    );

    /** Add current page to the array */
    if ( $m['pagi_paged'] >= 1 )
        $m['pagi_links'][] = $m['pagi_paged'];

    /** Add the pages around the current page to the array */
    if ( $m['pagi_paged'] >= 3 ) {
        $m['pagi_links'][] = $m['pagi_paged'] - 1;
        $m['pagi_links'][] = $m['pagi_paged'] - 2;
    }

    if ( ( $m['pagi_paged'] + 2 ) <= $m['pagi_max'] ) {
        $m['pagi_links'][] = $m['pagi_paged'] + 2;
        $m['pagi_links'][] = $m['pagi_paged'] + 1;
    }

    sort( $m['pagi_links'] );


    $m['pagi_prev_link'] = get_previous_posts_link();

    if ($m['pagi_prev_link']) $m['pagi_prev_link_res'] = appendChildBefore($m['pagi_prev_link'], "<span class='screen-reader-text'>Vorige pagina</span><i class='mdi mdi-arrow-left-thick'></i>");

    $m['pagi_volgende_link'] = get_next_posts_link();

    if ($m['pagi_volgende_link']) $m['pagi_volgende_link_res'] = appendChildBefore($m['pagi_volgende_link'], "<span class='screen-reader-text'>Volgende pagina</span><i class='mdi mdi-arrow-right-thick'></i>");

    return $m;
}

function art_meta_model($post) {

    //bepaalt of met auteur of niet getoond wordt.
    //als return = true, toon met auteur.

    $auteur = new WP_User( $post->post_author );
    $is_red = $auteur->roles[0] === 'editor' || $auteur->ID == 14; //mark is 14
    $zelf_keuze_aut = get_field('auteur_vernoemen_cond', $post->ID);
    $zelf_keuze_aut_res = get_field('auteur_vernoemd', $post->ID);
    $red_simpel = $is_red && !$zelf_keuze_aut;
    $overschrijving_nagatief = $zelf_keuze_aut && !$zelf_keuze_aut_res;

    return !($red_simpel || $overschrijving_nagatief);
}

function conversie_moment_model($ID, $post) {

    $titel = 'titel';
    $tekst = 'tekst';
    $datum = 'June 15, 2013 19:00';
    $duur = '120'; //minuten
    $adres = 'Dam, Amsterdam';

    $knoppen = array();

    if ($post->post_type !== 'agenda') {
        $agendastuk = get_field('agenda_link', $ID);
    } else {
        $agendastuk = $post;
    }


    $agenda_link = get_the_permalink($agendastuk->ID);
    $titel = $agendastuk->post_title;
    $tekst = apostrophe_weg(maak_excerpt($agendastuk, 300));

    $datumH = get_field('datum', $agendastuk->ID);
    if ($datumH && $datumH !== '') $datum = $datumH;

    $duurH = get_field('duur', $agendastuk->ID);
    if ($duurH && $duurH !== '') {
        $duur = $duurH * 60; //was in uren
    }

    $adres = get_field('adres_tekst', $agendastuk->ID);

    if ($fb_event = get_field('facebook_evenement', $agendastuk->ID)) {
        $knoppen[] = array(
            'tekst' => "Facebook event",
            'link' => $fb_event,
            'class' => 'in-wit ikoon-links',
            'ikoon' => "facebook-box",
            'extern' => true
        );
    }

    $naam_organisator = get_field('naam_organisator', $agendastuk->ID);
    $naam_organisator = $naam_organisator ? $naam_organisator : '';

    if ($tel_nr = get_field('telefoonnummer', $agendastuk->ID)) {
        $knoppen[] = array(
            'tekst' => "Bel ".$naam_organisator . ' ('.$tel_nr.')',
            'link' => 'tel:'.$tel_nr,
            'class' => 'in-wit ikoon-links',
            'ikoon' => "cellphone",
        );
    }

    if ($mailadres = get_field('email_organisator', $agendastuk->ID)) {
        $knoppen[] = array(
            'tekst' => "Mail naar ".$naam_organisator . ' ('.$mailadres.')',
            'link' => 'mailto:'.$mailadres,
            'class' => 'in-wit ikoon-links',
            'ikoon' => "email-outline",
        );
    }

    //Als niet op agendapagina, ook link daarnaar maken.
    if ($post->post_type !== 'agenda' && $agenda_link && $agenda_link !== '') {
        $knoppen[] = array(
            'tekst' => "Bekijk in de agenda",
            'link' => $agenda_link,
            'class' => 'in-wit ikoon-links',
        );
    }


    return array(
        'cvm_titel' => $titel,
        'cvm_tekst' => $tekst,
        'cvm_knoppen' => $knoppen,
        'cvm_datum' => $datum,
        'cvm_duur' => $duur,
        'cvm_adres' => $adres,
    );
}

function cats_tags_model() {

    /**********************
    / VERSIE 1.1
    /////////////////////*/

    global $post;

    $r = '';

    $cats = get_the_category();

    $cc = 0;
    foreach ($cats as $c) {
        if ($c->cat_ID !== 1) $cc++;
    }

    $tags = get_the_tags();
    $ct = count($tags);

    $rubrieken = get_the_terms( $post->ID, 'rubriek' );
    $cr = count($rubrieken);

    if (($cats && $cc) || ($tags && $ct) || ($rubrieken && $cr)) {

        $r .= "<aside class='onder-tekst-terzijde' role='complementary'>";
            $r .= "<nav class='taxonomie-navigatie'>";
                $r .= "<ul class='taxonomie-navigatie-lijst'>";

                if ($cats && $cc) {
                    $r .= "<li class='taxonomie-navigatie-lijst-stuk'>";
                    $r .= $cc > 1 ? "Categorie&euml;n: " : "Categorie: ";
                    $tt = '';
                    foreach ($cats as $c) {
                        if ($c->cat_ID === 1) continue;
                        $tt .= "<a class='taxonomie-link categorie-link' href='".get_category_link($c->cat_ID)."'>".$c->name."</a>, ";
                    }
                    $r .= rtrim($tt, ', ');
                    $r .= ".</li>";
                }

                if ($tags && $ct) {
                    $r .= "<li class='taxonomie-navigatie-lijst-stuk'>";
                    $r .= $ct > 1 ? "Tags: " : "Tag: ";
                    $tt = '';
                    foreach ($tags as $t) {
                        $tt .= "<a class='taxonomie-link tag-link' href='".get_tag_link($t->term_id)."'>".$t->name."</a>, ";
                    }
                    $r .= rtrim($tt, ', ');
                    $r .= ".</li>";
                }

                if ($rubrieken && $ct) {
                    $r .= "<li class='taxonomie-navigatie-lijst-stuk'>";
                    $r .= $ct > 1 ? "Rubrieken: " : "Rubriek: ";
                    $tt = '';
                    foreach ($rubrieken as $rub) {
                        $tt .= "<a class='taxonomie-link rubriek-link' href='".get_term_link($rub->term_id)."'>".$rub->name."</a>, ";
                    }
                    $r .= rtrim($tt, ', ');
                    $r .= ".</li>";
                }

                $r .= "</ul>";
            $r .= "</nav>";
        $r .= "</aside>";

    }

    return $r;
}

function agenda_art_meta($post) {
    ob_start();
    $ID = $post->ID;
    ?>
    <div class="onder-afb">
        <div class="agenda-data blok artikel-meta">
            <span class="datum"><?php the_field('datum', $ID);?></span>
            <span class="datum"><?php the_field('adres_tekst', $ID);?></span>
        </div>

    </div>
<?php return ob_get_clean();
}

function krant_art_meta() {
    ob_start();?>
    <div class="onder-afb">
    </div>
<?php return ob_get_clean();
}

function single_content_model($post, $alt_art_meta = '') {

    //als bevat maar één paragraaf, dan intro niet als intro maar normale P nà meta blok

    $c = $post->post_content;
    $r = array();

    ob_start();

    $maar_een_alinea = false;

    $aantal_alineas = substr_count($post->post_content, "\n") + substr_count($post->post_content, "<br>") + substr_count($post->post_content, "</br>") + 1;

    if (!get_field('geen_auteur_datum_veld', $post->ID)) {

        $r['tot_eind_intro_expl'] = explode("</strong>", $c);

        //geen intro met strong? maak met p.
        if (!$via_strong = (count($r['tot_eind_intro_expl']) > 1)) {
            $r['tot_eind_intro_expl'] = explode("\n", $c);
        }

        if (count($r['tot_eind_intro_expl']) > 1) {

            $tot_eind_intro = $r['tot_eind_intro_expl'][0];

            //als via strong, dan nog begin element erafhalen.
            if ($via_strong) {
                $vanaf_begin_intro_expl = explode('<strong>', $tot_eind_intro);
                $vanaf_begin_intro = $vanaf_begin_intro_expl[1];
                //kan buggy zijn bij oudere artikelen.
                $r['rest_content'] = str_replace("<strong>".$vanaf_begin_intro."</strong>", '', $c);
            } else {
                $vanaf_begin_intro = $tot_eind_intro;
                $r['rest_content'] = str_replace($vanaf_begin_intro, '', $c);
            }

            echo "<em class='intro'>".$vanaf_begin_intro."</em>";

        } else {
            echo "<br>"; //anders ligt meta blok tegen afb.
        }

        //auteur blok
        if ($alt_art_meta === '') {

            $schrijver = get_field('schrijver', $post->ID);

            //als maar één paragraaf, metablok onderaan.
            set_query_var('metablok_onderaan', $aantal_alineas < 2 );

            if ($schrijver) {
                set_query_var('schrijver_id', $schrijver);
                get_template_part('sja/art-meta');
            } else {
                get_template_part('sja/art-meta-geen-auteur');
            }
        } else {
            echo $alt_art_meta;
        }

    } else {
        $r['tot_eind_intro_expl'] = array(); //ja lelijk
    }

    $r['print'] = ob_get_clean();
    return $r;

}

function agenda_filter_model(){

    $agenda_taxen = get_terms(array('momentsoort', 'plek', 'onderwerp'));
    $filters_inst = array();

    $archief = array_key_exists('archief', $_GET);

    $datum_vergelijking = ($archief ? '<' : '>=' );

    $datum_sortering = ($archief ? 'DESC' : 'ASC');

    foreach ($agenda_taxen as $at) {

        if (!array_key_exists($at->taxonomy, $filters_inst)) $filters_inst[$at->taxonomy] = array();

        $test_posts = get_posts(array(
            'posts_per_page'    => -1,
            'post_type'         => 'agenda',
            $at->taxonomy       =>  $at->slug,
           'meta_key'          => 'datum',
            'orderby'           => 'meta_value',
            'order'             => $datum_sortering,
            'meta_query'        => array(
                array(
                    'key' => 'datum',
                    'value' => date('Ymd'),
                    'type' => 'DATE',
                    'compare' => $datum_vergelijking
                )
            ),
        ));

        $print[] = array($at->taxonomy."-".$at->slug => count($test_posts));

        $test_count = count($test_posts);

        //geen lege taxen.
        if ($test_count > 0) {
            $filters_inst[$at->taxonomy][] = array(
                'slug' => $at->slug,
                'name' => ucfirst($at->name),
                'count' => $test_count
            );
        }

    }

    $filters_actief = false;

    foreach ($filters_inst as $n => $w) {
        if (array_key_exists($n, $_POST)) {
            $filters_actief = true;
            break;
        }
    }

    return array(
        'filters_actief' => $filters_actief,
        'filters_inst' => $filters_inst,
    );
}