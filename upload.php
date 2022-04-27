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
	
	$start = $gpx->metadata->time; // Time activity started
	$track = $gpx->trk;
	$name = $track->name; // Name of activity
	$type = $track->type; // e.g. cycling (TODO: maybe make this a category automatically?)
    $description = property_exists($track, "desc") ? $track->desc : $type;
	
	$points = []; // Array to contain all points
	foreach ($track->trkseg->trkpt as $point) {
		$lat = floatval($point["lat"]);
		$lon = floatval($point["lon"]);
		$ele = floatval($point->ele);
		$time = strtotime($point->time);
		$points[] = [$time, $lat, $lon, $ele];
	}

    // How many seconds long the activity is (from the last track point)
    $duration = $time - strtotime($start);

    // Convert points array to MySQL line string format
    $linestring = "LINESTRING(" . implode(",", array_map(function($point) { return $point[1] . " " . $point[2]; }, $points)) . ")";
	
    // 4326 is the spatial reference system WGS84 used for the Earth
	$id = $db->query("INSERT INTO activity (username, name, duration, description, start_time, gps_track) 
                VALUES (?, ?, ?, ?, ?, ST_LineStringFromText(?, 4326))", 
        [$_SESSION["username"], $name, $duration, $description, substr($start, 0, 20), $linestring]);
	
	if ($id) header("Location: view.php?activity=" . $id);
    else die("Something went wrong");
?>