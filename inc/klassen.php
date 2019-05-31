<?php

get_template_part('inc/functions/legacy'); //verwijderen in productie?

class Array_constr {
	function __construct($a = array()) {
		if (is_array($a)) {
			foreach ($a as $k=>$v) {
				$this->$k = $v;
			}
		} else {
			$this->naam = $a;
		}
	}
}

class Knop extends Array_constr{

	public $class, $link, $tekst, $extern, $schakel, $html;

	public function __construct ($a = array()) {
		parent::__construct($a);
	}

	public function nalopen () {
		if (!cp_bestaat_niet_leeg('ikoon', $this)) $this->ikoon = "arrow-right-thick";
	}

	public function maak() {
		$this->nalopen();
		$e = $this->extern ? " target='_blank' " : "";
		$this->html = "<a {$e}
				class='knop {$this->class}'
				href='{$this->link}'
				{$this->schakel}
			><span>{$this->tekst}</span><i class='mdi mdi-{$this->ikoon}'></i></a>";
		return $this->html;
	}

	public function print () {
		$this->maak();
		echo $this->html;
	}
}

class Widget_M extends Array_constr {

	public $naam, $verp_open, $verp_sluit, $gemaakt, $css_klassen, $vernietigd;

	public function __construct ($a) {
		parent::__construct($a);
	}

	public function maak() {

		if (!$this->css_klassen) $this->css_klassen = preg_replace('~[^\p{L}\p{N}]++~u', '', strtolower($this->naam));
		$this->extra_voor_verp();
		$this->verp_open = "<section class='widget $this->css_klassen'>";
		$this->verp_sluit = "</section>";
		$this->zet_inhoud();
		$this->gemaakt = true;

	}

	public function zet_inhoud() {
		$this->inhoud = "lege widget";
	}

	public function extra_voor_verp (){
		//voor kinderen om na te bewerken
	}

	public function vernietig() {
		$this->vernietigd = true;
	}

	public function print(){

		if ($this->vernietigd) return;

		if (!$this->inhoud || $this->inhoud === '') return;

		if (!$this->gemaakt) $this->maak();

		echo $this->verp_open;
		echo
		"<header><h2>{$this->naam}</h2></header>
			<div class='blok'>
			{$this->inhoud}
			</div>
		";

		echo $this->verp_sluit;
	}
}

class Article_c extends Array_constr{

	public $art, $gecontroleerd, $data_src, $console;

	public function __construct ($config, $post) {
		parent::__construct($config);
		$this->art = $post;
		$this->console = [];
	}

	public function test() {
		return "test";
	}

	public function controleer () {
		if ($this->gecontroleerd) return;

		$c = array('geen_afb', 'geen_tekst', 'in_zijbalk', 'is_categorie', 'gebruik_korte_titel', 'titel_overschrijving');
		foreach ($c as $cc) {
			$this->$cc = property_exists($this, $cc) ? $this->$cc : false;
		}

		$this->zet_permalink();
		$this->maak_titel();
		$this->maak_data_src();

		$this->htype = cp_bestaat_niet_leeg('htype',$this) ? $this->htype : "3";
		$this->exc_lim = cp_bestaat_niet_leeg('exc_lim',$this) ? $this->exc_lim : "600";
	}

	public function maak_data_src(){

		if ($this->in_zijbalk) {



			if ($this->is_categorie) {

				$img_id = get_field('tax_afb', 'category_'.$this->art->term_id);

				$thumb_url = wp_get_attachment_image_src($img_id, 'art_in_lijst', true);

				$this->data_src = "data-src='{$thumb_url[0]}'";
			} else {
				$thumb_id = get_post_thumbnail_id($this->art->ID);
				$thumb_url = wp_get_attachment_image_src($thumb_id,'art_in_lijst', true);

				if (strrpos($thumb_url[0], 'default.png')) {

					$img_uit_tekst = haal_img_uit_tekst($this->art->post_content);
					$thumb_url[0] = $img_uit_tekst[1];

				}

				$this->data_src = "data-src='{$thumb_url[0]}'";
			}
		}
	}

	public function maak_titel () {
		if (!$this->titel_overschrijving) {
			if ($this->is_categorie) {
				$this->art->post_title = $this->art->cat_name;
			} else if ($this->gebruik_korte_titel) {
				$korte = get_field('korte_titel', $this->art->ID);
				if (!$korte) {
					$this->art->post_title = beperk_woordental(35, $this->art->post_title);
				} else {
					$this->art->post_title =  $korte;
				}

			} else {
				//
			}
		} else {
			$this->art->post_title = $this->titel_overschrijving;
		}
		$this->art->post_title = trim($this->art->post_title);

	}

	public function zet_permalink() {
		if ($this->is_categorie) {
			$this->permalink = get_category_link( $this->art->term_id );
		} else {
			$this->permalink = get_permalink($this->art->ID);
		}
	}

	public function print_afb () {

		if ($this->is_categorie) {

			$img_id = get_field('tax_afb', 'category_'.$this->art->term_id);
			if (!$img_id){
				$img_id = get_field('cat_afb', 'category_'.$this->art->term_id);
			}

			$img = wp_get_attachment_image($img_id, 'art_in_lijst');



			if (strpos($img, 'alt=') === false || (
				count(explode('alt=""', $img)) === 2
			)) {
				$img = str_replace("<img ", "<img alt='{$this->art->cat_name}' ", $img);
				$this->console[] = 'vervangen alt';
			} else {
				$this->console[] = 'alt aanwezig';
			}
			$this->console[] = $this->art;

			$this->console[] = explode('alt=""', $img);


			//echo 'is_categorie_afb_gedaan';

		} else {

			//echo 'denkt dat dit een post dit';
			$thumbnail_id = get_post_thumbnail_id( $this->art->ID );
			$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

			if (!$alt || $alt === '') {
				$alt = $this->art->post_title;
			}

			$img = get_the_post_thumbnail($this->art, 'art_in_lijst', array( 'alt' => $alt ));

		}

		$e = explode(' ', $img);

		//is het een lege afbeelding?
		if ($e[0] === '') {

			$img = haal_img_uit_tekst($this->art->post_content);
			//$this->console = $img[2];
			$img = $img[0];

			if ($img) {
				$e = explode(' ', $img);
			}

		}

		if ($img && $img !== '') {
			//microdata ertussen
			$e[0] = $e[0] . " itemprop='image'";

			echo implode(' ', $e);
		}

	}

	public function maak_artikel () {

		if (!$this->gecontroleerd) $this->controleer();

		ob_start();

		?>

		<article itemscope itemtype='http://schema.org/BlogPosting' class="flex <?=$this->class?>" <?=$this->data_src?> >

			<span class='meta-niet-tonen'>
				<?php
					$toon_auteur = art_meta_model($this->art);
					if ($toon_auteur) {
						$auteur = get_userdata( $this->art->post_author );
						echo "<meta itemprop='author' content='".$auteur->data->display_name."'>";
					} else {
						echo "<meta itemprop='author' content='Redactie socialisme.nu'>";
					}
				?>

				<meta itemprop='datePublished' content='<?=$this->art->post_date?>'>
				<meta itemprop='dateModified' content='<?=$this->art->post_modified?>'>
				<span itemprop='publisher' itemscope itemtype="http://schema.org/Organization">
					<meta itemprop='name' content='Internationale Socialisten'>
				</span>
			</span>

			<?php if (!$this->geen_afb) : ?>
			<div class='art-links'>
				<a href='<?=$this->permalink?>' itemprop='mainEntityOfPage'>
					<?php $this->print_afb(); ?>
				</a>
			</div>
			<?php endif;?>

			<div class='art-rechts'>
				<a href='<?=$this->permalink?>'>
					<h<?=$this->htype?> itemprop='headline'>
					<?=$this->art->post_title?>
					</h<?=$this->htype?>>

					<?php if (!$this->geen_tekst) : ?>
						<p><?php echo maak_excerpt($this->art, $this->exc_lim); ?> <span class='lees-meer'>Lees meer<span class='screen-reader-text'>uit "<?=$this->art->post_title?>"</span>.</span></p>
					<?php endif;  ?>
				</a>
			</div>

		</article>
		<?php

		if (!empty($this->console)) echo "<script>console.dir(".json_encode($this->console).")</script>";

		return ob_get_clean();

	}

	public function print () {
		echo $this->maak_artikel();
	}
}

class Agenda extends Array_constr {

	public $omgeving;

	public function __construct ($a = array()) {
		parent::__construct($a);
		//filter, /omgeving, etc.
	}

	public function zet_is_widget(){
		$this->is_widget = $this->omgeving === "widget";
	}

	public function in_pagina_queryarg (){


		$this->console = [];

		$archief = array_key_exists('archief', $_POST) || array_key_exists('archief', $_GET);

		$datum_vergelijking = ($archief ? '<' : '>=' );

		$datum_sortering = ($archief ? 'DESC' : 'ASC');

		$args = array(
		    'post_type' 		=> 'agenda',
		    'post_status' 		=> 'publish',
		    'meta_key' 			=> 'datum',
			'orderby'			=> 'meta_value',
			'order'				=> $datum_sortering,
			'meta_query' 		=> array(
				array(
					'key' => 'datum',
					'value' => date('Ymd'),
					'type' => 'DATE',
					'compare' => $datum_vergelijking
				)
			),
		);

		$tax_query = array();
		$tax_namen = array('plek', 'onderwerp', 'momentsoort');

		foreach ($tax_namen as $t) {
			if (array_key_exists($t, $_POST) && $_POST[$t] !== '') {
				$tax_query[] = array(
		           'taxonomy' => $t,
		           'field'    => 'slug',
		           'terms'    => $_POST[$t],
				);
			}
		}

		if (count($tax_query)) {
			$args['tax_query'] = $tax_query;
		}

		$args_paged = $args;

		$args['posts_per_page'] = -1;
		$args_paged['posts_per_page'] = $this->aantal;

		$page = get_query_var('paged');

		if ($page and $page > 0) {
			$args_paged['offset'] = ($page - 1 ) * $this->aantal;
		}

		$this->query_args = array($args_paged, $args);

	}

	public function widget_queryarg () {

		$args = array(
		    'post_type' 		=> 'agenda',
		    'post_status' 		=> 'publish',
		    'posts_per_page'	=> $this->aantal,
		    'meta_key' 			=> 'datum',
			'orderby'			=> 'meta_value',
			'order'				=> 'ASC',
			'meta_query' 		=> array(
				array(
					'key' => 'datum',
					'value' => date('Ymd'),
					'type' => 'DATE',
					'compare' => '>='
				)
			),
		);

		$this->query_args = array($args, $args);

	}

	public function zet_agendastukken() {

		$this->is_widget ? $this->widget_queryarg() : $this->in_pagina_queryarg();

		$this->agendastukken = get_posts($this->query_args[0]);

		$this->is_widget ? NULL : $this->zet_totaal_aantal();

	}

	public function nalopen () {
		if (!cp_bestaat_niet_leeg('aantal', $this)) $this->aantal = 5;
		if (!cp_bestaat_niet_leeg('agenda_link', $this)) $this->agenda_link = get_post_type_archive_link('agenda');

	}

	public function zet_totaal_aantal() {

		$query_voor_tellen = get_posts($this->query_args[1]);
		//echo count($query_voor_tellen) . " / " . $this->aantal . " = " . $wp_query->max_num_pages;
		global $wp_query;
	   	$wp_query->max_num_pages = ceil(count($query_voor_tellen) / $this->aantal);

	}

	public function print () {

		$this->zet_is_widget();
		$this->zet_agendastukken();
		$this->nalopen();

		$verpakking_el = $this->is_widget ? "section" : "div";

		?>
		<<?=$verpakking_el?> class='agenda <?=$this->omgeving?>'>
			<?=($this->omgeving==="widget" ? "<h2>Agenda</h2>" : "")?>



			<div class=''>
				<ul>
					<?php

						foreach ($this->agendastukken as $a) :

							if (!$this->is_widget) {
								$content = maak_excerpt($a, 320);
								$this->rechts = "<div itemprop='description' class='agenda-rechts'><span>".$content."</span></div>";
							} else {
								$this->rechts = '';
							}

							$agenda_plek = wp_get_post_terms( $a->ID, 'plek');
							$stad = '';
							if (count($agenda_plek)) {
								foreach ($agenda_plek as $ap) {
									$stad .= $ap->name . ", ";
								}
							}

							$stad = substr($stad, 0, strlen($stad) -2); //laatste ', ' eraf

							$adres = get_field('adres', $a->ID);

							//legacy fix
							$adres = is_array($adres) ? $adres['address'] : $adres;

							$adres_name = explode(',', $adres);
							$adres_name = $adres_name[0];


							$afb = wp_get_attachment_image_src( get_post_thumbnail_id( $a->ID ), 'large' );

							$type_actie = implode('; ', array_map(function($soort) {
								return $soort->name;
							},get_the_terms($a->ID, 'momentsoort' )));


							echo
							"<li itemscope itemtype='http://schema.org/Event'>
								<meta itemprop='image' content='{$afb[0]}'>

								<a class='flex' itemprop='url' href='".get_the_permalink($a->ID)."'>

									<div class='agenda-links'>
										".$this->format_datum(get_field('datum', $a->ID))."
									</div>

									<div class='agenda-midden flex'>

										<span
											itemprop='location' itemscope itemtype='http://schema.org/PostalAddress'
											class='locatie'>
											<meta itemprop='streetAddress' content='$adres'>
											<meta itemprop='address' content='$adres'>
											<meta itemprop='addressLocality' content='$stad'>
											$stad ".
											($this->is_widget ? "" : " - " . $type_actie)."
										</span>

										<span class='titel' itemprop='name'>".$a->post_title."</span>
									</div>

									{$this->rechts}

								</a>
							</li>";
						endforeach; //agendastukken

					?>

				</ul>


				<?php

				if ($this->is_widget) {
					echo "<footer>";

					$agenda_knop = new Knop(array(
						'class'=> 'in-kleur',
						'link' => $this->agenda_link,
						'tekst'=> 'Hele agenda'
					));
					$agenda_knop->print();
					echo "</footer>";
				}


				?>


			</div>
		</<?=$verpakking_el?>>
		<?php
	}

	public function format_datum ($datum) {
		//if (!$this->is_widget) return $datum;

		// 0 = dag
		// 1 = maand
		// 2 = jaar
		// 3 = tijd

		$ma_ = array(
			'jan' => '01',
			'feb' => '02',
			'mrt' => '03',
			'apr' => '04',
			'mei' => '05',
			'jun' => '06',
			'jul' => '07',
			'aug' => '08',
			'sep' => '09',
			'okt' => '10',
			'nov' => '11',
			'dec' => '12'
		);

		$expl = explode(' ', $datum);

		$expl[1] = substr($expl[1], 0, 3);

		if ($expl[1] === 'maa') $expl[1] === 'mrt';

		$jaar_en_tijd = '';

		$dt_str = $expl[2]."-".$ma_[$expl[1]]."-".$expl[0]."T".$expl[3];
		$datetime = "<time
						itemprop='startDate'
						dateTime='$dt_str'
						>$dt_str
					</time>";

		if (!$this->is_widget) {
			return "$datetime<span><span>".$expl[0]." ".$expl[1]."</span> <span>".$expl[2]."</span><span class='met-streepje'>".$expl[3]."</span></span>";
		} else {
			return "$datetime<span><span>".$expl[0] . "</span> <span>" . $expl[1] . "</span></span>";
		}




	}
}

class Zijbalk_Posts extends Widget_M {

	public function __construct ($a = array()) {
		parent::__construct($a);
	}

	public function zet_inhoud () {

		$artikels = '';

		$art_c = new Article_c();

		$art_c->art->class ="in-lijst zijbalk";
		$art_c->art->afb_data_src = $art_c->art->afb_src;
		$art_c->art->tekst = "";
		$art_c->art->cat = "";
		$art_c->art->datum = "";

		$this->inhoud = $art_c->maak_artikel();

		$art_c->art->afb_src = "";
		$art_c->art->afb_data_src = "https://sjerpbouwtsites.nl/labs/ontwerp/IS/ingekleurd/afb/draadje-los-zonnepanelen.jpg";
		$this->inhoud .= $art_c->maak_artikel();

		$art_c->art->afb_data_src = "https://sjerpbouwtsites.nl/labs/ontwerp/IS/ingekleurd/afb/afa-demo-smal.jpg";
		$this->inhoud .= $art_c->maak_artikel();

	}
}

class Pag_fam extends Zijbalk_Posts{

	public $inhoud;

	public function __construct ($a = array()) {
		parent::__construct($a);
	}

	public function extra_voor_verp () {
		$this->css_klassen = $this->css_klassen . " pag-fam";
	}

	public function zet_inhoud () {

		$post = $GLOBALS['post'];

		if ($post->post_type !== 'page') {
			return;
		}

		$this->is_kind = $post->post_parent !== 0;
		if ($this->is_kind) {
			$this->ouder = $post->post_parent;
		} else {
			$this->ouder = $post->ID;
		}

		$pagina_query = new WP_Query();
		$alle_paginas = $pagina_query->query(array('post_type' => 'page', 'posts_per_page' => '-1'));
		$this->kinderen = get_page_children( $this->ouder, $alle_paginas );

		//als er geen kinderen zijn (0 of alleen zichzelf) dan zit deze pagina niet in een familie.
		if (count($this->kinderen) < 2)  {
			$this->vernietig();
			return;
		}

		//$this->inhoud .= "<script>console.dir(".json_encode($this).")</script>";

		ob_start();

		$hui = ($this->ouder === $post->ID ? 'huidige' : '');

		$art = new Article_c(
			array(
				'class' => "in-lijst blok zijbalk $hui",
				'htype' => 3,
				'geen_tekst' => true,
				'in_zijbalk' => true
			),
		get_post($this->ouder));

		$art->print();


		foreach ($this->kinderen as $k) {

			$hui = ($k->ID === $post->ID ? 'huidige' : "");

			$art = new Article_c(
				array(
					'class' => "in-lijst blok zijbalk $hui",
					'htype' => 3,
					'geen_afb'	=> true,
					'geen_tekst' => true,
					'in_zijbalk' => true
				),
			get_post($k));

			$art->print();
		}

		$this->inhoud .= ob_get_clean();

	}
}

class Ext_media extends Widget_M {

	public function __construct ($a = array()) {
		parent::__construct($a);

		$this->gekke_ikonen = array(
			'youtube' => "youtube-play",
			"google" => "google-plus",
			"google plus" => "google-plus",
			"linkedin" => "linkedin-box",
			"pinterest" => "pinterest-box",
		);

	}

	public function extra_voor_verp () {
		$this->css_klassen = $this->css_klassen . " met-ruimte ext-media";
	}

	public function pak_ikoon ($naam){

		if (array_key_exists($naam, $this->gekke_ikonen)) {
			return $this->gekke_ikonen[$naam];
		} else {
			return $naam;
		}
	}

	public function zet_inhoud () {

		$this->inhoud = '<ul>';

		for ($i=1; $i < $this->instance['aantal_media'] + 1; $i++) {

			$ikoon = $this->pak_ikoon($this->instance['med_'.$i.'_medium']);

			$this->inhoud .= "
				<li>
					<a
					href='{$this->instance['med_'.$i.'_link']}'
					target='_blank'>
						<i class='mdi mdi-$ikoon'></i>
						<span>{$this->instance['med_'.$i.'_tekst']}</span>
					</a>
				</li>
			";

		}

		$this->inhoud .= "</ul>";


	}
}