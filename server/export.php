<?php
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

if (empty($_GET["device"])) $device='SL514BS';
if(isset($_GET["device"])) $device=$_GET["device"];



@include"_config.php";
$filename = "${device}_${year}.${month}.${day}";         //File Name

//create MySQL connection
#$sql = "Select lat AS Latitude, lon AS Longiture, alt AS 'Nadmorska vyska', spd AS Rychlost, sat AS Satelitov, ip AS IP, time AS 'Datum a Cas', device AS SPZ, direction AS Smer from $MySQL_table1 WHERE device='$device' AND time like '$year-$month-$day%' GROUP BY DATE_FORMAT(`time`, '%H:%i') order by time desc";

$sql="SELECT ROUND($MySQL_table1.lat,4) AS Latitude, ROUND($MySQL_table1.lon,4) AS Longiture, alt AS 'Nadmorska vyska', spd AS Rychlost, sat AS Satelitov, ip AS IP, replace(replace(time, 'Z', ''),'T',' ') AS 'Datum a Cas', device AS SPZ, direction AS Smer, $MySQL_table3.miesto AS Adresa FROM $MySQL_table1 INNER JOIN $MySQL_table3 ON ROUND($MySQL_table1.lat,4)=$MySQL_table3.lat WHERE device='$device' AND time like '$year-$month-$day%' GROUP BY DATE_FORMAT(`time`, '%H:%i') order by time desc";


$Connect = @mysql_connect($MySQL_server, $MySQL_user, $MySQL_user_password) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());

//select database
$Db = @mysql_select_db($MySQL_db, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());

//execute query
$result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());
$file_ending = "xls";

//header info for browser

header('Content-Encoding: UTF-8');
header("Content-Type: application/xls; charset=UTF-8");
header("Content-Disposition: attachment; filename=$filename.csv");
echo "\xEF\xBB\xBF"; // UTF-8 BOM
header("Pragma: no-cache");
header("Expires: 0");

/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = ";"; //tabbed character

//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {

echo mysql_field_name($result,$i) . $sep;
}
print("\n");
//end of printing column names

//start while loop to get data
    while($row = mysql_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }
?>
