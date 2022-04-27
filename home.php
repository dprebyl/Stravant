<!DOCTYPE html>
<?php
	require "db.php";
	ensure_logged_in();

	if (isset($_GET["friend"])) {
		// TODO: Check the friend is valid. If so, display the friend's activities and categories, but read only (no delete)
		$username = $_GET["friend"];
	}
	else {
		$username = $_SESSION["username"];
	}
	
	if (isset($_GET["category"])) {
		// TODO: Handle filtering by category
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
			<li class="nav-item active">
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
				<h1>
					<?=isset($_GET["category"]) ? $_GET["category"] : "All"?> activities of <?=$username?>
					<button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#upload">Upload</a>
				</h1>
				<!-- TODO: Map of all activities -->
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Date/Time</th><th>Name</th><th>Distance</th><th>Duration</th><th></th>
						</tr>
					</thead>
					<tbody>
						<?php
							// TODO: Maybe join could be used here to get color of a category or something, also could double-check friendship
							$activities = $db->query("SELECT activity_id, name, start_time, miles, duration FROM activity WHERE username = ?", [$username]);
							foreach ($activities as $activity) {
								echo "<tr>";
								echo "<td>" . date("n/d/y g:ia", strtotime($activity["start_time"])) . "</td>";
								echo "<td><a href='view.php?id=" . $activity["activity_id"] . "'>" . $activity["name"] . "</a></td>";
								echo "<td>" . number_format($activity["miles"], 2) . " mi</td>";
								echo "<td>" . gmdate("G:i", $activity["duration"]) . "</td>";
								echo "<td class='text-right'><a href='delete-activity.php?activity=" . $activity["activity_id"] . "' class='text-danger font-weight-bold'>&times;</a></td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
			<script>
				function removeFriend(friend){
					document.getElementById("delete-friend-target-text").innerText=friend;
					document.getElementById("delete-friend-target").value = friend;
				}
				function deleteCategory(category){
					document.getElementById("delete-category-target-text").innerText=category;
					document.getElementById("delete-category-target").value = category;
				}
				function updateCategory(category, color){
					document.getElementById("categoy-name").value=category;
					document.getElementById("categoy-name").innerText=category;
					document.getElementById("color").innerText=color;
					document.getElementById("color").value=color;
					document.getElementById("original-category").value=category;
					document.getElementById("delete-category-target").value = category;
				}
			</script>
			<!-- TODO: Display these horizontally on small screens https://stackoverflow.com/questions/65222546/can-bootstrap-columns-be-vertically-stacked -->
			<div class="col-lg-4">
				<h1>
					Friends
					<button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#add-friend">Add</a>
				</h1>
				<table class="table table-sm table-striped">
					<thead>
						<tr>
							<th>Username</th><th></th>
						</tr>
					</thead>
					<tbody>
						<?php
							if (isset($_SESSION["friend_error"])) {
								echo '<div class="alert alert-danger" role="alert">';
								echo $_SESSION["friend_error"] . ".";
								echo "</div>";
								unset($_SESSION["friend_error"]);
							}
						?>
						<?php
							$friends = $db->query("SELECT friend FROM friendship WHERE user = ?", [$username]);
							foreach ($friends as $friend) {
								echo "<tr>";
								echo "<td><a href='home.php?friend=" . $friend["friend"] . "'>" . $friend["friend"] . "</a></td>";
								echo "<td class='text-right'><a href='#delete-friend' data-toggle='modal' data-target='#delete-friend' onclick='removeFriend(\"" . $friend["friend"] . "\")' class='text-danger font-weight-bold'>&times;</a></td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
				<h1>
					Categories
					<button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#add-category">Add</a>
				</h1>
				<table class="table table-sm table-striped">
					<thead>
						<tr>
							<th>Category</th>
							<th>Color</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
							if (isset($_SESSION["category_error"])) {
								echo '<div class="alert alert-danger" role="alert">';
								echo $_SESSION["category_error"] . ".";
								echo "</div>";
								unset($_SESSION["category_error"]);
							}
						?>
						<?php
							$categories = $db->query("SELECT name, color FROM category WHERE username = ?", [$username]);
							foreach ($categories as $category) {
								echo "<tr>";
								$url = "home.php?category=" . $category["name"];
								if (isset($_GET["friend"])) $url .= "&friend=" . $_GET["friend"];
								echo "<td><a href='$url' style='color:" . $category["color"] ."'>" . $category["name"] . "</td>";
								echo "<td class='text-right'><a href='#add-category' data-toggle='modal' data-target='#add-category' onclick='updateCategory(\"" . $category["name"] . "\", \"" . $category["color"] . "\")'>edit</a></td>";
								echo "<td class='text-right'><a href='#delete-category' data-toggle='modal' data-target='#delete-category' onclick='deleteCategory(\"" . $category["name"] . "\")' class='text-danger font-weight-bold'>&times;</a></td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="upload" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Upload a new activity</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="POST" action="upload.php" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="form-group">
							<label for="file">Select GPX file (or drag and drop):</label>
							<input type="file" class="form-control-file" id="file" name="file">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success">Upload</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="add-friend" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add a friend</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="POST" action="add-friend.php">
					<div class="modal-body">
						<div class="form-group">
							<label for="friend">Username:</label>
							<input type="text" class="form-control" id="friend" name="friend">
						</div>
						<p>
							Note: They must also add you as a friend before you can view their activities.
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="delete-friend" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Remove Friend</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="POST" action="delete-friend.php">
					<div class="modal-body">
						Remove friend <span id="delete-friend-target-text"></span>?
					</div>
					<input type="text" hidden id="delete-friend-target" name="friend" class="form-control" />
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-danger">Remove</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="delete-category" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Delete Category</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="POST" action="delete-category.php">
					<div class="modal-body">
						Delete category <span id="delete-category-target-text"></span>?
					</div>
					<input type="text" hidden id="delete-category-target" name="category" class="form-control" />
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-danger">Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="add-category" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add a category</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="POST" action="add-category.php">
					<div class="modal-body">
						<div class="form-group">
							<label for="categoy-name">Name:</label>
							<input type="text" class="form-control" id="categoy-name" name="categoy-name">
						</div>
						<div class="form-group">
							<label for="color">Color:</label>
							<input type="color" class="form-control" id="color" name="color">
						</div>
						<input type="text" hidden id="original-category" name="original-category" class="form-control" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>
</html>