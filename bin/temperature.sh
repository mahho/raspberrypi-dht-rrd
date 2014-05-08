#!/bin/bash

function save {
	measure=`/root/Adafruit_DHT 17` #path to adafruit dht, 17 - pin number
	temperature=`echo $measure | awk '{ print $2 }'`
	humidity=`echo $measure | awk '{ print $4 }'`
	echo "Insert into measures (temperature, humidity) values ('$temperature', '$humidity');" | mysql -u User -pPassword temperatures;
}

ps=`ps -ef | grep fruit | grep Ada`;
if [ $ps -n ]; then
	save;
else
	#sometimes adafruit is not responding...
	echo 'killing temp' `date "+%Y-%m-%d %H:%M:%S"` >> /root/bin/adafruit.log;
	killall -9 /root/Adafruit_DHT_17;
	sleep 5;
	save;
fi;


#echo "Insert into measures (temperature, humidity) values ('$temperature', '$humidity');" | mysql -u temp -pjajwogUrim3 temperatures ;