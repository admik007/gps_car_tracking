#!/bin/bash
#stty -F /dev/ttyS0 -echo
MODEL=`cat /proc/cpuinfo | grep Model | cut -d ':' -f2 | cut -d ' ' -f1,2,3,4`
if [ "${MODEL}" == " Raspberry Pi Zero" ]; then
 gpsd /dev/ttyS0 -nb -S 9999 -G -F /var/run/gpsd.sock
else
 gpsd /dev/ttyAMA0 -nb -S 9999 -G -F /var/run/gpsd.sock
fi
