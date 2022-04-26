<!DOCTYPE html>
<?php
	require "db.php";

	$error = false;

	if (isset($_POST["username"])) {
		$user = $db->query("SELECT password FROM user WHERE username = ?", [$_POST["username"]]);
		if (count($user) != 1) {
			$error = "Username not found";
		}
		elseif (!password_verify($_POST["password"], $user[0]["password"])) {
			$error = "Incorrect password";
		}
		else {
			$_SESSION["username"] = $_POST["username"];
			header("Location: home.php");
		}
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
	<div class="container">
		<div class="row">
			<div class="col text-center">
				<img src="logo.png" class="my-4" alt="Stravan't">
				<form method="POST">
					<?php if ($error): ?>
						<div class="alert alert-danger" role="alert">
							<?=$error?>. Maybe it would be easier to just <a href="steal.php">steal an account</a>?
						</div>
					<?php endif; ?>
					<div class="form-group row">
						<label for="username" class="col-sm-2 col-form-label">Username</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="username" name="username">
						</div>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password" name="password">
						</div>
					</div>
					<input type="submit" class="btn btn-primary" value="Login">
				</form>
				<hr>
				<p>
					<a href="register.php">Register</a>
					or
					<a href="steal.php">steal an account</a>
				</p>
			</div>
		</div>
	</div>
</body>
</html>