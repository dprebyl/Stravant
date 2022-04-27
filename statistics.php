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
						$dist = $db->query("select * from activity as act where username=? or username in (select friend from friendship where user=?) order by act.miles desc", [$_SESSION["username"], $_SESSION["username"]])[0];
						echo "<td>Greatest Distance</td>";
						echo "<td>" . $dist["username"] . "</td>";
						echo "<td>" . $dist["name"] . "</td>";
						echo "<td>" . $dist["miles"] . "</td>";
						echo "</tr>";
						echo "<tr>";
						$dist = $db->query("select * from activity as act where username=? or username in (select friend from friendship where user=?) order by act.miles asc", [$_SESSION["username"], $_SESSION["username"]])[0];
						echo "<td>Lowest Distance</td>";
						echo "<td>" . $dist["username"] . "</td>";
						echo "<td>" . $dist["name"] . "</td>";
						echo "<td>" . $dist["miles"] . "</td>";
						echo "</tr>";
						echo "<tr>";
						$dist = $db->query("select * from activity as act where username=? or username in (select friend from friendship where user=?) order by act.duration desc", [$_SESSION["username"], $_SESSION["username"]])[0];
						echo "<td>Greatest Duration</td>";
						echo "<td>" . $dist["username"] . "</td>";
						echo "<td>" . $dist["name"] . "</td>";
						echo "<td>" . $dist["duration"] . "</td>";
						echo "</tr>";
						echo "<tr>";
						$dist = $db->query("select * from activity as act where username=? or username in (select friend from friendship where user=?) order by act.duration asc", [$_SESSION["username"], $_SESSION["username"]])[0];
						echo "<td>Lowest Duration</td>";
						echo "<td>" . $dist["username"] . "</td>";
						echo "<td>" . $dist["name"] . "</td>";
						echo "<td>" . $dist["duration"] . "</td>";
						echo "</tr>";
						echo "<tr>";
						$dist = $db->query("select username, avg(miles) as avg_miles from activity as act group by username having username=? or username in (select friend from friendship where user=?) order by avg_miles desc;", [$_SESSION["username"], $_SESSION["username"]])[0];
						echo "<td>Greatest Average Distance</td>";
						echo "<td>" . $dist["username"] . "</td>";
						echo "<td></td>";
						echo "<td>" . $dist["avg_miles"] . "</td>";
						echo "</tr>";
						echo "<tr>";
						$dist = $db->query("select username, avg(miles) as avg_miles from activity as act group by username having username=? or username in (select friend from friendship where user=?) order by avg_miles asc;", [$_SESSION["username"], $_SESSION["username"]])[0];
						echo "<td>Lowest Average Distance</td>";
						echo "<td>" . $dist["username"] . "</td>";
						echo "<td></td>";
						echo "<td>" . $dist["avg_miles"] . "</td>";
						echo "</tr>";
						echo "<tr>";
						$dist = $db->query("select username, avg(duration) as avg_dur from activity as act group by username having username=? or username in (select friend from friendship where user=?) order by avg_dur desc;", [$_SESSION["username"], $_SESSION["username"]])[0];
						echo "<td>Greatest Average Duration</td>";
						echo "<td>" . $dist["username"] . "</td>";
						echo "<td></td>";
						echo "<td>" . $dist["avg_dur"] . "</td>";
						echo "</tr>";
						echo "<tr>";
						$dist = $db->query("select username, avg(duration) as avg_dur from activity as act group by username having username=? or username in (select friend from friendship where user=?) order by avg_dur asc;", [$_SESSION["username"], $_SESSION["username"]])[0];
						echo "<td>Lowest Average Duration</td>";
						echo "<td>" . $dist["username"] . "</td>";
						echo "<td></td>";
						echo "<td>" . $dist["avg_dur"] . "</td>";
						echo "</tr>";
					?>
				</tbody>
			</table>
			</div>
		</div>
	</div>

</body>
</html>