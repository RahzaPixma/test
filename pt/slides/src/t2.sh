#!/bin/bash
#run omx
pid_omx=./pid_omx
if [ -f "$pid_omx" ] && kill -0 `cat $pid_omx` 2>/dev/null; then
    echo "Omxplayer is still running..."
else 
    omxplayer -o hdmi ./slides/videos/$1 &
    echo $! > $pid_omx
    sleep 2
fi 
