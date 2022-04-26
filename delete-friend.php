<?php
	require_once "db.php";

	$error = false;
	if (isset($_GET["friend"])) {
		$results = $db->query("select * from friendship where friend = ? and user=?", [$_GET["friend"], $_SESSION["username"]]);
		if (count($results) == 0) {
			$error = "Friendship no longer exists";
		} else {
			$db->query("delete from friendship where user=? and friend=?", [$_SESSION["username"], $_GET["friend"]]);
			header("Location: home.php");
			return;
		}
	} else {
		header("Location: home.php");
		return;
	}

	header("Location: home.php?friend_error=" . $error);
?>