/* eslint-disable */
function grootte(e) {

	var
	doel = actieInit(e, 'grootte'),
	huiGrootte = (html.hasAttribute('data-grootte') ? Number(html.getAttribute('data-grootte')) : 0),
	zetGrootte;

	if (huiGrootte < 2 && doel.classList.contains('meer')) {
		zetGrootte = huiGrootte + 1;
	} else if (huiGrootte > 0 && doel.classList.contains('minder')) {
		zetGrootte = huiGrootte - 1;
	} else if (doel.classList.contains('herstel')) {
		zetGrootte = 0;
	} else {
		zetGrootte = huiGrootte;
	}
	html.setAttribute('data-grootte', zetGrootte);
	if (sessionStorage) {
		sessionStorage.setItem('grootte', zetGrootte);
	}
}

function contrast(e) {

	var
	doel = actieInit(e, 'contrast'),
	huiContrast = (html.hasAttribute('data-contrast') ? Number(html.getAttribute('data-contrast')) : 0),
	zetContrast;

	if (!huiContrast && doel.classList.contains('meer')) {
		zetContrast = 1;
	} else if (huiContrast && doel.classList.contains('meer')) {
		//alert('Contrast kan niet groter.');
		zetContrast = huiContrast;
	} else if (doel.classList.contains('herstel')) {
		zetContrast = 0;
	}

	html.setAttribute('data-contrast', zetContrast);
	if (sessionStorage) {
		sessionStorage.setItem('contrast', zetContrast);
	}
}

function dyslexie (e) {
	var
	doel = actieInit(e, 'dyslexie');
	installeerDyslexie();
	if (doel.classList.contains('meer')) {
		html.setAttribute('data-dyslexie', '1');

		var huiGrootte = html.hasAttribute('data-grootte') ? Number(html.getAttribute('data-grootte')) : 0;
		if (!huiGrootte) {
			html.setAttribute('data-grootte', 1);
			sessionStorage.setItem('grootte', 1);
		}
		if (sessionStorage) {
			sessionStorage.setItem('dyslexie', 1);
		}
	} else {
		html.setAttribute('data-dyslexie', '0');
		if (sessionStorage) {
			sessionStorage.setItem('dyslexie', 0);
		}
	}

}

function installeerDyslexie(){

	if (document.getElementById('dyslexie')) {
		return;
	}

	var dys = document.createElement('style');

	var beginFF = "@font-face {font-family: 'Dyslexie';src: url('";
	var fontUri = TEMPLATE_URL + "/lettertypen/";

	var t = beginFF+fontUri+"OpenDyslexic-Bold.otf');}";
	t += beginFF+fontUri+"OpenDyslexic-BoldItalic.otf');}";
	t += beginFF+fontUri+"OpenDyslexic-Italic.otf');}";
	t += beginFF+fontUri+"OpenDyslexic-Regular.otf');}";

	dys.textContent = t;
	dys.setAttribute('id', 'dyslexie');
	doc.getElementsByTagName('head')[0].appendChild(dys);


}

function toegankelijkheidInit(){
	if (sessionStorage) {

		var g = sessionStorage.getItem('grootte');
		if (g) {
			html.setAttribute('data-grootte', g);
		}
		var c = sessionStorage.getItem('contrast');
		if (c) {
			html.setAttribute('data-contrast', c);
		}
		var d = sessionStorage.getItem('dyslexie');
		if (d) {
			html.setAttribute('data-dyslexie', d);
			if (d == 1) installeerDyslexie();
		}
	}

	$('#toegankelijkheidsschakel').on('keydown', function(event){
	    if (event.which == 13 || event.keyCode == 13) {
	        var tglabel = document.getElementById('toegankelijkheidslabel');
	        if (tglabel.hasAttribute('data-tekst')) {
	        	var tekst = tglabel.getAttribute('data-tekst');
	        	tekst.innerHTML = tekst;
	        	tekst.removeAttribute('data-tekst');
	        }
	    }

	});

}