<h2 style="text-align:center; background-color:#212121;color:#ffffff">People on the ISS</h2>
<br>
<div style="padding-bottom:40px">

    <?php
        $url = 'http://api.open-notify.org/astros.json';

        $cURL = curl_init();

        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        
        $result = curl_exec($cURL);

        curl_close($cURL);

        $arrays =  json_decode($result);
    ?>
    <table class="tableCoor">
        <thead>
            <tr class="tableCoor-head">
                <th style="color:#212121">Name</th> 
                <th style="color:#212121">Craft</th> 
            </tr>
        </thead>
        <?php 
            for ($i=0; $i<10; $i++) {
        ?>
        <tr>
            <td><?php echo $arrays -> people[$i] -> name ?> </td>
            <td><?php echo $arrays -> people[$i] -> craft ?> </td> 
        </tr>         
        <?php  } ?>     
    </table>
</div>
