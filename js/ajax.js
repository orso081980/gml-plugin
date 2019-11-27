
var path = window.location.protocol + "//" + window.location.host + "/";

var map;

function initMap() {

	map = new google.maps.Map(document.getElementById('gml-map'), {
		center: {
			lat: 39.215028,
			lng: 9.125978
		},
		zoom: 17,
		mapTypeControlOptions: {
			mapTypeIds: ['satellite', 'styled_map']
		}
	});

	$.ajax({
		url: path+'/wp-content/plugins/google-map-locations/js/maps.json',
		method: "GET",
		dataType: 'json',
		success: function(result){
			var styledMapType = new google.maps.StyledMapType(result, {
				name: 'Map'
			});
			map.mapTypes.set('styled_map', styledMapType);
			map.setMapTypeId('styled_map');
		},
		error:function() {
			console.log("Error");               
		}
	});

	$.ajax({
		type: "GET",
		url: my_ajax_object.ajax_url,
		data: { 'action': 'gml_show' },
		success:function(data) {
			const locations = JSON.parse(data);
			Object.keys(locations).forEach(function(key) {
				marker = new google.maps.Marker({
					position: new google.maps.LatLng(locations[key].lat, locations[key].lng),
					map: map
				});
				marker.setMap(map);
				var infowindow = new google.maps.InfoWindow();
				var marker;
				google.maps.event.addListener(marker, 'click', () => {
					infowindow.setContent(
						"<h2>"+locations[key].name+"</h2>"+
						"<p>"+locations[key].html+"</p>"
						);
					infowindow.open(map, marker);
				});
			});
		}
	});

}

initMap();
