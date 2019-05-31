/*eslint-disable*/
function asideAfbSchakel(art){
	//schakelt afbeelding en link bovenaan sectie
	var dataSrc = art.getAttribute('data-src');
	var img = art.parentNode.querySelector('article img');

	if (dataSrc === img.src) {
		return;
	} else {
		img.src = dataSrc;
		img.removeAttribute('srcset');
		img.parentNode.setAttribute('href', art.querySelector('a').href);
	}
}

function widgetAfbWissel() {

	var asideArts = aside.getElementsByTagName('article');
	for (i = asideArts.length - 1; i >= 0; i--) {
		asideArts[i].addEventListener('mouseenter', function(e){
			if (e.target.hasAttribute('data-src')) {
				asideAfbSchakel(e.target);
			}
		});
	}

}

function theorieNav(){

	var scrollInterval;


	// fix  voor als toch strong is gebruikt
	$('.widget.theorie-nav a').each(function(){

	  	if ($(this).find('strong').length) {
	  		var ttt = $(this).find('strong').text();
	  		$(this).find('strong').remove();
	  		$(this).append(ttt);
	  		$(this).attr('href', '#'+ttt);
	    }
	});


	var alleKoppen = $('.theorie-nav a').map(function(){



	    var r = this.href.split('#')[1];
		if (r) {
			var ret = {
				id: r,
				el: this,
				kopSchermOffset: $('#' + r).offset().top,
			};
			return ret;
		}
	});

	$(".theorie-nav").on('click', 'a', function(e){

		if (this.classList.contains('geen-titel')) return;

		e.preventDefault();
		clearInterval(scrollInterval);
		var $el = $('#'+this.href.split('#')[1]);

		if (!$el.length) $el = $('h1');

	    $('html, body').animate({
	        scrollTop: $el.offset().top - $('#stek-kop').height() - 10
	    }, 600);

		if (window.innerWidth < 900) {
			theorieMenuAnimMob(this);
		} else {
			//theorieMenuAnimDesk(this);
		}

		zetScrollInterval(alleKoppen);

	});

	if (window.innerWidth < 900) {

		$(".geen-titel").on('click', function(e){

			e.preventDefault();

			var
			_a = this,
			alleAnchors = [],
			huidigeIndex;


			$(".theorie-nav li a").each(function(i, el){
				alleAnchors.push(this);
				if (el.classList.contains('huidige')) huidigeIndex = i;
			});

			if (_a.classList.contains('links')) {

				if (huidigeIndex === 0) {
					return;
				} else {
					alleAnchors[huidigeIndex - 1].click();
				}

			} else {

				if (huidigeIndex === (alleAnchors.length -1)) {
					return;
				} else {
					alleAnchors[huidigeIndex + 1].click();
				}

			}




/*			var
			$ol = $('.theorie-nav ol'),
			curL = Number($ol.css('left').replace('px', '')),
			komL = curL + Number(this.getAttribute('data-scroll')),
			olW = Number($ol.css('width').replace('px', ''));

			if (komL > 0) {
				komL = 0;
			}

			$ol.css({left: komL+'px'});*/
		});
	}

	scrollInterval = zetScrollInterval(alleKoppen);

}

function zetScrollInterval(alleKoppen) {
	var scrollInterval = setInterval(function(){
		var
		refScrollPos = $(document).scrollTop() + window.innerHeight * 0.25,
		d;

		for (i = 0; i < alleKoppen.length; i++) {
			d = alleKoppen[i];
			if (d.kopSchermOffset > refScrollPos) {
				if (i > 0) d = alleKoppen[(i-1)];
				break;
			}
		}

		$('.theorie-nav').removeClass('maagdelijk');

		$('.theorie-nav a.huidige').removeClass('huidige');
		d.el.classList.add('huidige');

		if (window.innerWidth < 900) {
			theorieMenuAnimMob(d.el);
		}

	}, 250);
	return scrollInterval;
}

function theorieMenuAnimGen(el){
	var tn = $(".theorie-nav");
	tn.removeClass('maagdelijk');
	tn.find('.huidige').removeClass('huidige');
	el.classList.add('huidige');
}

function theorieMenuAnimMob(el){
	var tn = $(".theorie-nav");
	tn.removeClass('maagdelijk');

	var $prev;
	var par = el.parentNode;

	if (par.classList.contains('eerste')) {

		if (par.classList.contains('li-li')) {
			$prev = $(par.parentNode.parentNode).prev();
		} else {
			$prev = $(el); //hacky
		}
	} else {

		var p = $(par).prev();
		if (!p.find('ul').length) {
			$prev = p;
		} else {
			$prev = p.find('ul li').last();
		}

	}

	var $ol = $(el).closest('ol');
	var os = $prev[0].offsetLeft - 40;
	$ol.css({left: (-1*os) + "px"});
}

function titelId(art){

	var titelGetal, titelEls;

	for (titelGetal = 1; titelGetal < 7; titelGetal++) {

		titelEls = art.getElementsByTagName('h'+titelGetal);

		for (i = titelEls.length - 1; i >= 0; i--) {
			titelEls[i].setAttribute('id', titelEls[i].textContent.replace(/\W+/g, ""));
		}

	}

}