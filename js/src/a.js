/*eslint-disable*/
var doc, body, html, aside, i, l;

function klikBaas(){

	body.addEventListener('click', function(e){

		var
		funcNamen = ['schakel', 'scroll', 'grootte', 'contrast', 'dyslexie'],
		f;

		for (var i = funcNamen.length - 1; i >= 0; i--) {
			f = funcNamen[i];
			if (e.target.classList.contains(f) || e.target.parentNode.classList.contains(f)) {
				window[f](e);
			}
		}

	});

}

function carouselInit(){
	var
	wi = window.innerWidth,
	s = wi > 899 ? 3 : wi > 599 ? 2 : 1;

	$('.carousel').slick({
	  infinite: true,
	  slidesToShow: s,
	  slidesToScroll: s,
	  prevArrow: "<i class='mdi mdi-chevron-left'></i>",
	  nextArrow: "<i class='mdi mdi-chevron-right'></i>",
	});
}

function kopieerShare(shareDaddy){
	$s = shareDaddy.first();
	if ($s.length) {
		$("#footer-share-kopie").append($s.clone());
		var a = $(".artikel-meta.blok");
		if (a.length) {
			a.after($s.clone());
		} else {
			$(".onder-afb").append($s.clone());
		}
		$s.remove();
	}

	// titel toegankelijker maken
	var alleShareTitels = document.querySelectorAll('.sd-title');
	for (var i = alleShareTitels.length - 1; i >= 0; i--) {
		alleShareTitels[i].innerHTML = alleShareTitels[i].textContent.replace('.', '') + "<span class='screen-reader-text'>op sociale media</span>.";
	}
}


function init() {
	doc = document;
	body = doc.getElementsByTagName('body')[0] || null;
	html = doc.getElementsByTagName('html')[0] || null;
	aside = doc.getElementById('zijbalk') || null;
}

function verschrikkelijkeHacks(){

	if (aside) {
		var
		l = aside.getElementsByTagName('section').length;
		l -= aside.getElementsByClassName('theorie-nav').length; //theorie nav gaat naar pos fixed.

		var
		c = (l%2 === 0 ? 'even' : 'oneven');

		aside.classList.add('sectietal-'+c);
	}

}


window.onload = function(){

	init();

	toegankelijkheidInit();

	klikBaas();

	verschrikkelijkeHacks();

	var shareDaddy = $('.sharedaddy');
	if (shareDaddy.length) kopieerShare(shareDaddy);


	var art = doc.querySelector('main article');
	if (art) titelId(art);

	if (doc.getElementById('agenda-filter')) agendaFilter();

	if (doc.getElementsByClassName('carousel')[0]) carouselInit();

	var kalenderPrint = doc.getElementById('kalender-print');
	if (kalenderPrint) printAgendaIntegratie(kalenderPrint);

	if (!!document.querySelector('.widget.theorie-nav')) theorieNav();

	if (aside) widgetAfbWissel();

	//na alle rendering:
	var onderschrift = doc.getElementsByClassName('onderschrift')[0];
	if (onderschrift) onderschriftAchtergrond(onderschrift);

	var agendaKaart = document.getElementById('agenda-kaart');
	if (agendaKaart) maakKaart(agendaKaart);



};