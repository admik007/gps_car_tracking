<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 <title> GPS logging and tracking </title>
 <link rel="SHORTCUT ICON" href="favicon.ico" type="image/x-icon">
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
 <meta name="keywords" lang="sk" content="gps, logging, tracking">
 <meta name="description" content="Webpage for GPS logging">
 <meta name="Author" content="ZTK-Comp WEBHOSTING">
 <meta name="copyright" content="(c) 2015 ZTK-Comp">
 <link href="calendar.css" type="text/css" rel="stylesheet">
</head>
<body bgcolor="silver">

<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

@include"_config.php";

mysql_connect($MySQL_server, $MySQL_user, $MySQL_user_password);
$spojenie=mysql_connect($MySQL_server,$MySQL_user,$MySQL_user_password);
$spojeniedb=mysql_select_db($MySQL_db);

$ip=$_SERVER["REMOTE_ADDR"]; 


if (empty($_GET["day"])) $day=Date("d");
if(isset($_GET["day"])) $day=$_GET["day"];

if (empty($_GET["month"])) $month=Date("m");
if(isset($_GET["month"])) $month=$_GET["month"]; 

if (empty($_GET["year"])) $year=Date("Y");
if(isset($_GET["year"])) $year=$_GET["year"];

if (empty($_GET["hour"])) $hour=Date("H");
if(isset($_GET["hour"])) $hour=$_GET["hour"];

if (empty($_GET["minute"])) $minute=Date("i");
if(isset($_GET["minute"])) $minute=$_GET["minute"];

if (empty($_GET["second"])) $second=Date("s");
if(isset($_GET["second"])) $second=$_GET["second"];

if(isset($_GET["lat"])) $lat=$_GET["lat"];
if(isset($_GET["lon"])) $lon=$_GET["lon"];
if(isset($_GET["alt"])) $alt=round($_GET["alt"]);
if(isset($_GET["acc"])) $acc=$_GET["acc"];
if(isset($_GET["spd"])) $spd=$_GET["spd"];
if(isset($_GET["sat"])) $sat=$_GET["sat"];
if(isset($_GET["bat"])) $bat=$_GET["bat"];
if(isset($_GET["time"])) $time=$_GET["time"];

if (empty($_GET["device"])) $device="KE978IE";
if(isset($_GET["device"])) $device=$_GET["device"];

if(isset($_GET["provider"])) $provider=$_GET["provider"];
if(isset($_GET["direction"])) $direction=$_GET["direction"];
if(isset($_GET["devicerpi"])) $devicerpi=$_GET["devicerpi"];
if(isset($_GET["temprpi"])) $temprpi=$_GET["temprpi"];
if(isset($_GET["loadrpi"])) $loadrpi=$_GET["loadrpi"];



$HOST=$_SERVER["SERVER_NAME"];
if ($HOST != "gps.DIFFERENT_HOST.sk") {
 $SHOW='1';
} else {
$device="NODE04";
}

if (!empty($_GET["time"])) {
 $timeT=str_replace("T","\T",$time);
 $timeZ=str_replace("Z","\Z",$timeT);
 $epoch= strtotime (gmdate($timeZ));
 $time=date ('Y-m-d\TH:i:s\Z',$epoch);
 MySQL_Query("INSERT INTO $MySQL_table1 VALUES('0','$lat','$lon','$alt','$acc','$spd','$sat','$time','$bat','$ip','$year','$month','$day','$hour','$minute','$second','$device','$provider','$direction','$devicerpi','$temprpi','$loadrpi')");
} else {
 $REQUESTED=$month;
 $CURRENT=Date("m");
 $CURRENTLAST=date("m", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
 if (($CURRENT == $REQUESTED) || ($CURRENTLAST == $REQUESTED)) {
  $MySQL_table=$MySQL_table1;
 }
 else {
  $MySQL_table=$MySQL_table2;
 }
 $tracking_list_db_total = MySQL_Query("SELECT * FROM $MySQL_table WHERE lat!='0.0' AND lon!='0.0' AND device='$device' AND time like '$year-$month-$day%' order by time asc");
 $tracking_list_db_row_total = MySQL_numrows ($tracking_list_db_total);

 $tracking_list_db = MySQL_Query("SELECT * FROM $MySQL_table WHERE lat!='0.0' AND lon!='0.0' AND device='$device' AND time like '$year-$month-$day%' GROUP BY DATE_FORMAT(`time`, '%H:%i') order by time desc");
 $tracking_list_db_row = MySQL_numrows ($tracking_list_db);
 if ($SHOW == "1") {
  $ZTK_DEVICES="<tr>
  <td align=\"center\">
   <a href=\"?year=$year&amp;month=$month&amp;day=$day&amp;device=NODE01\" style=\"text-decoration:none\"><b>NODE01</b></a> -
   <a href=\"?year=$year&amp;month=$month&amp;day=$day&amp;device=NODE02\" style=\"text-decoration:none\"><b>NODE02</b></a> -
   <a href=\"?year=$year&amp;month=$month&amp;day=$day&amp;device=NODE03\" style=\"text-decoration:none\"><b>NODE03</b></a> -
   <a href=\"?year=$year&amp;month=$month&amp;day=$day&amp;device=NODE04\" style=\"text-decoration:none\"><b>NODE04</b></a>
  </td>
 </tr>";
 } else {
  $ZTK_DEVICES="
";
 }

 echo "<table border=\"1\" width=\"364\" align=\"center\" bgcolor=\"$bgsob\">
".$ZTK_DEVICES."
 <tr>
  <td colspan=\"4\" align=\"center\">
   <a href=\"osm.php?year=$year&amp;month=$month&amp;day=$day&amp;device=$device\" target=\"_blank\" style=\"text-decoration:none\"><b>Zobraz mapu</b> ($tracking_list_db_row) / ($tracking_list_db_row_total)</a><br>
   <a href=\"export.php?year=$year&amp;month=$month&amp;day=$day&amp;device=$device\" target=\"_blank\" style=\"text-decoration:none\"><b>Export Excel</a><br>

</table>\n\n";


#### KALENDAR
class Calendar {
    /**
     * Constructor
     */
    public function __construct(){
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }
    /********************* PROPERTY ********************/
    public $dayLabels = array("Pon","Uto","Str","Štv","Pia","Sob","Ned");
    public $currentYear=0;
    public $currentMonth=0;
    public $currentDay=0;
    public $currentDate=null;
    public $daysInMonth=0;
    public $naviHref= null;
    /********************* PUBLIC **********************/
    /**
    * print out the calendar
    */
    public function show() {
        $year  = null;
        $month = null;
        if(null==$year&&isset($_GET['year'])){
            $year = $_GET['year'];
        }else if(null==$year){
            $year = date("Y",time());
        }
        if(null==$month&&isset($_GET['month'])){
            $month = $_GET['month'];
        }else if(null==$month){
            $month = date("m",time());
        }
        $this->currentYear=$year;
        $this->currentMonth=$month;
        $this->daysInMonth=$this->_daysInMonth($month,$year);
        $content='<div id="calendar">'.
                        '<div class="box">'.
                        $this->_createNavi().
                        '</div>'.
                        '<div class="box-content">'.
                                '<ul class="label">'.$this->_createLabels().'</ul>';
                                $content.='<div class="clear"></div>';
                                $content.='<ul class="dates">';

                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                    //Create days in a week
                                    for($j=1;$j<=7;$j++){
                                        $content.=$this->_showDay($i*7+$j);
                                    }
                                }
                                $content.='</ul>';
                                $content.='<div class="clear"></div>';
                        $content.='</div>';
        $content.='</div>';
        return $content;
    }
    /********************* PRIVATE **********************/
    /**
    * create the li element for ul
    */
    public function _showDay($cellNumber){
        $this->device=$_GET["device"];
        if($this->currentDay==0){
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                $this->currentDay=1;
            }
        }
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
            $cellContent = $this->currentDay;
	    if ($cellContent < 10) {
	         $cellContent = '0'.$cellContent;
	    }

            $this->currentDay++;
        }else{
            $this->currentDate =null;
            $cellContent=null;
        }
        return '<li><a href="?year='.$this->currentYear.'&amp;month='.$this->currentMonth.'&amp;day='.$cellContent.'&amp;device='.$this->device.'">'.$cellContent.'</a></li>';
    }
    /**
    * create navigation
    */
    public function _createNavi(){
        $this->device=$_GET["device"];
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
        return
            '<div class="header">'.
                '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&amp;year='.$preYear.'&amp;device='.$this->device.'"> <<< </a>'.
                '<span class="title"><a href="?">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</a> </span>'.
                '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&amp;year='.$nextYear.'&amp;device='.$this->device.'"> >>> </a>'.
            '</div>';
    }
    /**
    * create calendar week labels
    */
    public function _createLabels(){
        $content='';
        foreach($this->dayLabels as $index=>$label){
            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
        }
        return $content;
    }
    /**
    * calculate number of weeks in a particular month
    */
    public function _weeksInMonth($month=null,$year=null){
        if( null==($year) ) {
            $year =  date("Y",time());
        }
        if(null==($month)) {
            $month = date("m",time());
        }
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
        if($monthEndingDay<$monthStartDay){
            $numOfweeks++;
        }
        return $numOfweeks;
    }
    /**
    * calculate number of days in a particular month
    */
    public function _daysInMonth($month=null,$year=null){
        if(null==($year))
            $year =  date("Y",time());
        if(null==($month))
            $month = date("m",time());
        return date('t',strtotime($year.'-'.$month.'-01'));
    }
}
$calendar = new Calendar();
echo $calendar->show();
#### KALENDAR


function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 5) {
$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
$distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
return round($distance, $decimals);
}
if ($SHOW == "1"){
$ZTK_PARAM_MENU="
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Temp</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Load</b></font></td>";
} else {
 $ZTK_PARAM_MENU="";
}

 $WEB_HEADER="
<table align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
 <tr>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>DBid</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>ID</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Súradnice</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Nadm. výška</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Presnost</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Rýchlosť</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Smer</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Satelity</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Čas</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>IP</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>Miesto</b></font></td>
  <td bgcolor=\"#000000\"><font color=\"00FAAA\"><b>ŠPZ</b></font></td>".$ZTK_PARAM_MENU."
 </tr>
";
 $WEB_MIDDLE="";
 $WEB_FOOTER="</table>\n\n";

$lon_last=0;
$lat_last=0;
$KM=0;
$km_st=0;

 for ($i = 1; $i <= $tracking_list_db_row; $i++) {
  $entries = mysql_fetch_array ($tracking_list_db);

  $direction='---';
  if (($entries['direction'] >   '0') and ($entries['direction'] <  '45' )) { $drection='S';}
  if (($entries['direction'] >  '46') and ($entries['direction'] < '125' )) { $direction='V';}
  if (($entries['direction'] > '126') and ($entries['direction'] < '225' )) { $direction='J';}
  if (($entries['direction'] > '226') and ($entries['direction'] < '275' )) { $direction='Z';}
  if (($entries['direction'] > '276') and ($entries['direction'] < '366' )) { $direction='S';}

  $bgmiesto='#ffffff';

  include ('locations.php');
############################################
############## GPS TO ADDRESS ##############
############################################
  $GET_LAT=substr($entries['lat'], 0, 7);
  $GET_LON=substr($entries['lon'], 0, 7);
  $MIESTO_DB=MySQL_Query("SELECT miesto FROM $MySQL_table3 WHERE lat like '$GET_LAT%' and lon like '$GET_LON%' AND status='OK' LIMIT 1;");
  $MIESTO_DB_ROW = MySQL_numrows ($MIESTO_DB);
  if ($MIESTO_DB_ROW == "1" ) {
   $MIESTO=mysql_fetch_array ($MIESTO_DB);
   $miesto=$MIESTO['miesto'];
  } else {
   $url="https://maps.googleapis.com/maps/api/geocode/json?key=$GAPIKEY&latlng=$GET_LAT,$GET_LON";
   $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_VERBOSE, true);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_USERAGENT, $agent);
   curl_setopt($ch, CURLOPT_URL,$url);
   if(curl_exec($ch) === false) {
    $miesto="CURL_ERR: no info";
   } else {
    $json = curl_exec($ch);
    curl_close($ch);
    $someJSON = json_encode($json);
    $decoded=json_decode($someJSON,true);
    preg_match('"(.*)\"formatted_address\" : \"(.*)\""',$decoded,$m);
    if($m[2] != '') {
     $miesto="GGL: ".$m[2];
     MySQL_Query("INSERT INTO $MySQL_table3 VALUES('$GET_LAT','$GET_LON','$m[2]','OK');");
    } else {
     $miesto="GGL ERR: no info";
    }
   }
  }
############################################
############## GPS TO ADDRESS ##############
############################################

  if ($entries['provider'] == 'network') { $bgmiesto='#f0f000'; }
  if ($entries['bat'] < '20') { $bgmiesto='#ff8000';}
  if ($entries['bat'] < '10') { $bgmiesto='#ff0000';}
  $SPD=$entries['spd']*3.6;
  if ($SPD < '3.8') {$SPD='0';}

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

  if ($SHOW == "1") {
  $ZTK_PARAM_DATA='
  <td bgcolor="'.$bgmiesto.'">'.$entries['temprpi'].'</td>
  <td bgcolor="'.$bgmiesto.'">'.$entries['loadrpi'].'</td>';
  } else {
   $ZTK_PARAM_DATA='';
  }

  $WEB_MIDDLE=$WEB_MIDDLE.' <tr>
  <td bgcolor="'.$bgmiesto.'">'.$entries['id'].'</td>
  <td bgcolor="'.$bgmiesto.'">'.$i.'</td>
  <td bgcolor="'.$bgmiesto.'"><a href="osm.php?id='.$entries['id'].'&amp;lat='.$entries['lat'].'&amp;lon='.$entries['lon'].'&amp;zoom=18" target="_blank" style="text-decoration:none">'.round($entries['lat'],4).' / '.round($entries['lon'],4).'</a></td>
  <td bgcolor="'.$bgmiesto.'">'.round($entries['alt'],3).' m.n.m.</td>
  <td bgcolor="'.$bgmiesto.'">+/- '.round($entries['acc'],3).' m</td>
  <td bgcolor="'.$bgmiesto.'" >'.round($SPD,2).' km/h</td>
  <td bgcolor="'.$bgmiesto.'" align="center">'.$direction.'</td>
  <td bgcolor="'.$bgmiesto.'" align="center">'.$entries['sat'].'</td>
  <td bgcolor="'.$bgmiesto.'">'.str_replace("T"," / ",$entries['time']).'</td>
  <td bgcolor="'.$bgmiesto.'">'.$entries['ip'].'</td>
  <td bgcolor="'.$bgmiesto.'">'.$miesto.'</td>
  <td bgcolor="'.$bgmiesto.'">'.$entries['device'].'</td>'.$ZTK_PARAM_DATA.'
 </tr>
'; 

  $KM=$KM+$km2;
 }
 echo $WEB_HEADER.$WEB_MIDDLE.$WEB_FOOTER;
 echo '<p align="center">
<a href="http://validator.w3.org/check?uri=referer" target="_blank">
 <img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88" border="0">
</a> <br>
';


 echo "<font color=\"000DDA\"> Priblížne prejazdené km: ".round(($KM - $km_st),2)." </font> <br>
";
 MySQL_error();
 MySQL_close();

 $mtime = explode(' ', microtime());
 $totaltime = $mtime[0] + $mtime[1] - $starttime;
 printf ('<font color="000DDA"> Stránka vygenerovaná za %.3f sekundy. </font>', $totaltime);
}
?>

</body>
</html>
