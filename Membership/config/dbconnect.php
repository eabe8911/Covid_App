<?php 

/* Credentials */
$servername = "maxcheng.tw:3307";
$username = "root";
$password = ",-4,4p-2";
$database = "membership";


/* Connection */
$conn = new mysqli($servername, $username, $password, $database);

/* If connection fails for some reason */
if ($conn->connect_error) {
	die("Database connection failed: ". $conn->connect_error);
}

?>