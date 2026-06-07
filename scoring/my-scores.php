<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>My Scores Over Time</h1>

		<p>Please enter your Archery Australia number below</p>

		<form action="my-scores-results.php">
			<p>AA Number: <input type="text" name="aanumber"></p>
			<p>Date from: <input type="date" name="date_from"> &nbsp; Date to: <input type="date" name="date_to"></p>
			<p><input type="submit" value="Retrieve"></p>
		</form>
		<p style="color:red<?php echo (array_key_exists("invalid", $_GET) ? "" : ";display:none"); ?>">That number has not been registered in the archery database.</p>
	</body>
</html>
