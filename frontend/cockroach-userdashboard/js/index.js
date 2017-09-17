var maxBounds =  L.latLngBounds(
  L.latLng(5.499550, -167.276413),
  L.latLng(83.162102, -52.233040)
);

//var map = L.map('map').setView([48.517,18.255], 5);

var map = L.map('map',{ zoomControl:false, attributionControl: false, }).setView([37.8, -96], 4);;

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var transactionLayer = L.layerGroup();
var greenIcon = L.icon({
    iconUrl: 'img/Aqua-Ball-Green-icon.png',
    iconSize: [20, 20]
});
var redIcon = L.icon({
    iconUrl: 'img/Aqua-Ball-Red-icon.png',
    iconSize: [20, 20]
});

var realtime = L.realtime({
    url: 'http://97.107.133.58/status',
    crossOrigin: true,
    type: 'json'
    }, {
    interval: 1 * 1000,
    getFeatureId: function(featureData){
        return featureData.id;
    },
    pointToLayer: function (feature, latlng) {
        if (feature.properties.Status == "red") {
            marker = L.marker(latlng, {icon: redIcon});
        }
        if (feature.properties.Status == "green") {
            marker = L.marker(latlng, {icon: greenIcon});
        }

        var status = feature.properties.Status == 'green' ? 'Active' : 'Down';

        marker.bindPopup('Name: ' + feature.properties.Name +
                       '<br/> Status: ' + status);
        marker.addTo(transactionLayer);
        return marker;
    }

}).addTo(map);

realtime.on('update', function() {
    map.fitBounds(realtime.getBounds(), {maxZoom: 20});
});
