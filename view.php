<!DOCTYPE html>
<?php
	require "db.php";
	
	$activity = $db->query("SELECT *, ST_AsText(gps_track) AS gps_track
							FROM activity
							WHERE activity_id = ? AND username = ?", // TODO: Also allow viewing activities of friends
							[$_GET["id"], $_SESSION["username"]])[0];

	// Convert GPS track from "LINESTRING(lat lon,lat lon,...)" to array
	$coords = [];
	foreach (explode(",", substr($activity["gps_track"], 11, -1)) as $latlon) {
		$coords[] = explode(" ", $latlon);
	}
?>
<html>
<head>
	<title>Stravan't</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="libs/bootstrap.min.css">
	<link rel="stylesheet" href="libs/leaflet.css">
	<script src="libs/jquery.slim.min.js"></script>
	<script src="libs/popper.min.js"></script>
	<script src="libs/bootstrap.min.js"></script>
	<script src="libs/leaflet.js"></script>
</head>
<body>
	<nav class="navbar navbar-expand navbar-light bg-light">
		<a class="navbar-brand" href="home.php">
			<img src="logo.png" alt="Stravan't" height="30">
		</a>
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" href="home.php">Home</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="statistics.php">Records and statistics</a>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<span class="navbar-text">
					Logged in as <?=$_SESSION["username"]?>
				</span>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="logout.php">Logout</a>
			</li>
		</ul>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<h1><?=$activity["name"]?></h1>
				<div id="map" style="height: 400px"></div>
				<script>
					const MAP_STYLES = [ // https://docs.mapbox.com/api/maps/styles/
						"mapbox/outdoors-v11",
						"mapbox/streets-v11",
						"mapbox/satellite-streets-v11",
						"mapbox/satellite-v9",
						"mapbox/dark-v10",
						"mapbox/navigation-night-v1",
					];

					// Default to campus
					var map = L.map('map', {center: [38.957, -95.253], zoom: 15});
					
					let baseLayers = [];
					for (let mapStyle of MAP_STYLES) {
						// Clean up the name (really I should just store names for each layer)
						let layerName = mapStyle.split("/")[1];
						layerName = layerName.slice(0, layerName.lastIndexOf("-"));
						layerName = layerName.replace("-", " ");
						layerName = layerName.charAt(0).toUpperCase() + layerName.slice(1);

						baseLayers[layerName] = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
							attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery &copy; <a href="https://www.mapbox.com/">Mapbox</a>',
							maxZoom: 18,
							id: mapStyle,
							tileSize: 512,
							zoomOffset: -1,
							accessToken: "<?=MAPBOX_KEY?>"
						});
					}
					
					// Select the first base layer by default
					baseLayers[Object.keys(baseLayers)[0]].addTo(map);
					
					let name = "<?=$activity["name"]?>"
					let coords = <?=json_encode($coords)?>;
					let color = "red"; // TODO: Based on the first category maybe?

					let line = L.polyline(coords, {"color": color}).addTo(map);
					let layers = {"Activity": line};
					map.fitBounds(line.getBounds());

					L.control.layers(baseLayers, layers).addTo(map); // User controls in top-right
				</script>
			</div>
			<div class="col-lg-4">
				<h1>
					Details
					<button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#edit">Edit</a>
				</h1>
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between">
						<b>Date/time</b>
						<span><?=date("n/d/y g:ia", strtotime($activity["start_time"]))?></span>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<b>Distance</b>
						<span><?=number_format($activity["miles"], 2)?> mi</span>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<b>Duration</b>
						<span><?=gmdate("G:i:s", $activity["duration"])?></span>
					</li>
					<li class="list-group-item">
						<b>Categories</b>
						<div>TODO</div>
					</li>
					<li class="list-group-item">
						<b>Description</b>
						<div><?=$activity["description"]?></div>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="modal fade" id="edit" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="POST" action="view.php">
					<div class="modal-body">
						<div class="form-group">
							<label for="file">TODO:</label>
							<input type="text" class="form-control-file" id="file" name="file" value="TODO" disabled>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>