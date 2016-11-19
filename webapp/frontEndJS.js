function initMap() {
    var initialLocation = {lat: -6.813111, lng: 39.304491};

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: initialLocation
    });

    //checkArray();

    //createEmergencyMarker(-6.813111, 39.314491,map);
    //createEmergencyRadius(-6.813111, 39.314491,map);
    createMarker(-6.813111, 39.324491,map);
}

function checkArray(){
    var long = "<?php echo $vessels[0] ?>";
    console.log('LONG IS' + long);
}

function createMarker(latitude, longitude,map){
    var location = {lat: latitude, lng: longitude};
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        icon: 'icon.png'
    });
}
function createEmergencyMarker(latitude, longitude,map){
    var location = {lat: latitude, lng: longitude};
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        label: label,
        icon: 'http://maps.google.com/mapfiles/kml/shapes/caution.png'
    });
}

function createEmergencyRadius(lat,lng,map){
    var circle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        map: map,
        center: {lat: lat, lng:lng},
        radius: 1000
    });
}
