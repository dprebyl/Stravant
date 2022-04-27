<?php
    require "db.php";
	ensure_logged_in();
    var_dump($_POST, $_SESSION);
    // Ensure the activity belongs to the user
    if (count($db->query("SELECT 1 FROM activity WHERE activity_id = ? AND username = ?", 
                         [$_POST["activity_id"], $_SESSION["username"]])) < 1)
        die("Invalid activity or no permission"); 

    $db->query("UPDATE activity SET name = ?, description = ? WHERE activity_id = ? AND username = ?",
        [$_POST["name"], $_POST["description"], $_POST["activity_id"], $_SESSION["username"]]);

    // remove current categories
    $db->query("delete from category_assignment where activity_id=?", [$_POST["activity_id"]]);

    // add new categories
    if (strlen($_POST["categories"]) > 0) {
        if (strpos($_POST["categories"], ",") !== false) {
            // Multiple categories
            $categories = explode(",", $_POST["categories"]);
        } else {
            $categories = [$_POST["categories"]];
        }
        foreach ($categories as $category) {
            if ($category === " " || $category === "") {
                continue;
            }
            $db->query("insert into category_assignment (activity_id, name) values (?, ?)", [$_POST["activity_id"], trim($category)]);
        }
    }
    
    header("Location: view.php?id=" . $_POST["activity_id"]);
?>