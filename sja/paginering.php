<div class="navigation"><ul>
<?php
//vorige pagina
if ($pagi_prev_link) printf( '<li>%s</li>' . "\n", $pagi_prev_link_res );

$srt = "<span class='screen-reader-text'>pagina</span>";

//pagina 1, als niet al in links.
if ( ! in_array( 1, $pagi_links ) ) {
    $class = 1 == $pagi_paged ? ' class="active"' : '';
    printf( '<li%s><a href="%s">'.$srt.'%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
    if ( ! in_array( 2, $pagi_links ) ) echo '<li>…</li>';
}

//huidige pagina en die eromheen
foreach ( (array) $pagi_links as $link ) {
    $class = $pagi_paged == $link ? ' class="active"' : '';
    printf( '<li%s><a href="%s">'.$srt.'%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
}

//laatste pagina, als in al in links.
if ( ! in_array( $pagi_max, $pagi_links ) ) {
    if ( ! in_array( $pagi_max - 1, $pagi_links ) )
        echo '<li>…</li>' . "\n";
    $class = $pagi_paged == $pagi_max ? ' class="active"' : '';
    printf( '<li%s><a href="%s">'.$srt.'%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $pagi_max ) ), $pagi_max );
}

//volgende pagina
if ( $pagi_volgende_link ) {
    printf( '<li>%s<span class="screen-reader-text">volgende pagina</span></li>' . "\n", $pagi_volgende_link_res );
} ?>

</ul></div>