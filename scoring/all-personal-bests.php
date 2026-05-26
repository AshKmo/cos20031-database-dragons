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
select distinct
	Round.RoundID as RoundID,
	Round.Name as Name,
	Round.DateCreated as DateCreated

	from End

	inner join CompetitionEnd on End.EndID = CompetitionEnd.EndID
	inner join Round on End.RangeRound = Round.RoundID

	order by Name asc, DateCreated desc
;
");

$stmt->execute();

$rounds = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>All Personal Bests in Round</h1>

		<p>Please select a round for which to retrieve the personal best score for each archer.</p>
		<p>If you do not see a particular round here, then no scores have yet been recorded for that round.</p>

		<table>
			<tbody>
				<tr>
					<th>Name</th>
					<th>Date created</th>
				</tr>

				<?php
					foreach ($rounds as $round) {
						echo "<tr>";

						echo "<td><a href='all-personal-bests-results.php?round={$round["RoundID"]}&round_name={$round["Name"]}'>{$round["Name"]}</a></td>";
						echo "<td>{$round["DateCreated"]}</td>";

						echo "</tr>";
					}
				?>
			</tbody>
		</table>

		<?php
			if (!$rounds) {
				echo "<em>No rounds have yet been entered into the system.</em>";
			}
		?>
	</body>
</html>
