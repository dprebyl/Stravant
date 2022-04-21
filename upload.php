<?php
    require "db.php";
    

    $file = $_FILES["file"];
    $filename = $file["name"];

    // Check it is a GPX file
    if (strtolower(substr($filename, -4)) != ".gpx") {
        die("Error: File must be .gpx format");
    }

    $gpx = simplexml_load_file($file["tmp_name"]) or die("Failed to load file");

    // TODO: Parse the GPX, insert into database, and redirect to view.php for the new activity
    var_dump($gpx);
?>