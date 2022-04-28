<!DOCTYPE html>
<?php
	require "db.php";
	ensure_logged_in();
	
	$activity = $db->query("SELECT *, ST_AsText(gps_track) AS gps_track
							FROM activity
							WHERE activity_id = ? AND (username = ? OR username IN (SELECT friend FROM friendship WHERE user = ?))",
							[$_GET["id"], $_SESSION["username"], $_SESSION["username"]]);
	if (count($activity) == 0) {
		die("Activity does not belong to self or friend");
	}
	$activity = $activity[0];
	$categories = $db->query("SELECT cat.name, cat.color from category_assignment as ca join activity as act on ca.activity_id=act.activity_id join category as cat on ca.name=cat.name and act.username=cat.username where act.activity_id=?", [$activity["activity_id"]]);

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
	<link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="libs/bootstrap.min.css">
	<link rel="stylesheet" href="libs/leaflet.css">
	<script src="libs/jquery.slim.min.js"></script>
	<script src="libs/popper.min.js"></script>
	<script src="libs/bootstrap.min.js"></script>
	<script src="libs/autoComplete.min.js"></script>
	<script src="libs/leaflet.js"></script>
</head>
<body class="pb-4">
	<script>
		window.onload = function () {
			let categories = <?=json_encode($db->query("SELECT * FROM category where username=?;", [$_SESSION["username"]]))?>;
			let autoCompleteJS = new autoComplete({
				selector: "#categories", 
				placeHolder: "category",
				threshold: 0,
				data: {
						src: categories,
						keys: ["name"],
						filter: (list) => {
							let current = document.getElementById("categories").value.split(", ");
							let f = list.filter(c => current.indexOf(c.match) == -1);
							return f;
						}
					},
				resultsList: {
					element: (list, data) => {
						list.style="overflow-y: auto; overflow-x: hidden; width: 100%";
						list.classList.add("list-group");
					},
					noResults: true,
					maxResults: 100000,
					tabSelect: true,
					destination: "#categories"

				},
				resultItem: {
					element: (item, data) => {
						item.innerHTML = `
						<span style="white-space: nowrap; width: 100%;">
						${data.value.name}
						</span>`;
						item.classList.add("list-group-item");
						item.style.color = data.value.color;
					}
				},
				events: {
					input: {
						selection(event) {
							// Convert to list
							const feedback = event.detail;
							const input = autoCompleteJS.input;
							const selection = feedback.selection.match.trim();
							const query = input.value.split(",").map(item => item.trim());
							query.pop();
							query.push(selection);
							input.value = query.join(", ") + ", ";
							autoCompleteJS.start();
							autoCompleteJS.open();
						},
						focus: () => {
							autoCompleteJS.start();
							autoCompleteJS.open();
						},
					},
				},
				trigger: (query) => {
					return true;
				},
				query: (query) => {
					// Only query with last item
					const querySplit = query.split(",");
					const lastQuery = querySplit.length - 1;
					const newQuery = querySplit[lastQuery].trim();

					return newQuery;
				},
			});
		};
	</script>
	<nav class="navbar navbar-expand navbar-light bg-light mb-2">
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
						<div>
							<?php
								$i = 0;
								foreach ($categories as $category) {
									echo "<span style='color:" . $category["color"] . "'>" . $category["name"] . "</span>";
									if ($i < count($categories) - 1) {
										echo ", ";
									}
									$i++;
								}
							?>
						</div>
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
				<form method="POST" action="edit.php">
					<input type="hidden" name="activity_id" value="<?=$_GET["id"]?>">
					<div class="modal-body">
						<div class="form-group">
							<label for="name">Name:</label>
							<input type="text" class="form-control" id="name" name="name" value="<?=$activity["name"]?>">
						</div>
						<div class="form-group">
							<label for="categories">Categories:</label>
							<input type="text" autocomplete="off" class="form-control" id="categories" name="categories" value="<?=implode(", ", array_map(function($cat) {return $cat["name"]; }, $categories))?>">
						</div>
						<div class="form-group">
							<label for="description">Description:</label>
							<textarea class="form-control" id="description" name="description"><?=$activity["description"]?></textarea>
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