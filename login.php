<?php
    require "db.php";

    $username = $_POST["username"];
    $password = $_POST["password"];

    // TODO: Verify password using database
    if (true) {
        $_SESSION["username"] = $username;
        header("Location: home.php");
    }
    else {
        header("Location: index.php?error=y");
    }
?>