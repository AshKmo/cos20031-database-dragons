<?php
require "../credentials.php";

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$stmt = $conn->prepare("
select
	Archer.AANumber as 'AA Number',
	concat(Archer.FirstName, ' ', Archer.LastName) as 'Archer name',
	Round.Name as 'Round shot',
	CompetitionEnd.CategoryMappingDivision as 'Equipment', sum(a1.Value + a2.Value + a3.Value + a4.Value + a5.Value + a6.Value) as 'Total score', sum(End.Arrow1 = 'X') + sum(End.Arrow2 = 'X') + sum(End.Arrow3 = 'X') + sum(End.Arrow4 = 'X') + sum(End.Arrow5 = 'X') + sum(End.Arrow6 = 'X') as 'Number of X scores'
	from End
	inner join CompetitionEnd on End.EndID = CompetitionEnd.EndID
	inner join Archer on End.Archer = Archer.AANumber
	inner join Round on End.RangeRound = Round.RoundID
	inner join ArrowScore a1 on End.Arrow1 = a1.Name
	inner join ArrowScore a2 on End.Arrow2 = a2.Name
	inner join ArrowScore a3 on End.Arrow3 = a3.Name
	inner join ArrowScore a4 on End.Arrow4 = a4.Name
	inner join ArrowScore a5 on End.Arrow5 = a5.Name
	inner join ArrowScore a6 on End.Arrow6 = a6.Name
	where CompetitionEnd.CategoryMappingCompetition = ?
	group by Archer.AANumber
	order by `Total score` desc, `Number of X scores` desc
;
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
