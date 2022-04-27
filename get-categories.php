<?php
    require_once "db.php";
	ensure_logged_in();
    header('Content-type: application/json');
    echo json_encode($db->query("SELECT * FROM category where username=?;", [$_SESSION["username"]]));
?>