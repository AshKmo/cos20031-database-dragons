<?php
require "../credentials.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$stmt = $conn->prepare("
select
	CompetitionID,
	Name,
	DateHeld,
	InChampionship

	from Competition
	;
");

$stmt->execute();

$results = $stmt->get_result();

$comps = $results->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Competition Scoring</h1>

		<p>Which competition would you like to retrieve the scores for?</p>

		<table>
			<tbody>
				<tr>
					<th>Name</th>
					<th>Date Held</th>
					<th>In Championship?</th>
				</tr>

				<?php
					foreach ($comps as $i=>$comp) {
						echo "<tr>";

						echo "<td><a href='comp-results.php?comp_id={$comp['CompetitionID']}&comp_name={$comp['Name']}'>{$comp["Name"]}</a></td>";
						echo "<td>{$comp["DateHeld"]}</td>";

						$in_champ = $comp["InChampionship"];

						echo "<td style='background-color:".($in_champ ? "lime" : "red").";".($in_champ ? "" : "color:white")."'>".($in_champ ? "Yes" : "No")."</td>";

						echo "</tr>";
					}
				?>
			</tbody>
		</table>
	</body>
</html>
