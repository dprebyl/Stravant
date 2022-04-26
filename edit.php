<?php
    require "db.php";
	ensure_logged_in();
    var_dump($_POST, $_SESSION);
    // Ensure the activity belongs to the user
    if (count($db->query("SELECT 1 FROM activity WHERE activity_id = ? AND username = ?", 
                         [$_POST["activity_id"], $_SESSION["username"]])) < 1)
        die("Invalid activity or no permission"); 

    // TODO: Categories
    $db->query("UPDATE activity SET name = ?, description = ? WHERE activity_id = ? AND username = ?",
        [$_POST["name"], $_POST["description"], $_POST["activity_id"], $_SESSION["username"]]);
    
    header("Location: view.php?id=" . $_POST["activity_id"]);
?>