<!DOCTYPE html>
<?php
	require "db.php";
	$error = false;
	if (isset($_POST["username"])) {
		$_SESSION["username"] = $_POST["username"];
		header("Location: home.php");
	}
?>
<html>
<head>
	<title>Stravan't</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="libs/bootstrap.min.css">
	<script src="libs/jquery.slim.min.js"></script>
	<script src="libs/popper.min.js"></script>
	<script src="libs/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col text-center">
				<img src="logo.png" class="my-4" alt="Stravan't">
				<h1>Steal an account</h1>
				<p>Choose any existing account to login to, no password required!</p>
				<form method="POST">
					<div class="form-group row">
						<label for="username" class="col-sm-2 col-form-label">Username</label>
						<div class="col-sm-10">
							<select type="text" class="form-control" id="username" name="username">
								<?php
									$usernames = array_column($db->query("SELECT username FROM user"), "username");
									foreach ($usernames as $username) {
										echo "<option value='$username'>$username</option>";
									}
								?>
							</select>
						</div>
					</div>
					<input type="submit" class="btn btn-primary" value="Steal">
				</form>
				<hr>
				<p>
					<a href="index.php">Login</a>
					or
					<a href="register.php">register a new account</a>
				</p>
			</div>
		</div>
	</div>
</body>
</html>