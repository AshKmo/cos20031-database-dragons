<?php
$server = "feenix-mariadb.swin.edu.au";
$username = "s105926680";
$password = "xx";
$DBName = $username . "_db";

$conn = new mysqli($server, $username, $password, $DBName);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$stmt = $conn->prepare("

");

$stmt->bind_param("i", $_GET['comp_id']);

$stmt->execute();

$results = $stmt->get_result();

$comp_results = $results->fetch_all();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Competition Scoring</h1>

		<p style="font-size:150%">Results for <strong><?php echo $_GET['comp_name']; ?></strong></p>

		<p><a href="comp-scoring.php">Select another competition</a></p>

		<table>
			<tbody>
				<tr>
					<th>AA Number</th>
					<th>Name</th>
					<th>Round</th>
					<th>Division</th>
					<th>Total score</th>
					<th>Number of X scores</th>
				</tr>

				<?php
					foreach ($comp_results as $result) {
						echo "<tr>";

						foreach ($result as $v) {
							echo "<td>$v</td>";
						}

						echo "</tr>";
					}
				?>
			</tbody>
		</table>

		<?php
			if (!$comp_results) {
				echo "<em>No scores have yet been recorded for this competition.</em>";
			}
		?>
	</body>
</html>
