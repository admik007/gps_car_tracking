<?php
@include"_config.php";

mysql_connect($MySQL_server, $MySQL_user, $MySQL_user_password);
$spojenie=mysql_connect($MySQL_server,$MySQL_user,$MySQL_user_password);
$spojeniedb=mysql_select_db($MySQL_db);

$tracking_list_db = MySQL_Query("SELECT * FROM $MySQL_table3 WHERE lat!='0.0' AND lon!='0.0' AND status!='OK' ORDER BY miesto DESC;");

$tracking_list_db_row = MySQL_numrows ($tracking_list_db);

for ($i = 1; $i <= $tracking_list_db_row; $i++) {
 $entries = mysql_fetch_array ($tracking_list_db);
 $GET_LAT=substr($entries['lat'], 0, 7);
 $GET_LON=substr($entries['lon'], 0, 7);
 $MIESTO_DB=MySQL_Query("SELECT * FROM $MySQL_table3 WHERE lat like '$GET_LAT%' and lon like '$GET_LON%' AND status !='OK' LIMIT 1;");
 $MIESTO_DB_ROW = MySQL_numrows ($MIESTO_DB);
 if ($MIESTO_DB_ROW == "1" ) {
  $MIESTO=mysql_fetch_array ($MIESTO_DB);
  $miesto=$MIESTO['miesto'];
  echo "INSERT INTO $MySQL_table3 VALUES('$GET_LAT','$GET_LON','$miesto','OK'); <br>";
  MySQL_Query("INSERT INTO $MySQL_table3 VALUES('$GET_LAT','$GET_LON','$miesto','OK');");

  echo "DELETE FROM $MySQL_table3 WHERE lat like '$GET_LAT%' AND lon like '$GET_LON%' AND status!='OK'; <br>";
  MySQL_Query("DELETE FROM $MySQL_table3 WHERE lat like '$GET_LAT%' AND lon like '$GET_LON%' AND status!='OK';");


  echo "<br>";
 }
}
MySQL_error();
MySQL_close();
?>

