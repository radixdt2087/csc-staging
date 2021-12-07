(function(_, $) {
    (function($) {
        var map = null;
        var saved_point = user_location_point = null;
        var marker = null;
        var map_params = null;
        var user_location_marker = null;
        var latitude = 0;
        var longitude = 0;
        var zoom = 0;
        var latitude_name = '';
        var longitude_name = '';
        var map_container = '';
        var storeDataLength = 0;
        var store_markers = null;
        function updatePoint(point) {
            if (saved_point && marker) {
                marker.setMap(null);
            }
            marker = new google.maps.Marker({
                position: point,
                map: map
            });
       
            //marker.setMap(map);
            saved_point = point;
        }

        function updateMapCenterPoint(latitude, longitude) {
            if('google' in window) {
                try {
                    if(typeof google  != 'undefined' && google && google.maps){
                        map_center = new google.maps.LatLng(latitude, longitude);
                        map.setCenter(map_center);
                        if (user_location_marker) {
                            user_location_marker.setMap(null);
                        }
                        user_location_marker = new google.maps.Marker({
                            position: map_center,
                            map: map,
                            infoWindowIndex: storeDataLength
                        });
                        user_location_marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
                        user_location_marker.setMap(map);
                        map.setZoom(10)
                        map.setCenter(map_center);
                        saved_point = map_center;
                        //balloon content collecting
                        var marker_html = '<div style="padding-right: 10px"><strong>' + _.wk_your_location + '</strong><p></div>';
                        var infowindow = new google.maps.InfoWindow({
                            content: marker_html
                        });
                        google.maps.event.addListener(user_location_marker, 'click',
                            function(event) {
                                map.panTo(event.latLng);
                                infowindow.open(map, this);
                            }
                        );
                        if (!$('#store_pickup_point_search').val()){
                            var geocoder = new google.maps.Geocoder();
                            geocoder.geocode({
                                'location': saved_point
                            }, function(results, status) {
                                if (status === google.maps.GeocoderStatus.OK) {
                                    place = results[0];
                                    if (place.formatted_address){
                                        $('#store_pickup_point_search').val(place.formatted_address);
                                    }
                                }
                            });
                        }
                    }
                } catch (error) {
                        
                }
            }
        }

        function addMapListeners() {
            google.maps.event.addListener(map, 'click', function(event) {
                updatePoint(event.latLng);
            });
        }
        var methods = {
            init: function(options, callback) {
                try{
                    if (!('google' in window)) {
                        var api_key = "";
                        if (options.api_key) {
                            api_key = "&key=" + options.api_key;
                        }
                        $.getScript('//www.google.com/jsapi', function() {
                            setTimeout(function() { // do not remove it - otherwise it will be slow in ff
                                google.load('maps', '3.37', {
                                    other_params: "libraries=places&language=" + options.language + api_key,
                                    callback: function() {
                                        $.ceWspMap('init', options, callback);
                                    }
                                });
                            }, 0);
                        });
                        return false;
                    }

                    latitude = options.latitude;
                    longitude = options.longitude;
                    map_container = options.map_container;
                    storeData = options.storeData;
                    zoom = 10;
                    // Required fields - zoom, mapTypeId, center
                    map_params = {
                        zoomControl: true,
                        scaleControl: true,
                        streetViewControl: true,
                        mapTypeControl: true,
                        zoom: 10,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        center: new google.maps.LatLng(latitude, longitude)
                    }

                    if (_.area == 'A') {
                        $.extend(map_params, {
                            draggableCursor: 'crosshair',
                            draggingCursor: 'pointer',
                            streetViewControl: true,
                            mapTypeControl: true,
                        });
                       
                    } else {
                        $.extend(map_params, {
                            zoom: 15
                        });
                    }
                    if (typeof(callback) == 'function') {
                        callback();
                    }
                }catch(error) {
                }
            },

            showDialog: function(country_field, city_field, latitude_field, longitude_field, pincode_field) {
                var params_dialog = {
                    href: "",
                    keepInPlace: false,
                    dragOptimize: true
                };

                $('#map_picker').ceDialog('open', params_dialog);
                saved_point = null;
                marker = null;
                latitude_name = latitude_field;
                longitude_name = longitude_field;
                wk_latitude = $('#' + latitude_name + '_hidden').val();
                wk_longitude = $('#' + longitude_name + '_hidden').val();
                var map_center = null;
                map = new google.maps.Map(document.getElementById(map_container), map_params);
                if (wk_longitude && wk_latitude) {
                    latitude = wk_latitude;
                    longitude = wk_longitude;
                    map_center = new google.maps.LatLng(latitude, longitude);
                    map.setCenter(map_center);
                    updatePoint(map_center);
                    addMapListeners();
                } else if ($('#' + city_field).val() || $('#' + country_field).val() || $('#' + pincode_field).val()) {
                    var address = '';
                    var value = $('#' + city_field).val();
                    if (value) {
                        var city = value;
                        address += value;
                    }
                    var value = $('#' + country_field).val();
                    if (value) {
                        value = $('#' + country_field + ' option:selected').text();
                        if (address) {
                            address += ' ';
                        }
                        address += value;
                    }
                    var value = $('#' + pincode_field).val();
                    if (value) {
                        if (address) {
                            address += ' ';
                        }
                        address += value;
                    }

                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        'address': address
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (city && city.length) {
                                map.setZoom(10);
                            }
                            $('#' + map_container).show();
                            map_center = results[0].geometry.location;
                            map.setCenter(map_center);
                            addMapListeners();
                        } else {
                            // fn_alert($.tr('text_address_not_found') + ': ' + address);
                        }
                    });

                } else {
                    map_center = new google.maps.LatLng(latitude, longitude);
                    map.setCenter(map_center);
                    updatePoint(map_center);
                    addMapListeners();
                }
            },

            show: function(options) {
                if (!map_params) {
                    return $.ceWspMap('init', options, function() {
                        $.ceWspMap('show', options);
                    });
                }
                try{
                    if (_.area != 'A') {
                        $.ceWspMap('loadAutoComplete');
                    }
                    map = new google.maps.Map(document.getElementById(options.map_container), map_params);
                    bounds = new google.maps.LatLngBounds();
                    markers = Array();
                    infoWindows = Array();
                    store_markers = Array();
                    var marker;
                    var i = 0;
                    storeDataLength = storeData.length;
                    if(user_location_marker==null && !saved_point) {
                        updateMapCenterPoint(latitude, longitude)
                    }
                    for (var keyvar = 0; keyvar < storeData.length; keyvar++) {
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(storeData[keyvar]['latitude'], storeData[keyvar]['longitude']),
                            map: map,
                            infoWindowIndex: i
                        });
                        marker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');

                        marker.setMap(map);
                        bounds.extend(marker.position);

                        //balloon content collecting
                        var marker_html = '<div style="padding-right: 10px"><strong>' + storeData[keyvar]['name'] + '</strong><p>';

                        if (storeData[keyvar]['city'] != '') {
                            marker_html += storeData[keyvar]['city'] + ', ';
                        }
                        if (storeData[keyvar]['country_title'] != '') {
                            marker_html += storeData[keyvar]['country_title']+ ', ';
                        }
                        if (storeData[keyvar]['pincode'] != '') {
                            marker_html += storeData[keyvar]['pincode'];
                        }

                        marker_html += '</p><\/div>';

                        var infowindow = new google.maps.InfoWindow({
                            content: marker_html
                        });

                        google.maps.event.addListener(marker, 'click',
                            function(event) {
                                if (event)
                                    map.panTo(event.latLng);
                                infoWindows[this.infoWindowIndex].open(map, this);
                                map.setZoom(18);
                            }
                        );

                        infoWindows.push(infowindow);
                        markers.push(marker);
                        store_markers[storeData[keyvar]['store_id']] = i; 
                        i++;
                    }
                    if (storeData.length == 1) {
                        map.setCenter(marker.getPosition());
                        map.setZoom(15);
                    } else if(storeData.length>=1)  {
                        map.fitBounds(bounds);
                    }
                }catch(error) {
                }
            },

            saveLocation: function() {
                if (saved_point) {
                    $('#' + latitude_name).val(saved_point.lat());
                    $('#' + latitude_name + '_hidden').val(saved_point.lat());
                    $('#' + longitude_name).val(saved_point.lng());
                    $('#' + longitude_name + '_hidden').val(saved_point.lng());
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        'location': saved_point
                    }, function(results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            place = results[0];
                            if (place) {
                                var lengt=place.address_components.length;
                                var country = country1 = '';
                                var state = state1 = '';
                                var city = city1 = '';
                                var address = '';
                                var zip_code = '';
                                var i=0;
                                for(i=0;i < lengt;++i){
                                    if(place.address_components[i].types.indexOf("country") > -1){
                                        country=place.address_components[i]. short_name;
                                        country1=place.address_components[i]. long_name;
                                    }
                                    if(place.address_components[i].types.indexOf("administrative_area_level_1") > -1){
                                        state=place.address_components[i]. short_name;
                                        state1=place.address_components[i]. long_name;
                                    }
                                    if(place.address_components[i].types.indexOf("locality") > -1){
                                        city=place.address_components[i]. short_name;
                                        city1=place.address_components[i]. long_name;
                                    }
                                    if(place.address_components[i].types.indexOf("postal_code") > -1){
                                        zip_code=place.address_components[i]. short_name;
                                    }
                                } 
                                var  data={};
                                var formatted_address=place.formatted_address;
                                var ret =formatted_address.replace(country,'');
                                var ret =ret.replace(country1,'');
                                var ret =ret.replace(zip_code,'');
                                var ret =ret.replace(city,'');
                                var ret =ret.replace(state,'');
                                var ret =ret.replace(state1,'');
                                
                                var ret =ret.replace(/,/g,'');
                                document.getElementById('elm_address').value =ret;
                                document.getElementById('elm_pincode').value =zip_code;
                                document.getElementById('elm_country').value = country;
                                $('#elm_country').trigger('change');
                                document.getElementById('elm_city').value =city;
                                document.getElementById('elm_state').value =state1;
                                if(!document.getElementById('elm_state').value){
                                    document.getElementById('elm_state').value = state;
                                }
                                document.getElementById('elm_latitude_hidden').value = place.geometry.location.lat();
                                document.getElementById('elm_latitude').value = place.geometry.location.lat();
                                document.getElementById('elm_longitude_hidden').value = place.geometry.location.lng();
                                document.getElementById('elm_longitude').value = place.geometry.location.lng();
                                document.getElementById('elm_place_id_hidden').value = place.place_id;
                            } else {
                              //window.alert('No results found');
                            }
                        } else {
                            // fn_alert($.tr('text_address_not_found') + ': ' + status);
                        }
                    });
                }

                saved_point = null;
            },

            viewLocation: function(latitude, longitude, store_id) {
                var latLng = new google.maps.LatLng(latitude, longitude);
                map.setCenter(latLng);
                map.setZoom(15);
                if (store_id && store_markers) {
                    var index = store_markers[store_id];
                    google.maps.event.trigger(markers[index], 'click');
                }
            },

            viewLocations: function() {
                map.fitBounds(bounds);
            },
            loadAutoComplete: function() {
                var placeSearch, autocomplete;
                // // Create the autocomplete object, restricting the search predictions to
                // // geographical location types.
                autocomplete = new google.maps.places.Autocomplete(
                    document.getElementById('store_pickup_point_search'), {types: ['geocode']});
                // Avoid paying for data that you don't need by restricting the set of
                // place fields that are returned to just the address components.
                autocomplete.setFields(['geometry']);
                // When the user selects an address from the drop-down, populate the
                // address fields in the form.
                autocomplete.addListener('place_changed', function(){
                    var place = autocomplete.getPlace();
                    if (place.geometry && place.geometry.location){
                        document.getElementById('wk_customer_lat').value = place.geometry.location.lat();
                        document.getElementById('wk_customer_lng').value = place.geometry.location.lng(); 
                    }
                });
            }
        }

        $.extend({
            ceWspMap: function(method) {
                if (methods[method]) {
                    return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
                } else {
                    $.error('ty.map: method ' + method + ' does not exist');
                }
            }
        });
        
    })($);

    $(document).ready(function() {
        $('.cm-map-dialog').on('click', function() {
            $.ceWspMap('showDialog', 'elm_country', 'elm_city', 'elm_latitude', 'elm_longitude', 'elm_pincode');
        });

        $('.cm-wsp-map-save-location').on('click', function() {
            $.ceWspMap('saveLocation');
        });

        $('.cm-wsp-map-view-location').on('click', function(e) {
            e.preventDefault();
            var jelm = $(this);
            var latitude = jelm.data('ca-latitude');
            var longitude = jelm.data('ca-longitude');
            var store_id = jelm.data('ca-store-id');
            $.ceWspMap('viewLocation', latitude, longitude, store_id);
        });

        $('.cm-wsp-map-view-locations').on('click', function() {
            $.ceWspMap('viewLocations');
        });

        $('.wk-select-your-location').on('click', function(){
            localStorage.setItem('wk_location_setup', false);
            fn_wk_getLocation(false);
        });

        $('.wk_show_hide_description').on('click',function(){
            if($(this).next('.wk_store_description').is(':visible')){
                $(this).text(_.wk_show_description);
            }else{
                $(this).text(_.wk_hide_description);
            }
            $(this).next('.wk_store_description').toggle();
        });

        function fn_wk_getLocation(is_check = true) {
            if (!localStorage.getItem('wk_location_setup') || !is_check){
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                }
            }
        }

        function showPosition(position) {
            $.ceAjax('request',fn_url("wk_store_pickup.set_location"),{
                data: {
                    wkCustomerLat:position.coords.latitude,
                    wkCustomerLng:position.coords.longitude
                },
                method:'post',
                callback:function(){
                    localStorage.setItem('wk_location_setup', true);
                    $('#store_pickup_point_search').val('');
                    $('.wk_store_pickup_point_searchbtn').click();
                }
            });
        }
        if (_.area == 'C'){
            fn_wk_getLocation();
        }
    });
}(Tygh, Tygh.$));