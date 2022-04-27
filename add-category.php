<?php
	require_once "db.php";

	if (isset($_POST["category-name"])) {
		$results = $db->query("select * from category where username=? and name=?", [$_SESSION["username"], $_POST["category-name"]]);
		if (empty($_POST["category-name"])) {
			$_SESSION["category_error"] = "Category name cannot be empty";
		}
		else if (count($results) > 0 && $_POST["category-name"] != $_POST["original-category"]) {
			$_SESSION["category_error"] = "Category already exists";
		} else {
			if (!empty($_POST["original-category"])) {
				// Editing a category
				
				// get activities with this category
				$activities = $db->query("SELECT cat.activity_id from category_assignment as cat join activity as act on cat.activity_id=act.activity_id where act.username=? and cat.name=?", [$_SESSION["username"], $_POST["original-category"]]);
				$activities = array_map(function($act) {return $act["activity_id"];}, $activities);
				// delete the existing assignments 
				$db->query("DELETE from category_assignment where activity_id in (" . implode(", ", array_fill(0, count($activities), '?')) . ") and name=?", array_merge($activities, [$_POST["original-category"]]));
				// rename category
				$db->query("UPDATE category set name=?, color=? where name=? and username=?", [$_POST["category-name"], $_POST["color"], $_POST["original-category"], $_SESSION["username"]]);
				// Reassign activities to new name
				foreach($activities as $activity) {
					$db->query("INSERT into category_assignment (activity_id, name) VALUES (?, ?)", [$activity, $_POST["category-name"]]);
				}

			} else {
				// Creating a category
				$db->query("INSERT INTO category (name, username, color) VALUES (?, ?, ?)", [$_POST["category-name"], $_SESSION["username"], $_POST["color"]]);
			}
		}
	}

	header("Location: home.php");
?>