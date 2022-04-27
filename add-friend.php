<?php
	require_once "db.php";

	if (isset($_POST["friend"])) {
		$results = $db->query("select * from user where username = ?", [$_POST["friend"]]);
		if (count($results) == 0 || $results[0]["username"] == $_SESSION["username"]) {
			$_SESSION["friend_error"] = "Username not found";
		} else {
			$db->query("INSERT INTO friendship (user, friend) VALUES (?, ?)", [$_SESSION["username"], $_POST["friend"]]);
		}
	}

	header("Location: home.php");
?>