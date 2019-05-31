<?php
ob_start();
the_post_thumbnail('bovenaan_art');
$img = ob_get_clean();

if ($img !== '') {

	echo voeg_attr_in($img, "itemprop='image'");

	$doc = new DOMDocument();
	$doc->loadHTML('<?xml encoding="UTF-8">' . $img);
	$xpath = new DOMXPath($doc);
	$desc = strip_tags($xpath->evaluate("string(//img/@data-image-description)"), "<br>");

	if ($desc !== '') echo "<span class='onderschrift'>".($desc !== '' ? $desc : '')."</span>";
}