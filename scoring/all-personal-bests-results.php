<?php
require "../credentials.php";

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$stmt = $conn->prepare("
select
    `Archer`,
    `Archer name`,
    `Equipment`,
    `Shot on`,
    `Score`,
    `Number of X scores`

    from (select
        *,
        @currentArcher <> `Archer` or @currentDivision <> `Equipment` as 'Necessity',
        (@currentArcher := `Archer`),
        (@currentDivision := `Equipment`)

        from (select
            Archer.AANumber as 'Archer',
            concat(Archer.FirstName, ' ', Archer.LastName) as 'Archer name',
            Round.Name as 'Round name',
            Round.RoundID as 'Round',
            CompetitionEnd.CategoryMappingDivision as 'Equipment',
            Competition.DateHeld as 'Shot on',
            sum(End.Arrow1 + End.Arrow2 + End.Arrow3 + End.Arrow4 + End.Arrow5 + End.Arrow6) as 'Score',
            sum(End.Arrow1 = 'X') + sum(End.Arrow2 = 'X') + sum(End.Arrow3 = 'X') + sum(End.Arrow4 = 'X') + sum(End.Arrow5 = 'X') + sum(End.Arrow6 = 'X') as 'Number of X scores'

            from End

            natural join CompetitionEnd
            inner join Round on End.RangeRound = Round.RoundID
            inner join Competition on CompetitionEnd.CategoryMappingCompetition = Competition.CompetitionID
            inner join Archer on End.Archer = Archer.AANumber

            where End.RangeRound = ?

            group by Archer.AANumber, CompetitionEnd.CategoryMappingCompetition

            order by Archer.AANumber, CompetitionEnd.CategoryMappingDivision, `Score` desc, `Number of X scores` desc
        ) t

        inner join (select @currentArcher := '', @currentDivision := '') as c
    ) t

    where `Necessity`
;
");

$stmt->bind_param("i", $_GET['round']);

$stmt->execute();

$results = $stmt->get_result();

$pb_results = $results->fetch_all();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>All Personal Bests in Round</h1>

		<p style="font-size:150%">Personal Best Results for <strong><?php echo $_GET['round_name']; ?></strong></p>

		<p><a href="all-personal-bests.php">Select another round</a></p>

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
					foreach ($pb_results as $result) {
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
			if (!$pb_results) {
				echo "<em>No scores have yet been recorded for this round.</em>";
			}
		?>
	</body>
</html>
