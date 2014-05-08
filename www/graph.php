<?php
//  function graphGenerate() {
	unlink("temp.png");
	include("Class/TemperatureStats.php");
	$temp = new TemperatureStats();
	$measures = array_reverse($temp->getLastWeek());
	
	$options = array(
		"--step", "300",            // Use a step-size of 5 minutes
//		"--start", "-6 months",     // this rrd started 6 months ago
		"-b", strtotime($measures[0]["timestamp"]),
		"DS:temp:GAUGE:600:0:50",
		"DS:hum:GAUGE:600:0:100",
		"RRA:AVERAGE:0.5:1:288",
		"RRA:AVERAGE:0.5:12:168",
		"RRA:AVERAGE:0.5:228:365",
	);
	    
	$ret = rrd_create("data/data.rrd", $options);
	if (!$ret) {
		echo "<b>Creation error: </b>".rrd_error()."\n";
	}
	
	foreach ($measures as $key => $measure) {
		$timestamp = strtotime($measure["timestamp"]);

		$temperature = $measure["temperature"];
		$humidity = $measure["humidity"];
		$data = array($timestamp . ":" . $temperature . ":" . $humidity);
		$ret = rrd_update("data/data.rrd", $data);
	}
	
	$graphOptions = array(
			"DEF:temp=data/data.rrd:temp:AVERAGE", 
			"LINE4:temp#FF0000:Temperatura *C\j", 
//			"CDEF:temptrend=temp,2048,TREND",
//			"LINE2:temptrend#99aaFF",
			"CDEF:temppredict=300,-7,1800,temp,PREDICT",
			"LINE2:temppredict#ccaabb",
			"GPRINT:temp:LAST:Aktualnie\:  %2.2lf *C ",
			"GPRINT:temp:MIN:Minimum\:%9.2lf%s*C ",
			"GPRINT:temp:AVERAGE:Średnio\:%9.2lf%s*C ",
			"GPRINT:temp:MAX:Maximum\:%9.2lf%s*C \j",

			"DEF:hum=data/data.rrd:hum:AVERAGE", 
			"LINE3:hum#00FF00:Wilgotność %\j",
			"CDEF:humtrend=hum,2048,TREND",
			"LINE2:humtrend#99aa00",
			"CDEF:humpredict=86400,-7,3600,hum,PREDICT",
			"LINE2:humpredict#ccaabb",
			"GPRINT:hum:LAST:Aktualnie\: %2.2lf %% ",
			"GPRINT:hum:MIN:Minimum\:%9.2lf%s%% ",
			"GPRINT:hum:AVERAGE:Średnio\:%9.2lf%s%% ",
			"GPRINT:hum:MAX:Maximum\:%9.2lf%s%% ",
			
			"-v *C / wilgotność %", "--right-axis", "1:0",
			"-w 600", "-h 200", "--slope-mode"
		);
	rrd_graph("temp.png", $graphOptions);
	
	$weekly = $graphOptions;
	array_push($weekly, "--start");
	array_push($weekly, "-1w");
	rrd_graph("tempweek.png", $weekly);

	$hour = $graphOptions;
	array_push($hour, "--start");
	array_push($hour, "-5h");
	array_push($hour, "--end");
	array_push($hour, "+1h");
	rrd_graph("temphour.png", $hour);	
	echo rrd_error();
//  }
?>
