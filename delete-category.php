<?php
	require_once "db.php";
	ensure_logged_in();

	if (isset($_POST["category"])) {
		$results = $db->query("select * from category where username=? and name=?", [$_SESSION["username"], $_POST["category"]]);
		if (count($results) == 0) {
			$_SESSION["category_error"] = "Category no longer exists";
		} else {
			$db->query("delete from category_assignment where activity_id in (select activity_id from activity where username=?) and name=?", [$_SESSION["username"], $_POST["category"]]);
			$db->query("delete from category where username=? and name=?", [$_SESSION["username"], $_POST["category"]]);
		}
	}
	header("Location: home.php");
?>