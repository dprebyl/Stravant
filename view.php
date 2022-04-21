<!DOCTYPE html>
<?php
	require "db.php";
	// TODO: Check the user has permission (their own activity or an activity of someone who friended them)
	$activity = $db->query("SELECT * FROM activity WHERE id = ?", [$_GET["id"]]);
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
			<div class="col-sm-8">
				<h1>
					TODO: Activity name
				</h1>
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
					
					// TODO: Get these things from the database
					let name = "TODO activity name"
					let coords = [[38.9, -95.2], [39.0, -95.3], [39.0, -95.1]];
					let color = "red";

					let line = L.polyline(coords, {"color": color}).addTo(map);
					let layers = {"Activity": line};
					map.fitBounds(line.getBounds());

					L.control.layers(baseLayers, layers).addTo(map); // User controls in top-right
				</script>
			</div>
			<div class="col-sm-4">
				<h1>
					Details
					<button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#edit-details">Edit</a>
				</h1>
				TODO: Categories, miles, etc. go here
			</div>
		</div>
	</div>

</body>
</html>