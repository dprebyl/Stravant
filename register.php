<!DOCTYPE html>
<?php
	$error = false;
	if (isset($_POST["username"])) {
		if ($_POST["password"] != $_POST["password-confirm"]) {
			$error = "Passwords do not match";
		}
		// TODO: Check if username taken or password too weak
		// Ensure username is valid (no special characters)
		// If ok then insert into DB and redirect to login
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
					<div class="form-group row">
						<label for="password-confirm" class="col-sm-2 col-form-label">Confirm Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password-confirm" name="password-confirm">
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