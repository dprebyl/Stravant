<?php
	require "db.php";
	ensure_logged_in();
	// TODO: Test
	$db->query("DELETE FROM activity WHERE username = ? AND id = ?", [$_SESSION["username"], $_GET["id"]]);
	header("Location: home.php");
?>