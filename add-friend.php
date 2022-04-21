<?php
	require "db.php";
	// TODO: Test
	$db->query("INSERT INTO friend (usename, friend) VALUES" [$_SESSION["username"], $_POST["friend"]]);
	header("Location: home.php");
?>