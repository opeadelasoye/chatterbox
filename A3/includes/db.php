<?php
    $currentPath = $_SERVER['REQUEST_URI'];
    if(str_contains($currentPath, 'includes')){
        header("Location: ../index.php");
    }

	session_start();

	$host = "localhost";
    $username = "root";
    $password = "root";
    $dbName = "2170-w22";

    $database = new mysqli($host, $username, $password, $dbName);

	if($database->connect_error){
        die("Error code: " . $database->connect_errno . "<br>" . $database->connect_error);
    }
?>

