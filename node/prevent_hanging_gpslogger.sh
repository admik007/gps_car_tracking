#!/bin/bash

# Check for running process. If more, kill it. Prevent hanging
ITSELF=`ps -ef | grep prevent_hanging_gpslogger | grep -v grep | wc -l`
if [ "${ITSELF}" -gt "4" ]; then
 for i in `ps -ef | grep "prevent_hanging_gpslogger" | grep -v grep | grep -v vi | awk {'print $2'}`; do
  kill -9 $i
 done
fi

PROCESSES_NR=`ps -ef | grep "python /root/scripts/get_only_gps.py" | grep -v grep | wc -l`
if [ "${PROCESSES_NR}" -gt "1" ]; then
 for i in `ps -ef | grep "python /root/scripts/get_only_gps.py" | grep -v grep | awk {'print $2'}`; do
  kill -9 $i
  logger -p info "Killed process (get_only_gps) - ${i}"
 done
else
 python /root/scripts/get_only_gps.py
fi
