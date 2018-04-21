<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>DDBG</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100%;
        }
        #SelectDistrict {
            position: fixed;
            left: 40%;
            z-index: 1000;
        }
    </style>
</head>
<body>
<select id="SelectDistrict">
    <option></option>
    @foreach($districts as $district)
        <option value="{{ $district->id }}">{{ $district->name }}</option>
    @endforeach
</select>
<div id="map"></div>
<script>
    function initMap()
    {
        var lat = 10.771808;
        var lng = 106.67818;
        var latLng = new google.maps.LatLng(lat, lng);

        var mapOptions = {
            center: new google.maps.LatLng(lat, lng),
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var district;

        $(document).ready(function() {
            $('#SelectDistrict').change(function() {
                var val = $(this).val();
                if(val)
                {
                    $.ajax({
                        url: '{{ action('HomeController@home') }}',
                        type: 'get',
                        data: 'id=' + val,
                        success: function(result) {
                            if(result)
                            {
                                result = JSON.parse(result);

                                var boundary = [];

                                result.forEach(function(value) {
                                    boundary.push({
                                        'lat': value[1],
                                        'lng': value[0]
                                    });
                                });

                                if(district)
                                    district.setMap(null);

                                district = new google.maps.Polygon({
                                    paths: boundary,
                                    strokeColor: '#FF0000',
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillColor: '#FF0000',
                                    fillOpacity: 0.35
                                });

                                district.setMap(map);

                                var center = new google.maps.LatLng(result[0][1], result[0][0]);
                                map.setOptions({
                                    center: center,
                                    zoom: 13,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                });
                            }
                        }
                    });
                }
                else if(district)
                {
                    district.setMap(null);
                    district = null;
                }
            });
        });
    }
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAS1R7TJDy4-n3tloeAILG5mB8mBVqjcE&libraries=places&callback=initMap"></script>
</body>
</html>