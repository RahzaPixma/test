#!/bin/bash

cond="2016-07-28 14:24:00"
#cond=$1

while true
do
 echo "Running..."

 unix_todate=$(date +%s)
 unix_cond=$(date -d "${cond}" "+%s")
 echo ${unix_todate}
 echo ${unix_cond}
if [ $unix_todate -lt $unix_cond ]
then
   echo "below condition"
else
   echo "above condition"
   #killall omxplayer.bin
fi

sleep 2
done