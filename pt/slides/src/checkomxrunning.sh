#!/bin/bash


#jika checkomxrunning already running, then jgn run lagi
#PROCESS2=checkomxrunning.sh
#PIDS2=`ps cax | grep $PROCESS2 | grep -o '^[ ]*[0-9]*'`
#if [ -z "$PIDS2" ]; then
#  echo "Checkomxrunning is not running" 1>&2
#else
#  for PID2 in $PIDS2; do
#    echo "Checkomxrunning running with pid = $PID2"
#  done
#  exit 0
#fi



#cond="2016-06-28 14:24:00"
#unix_cond=1467604296

#convert to integer (deduct -2 sec)
unix_cond=$1

while true
do
 echo "Checking time to kill omxplayer..."

 unix_todate=$(date +%s)
 
 echo $unix_todate
 echo $unix_cond
if [ $unix_todate -lt $unix_cond ]; then
   echo "below condition"
else
   echo "above condition"
   sh killomx.sh
   exit 0
fi

#jika omxplayer dh tamat, kill myself
PROCESS=omxplayer.bin
PIDS=`ps cax | grep $PROCESS | grep -o '^[ ]*[0-9]*'`
if [ -z "$PIDS" ]; then
  echo "Omxplayer already finished." 1>&2
  exit 0
else
  for PID in $PIDS; do
    echo "Omxplayer running with pid = $PID"
  done
fi

sleep 2
done