#!/bin/bash
#sh simomxplayer.sh &
#sleep 2
#sh checkomxrunning.sh "$1" &
#exit 0


#run omx
pid_omx=./pid_omx
if [ -f "$pid_omx" ] && kill -0 `cat $pid_omx` 2>/dev/null; then
    echo "Omxplayer is still running..."
else 
    omxplayer -o hdmi "$1" &
    echo $! > $pid_omx
    sleep 2
fi  


#run checkingtime
pid_chk=./pid_chk
if [ -f "$pid_chk" ] && kill -0 `cat $pid_chk` 2>/dev/null; then
    echo "Checkingomx is still running..."
    exit 1
else 
    sh checkomxrunning.sh "$2" &
    echo $! > $pid_chk
fi  


exit 0