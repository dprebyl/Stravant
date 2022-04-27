<?php
	require_once "db.php";
	ensure_logged_in();

	if (isset($_POST["activity"])) {
		$results = $db->query("select * from activity where username=? and activity_id=?", [$_SESSION["username"], $_POST["activity"]]);
		if (count($results) == 0) {
			$_SESSION["activity_error"] = "Activity no longer exists";
		} else {
			$db->query("delete from category_assignment where activity_id=?", [$_POST["activity"]]);
			$db->query("delete from activity where activity_id=?", [$_POST["activity"]]);
		}
	}
	header("Location: home.php");
?>