#!/bin/bash
kill $(ps aux | grep '[r]edis' | awk '{print $2}') > /dev/null
kill $(ps aux | grep '[n]ode' | awk '{print $2}') > /dev/null
kill $(ps aux | grep '[g]ulp' | awk '{print $2}') > /dev/null
gulp watch >/dev/null & 
redis-server >/dev/null & 
node socket.js >/dev/null & 