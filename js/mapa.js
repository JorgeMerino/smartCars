function iniciarMapa() {
	var propiedad_mapa = {
		center: {lat: 40.0098132, lng: -3.0181761},
		zoom: 7,
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	//Se muestra en los id's #mapa
	var mapa = new google.maps.Map(document.getElementById("mapa"), propiedad_mapa);
	
	var marcadorMadrid = new google.maps.Marker({
		position: {lat: 40.4292841, lng: -3.7158331},
		title: "Madrid - C/Princesa 42"
	});
	marcadorMadrid.setMap(mapa);
	
	var marcadorValencia = new google.maps.Marker({
		position: {lat: 39.4703043, lng: -0.4105284},
		title: "Valencia - Av/del Cid 138"
	});
	marcadorValencia.setMap(mapa);
}
google.maps.event.addDomListener(window, "load", iniciarMapa);