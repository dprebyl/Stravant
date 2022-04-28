<!DOCTYPE html>
<?php require "db.php"; ?>
<html>
<head>
	<title>Stravan't</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="libs/bootstrap.min.css">
	<script src="libs/jquery.slim.min.js"></script>
	<script src="libs/popper.min.js"></script>
	<script src="libs/bootstrap.min.js"></script>
</head>
<body class="pb-4">
	<nav class="navbar navbar-expand navbar-light bg-light mb-2">
		<a class="navbar-brand" href="home.php">
			<img src="logo.png" alt="Stravan't" height="30">
		</a>
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" href="home.php">Home</a>
			</li>
			<li class="nav-item active">
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
			<div class="col">
				<h1>
					Records
				</h1>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col">
			<table class="table">
				<thead>
					<th>Record</th>
					<th>User</th>
					<th>Activity</th>
					<th></th>
				</thead>
				<tbody>
					<?php
						echo "<tr>";
						$result = $db->query("select username, name, round(miles, 2) as miles, activity_id from activity as act where username in (select friend from mutuals where user=?) order by act.miles desc", [$_SESSION["username"]])[0];
						echo "<td>Greatest Distance</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td><a href='view.php?id=" . $result["activity_id"] . "'>". $result["name"] . "</a></td>";
						echo "<td>" . $result["miles"] . " miles</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, name, round(miles, 2) as miles, activity_id from activity as act where username in (select friend from mutuals where user=?) order by act.miles asc", [$_SESSION["username"]])[0];
						echo "<td>Lowest Distance</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td><a href='view.php?id=" . $result["activity_id"] . "'>". $result["name"] . "</a></td>";
						echo "<td>" . $result["miles"] . " miles</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, name, round(duration/60/60, 2) as duration, activity_id from activity as act where username in (select friend from mutuals where user=?) order by act.duration desc", [$_SESSION["username"]])[0];
						echo "<td>Greatest Duration</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td><a href='view.php?id=" . $result["activity_id"] . "'>". $result["name"] . "</a></td>";
						echo "<td>" . $result["duration"] . " hours</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, name, round(duration/60/60, 2) as duration, activity_id from activity as act where username in (select friend from mutuals where user=?) order by act.duration asc", [$_SESSION["username"]])[0];
						echo "<td>Lowest Duration</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td><a href='view.php?id=" . $result["activity_id"] . "'>". $result["name"] . "</a></td>";
						echo "<td>" . $result["duration"] . " hours</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, round(avg(miles), 2) as avg_miles from activity as act group by username having username in (select friend from mutuals where user=?) order by avg_miles desc;", [$_SESSION["username"]])[0];
						echo "<td>Greatest Average Distance</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td></td>";
						echo "<td>" . $result["avg_miles"] . " miles</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, round(avg(miles), 2) as avg_miles from activity as act group by username having username in (select friend from mutuals where user=?) order by avg_miles asc;", [$_SESSION["username"]])[0];
						echo "<td>Lowest Average Distance</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td></td>";
						echo "<td>" . $result["avg_miles"] . " miles</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, round(avg(duration)/60/60, 2) as avg_dur from activity as act group by username having username in (select friend from mutuals where user=?) order by avg_dur desc;", [$_SESSION["username"]])[0];
						echo "<td>Greatest Average Duration</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td></td>";
						echo "<td>" . $result["avg_dur"] . " hours</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, round(avg(duration)/60/60, 2) as avg_dur from activity as act group by username having username in (select friend from mutuals where user=?) order by avg_dur asc;", [$_SESSION["username"]])[0];
						echo "<td>Lowest Average Duration</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td></td>";
						echo "<td>" . $result["avg_dur"] . " hour</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, round(miles/duration*60*60, 2) as speed, name, activity_id from activity as act where username in (select friend from mutuals where user=?) order by speed desc", [$_SESSION["username"]])[0];
						echo "<td>Fastest Speed</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td><a href='view.php?id=" . $result["activity_id"] . "'>". $result["name"] . "</a></td>";
						echo "<td>" . $result["speed"] . " miles/hour</td>";
						echo "</tr>";
						echo "<tr>";
						$result = $db->query("select username, round(miles/duration*60*60, 2) as speed, name, activity_id from activity as act where username in (select friend from mutuals where user=?) order by speed asc", [$_SESSION["username"]])[0];
						echo "<td>Slowest Speed</td>";
						echo "<td><a href='home.php?friend=" . $result["username"] . "'>". $result["username"] . "</a></td>";
						echo "<td><a href='view.php?id=" . $result["activity_id"] . "'>". $result["name"] . "</a></td>";
						echo "<td>" . $result["speed"] . " miles/hour</td>";
						echo "</tr>";
					?>
				</tbody>
			</table>
			</div>
		</div>
	</div>

</body>
</html>