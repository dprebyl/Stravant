<?php
    require "db.php";
    ensure_logged_in();
    
    $file = $_FILES["file"];
    $filename = $file["name"];

    // Check it is a GPX file
    if (strtolower(substr($filename, -4)) != ".gpx") {
        die("Error: File must be .gpx format");
    }

    $gpx = simplexml_load_file($file["tmp_name"]) or die("Failed to load file");
    // TODO: Parse the GPX, insert into database, and redirect to view.php for the new activity
	
	$start = substr($gpx->metadata->time, 0, 20); // Time activity started, converted to SQL-friendly format
	$track = $gpx->trk;
	$name = $track->name; // Name of activity
	$type = $track->type; // e.g. cycling (TODO: maybe make this a category automatically?)
    // TODO: Check if desc is present
	
	$points = []; // Array to contain all points
	foreach ($track->trkseg->trkpt as $point) {
		$lat = floatval($point["lat"]);
		$lon = floatval($point["lon"]);
		$ele = floatval($point->ele);
		$time = strtotime($point->time);
		$points[] = [$time, $lat, $lon, $ele];
	}

    // Convert points array to MySQL line string format
    $linestring = "LINESTRING(" . implode(",", array_map(function($point) { return $point[1] . " " . $point[2]; }, $points)) . ")";
	
	$db->query("INSERT INTO activity (username, start_time, name, gps_track) VALUES (?, ?, ?, ST_LineStringFromText(?))", [$_SESSION["username"], $start, $name, $linestring]);
	
	// TODO: Get the primary key back
	//header("Location: view.php?activity=");
?>