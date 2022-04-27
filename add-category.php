<?php
	require_once "db.php";

	if (isset($_POST["categoy-name"])) {
		$results = $db->query("select * from category where username=? and name=?", [$_SESSION["username"], $_POST["categoy-name"]]);
		if (empty($_POST["categoy-name"])) {
			$_SESSION["category_error"] = "Category name cannot be empty";
		}
		else if (count($results) > 0 && $_POST["categoy-name"] != $_POST["original-category"]) {
			$_SESSION["category_error"] = "Category already exists";
		} else {
			if (!empty($_POST["original-category"])) {
				// Editing a category
				$db->query("UPDATE category set name=?, color=? where name=? and username=?", [$_POST["categoy-name"], $_POST["color"], $_POST["original-category"], $_SESSION["username"]]);
			} else {
				// Creating a category
				$db->query("INSERT INTO category (name, username, color) VALUES (?, ?, ?)", [$_POST["categoy-name"], $_SESSION["username"], $_POST["color"]]);
			}
		}
	}

	header("Location: home.php");
?>