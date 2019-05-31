<?php

function registreer_sidebars(){

	register_sidebar(array(
	    'name'          => __( 'zijbalk' ),
	    'id'            => 'zijbalk',
	    'description'   => __( 'Voeg hier widgets toe om ze te laten verschijnen in de zijbalk. Als je niet resultaten ziet van opslaan: herladen'),
	    'before_widget' => '<section class="widget %2$s">',
	    'after_widget'  => '</section>',
	    'before_title'  => '<h3 class="widget-title">',
	    'after_title'   => '</h3>',
	));

	register_sidebar(array(
	    'name'          => __( 'voorpagina midden' ),
	    'id'            => 'voorpagina_midden',
	    'description'   => __( 'Dit komt terecht onder de eerste vier artikelen op de voorpagina'),
	    'before_widget' => '<section class="widget %2$s">',
	    'after_widget'  => '</section>',
	    'before_title'  => '<h3 class="widget-title">',
	    'after_title'   => '</h3>',
	));

	register_sidebar(array(
	    'name'          => __( 'onder krant' ),
	    'id'            => 'onder_krant',
	    'before_widget' => '<section class="widget %2$s in-pagina">',
	    'after_widget'  => '</section>',
	    'before_title'  => '<h2 class="widget-title streep-eronder">',
	    'after_title'   => '</h2>',
	));

	register_sidebar(array(
	    'name'          => __( 'onder lidworden' ),
	    'id'            => 'onder_lidworden',
	    'before_widget' => '<section class="widget %2$s in-pagina">',
	    'after_widget'  => '</section>',
	    'before_title'  => '<h2 class="widget-title streep-eronder">',
	    'after_title'   => '</h2>',
	));

}

add_action( 'widgets_init', 'registreer_sidebars' );


class Zijbalk_posts_w extends WP_Widget {

    function __construct() {

    	parent::__construct(
        	// Base ID of your widget
	        'zijbalk_posts',

	        // Widget name will appear in UI
	        __('zijbalk posts', 'zijbalk_posts_domain'),

	        // Widget description
	        array( 'description' => __( 'Zet varia posts/pagina\'s in de zijbalk', 'zijbalk_posts_domain' ), )
        );

    }

    // Frontend
    public function widget( $args, $instance ) {

		    echo $args['before_widget'];
		    if ( ! empty( $title ) )
		    echo $args['before_title'] . $title . $args['after_title'];


		echo "
	    <section class='widget {$instance['keuze']}'>
	    	<header><h2>{$instance['title']}</h2></header>
	    	<div class='blok'>
		";

			switch ($instance['keuze']) {

				case 'uitgelicht':
					$this->uitgelicht();
					break;

				case "themas":
					$this->themas($instance);
					break;

				default:
					//
					break;
			}


		echo "</div>
	    </section>";


		    echo $args['after_widget'];

    }

    public function uitgelicht(){
		$sticky = get_option('sticky_posts');
		// check if there are any
		if (!empty($sticky)) {
		    // optional: sort the newest IDs first
		    rsort($sticky);
		    // override the query
		    $args = array(
		        'post__in' => $sticky,
		        'posts_per_page'	=> 3
		    );
		    $query = new WP_Query( $args );
		    // the loop
			if ($query->post_count > 0) {

				$een = false;
				foreach ($query->posts as $post) :

					$up = new Article_c(
						array(
							'class' 	=> 'flex in-lijst zijbalk',
							'geen_afb'	=> $een,
							'geen_tekst' => true,
							'in_zijbalk' => true
						),
						$post
					);

					$up->print();

					$een = true;

				endforeach;
			}
		    //reset_query();
		}
    }

    public function uitgelicht_OUD () {


		$args = array(
			'posts_per_page' => 3,
			'post__in'  => get_option( 'sticky_posts' ),
			'ignore_sticky_posts' => 1
		);
		$query = new WP_Query( $args );

		if ($query->post_count > 0) {

			$een = false;
			foreach ($query->posts as $post) :

				$up = new Article_c(
					array(
						'class' 	=> 'flex in-lijst zijbalk',
						'geen_afb'	=> $een,
						'geen_tekst' => true,
						'in_zijbalk' => true
					),
					$post
				);

				$up->print();

				$een = true;

			endforeach;
		}


    	?>

    <?php }

    public function themas ($instance) {

    	$cats = get_categories();

    	$gekozen_cats = explode(',', $instance['gekozen_cats']);

    	$een = false;
		foreach ($cats as $c) {
			if ($c->term_id === 1) continue; // is 'geen cat'
			if ($c->category_count < 1) continue;//lege cat
			if (!in_array($c->term_id, $gekozen_cats)) continue;

			//maskeren als post
			$a = new Article_c(array(
				'is_categorie' => true,
				'class' 	=> 'flex in-lijst zijbalk',
				'geen_afb'	=> $een,
				'geen_tekst' => true,
				'in_zijbalk' => true
			), $c);

			$een = true;

			$a->print();

		}

    }

    // Backend
    public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'De titel', 'zijbalk_posts_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Titel:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php

		$keuze = ! empty( $instance['keuze'] ) ? $instance['keuze'] : "uitgelicht";
		$opties = array('uitgelicht', 'pagina-familie', 'themas');


		?>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'keuze' ) ); ?>"
			><?php esc_attr_e( 'keuze:', 'text_domain' ); ?>
			</label>

			<select
				class='widefat'
				id="<?php echo esc_attr( $this->get_field_id( 'keuze' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'keuze' ) ); ?>"
			>
				<?php foreach ($opties as $o) :

					$sel = $o === $instance['keuze'] ? " selected " : "";

					echo "<option $sel value='$o'>$o</option>";
				endforeach;?>
			</select>
		</p>


		<?php if ($instance['keuze'] === 'themas') :

		$cats = get_categories();

		$gekozen_cat_ids = explode(',', $instance['gekozen_cats']);


		foreach ($cats as $c) {
			if ($c->term_id === 1) continue; // is 'geen cat'
			if ($c->category_count < 1) continue;//lege cat

			$checked = in_array($c->term_id, $gekozen_cat_ids) ? " checked='checked' " : "";

			echo "<p>
				<label for='cat-cb-{$c->slug}'>
					{$c->category_nicename}
				</label>
				<input
					class='cat-cbs'
					id='cat-cb-{$c->slug}'
					type='checkbox'
					value='{$c->term_id}'
					$checked
				>
			</p>";
		}

		echo "<input
				type='hidden'
				id='zp-print-cat-ids'
				value='{$instance['gekozen_cats']}'
				name='".esc_attr( $this->get_field_name( 'gekozen_cats' ) )."'

			>";


		?>
		<script>

			jQuery(function(){
				jQuery(".cat-cbs").on('change', function(){
					var $print = jQuery('#zp-print-cat-ids');
					$print.val('');
					var t = '';
					jQuery(".cat-cbs:checked").each(function(){
						t+= this.value +",";
					});
					t = t.substring(0, t.length - 1);
					$print.val(t);
				});
			});

		</script>

		<?php endif; //als is themas
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['keuze'] = ( ! empty( $new_instance['keuze'] ) ) ? strip_tags( $new_instance['keuze'] ) : '';
		$instance['gekozen_cats'] = ( ! empty( $new_instance['gekozen_cats'] ) ) ? strip_tags( $new_instance['gekozen_cats'] ) : '';

		return $instance;
	}
}

class Krant_carousel extends WP_Widget {

    function __construct() {

    parent::__construct(
        	// Base ID of your widget
	        'krant-carousel',

	        // Widget name will appear in UI
	        __('krant carousel', 'krant-carousel_domain'),

	        // Widget description
	        array( 'description' => __( 'Zet de krantcarousel erin.', 'krant-carousel_domain' ), )
        );

    }


    // Frontend
    public function widget($args, $instance) {


    	$kranten = get_posts(array(
    		'posts_per_page' 	=> 10,
    		'post_type'			=> 'krant'
    	));


		echo "<section class='krant-promo blok conversie'>";
			echo "<h2 class='streep-eronder'>De Socialist</h2>";
			echo "<div class='art-verp carousel'>";


				foreach ($kranten as $k) :

					$pl = get_the_permalink($k->ID);

					echo "
						<article>
							<a class='flex' href='$pl'>
								<div class='krant-promo-links'>
									".get_the_post_thumbnail($k, 'krant')."
								</div>
								<div class='krant-promo-rechts'>
									<h3>{$k->post_title}<span>".get_field('editie', $k->ID)."</span></h3>
									".wpautop(maak_excerpt($k, 105))."
								</div>
							</a>
						</article>
					";
				endforeach;


			echo"</div>";
			echo "<footer>";



			$neem_abo = new Knop(array(
				'class'=> 'in-wit',
				'link' => get_post_type_archive_link('krant'),
				'tekst'=> 'Abo vanaf &euro;3,50'
			));
			$neem_abo->print();

			echo "</footer>";
		echo "</section>";


    }

    // Backend
    public function form($instance) {

	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		return $instance;
	}
}

class Agenda_w extends WP_Widget {

    function __construct() {

    parent::__construct(
        	// Base ID of your widget
	        'agenda',

	        // Widget name will appear in UI
	        __('agenda', 'agenda_domain'),

	        // Widget description
	        array( 'description' => __( 'Zet de agenda erin.', 'krant-carousel_domain' ), )
        );

    }

    // Frontend
    public function widget($args, $instance) {

		$agenda = new Agenda(array(
			'aantal' => 5,
			'omgeving' => 'widget'
		));
		$agenda->print();


    }

    // Backend
    public function form($instance) {

	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		return $instance;
	}
}

class Ext_media_w extends WP_Widget {

    function __construct() {

	    parent::__construct(
	        	// Base ID of your widget
		        'ext_media',

		        // Widget name will appear in UI
		        __('externe media', 'ext_media_domain'),

		        // Widget description
		        array( 'description' => __( 'Plaats links naar andere media. Vul slechts zoveel media in als je wilt gebruiken.' ), )
	        );


    }

    public function zet_media_info($instance){
	    $this->media_info = array();

	    $aantal_media = (! empty( $instance['aantal_media'] ) ? $instance['aantal_media'] : 5) + 1;

		for ($i=1; $i < $aantal_media; $i++) {

			$link_s = "med_".$i."_link";
			$tekst_s = "med_".$i."_tekst";
			$medium_s = "med_".$i."_medium";

			$link_n = "medium ".$i." url";
			$tekst_n = "medium ".$i." linktekst";
			$medium_n = "medium ".$i." naam v medium";

			$this->media_info[] = array(
				$link_s => $this->pak_instance_waarde($instance, $link_s, $link_n),
				$tekst_s => $this->pak_instance_waarde($instance, $tekst_s, $tekst_n),
				$medium_s => $this->pak_instance_waarde($instance, $medium_s, $medium_n),
			);
		}
    }

    public function pak_instance_waarde($inst, $sleutel, $naam) {
    	return (! empty( $instance[$sleutel] ) ? $instance[$sleutel] : esc_html__( $naam, "ext_media_domain" ));
    }

    // Frontend
    public function widget($args, $instance) {

			$em = new Ext_media(array(
				'naam'		=> 'Vind ons op',
				'instance'	=> $instance
			));
			$em->zet_inhoud();
			$em->print();
    }

    // Backend
    public function form( $instance ) {

    	$this->zet_media_info($instance);

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'De titel', 'ext_media_domain' );

		$aantal_media = ! empty( $instance['aantal_media'] ) ? $instance['aantal_media'] : 5;

		?>

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Titel:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'aantal_media' ) ); ?>"><?php esc_attr_e( 'Aantal media:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'aantal_media' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'aantal_media' ) ); ?>" type="number" value="<?php echo esc_attr( $aantal_media ); ?>">
		</p>

		<?php foreach ($this->media_info as $mi) : foreach ($mi as $k=>$v) : ?>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( $k ) ); ?>"><?php esc_attr_e( "$v:", 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $k ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $k ) ); ?>" type="text" value="<?php echo esc_attr( $instance[$k] ); ?>">
			</p>
		<?php endforeach; endforeach;


	}

	public function update( $new_instance, $old_instance ) {

		$this->zet_media_info($old_instance);


		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['aantal_media'] = ( ! empty( $new_instance['aantal_media'] ) ) ? strip_tags( $new_instance['aantal_media'] ) : 5;


		foreach ($this->media_info as $mi) : foreach ($mi as $k=>$v) :
			$instance[$k] = ( ! empty( $new_instance[$k] ) ) ? strip_tags( $new_instance[$k] ) : '';
		endforeach; endforeach;

		return $instance;
	}
}

class Formulier_w extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'formulier',
			'description' => 'plak de contact form 7 shortcode hier.',
		);
		parent::__construct( 'formulier', 'formulier', $widget_ops );
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		if ( ! empty( $instance['shortcode'] ) ) {
			echo do_shortcode($instance['shortcode']);
		}
		echo $args['after_widget'];
	}

	public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<?php

		$shortcode = ! empty( $instance['shortcode'] ) ? $instance['shortcode'] : esc_html__( 'New shortcode', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'shortcode' ) ); ?>"><?php esc_attr_e( 'shortcode:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'shortcode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'shortcode' ) ); ?>" type="text" value="<?php echo esc_attr( $shortcode ); ?>">
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['shortcode'] = ( ! empty( $new_instance['shortcode'] ) ) ? strip_tags( $new_instance['shortcode'] ) : '';

		return $instance;
	}
}

class Theorienav_w extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'theorienav',
		);
		parent::__construct( 'theorienav', 'theorienav', $widget_ops );
	}

	public function widget( $args, $instance ) {

		//niet printen op theorie overzichtspagina.
		if ($GLOBALS['post']->post_type !== 'post' || is_category()) {
			return;
		}


	?>
	<section class='gerelateerd widget theorie-nav maagdelijk'>
		<header>
			<h2>Navigatie</h2>
		</header>
		<nav class='blok'>
		<a href='#' class='geen-titel links'>
			<i class='mdi mdi-arrow-left-thick'></i>
			<span class='screen-reader-text'>Scroll door lijst koppen naar links</span>
		</a>
			<?php

				$this->titel_decode = array();

				$pc = $GLOBALS['post']->post_content;
				$pt = $GLOBALS['post']->post_title;

				$expl = explode("<h2>", $pc);
				$titels = array();
				$temp = '';

				foreach ($expl as $e) {

					//eerste blok
					if (!strpos($e, "</h2>")) {
						$temp = $this->nepencode($pt);
						$titels[] = strip_tags($temp);
						$this->titel_decode[$temp] = $pt;

					} else {

						$e2 = explode("</h2>", $e);
						$blok_titel = strip_tags($e2[0]);
						$blok_rest = $e2[1];


						$h3_ar = array();

						if(strpos($blok_rest, "<h3>")) {
							$e3 = explode("<h3>", $blok_rest);

							foreach ($e3 as $ee) {
								if (!strpos($ee, "</h3>")) {
									continue;
								} else {
									$e4 = explode('</h3>', $ee);
									$temp = $this->nepencode($e4[0]);
									$h3_ar[] = $temp;
									$this->titel_decode[$temp] = $e4[0];
								}
							}

						}

						$temp = $this->nepencode($blok_titel);
						$this->titel_decode[$temp] = $blok_titel;

						$titels[] = array(
							'h2' => strip_tags($temp),
							'h3' => strip_tags($h3_ar)
						);

					}
				}


				$li_tel = 0;
				echo "<ol>";
				foreach ($titels as $t) {

					echo "<li class='".($li_tel < 1 ? "eerste" : "")."'>";

					if (!is_array($t) or empty($t['h3'])) {

						if (!is_array($t)) {
							echo $this->t_a_conv(strip_tags($t));
						} else {
							echo $this->t_a_conv(strip_tags($t['h2']));
						}

					} else {

						echo "<ul>";
						echo "<li class='eerste li-li'>";
						echo $this->t_a_conv(strip_tags($t['h2']));
						echo "</li>";

						foreach ($t['h3'] as $h3) {
							echo "<li>";
							echo $this->t_a_conv(strip_tags($h3));
							echo "</li>";
						}

						echo "</ul>";

					}
					echo "</li>";
					$li_tel++;
				}
				echo "</ol>";
			?>
		<a href='#' class='geen-titel rechts'>
			<i class='mdi mdi-arrow-right-thick'></i>
			<span class='screen-reader-text'>Scroll door lijst koppen naar rechts</span>
		</a>
		</nav>

	</section>
	<?php
	}

	public function nepencode($bla) {
		return preg_replace('~[^\p{L}\p{N}]++~u', '', $bla);
	}

	public function t_a_conv ($t) {

		$tt = preg_replace('/\W+/', '', $t);

		return "<a href='#$tt'>".$this->titel_decode[$t]."</a>";
	}


	public function form( $instance ) { ?>

	<p>geen invoervelden hier.</p>.

	<?php }

	public function update( $new_instance, $old_instance ) {
	}
}

class Pag_familie_w extends WP_Widget {

    function __construct() {

    parent::__construct(
        	// Base ID of your widget
	        'Pag_familie_w',

	        // Widget name will appear in UI
	        __('pagina familie', 'pagina_familie_domain'),

	        // Widget description
	        array( 'description' => __( 'als een pagina onderdeel van een familie is wordt deze widget actief.', 'pagina_familie_domain' ), )
        );

    }

    // Frontend
    public function widget($args, $instance) {

    	$fam = new Pag_fam('Lees verder');
    	$fam->maak();
		$fam->print();

    }

    // Backend
    public function form($instance) {

	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		return $instance;
	}
}

// Register and load the widget
function widget_wrap() {
    register_widget( 'zijbalk_posts_w' );
    register_widget( 'krant_carousel' );
    register_widget( 'agenda_w' );
    register_widget( 'ext_media_w' );
    register_widget( 'formulier_w' );
    register_widget( 'theorienav_w' );
    register_widget( 'pag_familie_w' );
}

add_action( 'widgets_init', 'widget_wrap' );


