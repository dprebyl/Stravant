<?php
	require_once "db.php";

	if (isset($_POST["friend"])) {
		$results = $db->query("select * from friendship where user=? and friend=?", [$_SESSION["username"], $_POST["friend"]]);
		if (count($results) == 0) {
			$_SESSION["friend_error"] = "Friendship no longer exists";
		} else {
			$db->query("delete from friendship where user=? and friend=?", [$_SESSION["username"], $_POST["friend"]]);
		}
	}
	header("Location: home.php");
?>