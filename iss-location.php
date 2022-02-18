<?php include ('header.php') ?>

<title>ISS Locator</title>
<?php 
    if(empty($_POST['issDatetime'])){
        header("Location: index.php");
        die();
    }
    
    /* Convert datetime to timestamp */
    $getTime = $_POST['issDatetime'];
    $genTimestamp = strtotime($getTime);

    $time = [];
    $time[6] = $genTimestamp ;

    for ($j = 5; $j >= 0 ; $j--) {
        $time[$j] = $time[$j+1]-600;
    }
    for ($t = 7; $t <= 12; $t++) {
        $time[$t] = $time[$t-1]+600;
    } 

    /* ISS Location using https://api.wheretheiss.at/v1/satellites/25544/positions?timestamps={timestamp} */
    $strtourl = "https://api.wheretheiss.at/v1/satellites/25544/positions?timestamps=$time[0],$time[1],$time[2],$time[3],$time[4],$time[5],$time[6],$time[7],$time[8],$time[9],$time[10],$time[11],$time[12]&units=miles";
    $url = $strtourl;

    $cURL = curl_init();
                
    curl_setopt($cURL, CURLOPT_URL, $url);
    curl_setopt($cURL, CURLOPT_HTTPGET, false);
    curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
                
    curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json'
    ));
                
    $result = curl_exec($cURL);
    curl_close($cURL);
    $arrays =  json_decode($result);
?>

<style>
table
{
    counter-reset: rowNumber;
}

table tr > td:first-child
{
    counter-increment: rowNumber;
}

table tr td:first-child::before
{
    content: counter(rowNumber);
    min-width: 1em;
    margin-right: 0.5em;
}
</style>
<body background="https://3dwarehouse.sketchup.com/warehouse/v1.0/publiccontent/bbbf2a17-987c-4fe6-b190-ac8fe0e235e9">
    <div class="wrapper">

        <h1><a href="index.php">International Space Station</a></h1>
        <br>
        <hr>
        <?php include ('iss-locator.php'); ?>
        <br>
        <h2 style="text-align:center; background-color:#212121;color:#ffffff">ISS Location (<?php echo $_POST['issDatetime'] ?>)</h2>
        <br>
        <section class="isslive">   
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
                integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
                crossorigin=""
            />
            <div id="mapid">
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

                    async function getData(){
                        const latitude = <?php print_r($arrays[6] -> latitude) ?>;
                        const longitude = <?php print_r($arrays[6] -> longitude) ?>;
                        const footprint = <?php print_r($arrays[6] -> footprint) ?>;
                            
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
        </section>
        <h2 style="text-align:center; background-color:#212121;color:#ffffff">ISS Timestamp (<?php echo $_POST['issDatetime'] ?>)</h2>
        <div class="container" style="margin-bottom: 10em;">
        
            <br>
            <table class="tableCoor">
                <thead>
                    <tr class="tableCoor-head">
                        <th style="color:#212121">No.</th> 
                        <th style="color:#212121">Timestamp</th> 
                        <th style="color:#212121">Id</th> 
                        <th style="color:#212121">Name</th> 
                        <th style="color:#212121">Latitude</th>
                        <th style="color:#212121">Longtitude</th>
                    </tr>
                </thead>

                <?php 
                foreach ($arrays as $value){  
                ?>
                <tr>
                    <td></td>
                    <td>&nbsp&nbsp<?php echo $value -> timestamp ?> </td>
                    <td><?php echo $value -> id ?> </td> 
                    <td><?php echo $value -> name ?> </td> 
                    <td><?php echo $value -> latitude ?> </td>
                    <td><?php echo $value -> longitude ?> </td>
                </tr>

                <?php  } ?>            

            </table>
            <br>
            <p id="legend">
                Timestamp 7 : Selected Time & Date <br>
                Timestamp 1-6 : 10 minutes backward <br>
                Timestamp 8-13 : 10 minutes forward 
            </p>
            <br>

        </div>
    </div>
</body>
