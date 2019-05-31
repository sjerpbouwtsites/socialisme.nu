/*eslint-disable*/
function schakel(e) {

	var
	doel = actieInit(e, 'schakel'),
	toon = doc.querySelectorAll( doel.getAttribute('data-toon') ),
	antiSchakel,
	anti = [],
	i;

	if (doel.hasAttribute('data-doorschakel')) {
		doc.querySelector(doel.getAttribute('data-doorschakel')).click();
		return;
	}

	if (doel.hasAttribute('data-anti')) {

		antiSchakel = doc.querySelectorAll(doel.getAttribute('data-anti'));
		var ai;
		for (i = antiSchakel.length - 1; i >= 0; i--) {
			ai = antiSchakel[i];
			ai.classList.remove('open');
			body.classList.remove(ai.id+'-open');
			anti.push(doc.querySelectorAll( ai.getAttribute('data-toon')) );

			if (ai.hasAttribute('aria-expanded')){
				ai.setAttribute('aria-expanded', 'false');
			}
		}
	}

	//tonen of verstoppen afhankelijk van open
	var stijl = '';
	if (!doel.classList.contains('open')) {
		if(!body.classList.contains(doel.id+'-open')) {
			body.classList.add(doel.id+'-open');
		}
		stijl = "block";
		if (doel.hasAttribute('aria-expanded')){
			doel.setAttribute('aria-expanded', 'true');
		}
	} else {
		stijl = "none";
		body.classList.remove(doel.id+'-open');
		if (doel.hasAttribute('aria-expanded')){
			doel.setAttribute('aria-expanded', 'false');
		}
	}

	if (toon) {
		if (toon[0]) {
			toon[0].setAttribute('tabindex', '-1');
		}
		zetStijl(toon, 'display', stijl);
		if (toon[0]) {
			toon[0].focus();
		}


	}
	if (anti.length) {
		for (i = anti.length - 1; i >= 0; i--) {
			zetStijl(anti[i], 'display', 'none');
		}
	}

	doel.classList.toggle('open');


	if (doel.hasAttribute('data-f')) {
		schakelExtra[doel.getAttribute('data-f')]();
	}
}

var schakelExtra = {
	focusZoekveld: function(){
		doc.getElementById('zoekveld').getElementsByTagName('input')[0].focus();
	},
};

function scroll(e) {

	var scrollNaar;

	//var werkMet = e.target.classList.contains('knop') ? e.target : e.target.parentNode;
	var werkMet = actieInit(e, 'scroll');

	if (werkMet.hasAttribute('doel')) {

		scrollNaar = werkMet.getAttribute('doel');

	} else if (werkMet.hasAttribute('href')) {

		scrollNaar = werkMet.getAttribute('href');

	} else {

	}

	scrollNaar = scrollNaar.split('#')[1];

    $('html, body').animate({
        scrollTop: $(scrollNaar).offset().top - $('#stek-kop').height() - 10
    }, 600);
}