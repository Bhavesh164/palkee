<script>
    var infoWindowContentdata = [];

    function initMap() {
        var markerArray = [];
        var markerArray_driver = [];
        var map;

        var bounds = new google.maps.LatLngBounds();

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 5,
            center: new google.maps.LatLng('41.3775', '64.5853'),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        //  search Box start //

        input = (document.getElementById('search-input'));

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        //    map.setZoom(5);
        var searchBox = new google.maps.places.SearchBox(input);

        //var searchBox = new google.maps.places.SearchBox((input),{bounds: position});
        google.maps.event.addListener(searchBox, 'places_changed', function() {
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }
            for (var i = 0, place; place = places[i]; i++) {
                //                var image = {
                //                    url: place.icon,
                //                    size: new google.maps.Size(71, 71),
                //                    origin: new google.maps.Point(0, 0),
                //                    anchor: new google.maps.Point(17, 34),
                //                    scaledSize: new google.maps.Size(25, 25)
                //                };
                bounds.extend(place.geometry.location);
                latField = place.geometry.location.lat();
                lngField = place.geometry.location.lng();
            }
            //map.fitBounds(bounds);
            var latlng = new google.maps.LatLng(latField, lngField);
            var geocoder = geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'latLng': latlng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {

                        map.setCenter(latlng);
                    }
                }
            });
        });

        //  search Box End //

        map.setTilt(45);

        //    showpanicclients(map,markerArray,bounds,1);
        //    setInterval(function() { showpanicclients(map,markerArray,bounds,0); },30000);

        showalldrivers(map, markerArray_driver, bounds, 1);
        setInterval(function() {
            showalldrivers(map, markerArray_driver, bounds, 0);
        }, 10000);

        // Override our map zoom level once our fitBound s function runs (Make sure it only runs once)
        //    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        //        this.setZoom(5);
        //        google.maps.event.removeListener(boundsListener);
        //    });

    }

    function showalldrivers(map, markerArray_driver, bounds, set_bound) {

        //console.log(markerArray);

        var icon_red = new google.maps.MarkerImage('<?= asset('resources/assets/images/marker-red.png') ?>',
            new google.maps.Size(48, 48), new google.maps.Point(0, 0),
            new google.maps.Point(24, 44));

        var icon_green = new google.maps.MarkerImage('<?= asset('resources/assets/images/marker-green.png') ?>',
            new google.maps.Size(48, 48), new google.maps.Point(0, 0),
            new google.maps.Point(24, 44));

        var icon_blue = new google.maps.MarkerImage('<?= asset('resources/assets/images/marker-blue.png') ?>',
            new google.maps.Size(48, 48), new google.maps.Point(0, 0),
            new google.maps.Point(24, 44));

        // Multiple Markers


        var token = $('meta[name="csrf-token"]').attr('content');

        $.post(BASE_URL + "/admin/drivers/get_all_drivers", {
            _token: token
        }, function(response) {

            if (response.current_positions == '') {
                response.current_positions = 0;
            }


            var markers = response.current_positions;
            if (markers == 0 || markers == null) {
                markers = [];
            }

            // console.log(markers);

            // Info Window Content
            var infoWindowContent = response.positions_info;


            var geocoder = new google.maps.Geocoder();

            // Loop through our array of markers & place each one on the map  
            var driver_array = [];
            var marker_icon;
            var i;
            for (i = 0; i < markers.length; i++) {

                if (infoWindowContent[i].is_available == 1) {
                    marker_icon = icon_green;
                } else {
                    marker_icon = icon_blue;
                    //            if(infoWindowContent[i].is_available == 1)
                    //            {
                    //                marker_icon = icon_green;
                    //            }else
                    //            {
                    //                marker_icon = icon_red;
                    //            }

                }
                // console.log(infoWindowContent[i].status);
                var driver_id = markers[i][3];
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);

                infoWindowContentdata[driver_id] = infoWindowContent[i];

                if (markerArray_driver[driver_id] == null) {
                    var infoWindow = new google.maps.InfoWindow(),
                        marker;

                    marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: markers[i][0],
                        icon: marker_icon,
                    });

                    markerArray_driver[driver_id] = marker;
                    //console.log('load new marker');
                    attachInstructionText_driver(infoWindow, markerArray_driver[driver_id], driver_id);
                } else {
                    markerArray_driver[driver_id].setIcon(marker_icon);
                    transition([markers[i][1], markers[i][2]], markerArray_driver[driver_id], map);
                }





                // Allow each marker to have an info window    

                //        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                //            return function() {
                //                infoWindow.setContent(infoWindowContent[i][0]);
                //                infoWindow.open(map, marker);
                //            }
                //        })(marker, i));

                // Automatically center the map fitting all markers on the screen
                if (set_bound == 1) {
                    map.fitBounds(bounds);
                }
                driver_array.push(driver_id);

            }

            $.each(markerArray_driver, function(key, value) {
                if (($.inArray('' + key + '', driver_array) == -1) && (markerArray_driver[key] != null)) {
                    markerArray_driver[key].setMap(null);
                    markerArray_driver[key] = null;
                    // markerArray_driver.splice( $.inArray(key,markerArray_driver) ,1 );
                }
            });

        }, "json");
    }

    //function attachInstructionText(infoWindow,marker,content) {
    //    var geocoder = new google.maps.Geocoder();
    //    google.maps.event.addListener(marker, 'click', function(event) {
    //                geocoder.geocode({
    //                    'latLng': event.latLng
    //                }, function(results, status) {
    //                  //  console.log(results[0].formatted_address);
    //                    if (status == google.maps.GeocoderStatus.OK) {
    //                        if (results[0]) {
    //                            infoWindow.setContent('<p><b>Client Name: </b>'+content.fname+' '+content.lname+'</p>'
    //                                    +'<p><b>Client Email: </b>'+content.email+'</p>'
    //                                    +'<p><b>Client phone: </b>'+content.mobile_code+content.mobile+'</p>'
    //                                    +'<p><b>Address: </b>'+results[0].formatted_address+'</p>');
    //                            infoWindow.open(map, marker);
    //                        }
    //                    }
    //                });
    //            });    
    ////    google.maps.event.addListener(marker, 'click', function() {
    ////      // Open an info window when the marker is clicked on,
    ////      // containing the text of the step.
    ////      stepDisplay.setContent(text);
    ////      stepDisplay.open(map, marker);
    ////    });
    //  }

    function attachInstructionText_driver(infoWindow, marker, driver_id) {
        var geocoder = new google.maps.Geocoder();

        google.maps.event.addListener(marker, 'click', function(event) {
            var content = infoWindowContentdata[driver_id];
            geocoder.geocode({
                'latLng': event.latLng
            }, function(results, status) {
                //  console.log(results[0].formatted_address);
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        //                            if(content.is_available == 1)
                        //                            {
                        //                                availability = 'Online';
                        //                            }
                        //                            else
                        //                            {
                        //                                availability = 'Offline';
                        //                            }
                        infoWindow.setContent('<p><b>Driver Name: </b>' + content.full_name + '</p>' +
                            '<p><b>Driver Email: </b>' + content.email + '</p>' +
                            '<p><b>Driver Phone: </b>' + content.dial_code + '-' + content.phone + '</p>' +
                            '<p><b>Address: </b>' + results[0].formatted_address + '</p>');
                        infoWindow.open(map, marker);
                    }
                }
            });
        });
        //    google.maps.event.addListener(marker, 'click', function() {
        //      // Open an info window when the marker is clicked on,
        //      // containing the text of the step.
        //      stepDisplay.setContent(text);
        //      stepDisplay.open(map, marker);
        //    });
    }

    var numDeltas = 100;
    var delay = 10; //milliseconds
    //  var i = 0;
    // var deltaLat;
    // var deltaLng;

    function transition(result, marker, map) {
        var i = 0;
        var position0 = marker.getPosition().lat();
        var position1 = marker.getPosition().lng();
        var deltaLat = (result[0] - position0) / numDeltas;
        var deltaLng = (result[1] - position1) / numDeltas;
        // console.log(marker.getPosition().lat());
        // console.log(marker.getPosition().lng());
        moveMarker();

        function moveMarker() {

            position0 += deltaLat;
            position1 += deltaLng;
            var latlng = new google.maps.LatLng(position0, position1);
            //marker.setTitle("Latitude:"+position0+" | Longitude:"+position1);
            marker.setPosition(latlng);
            // map.panTo(latlng);
            if (i != numDeltas) {
                i++;
                //setTimeout(function() { moveMarker(marker,map); },delay);
                setTimeout(moveMarker, delay);
            }

        }

    }

    $(document).ready(function() {
        setTimeout(function() {
            initMap();
        }, 500);
    });
</script>