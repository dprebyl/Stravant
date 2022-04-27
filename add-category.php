<?php
	require_once "db.php";

	if (isset($_POST["categoy-name"])) {
		$results = $db->query("select * from category where username=? and name=?", [$_SESSION["username"], $_POST["categoy-name"]]);
		if (count($results) > 0) {
			$_SESSION["category_error"] = "Category already exists";
		} else {
			$db->query("INSERT INTO category (name, username, color) VALUES (?, ?, ?)", [$_POST["categoy-name"], $_SESSION["username"], $_POST["color"]]);
		}
	}

	header("Location: home.php");
?>