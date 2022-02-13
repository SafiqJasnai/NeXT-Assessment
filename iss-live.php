<section class="isslive">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
            integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
            crossorigin=""
    />
    <br>
    <h2 style="text-align:center; background-color:#212121;color:#ffffff">ISS Live</h2>
    <br>
    <div class="row">
        <div id="mapid" style="width:60%">   
            <script>
                const attribution = '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors';
                const map = L.map('mapid').setView([0, 0], 2);

                var iss_icon = L.icon({
                    iconUrl: 'img/iss.png',
                    iconSize: [100, 64]
                });
                const marker = L.marker([0, 0], {icon: iss_icon}).addTo(map);

                const tileUrl =  'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png';
                const tiles = L.tileLayer(tileUrl, { attribution });
                tiles.addTo(map);

                const API_URL = 'https://api.wheretheiss.at/v1/satellites/25544';

                async function getData(){
                    const response = await fetch(API_URL);
                    const data = await response.json();
                    console.log(data)
                    const { latitude, longitude, footprint } = data;
                    map.setView([latitude, longitude, footprint], 3);
                    marker.setLatLng([latitude, longitude, footprint]);

                    var popup = L.popup()
                    .setLatLng([latitude, longitude, footprint])
                    .setContent('Latitude: ' + latitude.toFixed(3) +  '<br>Longitude: ' + longitude.toFixed(3) + '<br>Footprint: ' + footprint.toFixed(3))
                    .openOn(map);
                    marker.bindPopup(popup).openPopup();
                }

                getData();

                setInterval(getData, 1000);

            </script>
        </div>  
        <div style="text-align:right;">  
            <iframe width="480" height="270" src="https://ustream.tv/embed/9408562" scrolling="no" allowfullscreen webkitallowfullscreen frameborder="0" style="border: 0 none transparent;"></iframe>
        </div>
    </div>
</section>
