#!/usr/bin/python -tt

import datetime as dt
import time
import gps
import subprocess
import os

session = gps.gps("localhost", "9999")
session.stream(gps.WATCH_ENABLE | gps.WATCH_NEWSTYLE)

out = subprocess.Popen("cat /proc/cpuinfo | grep Serial | cut -d ' ' -f2 | tr -d '\n' | tr -d '\r'", shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
device,stderr = out.communicate()

out = subprocess.Popen("/opt/vc/bin/vcgencmd measure_temp | cut -d '=' -f2 | cut -d \"'\" -f1 | tr -d '\n' | tr -d '\r'", shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
cputemp,stderr = out.communicate()

out = subprocess.Popen("cat /proc/loadavg | awk {'print $1'} | tr -d '\n' | tr -d '\r'", shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
load,stderr = out.communicate()

while True:
 try:
  report = session.next()
  print report
  if report['class'] == 'SKY':          # Reportuje pocet satelitov
   if hasattr(report, 'satellites'):
    num_sats = 0
    for satellite in report.satellites:
     if hasattr(satellite, 'used') and satellite.used:
      num_sats += 1

  if report['class'] == 'TPV':          # Reportuje lat, lon, alt, track, speed
   if hasattr(report, 'time'):
    if hasattr(report, 'lat'):
     lat = report.lat
    else:
     lat = 0.000000

    if hasattr(report, 'lon'):
     lon = report.lon
    else:
     lon = 0.000000

    if hasattr(report, 'alt'):
     alt = report.alt
    else:
     alt = 0

    if hasattr(report, 'track'):
     track = report.track
    else:
     track = 0

    if hasattr(report, 'speed'):
     speed = report.speed
    else:
     speed = 0

    if hasattr(report, 'climb'):
     climb = report.climb
    else:
     climb = 0

    if hasattr(report, 'mode'):
     mode = report.mode
    else:
     mode = 0
    print("Lat: "+str(round(lat,6))+"; Lon: "+str(round(lon,6))+"; Alt: "+str(round(alt,0))+"; Time: "+report.time+"; Speed: "+str(round(speed))+"; Track: "+str(round(track))+"; Climb: "+str(round(climb,0))+"; Mode: "+str(round(mode,0))+"; Sat: "+str(num_sats)+"; Device: "+device+"; Temp: "+cputemp+"; Load: "+(load)+"")

#    f= open("/mnt/{}.gpx".format(dt.datetime.now().strftime("%Y%m%d")),"a+") #yyyymmdd
#    f.write("<trkpt lat=\""+str(round(lat,8))+"\" lon=\""+str(round(lon,8))+"\"><ele>"+str(round(alt,0))+"</ele><time>"+report.time+"</time><speed>"+str(round(speed))+"</speed><src>gps</src><sat>"+str(num_sats)+"</sat> <devicerpi>"+device+"</devicerpi><temp>"+cputemp+"</temp><load>"+load+"</load></trkpt>\n")
#    f.close()
    f= open("/tmp/gps.gpx","w")
    f.write("LAT=+"+str(round(lat,8))+"\nLON=+"+str(round(lon,8)))
    f.close()

    os.system("/usr/bin/curl -s \"http://DEFINE_YOUR_SERVER/?lat="+str(round(lat,8))+"&lon="+str(round(lon,8))+"&time="+report.time+"&spd="+str(round(speed))+"&sat="+str(num_sats)+"&alt="+str(round(alt,0))+"&bat=100.0&acc=10.0&provider=gps&direction="+str(round(track))+"&device=NODE08&devicerpi="+device+"&temprpi="+cputemp+"&loadrpi="+load+"\" -o /dev/null")
    break

 except KeyError:
  pass
 except KeyboardInterrupt:
  quit()
 except StopIteration:
  session = None
  print("GPSD has terminated")
