<?php
	require "db.php";
	// TODO: Test
	$db->query("INSERT INTO category (usename, name, color) VALUES" [$_SESSION["username"], $_POST["name"], $_POST["color"]]);
	header("Location: home.php");
?>