
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
    iconSize: [20, 20],
    className: 'ping ping2'
});
var redIcon = L.icon({
    iconUrl: 'img/Aqua-Ball-Red-icon.png',
    iconSize: [20, 20],
    className: 'ping ping2'
});

var realtime = L.realtime({
    url: 'http://97.107.133.58/status',
    crossOrigin: true,
    type: 'json'
    }, {
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

      // create a red polygon from an array of LatLng points
      // var coords1 = [[35.6850, 139.7514], [1.2855, 103.8565]];
      // var coords12 = [[35.6850, 139.7514], [37.5670, -121.9829]];
      // var coords2 = [[37.5670, -121.9829], [40.7357, -74.1724]];
      // var coords23 = [[37.5670, -121.9829], [32.7787, -96.8217]];
      // var coords3 = [[50.1167, 8.6833], [32.7787, -96.8217]];
      // var coords4 = [[51.5092, -0.0955], [33.7490, -84.3880]];
      // var polygon1 = L.polygon(coords1, {color: '#F6BBC2'}).addTo(map);
      // var polygon12 = L.polygon(coords12, {color: '#FFFFFF'}).addTo(map);
      // var polygon2 = L.polygon(coords2, {color: '#EF8591'}).addTo(map);
      // var polygon23 = L.polygon(coords23, {color: '#B2E384'}).addTo(map);
      // var polygon3 = L.polygon(coords3, {color: '#F6BBC2'}).addTo(map);
      // var polygon4 = L.polygon(coords4, {color: '#B2E384'}).addTo(map);

      return marker;
    }

}).addTo(map);


realtime.on('update', function() {
    map.fitBounds(realtime.getBounds(), {maxZoom: 20});
});
