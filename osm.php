<?php
if(isset($_GET["id"])) $id=$_GET["id"];
if(isset($_GET["lat"])) $lat=$_GET["lat"];
if(isset($_GET["lon"])) $lon=$_GET["lon"];
if(isset($_GET["zoom"])) $zoom=$_GET["zoom"];
if(isset($_GET["day"])) $day=$_GET["day"];
if(isset($_GET["month"])) $month=$_GET["month"];
if(isset($_GET["year"])) $year=$_GET["year"];
if(isset($_GET["device"])) $device=$_GET["device"];
$tracking_list_db_row=0;

if ((empty($_GET["day"])) && (empty($_GET["month"])) && (empty($_GET["year"]))) {
 $day = Date("d");
 $month = Date("m");
 $year = Date("Y");
} else {
 $day=$_GET["day"];
 $month=$_GET["month"];
 $year=$_GET["year"];
}

if ((empty($_GET["lat"])) && (empty($_GET["lon"])) && (empty($_GET["zoom"]))) { 
# More points (day)
 @include"_config.php";

 mysql_connect($MySQL_server, $MySQL_user, $MySQL_user_password);
 $spojenie=mysql_connect($MySQL_server,$MySQL_user,$MySQL_user_password);
 $spojeniedb=mysql_select_db($MySQL_db);

 $REQUESTED=$month;
 $CURRENT=Date("m");
 $CURRENTLAST=date("m", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
 if (($CURRENT == $REQUESTED) || ($CURRENTLAST == $REQUESTED)) {
  $MySQL_table=$MySQL_table1;
 }
 else {
  $MySQL_table=$MySQL_table2;
 }

### < KM COUNTER >
 $lon_last=0;
 $lat_last=0;
 $KM=0;
 $km_st=0;

 function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 5) {
  $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
  $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
  return round($distance, $decimals);
 }

 $tracking_list_db = MySQL_Query("SELECT * FROM $MySQL_table WHERE lat!='0.0' AND lon!='0.0' AND device='$device' AND time like '$year-$month-$day%' GROUP BY DATE_FORMAT(`time`, '%H:%i') order by time desc");
 $tracking_list_db_row = MySQL_numrows ($tracking_list_db);

 for ($j = 1; $j <= $tracking_list_db_row; $j++) {
  $entries = mysql_fetch_array ($tracking_list_db);
  $point1 = array("lat" => $lat_last, "long" => $lon_last);
  $point2 = array("lat" => $entries['lat'], "long" => $entries['lon']);
  if ($KM == "0") {
   $km = 0;
   $km_st = distanceCalculation($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
   $km2 = distanceCalculation($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
  } else {
   $km = distanceCalculation($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
   $km2 = distanceCalculation($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
  }

  $lat_last=$entries['lat'];
  $lon_last=$entries['lon'];
  $KM=$KM+$km2;
 }
### </ KM COUNTER >


 $tracking_list_db = MySQL_Query("SELECT * FROM $MySQL_table WHERE lat!='0.0' AND lon!='0.0' AND device='$device' AND time like '$year-$month-$day%' AND ( second like '%0' OR second like '%5' ) order by time asc");
# $tracking_list_db = MySQL_Query("SELECT * FROM $MySQL_table WHERE device='$device' AND time like '$year-$month-$day%' GROUP BY DATE_FORMAT(`time`, '%H:%i') order by time desc");

 $tracking_list_db_row = MySQL_numrows ($tracking_list_db);

 for ($i = 0; $i <= 1; $i++) {
  $entries = mysql_fetch_array ($tracking_list_db);
  if ($entries == '0' ){
   $lat='48.700000';
   $lon='20.100000';
   $zoom='8';
  } else {
   $marker='var latLong = [
';
   for ($i = 1; $i <= $tracking_list_db_row; $i++) {
    $entries = mysql_fetch_array ($tracking_list_db);
    if ((!empty($entries['lat'])) && (!empty($entries['lon']))) {
     $direction='---';
     if (($entries['direction'] >   '0') and ($entries['direction'] <  '45' )) { $drection='S';}
     if (($entries['direction'] >  '46') and ($entries['direction'] < '125' )) { $direction='V';}
     if (($entries['direction'] > '126') and ($entries['direction'] < '225' )) { $direction='J';}
     if (($entries['direction'] > '226') and ($entries['direction'] < '275' )) { $direction='Z';}
     if (($entries['direction'] > '276') and ($entries['direction'] < '366' )) { $direction='S';}
     $marker=$marker.' ["<b>TIME: '.$entries['time'].'</b><br> <b>Address</b>: '.$entries['miesto'].'<br>Speed: '.$entries['spd']*3.6.'km/h<br> Direction: '.$direction.' / '.$entries['direction'].'°<br> Position: '.$entries['lat'].'/'.$entries['lon'].' ('.$entries['alt'].'mnm)",'.$entries['lat'].','.$entries['lon'].'],
';
     $lat=$entries['lat'];
     $lon=$entries['lon'];
     $zoom='10'; 
    }
   }
   $marker=$marker.' ["Default",0.000000,0.000000]
];';
  }
  MySQL_error();
  MySQL_close();
 }
} else {
##### One point #####
 $lat=$_GET["lat"];
 $lon=$_GET["lon"];
 $marker='var latLong = [ ["Last position: '.$lat.'/'.$lon.'",'.$lat.','.$lon.'] ];';
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 <title> OpenStreetMaps - Leaflet </title>
 <meta http-equiv="Expires" CONTENT="Sun, 12 May 2003 00:36:05 GMT">
 <meta http-equiv="Pragma" CONTENT="no-cache">
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <meta http-equiv="Cache-control" content="no-cache">
 <meta http-equiv="Content-Language" content="sk">
 <meta name="google-site-verification" content="GHY_X_yeijpdBowWr_AKSMWAT8WQ-ILU-Z441AsYG9A">
 <meta name="GOOGLEBOT" CONTENT="noodp">
 <meta name="pagerank" content="10">
 <meta name="msnbot" content="robots-terms">
 <meta name="msvalidate.01" content="B786069E75B8F08919826E2B980B971A">
 <meta name="revisit-after" content="2 days">
 <meta name="robots" CONTENT="index, follow">
 <meta name="alexa" content="100">
 <meta name="distribution" content="Global">
 <meta name="keywords" lang="en" content="osm, openstreetmaps, maps, leaflet">
 <meta name="description" content="OpenStreetMaps with Leaflet in Docker">
 <meta name="Author" content="ZTK-Comp WEBHOSTING">
 <meta name="copyright" content="(c) 2019 ZTK-Comp">
 <link rel="stylesheet" href="http://maps.ztk-comp.sk/leaflet.css">
 <script type="text/javascript" src="http://maps.ztk-comp.sk/leaflet.js"></script>
</head>
<body>
<?php
echo "<center>
<b>Lat:</b> ".$lat."<br>
<b>Lon:</b> ".$lon."<br>
<b>Points:</b> ".$tracking_list_db_row." / ".$j."<br>
<b>Priblížne prejazdené km:</b> ".round(($KM - $km_st),2)."<br>
<b>Screen:</b> "?>
<script>
 document.write(window.innerWidth-"30"+"px x ");
 document.write(window.innerHeight-"150"+"px");
</script>
<?php "</center> \n"; ?>

<table id="map" width="100"><td id="maph" height="100">
</td></table>

<script>
document.getElementById("map").width = window.innerWidth-"30";
document.getElementById("maph").height = window.innerHeight-"150";
</script>

<script type="text/javascript">
    map = L.map('map').setView([<?php echo $lat;?>, <?php echo $lon;?>], <?php echo $zoom;?>);
    L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//        'http://maps.ztk-comp.sk/{z}/{x}/{y}.png', {
    maxZoom: 18,
    }).addTo(map);

    var popup = L.popup();
    function onMapClick(e) {
        popup
        .setLatLng(e.latlng)
        .setContent(e.latlng.toString())
        .openOn(map);
    }
    map.on('click', onMapClick);


<?php echo $marker;?>

for (var i = 0; i < latLong.length; i++) {
    marker = new L.circle([latLong[i][1],latLong[i][2]], 20, {
    color: 'black',
    fillColor: 'grey',
    fillOpacity: 5,
    radius: 2
    }).addTo(map).bindPopup(latLong[i][0]);
}

</script>
<p align="center">
<a href="http://validator.w3.org/check?uri=referer" target="_blank">
 <img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88" border="0">
</a> 
</body>
</html>

