<?php
$server = "feenix-mariadb.swin.edu.au";
$username = "s105926680";
$password = "xx";
$DBName = $username . "_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($server, $username, $password, $DBName);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$aanumber = $_GET["aanumber"];

$stmt = $conn->prepare('select concat(FirstName, " ", LastName) from Archer where AANumber = ?;');
$stmt->bind_param("s", $aanumber);
$stmt->execute();
$archer_name = $stmt->get_result()->fetch_array()[0];

if (!$archer_name) {
	header("location: personal-best.php?invalid");
	die();
}

$stmt = $conn->prepare('
select
    `Round name`,
    `Equipment`,
    `Shot on`,
    `Score`,
    `Number of X scores`

    from
    (
        select
        *,
        @currentRound <> `Round` as "Necessity",
        (@currentRound := `Round`)

        from (select
            Round.Name as "Round name",
            Round.RoundID as "Round",
            CompetitionEnd.CategoryMappingDivision as "Equipment",
            Competition.DateHeld as "Shot on",
            sum(End.Arrow1 + End.Arrow2 + End.Arrow3 + End.Arrow4 + End.Arrow5 + End.Arrow6) as "Score",
            sum(End.Arrow1 = "X") + sum(End.Arrow2 = "X") + sum(End.Arrow3 = "X") + sum(End.Arrow4 = "X") + sum(End.Arrow5 = "X") + sum(End.Arrow6 = "X") as "Number of X scores"

            from End

            natural join CompetitionEnd
            inner join Round on End.RangeRound = Round.RoundID
            inner join Competition on CompetitionEnd.CategoryMappingCompetition = Competition.CompetitionID

            where End.Archer = ?

            group by CompetitionEnd.CategoryMappingCompetition

            order by Round.RoundID, `Score` desc, `Number of X scores` desc
        ) t

        inner join (select @currentRound := 0) as c
    ) t

    where `Necessity`

	order by `Round name`
;
');

$stmt->bind_param("s", $aanumber);

$stmt->execute();

$pb_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Personal Best</h1>

		<p style="font-size:150%">Personal Best scores for <strong><?php echo $archer_name; ?></strong></p>

		<p><a href="personal-best.php">Look up another archer</a></p>

		<table class="fancy_table">
			<tbody>
				<tr><th>Round name</th><th>Equipment</th><th>Shot on</th><th>Score</th><th>Number of X scores</th></tr>

				<?php
					foreach ($pb_results as $row) {
						echo "<tr>";

						foreach ($row as $value) {
							echo "<td>$value</td>";
						}

						echo "</tr>";
					}
				?>
			</tbody>
		</table>

		<?php
			if (!$pb_results) {
				echo "<em>No scores have yet been recorded for this archer.</em>";
			}
		?>

		<script src="fancy-table.js"></script>
	</body>
</html>
