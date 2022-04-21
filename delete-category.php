<?php
	require "db.php";
	// TODO: Test
	$db->query("DELETE FROM category WHERE username = ? AND name = ?", [$_SESSION["username"], $_GET["name"]]);
	header("Location: home.php");
?>