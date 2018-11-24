function initMap() {

    $.ajax({
        type: "POST",
        url: "async/map.php",
        success: function(result) {
            result = JSON.parse(result);
            if (result.result_status) {
                
                // The location of cent
                var cent = {lat: -33.4726900, lng: -70.6472400};
                // The map, centered at cent
                var map = new google.maps.Map(
                    document.getElementById('map'), {zoom: 4.5, center: cent});
                /*
                var paths = [];
                var markers1 = [];
                var markers2 = [];
                var contents = [];

                var infoWindow = new google.maps.InfoWindow();
                var res;
                
                for (var i = 1; i <= result.ammount; i++) {
                    if (i === 1) res = result.data1;
                    if (i === 2) res = result.data2;
                    if (i === 3) res = result.data3;

                    contents.push(res["tag_text"]);
                    var actualContent;
                    
                    paths.push( new google.maps.Polyline({
                        path: [{'lat': res[0].lat, 'lng': res[0].lng},{'lat': res[1].lat, 'lng': res[1].lng}],
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    }));
                    markers1.push( new google.maps.Marker({
                        position: {'lat': res[0].lat, 'lng': res[0].lng},
                        map: map,
                        title: res["title"]
                    }));
                    markers2.push( new google.maps.Marker({
                        position: {'lat': res[1].lat, 'lng': res[1].lng},
                        map: map,
                        title: res["title"]
                    }));

                    if (i == 1) actualContent = contents[0];
                    if (i == 2) actualContent = contents[1];
                    if (i == 3) actualContent = contents[2];

                    markers1[i - 1].addListener('click', function() {
                        infoWindow.setContent(actualContent);
                        infoWindow.open(map, this);
                    });
                    
                    markers2[i - 1].addListener('click', function() {
                        infoWindow.setContent(actualContent);
                        infoWindow.open(map, this);
                    });

                    paths[i - 1].setMap(map);
                }
                */
                
                var infoWindow = new google.maps.InfoWindow();

                if (result.ammount >= 1) {
                    var path1 = new google.maps.Polyline({
                        path: [{'lat': result.data1[0].lat, 'lng': result.data1[0].lng},{'lat': result.data1[1].lat, 'lng': result.data1[1].lng}],
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });
                    var marker11 = new google.maps.Marker({
                        position: {'lat': result.data1[0].lat, 'lng': result.data1[0].lng},
                        map: map,
                        title: result.data1["title"]
                    });
                    var marker12 = new google.maps.Marker({
                        position: {'lat': result.data1[1].lat, 'lng': result.data1[1].lng},
                        map: map,
                        title: result.data1["title"]
                    });
                    marker11.addListener('click', function() {
                        infoWindow.setContent(result.data1["tag_text"]);
                        infoWindow.open(map, marker11);
                    });
                    marker12.addListener('click', function() {
                        infoWindow.setContent(result.data1["tag_text"]);
                        infoWindow.open(map, marker12);
                    });
                    path1.setMap(map);
                }
                if (result.ammount >= 2) {
                    var path2 = new google.maps.Polyline({
                        path: [{'lat': result.data2[0].lat, 'lng': result.data2[0].lng},{'lat': result.data2[1].lat, 'lng': result.data2[1].lng}],
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });
                    var marker21 = new google.maps.Marker({
                        position: {'lat': result.data2[0].lat, 'lng': result.data2[0].lng},
                        map: map,
                        title: result.data2["title"]
                    });
                    var marker22 = new google.maps.Marker({
                        position: {'lat': result.data2[1].lat, 'lng': result.data2[1].lng},
                        map: map,
                        title: result.data2["title"]
                    });
                    marker21.addListener('click', function() {
                        infoWindow.setContent(result.data2["tag_text"]);
                        infoWindow.open(map, marker21);
                    });
                    marker22.addListener('click', function() {
                        infoWindow.setContent(result.data2["tag_text"]);
                        infoWindow.open(map, marker22);
                    });
                    path2.setMap(map);
                }
                if (result.ammount >= 3) {
                    var path3 = new google.maps.Polyline({
                        path: [{'lat': result.data3[0].lat, 'lng': result.data3[0].lng},{'lat': result.data3[1].lat, 'lng': result.data3[1].lng}],
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });
                    var marker31 = new google.maps.Marker({
                        position: {'lat': result.data3[0].lat, 'lng': result.data3[0].lng},
                        map: map,
                        title: result.data3["title"]
                    });
                    var marker32 = new google.maps.Marker({
                        position: {'lat': result.data3[1].lat, 'lng': result.data3[1].lng},
                        map: map,
                        title: result.data3["title"]
                    });
                    marker31.addListener('click', function() {
                        infoWindow.setContent(result.data3["tag_text"]);
                        infoWindow.open(map, marker31);
                    });
                    marker32.addListener('click', function() {
                        infoWindow.setContent(result.data3["tag_text"]);
                        infoWindow.open(map, marker32);
                    });
                    path3.setMap(map);
                }
                
            } else {
                $("#map").html("Error en la conexión a los mapas");
            }
        }
    })

}