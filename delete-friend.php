<?php
	require "db.php";
	// TODO: Test
	$db->query("DELETE FROM friend WHERE username = ? AND friend = ?", [$_SESSION["username"], $_GET["friend"]]);
	header("Location: home.php");
?>