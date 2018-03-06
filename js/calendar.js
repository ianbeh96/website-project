var map;
var marker2;
var service;
var infowindow;
var infowindow2;
var umn = {lat: 44.9739602, lng: -93.2330897};
var directionsDisplay;
var directionsService;
var num_rows = document.getElementsByTagName("tbody")[0].children.length;
function initMap() {
  directionsDisplay = new google.maps.DirectionsRenderer;
  directionsService = new google.maps.DirectionsService;
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 14,
    center: umn
  });
  var geocoder = new google.maps.Geocoder();
  var address = getLocations();
  var events_ = getEvent();
  for (i = 0; i < address.length; i++) {
    address[i] += "UMN";
  }
  for (i = 0; i < address.length; i++) {
    for (j = 0; j < events_.length; j++) {
      if (events_[j][1] == address[i].substring(0, address[i].length - 3)) {
        geocodeAddress(geocoder, map, address[i], events_[j][0]);
        j = j + 1000;
      }
    }
  }
  service = new google.maps.places.PlacesService(map);
  directionsDisplay.setMap(map);
  directionsDisplay.setPanel(document.getElementById("panel"));
}

function getLocations() {
  var regex = /^[\s]*-[\s]*$/;
  var locations = [];
  for (i = 1; i < (num_rows * 2); i += 2) {
    var row_elem = document.getElementsByTagName("tbody")[0].childNodes[i].children.length - 1;
    for (j = 3; j < 3 + (Math.pow(row_elem, 2) - Math.pow(row_elem - 1, 2)); j += 2) {
      if (!document.getElementsByTagName("tbody")[0].childNodes[i].childNodes[j].textContent.match(regex)) {
        var place = document.getElementsByTagName("tbody")[0].childNodes[i].childNodes[j].childNodes[3].textContent.split("-")[1];
        if (locations.indexOf(place) == -1) {
          locations.push(place);
        }
      }
    }
  }
  return locations;
}

function getEvent() {
  var regex = /^[\s]*-[\s]*$/;
  var a = [];
  for (i = 1; i < (num_rows * 2); i += 2) {
    var row_elem = document.getElementsByTagName("tbody")[0].childNodes[i].children.length - 1;
    for (j = 3; j < 3 + (Math.pow(row_elem, 2) - Math.pow(row_elem - 1, 2)); j += 2) {
      if (!document.getElementsByTagName("tbody")[0].childNodes[i].childNodes[j].textContent.match(regex)) {
        var b = document.getElementsByTagName("tbody")[0].childNodes[i].childNodes[j].childNodes[3].textContent.split("-")[1];
        if (a.indexOf(b) == -1) {
          a.push(document.getElementsByTagName("tbody")[0].childNodes[i].childNodes[j].childNodes[3].textContent.split("-"));
        }
      }
    }
  }
  return a;
}

function geocodeAddress(geocoder, resultsMap, address, events_) {
  geocoder.geocode({'address': address}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      resultsMap.setCenter(results[0].geometry.location);
      marker2 = new google.maps.Marker({
        map: resultsMap,
        position: results[0].geometry.location,
        title:address
        });
      marker2.setAnimation(google.maps.Animation.BOUNCE);
      var contentstring = "Name: " + address.substring(1, address.length - 4) + "<br><br>" + "Event: " + events_;
      infowindow2 = new google.maps.InfoWindow ({
        content: contentstring
        });
      google.maps.event.addListener(marker2, 'click', createWindow(resultsMap, infowindow2, marker2));
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

function createWindow(rmap, rinfowindow, rmarker){
  return function() {
    rinfowindow.open(rmap, rmarker);
  }
}

function findrestaurant() {
  var rad = document.getElementById("radius").value;
  var request = {
    location: umn,
    radius: rad,
    type: ["restaurant"]
  };
  service.nearbySearch(request, callback);
}

function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      createMarker(results[i]);
    }
  }
}

function createMarker(place) {
  var marker = new google.maps.Marker ({
    map: map,
    position: place.geometry.location
  });
  var contentstring = "Name: " + place.name + "<br><br>" +  "Address: " + place.vicinity;
  var infowindow = new google.maps.InfoWindow ({
    content: contentstring
  })
  google.maps.event.addListener(marker, 'click', createWindow(map, infowindow, marker));
}

function direction() {
  var initpos;
  var latlng;
  var geocoder = new google.maps.Geocoder();
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      latlng = {lat: pos.lat, lng: pos.lng};
      geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
          if (results[0]) {
            initpos = results[0].formatted_address;
            var destination = document.getElementById("destination").value;
            geocoder.geocode({'address': destination}, function(results, status) {
              if (status === google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
              } else {
                alert('Geocode was not successful for the following reason: ' + status);
              }
            });
            var transport = document.querySelector(".transport:checked").value;
            directionsService.route({
              origin: initpos,
              destination: document.getElementById("destination").value,
              travelMode: transport
            }, function(response, status) {
              if (status === 'OK') {
                directionsDisplay.setDirections(response);
              } else {
                window.alert('Directions request failed due to ' + status);
              }
            });
          } else {
            window.alert('No results found');
          }
        } else {
          window.alert('Geocoder failed due to: ' + status);
        }
      });
      map.setCenter(pos);
    }, function() {
      handleLocationError(true, infowindow, map.getCenter());
    });
  } else {
    handleLocationError(false, infowindow, map.getCenter());
  }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
  infoWindow.open(map);
}
