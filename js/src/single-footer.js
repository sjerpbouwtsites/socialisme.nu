function printAgendaIntegratie(kalenderPrint){

	var kalData = document.getElementById('kalender-data');

	var myCalendar = createCalendar({
	  options: {
	    class: 'my-class',

	    // You can pass an ID. If you don't, one will be generated for you
	    id: 'my-id'
	  },
	  data: {
	    // Event title
	    title: kalData.getAttribute('data-title'),

	    // Event start date
	    start: new Date(pAIhulp(kalData.getAttribute('data-start'))),

	    // Event duration (IN MINUTES)
	    duration: Number(kalData.getAttribute('data-duration')),

	    // You can also choose to set an end time
	    // If an end time is set, this will take precedence over duration
	    //end: new Date('June 15, 2013 23:00'),

	    // Event Address
	    address: kalData.getAttribute('data-address'),

	    // Event Description
	    description: kalData.getAttribute('data-description'),
	  }
	});

	kalenderPrint.appendChild(myCalendar);
}

function pAIhulp(datum){
	var s = datum.split(' ');

	var nlMaand = s[1];

	//maand omzetten
	var m = {
		'januari': 		"January",
		'februari': 	"February",
		'maart': 		"March",
		'april': 		"April",
		'mei': 			"May",
		'juni': 		"June",
		'juli': 		"July",
		'augustus': 	"August",
		'september': 	"September",
		'oktober': 		"October",
		'november': 	"November",
		'december':		"December"
	};

	if (nlMaand in m) {
		s[1] = m[nlMaand];
	}

	//volgorde omdraaien.
	var h = s[0];
	s[0] = s[1];
	s[1] = h;

	s[1] = s[1] + ","; //anders snapt datum t niet.

	var r = s.join(' ');
	return r;

}