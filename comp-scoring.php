<?php
$server = "feenix-mariadb.swin.edu.au";
$username = "s105926680";
$password = "xx";
$DBName = $username . "_db";

$conn = new mysqli($server, $username, $password, $DBName);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<style>
* {
	font-family: sans-serif;
}
		</style>
	</head>

	<body>
		<h1>Competition Scoring</h1>
	</body>
</html>
