function agendaFilter(){

	var $af = $("#agenda-filter");

	$af.on('click', 'select', function(e){
		$(e.target).addClass('geklikt');
	});

	$af.on('submit', function(e){
	/*	e.preventDefault();

		//@TODO gebaseerde op verrotte permalinks
		//get variabelen  post type erachter

		console.log(BASE_URL);

		var
		loc = location.href,
		s = loc.split('?'),
		action = BASE_URL,
		getVars = s[1].split('&');



		for(i = 0; i < getVars.length; i++) {
			var getKey = getVars[i].split('=')[0];
			if (getKey === 'post_type' || getKey === 'archief') {
				action += "?"+getVars[i];
			}
		}

		//filter waarden erachter plakken.
		$('.agenda-filters').each(function(){
			var _this = $(this);
			var keuze = _this.val();
			if (keuze) {
				action += "&" + this.name + "=" + keuze;
			}

		});

		//en gaan.

		if (confirm(action)) {
			location.href = action;
		}*/



	});
}



function maakKaart(kaartEl) {

	var
	mbToken = "pk.eyJ1Ijoic2plcnAtdmFuLXdvdWRlbiIsImEiOiJjajh5NmExaTAxa29iMzJwbDV0eXF4eXh4In0.HVBgF1SbusJzMwmjHcHS2w",
	lat = kaartEl.getAttribute('data-lat'),
	long = kaartEl.getAttribute('data-long'),
	zoom = kaartEl.getAttribute('data-zoom'),
	dezeKaart = L.map(kaartEl.getAttribute('id')).setView([lat, long], zoom);

	L.tileLayer('https://{s}.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+mbToken, {
	    attribution: '',
	    maxZoom: 19,
	    id: 'mapbox.streets',
	    accessToken: mbToken,
	}).addTo(dezeKaart);

	var LeafIcon = L.Icon.extend({
	    options: {
	       iconSize:     [30, 30],
	       iconAnchor:   [15, 15],
	       alt: 'popup van de kaart',
	       popupAnchor:  [-3, -30]
	    }
	});

	var sterIkoon = new LeafIcon({
	    iconUrl: IMG_URL+'ster-klein.png',
	    alt: 'pin op kaart'
	});

	var marker = L.marker([lat, long], {icon: sterIkoon}).addTo(dezeKaart);

	var markerTekst = "<h3>"+kaartEl.getAttribute('data-titel')+"</h3>";

	var kalData = document.getElementById('kalender-data');

	if (kalData) markerTekst += kalData.getAttribute('data-address');

	marker.bindPopup(markerTekst).openPopup();


	// dan maar met de hand ok
	document.querySelector('.leaflet-marker-icon').setAttribute('alt', 'pin op de kaart');

}