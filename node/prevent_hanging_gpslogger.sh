#!/bin/bash

# Check if GPSD is running
GPSD=`ps -ef | grep 9999| grep -v grep | wc -l`
if [ "${GPSD}" -eq "0" ]; then
 /root/scripts/gps_activate.sh &
fi

# Check for running process. If more, kill it. Prevent hanging
ITSELF=`ps -ef | grep prevent_hanging_gpslogger | egrep -v "grep|vi" | wc -l`
if [ "${ITSELF}" -gt "4" ]; then
 for i in `ps -ef | grep "prevent_hanging_gpslogger" | egrep -v "grep|vi" | awk {'print $2'}`; do
  kill -9 $i
 done
fi

PROCESSES_NR=`ps -ef | grep "python /root/scripts/get_only_gps.py" | grep -v SCREEN | grep -v grep | wc -l`
if [ "${PROCESSES_NR}" -eq "1" ]; then
 exit 0
else
 if [ "${PROCESSES_NR}" -gt "1" ]; then
  for i in `ps -ef | grep "python /root/scripts/get_only_gps.py" | grep -v grep | awk {'print $2'}`; do
   kill -9 $i
   logger -p info "Killed process (get_only_gps) - ${i}"
  done
  screen -S GET_GPS -dm python /root/scripts/get_only_gps.py
 else
  screen -S GET_GPS -dm python /root/scripts/get_only_gps.py
 fi
fi
