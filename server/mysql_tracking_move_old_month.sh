#!/bin/bash
DATE=`date '+%Y-%m'`
echo "INSERT INTO gps_tracking_archive SELECT * FROM gps_tracking WHERE time not like '${DATE}%' ORDER BY id DESC; " | mysql  --defaults-extra-file=/root/.mysql/mysqldump.cnf DATABASE
echo "DELETE FROM gps_tracking WHERE time not like '${DATE}%'; " | mysql  --defaults-extra-file=/root/.mysql/mysqldump.cnf DATABASE
