<?php
$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}
?>