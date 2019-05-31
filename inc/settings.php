<?php
/**********************************************************************
 * ZET EENVOUDIG JE EIGEN INSTELLINGEN.
 *
 * - - - - -
 *
 * STAP 1
 * Ga naar en schrijf in de functie 'opties_init'.
 * Maak eerst een instellingenpagina aan, zoals:
 * $extra_opties = new Inst_pagina('Extra opties');
 *
 * Het $extra_opties object bevat nu alles om de pagina aan te maken.
 *
 * - - - - -
 *
 * STAP 2
 * Bepaal of je een eigen pagina wilt maken of een kindpagina.
 * Indien je een kindpagina wilt, moet er al een ouderpagina zijn.
 * Stel, we hebben $extra_optiestwee, die een kind moet zijn van $extra_opties. Dat gaat alsvolgt:
 * $extra_optiestwee->is_kind_van($extra_opties);
 *
 * - - - - -
 *
 * STAP 3
 * creeer een sectie aldus:
 * $extra_opties->zet_sectie("Weergave");
 *
 * - - - - -
 *
 * STAP 4
 * creeer de velden.
 *
 * eenvoudigst:
 *
 * $extra_opties->zet_veld("Naam van mijn kat");
 *
 *
 * snelst voor meerdere velden:
 *
 * $extra_opties->zet_velden(array("Naam van mijn kat", "Favoriete snoep", "Nog een instelling"));
 *
 *
 * complexer, uitgebreider:
 *
 * naam, sectie naam, invoer type (tekst, tekstgebied, vinkdoos, selectie), opties
 *
 * $extra_opties->zet_veld("naam van mijn kat", "sectienaam", "tekst");
 *
 * $extra_opties->zet_veld("beschrijving van mijn kat", "sectienaam", "tekstgebied");
 *
 * $extra_opties->zet_veld("kat heeft honger", "sectienaam", "vinkdoos");
 *
 * $extra_opties->zet_veld("kat wilt als eten", "sectienaam", "selectie", array("brokjes", "vis", "snoepjes") );
 *
 * - - - - -
 *
 * STAP 5
 * herhaal stap 3 & 4 indien nodig.
 *
 * - - - - -
 *
 * STAP 6
 * registreer de pagina:
 * $extra_opties->registreer_pagina();
 *
 * - - - - -
 *
 * STAP 7
 * herhaal stap 1 - 6 indien nodig.
 *
 * - - - - -
 *
 * STAP 8
 * haal de opties op in je templates via $opties = get_opties('instellingspaginanaam');
 ****************************************************************************************/





function opties_init(){


}

add_action( 'admin_menu', 'opties_init' );


///////////////////////////////////////////////////////////////////


function inst_slug($tekst){

    $tekst = preg_replace('~[^\pL\d]+~u', '_', $tekst);
    $tekst = preg_replace('~[^_\w]+~', '', $tekst);
    $tekst = trim($tekst, '_');
    $tekst = preg_replace('~_+~', '_', $tekst);
    $tekst = strtolower($tekst);
    return $tekst;

}

function intst_ontslug($slug){
    return ucfirst(str_replace('_', ' ', $slug));
}

class Inst_pagina{

    function __construct($naam_rauw = ''){

        $this->pagina_naam = trim($naam_rauw);
        $this->pagina_id = inst_slug($this->pagina_naam) . "_inst";

        $this->secties = array();
        $this->huidige_sectie = '';
        $this->is_kind = false;
        $this->heeft_kind = false;

    }

    function maak_pagina(){

        if (!$this->is_kind) {
            //browser titel; menu titel; rechten; menu id; uitdraaifunctie via call_user_func_array
            add_menu_page(
                $this->pagina_naam,
                $this->pagina_naam,
                "administrator",
                $this->pagina_id,
                array($this, "print_pagina")
            );
        } else {
            add_submenu_page(
                $this->ouder_id,
                $this->pagina_naam,
                $this->pagina_naam,
                "administrator",
                $this->pagina_id,
                array($this, "print_pagina")
            );
        }

    }

    function is_kind_van($ouder = '') {
        if ($ouder === '') return;
        $this->is_kind = true;
        $this->ouder_id = $ouder->pagina_id;
        $ouder->registreer_kind($this->pagina_id);
        $this->ouder = $ouder;
    }

    function registreer_kind($kind_id = ''){

        if ($kind_id === '') return;

        $this->heeft_kind = true;

        if (!isset($this->kinderen)) $this->kinderen = array();

        $this->kinderen[] = $kind_id;
    }

    function pak_familie_lijst(){
        if ($this->is_kind) {
            return $this->ouder->pak_familie_lijst();
        } else {
            return array_merge(array($this->pagina_id), $this->kinderen);
        }
    }

    function print_pagina(){

        echo "<div class='wrap'>";
            echo "<h1>".$this->pagina_naam."</h1>";

            if (count($this->secties) > 0) {

                $actieve_pag = (array_key_exists('page', $_GET) ? $_GET['page'] : $this->pagina_id);

                if ($this->is_kind || $this->heeft_kind) {

                    $familie = $this->pak_familie_lijst();

                    echo '<h2 class="nav-tab-wrapper wp-clearfix">';

                    foreach ($familie as $familielid) {

                        $actief_class = ($actieve_pag === $familielid ? "nav-tab-active" : "");

                        $lid_naam = str_replace(" inst", "", intst_ontslug($familielid));

                        echo "<a href='".SITE_URI."/wp-admin/admin.php?page=$familielid' class='nav-tab $actief_class'>$lid_naam</a>";

                    }

                    echo '</h2>';

                } //tabs nodig

                echo '<form method="post" action="options.php">';

                    settings_fields($actieve_pag);
                    do_settings_sections($actieve_pag);

                    submit_button();

                echo '</form>';

                settings_errors();

                if (property_exists($this, 'beschrijving') and $this->beschrijving !== '') {
                    echo wpautop($this->beschrijving);
                }

            } //heeft secties

            else {

                echo "Er zijn geen secties aangemaakt. Voeg opties toe via settings.php";

            }

        echo "</div>";

    }

    function registreer_pagina(){

        register_setting(
            $this->pagina_id,
            $this->pagina_id,
            array($this, "valideer")
        );

    }

    function zet_sectie($sectie_naam){

        $deze_sectie = new Inst_sectie($this);

        $deze_sectie->maak($sectie_naam);

        $this->huidige_sectie = $deze_sectie->sectie_slug;

        $this->secties[] = $deze_sectie;

    }

    function zet_veld($naam, $sectie_naam = '', $type = "tekst", $opties = array()){

        if ($sectie_naam === '') {
            $sectie_naam = $this->huidige_sectie;
            $sectie_slug = $this->huidige_sectie;
        } else {
            $sectie_slug = inst_slug($sectie_naam);
        }

        $dit_veld = new Inst_veld($this, $sectie_slug);

        $dit_veld->maak($naam, $type, $opties);

    }

    function zet_velden($namen, $sectie_naam = '', $type = "tekst", $opties = array()){

        if (!is_array($namen)) {
            $namen = array($namen);
        }

        foreach ($namen as $naam) {
            $this->zet_veld($naam, $sectie_naam, $type, $opties);
        }
    }

    function pak_opties(){
        if (!isset($this->opties_ar)) {
            $this->opties_ar = get_option($this->pagina_id);
        }
        return (is_array($this->opties_ar) ? $this->opties_ar : array());
    }

    function valideer($invoer){

        $resultaat = array();

        foreach( $invoer as $s => $w ) {
            if( isset( $invoer[$s] ) ) {
                $resultaat[$s] = strip_tags( stripslashes( $invoer[ $s ] ), "<b><i><em><h1><h1><h2><h3><h4><h5><h6><p><blockquote><a><span><hr><ul><li><ol><strong><img>" );
            }
        }

        return apply_filters( array($this, "valideer"), $resultaat, $invoer );

    }

}


class Inst_sectie{

    function __construct($pagina){
        $this->pagina = $pagina;
    }

    function maak($sectie_naam = 'nieuwe sectie'){

        $this->sectie_naam = $sectie_naam;
        $this->sectie_slug = inst_slug($sectie_naam);

        if( false == get_option($this->pagina->pagina_id) ) {
            add_option($this->pagina->pagina_id);
        }

        //id, titel, cb, pagina.
        add_settings_section(
            $this->sectie_slug,
            $this->sectie_naam,
            array($this, "print_sectie"),
            $this->pagina->pagina_id
        );
    }

    function print_sectie (){
        //echo "print sectie";
    }

}

class Inst_veld{

    function __construct($pagina, $sectie){

        $this->pagina = $pagina;
        $this->sectie = $sectie;
    }

    function maak($veld_naam, $veld_type, $opties = array()){

        $this->veld_naam = $veld_naam;

        $this->veld_slug = inst_slug($this->veld_naam);

        $this->pag_en_naam = $this->pagina->pagina_id.'['.$this->veld_slug.']';

        $this->opties = $this->pagina->pak_opties();

        $this->inhoud = (array_key_exists($this->veld_slug, $this->opties) ? $this->opties[$this->veld_slug] : "");

        $this->beschrijving = array_key_exists('beschrijving', $this->opties) ? $this->opties['beschrijving'] : "";

        //id, titel, cb, pagina, sectie.
        add_settings_field(
            $this->veld_slug,
            $this->veld_naam,
            array($this, "print_veld_".$veld_type),
            $this->pagina->pagina_id,
            $this->sectie,
            $opties
        );

    }

    function print_veld_tekst(){

        echo
        '<input
            type="input"
            id="'.$this->veld_slug.'"
            name="'.$this->pag_en_naam.'"
            value="'.$this->inhoud.'"
        />';

    }

    function print_veld_tekstgebied(){

        echo
        '<textarea
            class="wp-editor-area"
            id="'.$this->veld_slug.'"
            rows="5"
            cols="60"
            name="'.$this->pag_en_naam.'"
        >' . $this->inhoud .'
        </textarea>';

    }

    function print_veld_wysiwyg(){

        wp_editor( $this->inhoud, $this->veld_slug, array(
                "textarea_name"     => $this->pag_en_naam
            )
        );

    }

    function print_veld_vinkdoos(){

        echo '
            <input
                type="checkbox"
                id="'.$this->veld_slug.'"
                name="'.$this->pag_en_naam.'"
                value="1"' .
                checked( 1, $this->inhoud, false )
            .'/>
        ';

    }

    function print_veld_selectie($opts){

        echo '
            <select id="'.$this->veld_slug.'" name="'.$this->pag_en_naam.'">
                <option value="default">'.intst_ontslug($this->veld_slug).'</option>';
            if (count($opts) > 0) {
                foreach ($opts as $opt) {
                  echo   '<option value="'.$opt.'"' . selected( $this->inhoud, $opt, false) . '>'.$opt.'</option>';
                }
            } else {
                echo '<option value="GEEN OPTIES!"' . selected( $this->inhoud, "GEEN OPTIES!", false) . '>GEEN OPTIES!</option>';
            }

        echo '</select>
        ';

    }

}

