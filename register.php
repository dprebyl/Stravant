<!DOCTYPE html>
<?php
	require "db.php";
	$error = false;
	if (isset($_POST["username"])) {
		if (!preg_match("/^\w+$/", $_POST["username"])) {
			$error = "Username can only contain letters, numbers, and underscores";
		}
		elseif ($db->query("SELECT username FROM user WHERE username = ?", [$_POST["username"]])) {
			$error = "Username already taken";
		}
		elseif (!preg_match("/^[A-Za-z]+$/", $_POST["first_name"]) || !preg_match("/^[A-Za-z]+$/", $_POST["last_name"])) {
			$error = "Name is required and can only contain letters";
		}
		elseif ($_POST["password"] != $_POST["password-confirm"]) {
			$error = "Passwords do not match";
		}
		elseif (strlen($_POST["password"]) < 8) {
			$error = "Password must be at least 8 characters";
			// Ideally there would be more password streghth requirements
		}
		else {
			// All good
			$hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
			$db->query("INSERT INTO user (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)",
				[$_POST["username"], $_POST["email"], $hash, $_POST["first_name"], $_POST["last_name"]]);
			header("Location: index.php");
		}
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
				<h1>Register a new account</h1>
				<form method="POST">
					<?php if ($error): ?>
						<div class="alert alert-danger" role="alert">
							<?=$error?>. Maybe it would be easier to just <a href="steal.php">steal an account</a>?
						</div>
					<?php endif; ?>
					<div class="form-group row">
						<label for="first_name" class="col-sm-2 col-form-label">First name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="first_name" name="first_name" value="<?=$_POST["first_name"]?:""?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="last_name" class="col-sm-2 col-form-label">Last name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="last_name" name="last_name" value="<?=$_POST["last_name"]?:""?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="email" class="col-sm-2 col-form-label">Email address</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="email" value="<?=$_POST["email"]?:""?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="username" class="col-sm-2 col-form-label">Username</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="username" name="username" value="<?=$_POST["username"]?:""?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password" name="password" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="password-confirm" class="col-sm-2 col-form-label">Confirm Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
						</div>
					</div>
					<input type="submit" class="btn btn-primary" value="Register">
				</form>
				<hr>
				<p>
					<a href="index.php">Login</a>
					or
					<a href="steal.php">steal an account</a>
				</p>
			</div>
		</div>
	</div>
</body>
</html>