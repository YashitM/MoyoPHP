//Google Maps
if (window.location.href.indexOf("takerides") !== -1 || window.location.href.indexOf("offerrides") !== -1 || window.location.href.indexOf("edit_ride") !== -1) {
    
    defaultLatLong = {lat: 12.978718, lng: 77.589731};

    var map = new google.maps.Map(document.getElementById('map'), {
      center: defaultLatLong,
      zoom: 13,
      mapTypeId: 'roadmap'
    });

    var input = document.getElementById('location_input');

    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds',map);
    // map.controls[google.maps.ControlPosition.TOP_LEFT].push(card);

    var marker = new google.maps.Marker({
        map: map,
        position: defaultLatLong,
        draggable: true,
        clickable: true
    });

    var currentLongitude = 0.0;
    var currentLatitude = 0.0;

    google.maps.event.addListener(marker, 'dragend', function(marker){
        var latLng = marker.latLng;
        currentLatitude = latLng.lat();
        currentLongitude = latLng.lng();
        var latlng = {lat: currentLatitude, lng: currentLongitude};
        var geocoder = new google.maps.Geocoder;
        geocoder.geocode({'location': latlng}, function(results, status) {
              if (status === 'OK') {
                if (results[0]) {
                  input.value = results[0].formatted_address;
                } else {
                  window.alert('No results found');
                }
              } else {
                window.alert('Geocoder failed due to: ' + status);
              }
            });
    });


    google.maps.event.addListener(autocomplete, "place_changed", function()
    {
        var place = autocomplete.getPlace();

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(15);
        }

        marker.setPosition(place.geometry.location);
        currentLatitude = marker.getPosition().lat();
        currentLongitude = marker.getPosition().lng();
    });

    var clicked_by = "";

    $("#myModal").on("shown.bs.modal", function (e) {
        google.maps.event.trigger(map, "resize");
        map.setCenter(defaultLatLong);
        clicked_by = e.relatedTarget.id;
    });

    $('#myModal').on('hidden.bs.modal', function (e) {
        var text = document.getElementById("location_input").value;
        if (clicked_by === "source_location_button") {
            document.getElementById('id_source_location').value = text;
            // console.log(currentLongitude)
            // console.log(currentLatitude)
            document.getElementById('id_sou_lati').value = currentLatitude;
            document.getElementById('id_sou_long').value = currentLongitude;
        }
        else if(clicked_by === "destination_location_button") {
            document.getElementById('id_destination_location').value = text;
            // console.log(currentLongitude)
            // console.log(currentLatitude)
            document.getElementById('id_des_lati').value = currentLatitude;
            document.getElementById('id_des_long').value = currentLongitude;
        }
    });

    $("#myModal2").on("shown.bs.modal", function (e) {
        google.maps.event.trigger(map, "resize");
        map.setCenter(defaultLatLong);
        clicked_by = e.relatedTarget.id;
        console.log(clicked_by)
    });

    $('#myModal2').on('hidden.bs.modal', function (e) {
        var text = document.getElementById("location_input").value;
        if (clicked_by === "source_location_button_take_ride") {
            document.getElementById('id_source_location').value = text;
            // console.log(currentLongitude)
            // console.log(currentLatitude)
            document.getElementById('id_sou_lati').value = currentLatitude;
            document.getElementById('id_sou_long').value = currentLongitude;
        }
        else if(clicked_by === "destination_location_button_take_ride") {
            document.getElementById('id_destination_location').value = text;
            // console.log(currentLongitude)
            // console.log(currentLatitude)
            document.getElementById('id_des_lati').value = currentLatitude;
            document.getElementById('id_des_long').value = currentLongitude;
        }
    });
}