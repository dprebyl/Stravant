<?php
    require "db.php";
    
    $file = $_FILES["file"];
    $filename = $file["name"];

    // Check it is a GPX file
    if (strtolower(substr($filename, -4)) != ".gpx") {
        die("Error: File must be .gpx format");
    }

    $gpx = simplexml_load_file($file["tmp_name"]) or die("Failed to load file");
    // TODO: Parse the GPX, insert into database, and redirect to view.php for the new activity
	
	$start = strtotime($gpx->metadata->time); // Time activity started
	$track = $gpx->trk;
	$name = $track->name; // Name of activity
	$type = $track->type; // e.g. cycling (TODO: maybe make this a category automatically?)
	
	$points = []; // Array to contain all points
	foreach ($track->trkseg->trkpt as $point) {
		$lat = floatval($point["lat"]);
		$lon = floatval($point["lon"]);
		$ele = floatval($point->ele);
		$time = strtotime($point->time);
		$points[] = [$time, $lat, $lon, $ele];
	}
	
	// TODO: Finish and test query
	// $db->query("INSERT INTO activity (username, start, name, track) VALUES (?, ?, ?, ?)", [$_SESSION["username"], $start, $name, json_encode($points)]);
	
	// TODO: Get the primary key back
	//header("Location: view.php?activity=");
?>