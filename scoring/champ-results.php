<?php
$server = "feenix-mariadb.swin.edu.au";
$username = "s105926680";
$password = "xx";
$DBName = $username . "_db";

$conn = new mysqli($server, $username, $password, $DBName);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$stmt = $conn->prepare('
select
	Archer as "AA Number",
	ArcherName as "Name",
	Round,
	Score,
	XCount as "Number of X scores",
	n

	from (select
		ScoreCount - case Archer = @archer and `Round` = @round when true then @n := @n + 1 else @n := 0 end as "Necessity",
		@archer := Archer as "Archer",
		ArcherName,
		@round := `Round` as "Round",
		Score,
		XCount,
		@n as n

		from (select
			ChampionshipRound.ScoreCount as "ScoreCount",
			Archer.AANumber as "Archer",
			concat(Archer.FirstName, " ", Archer.LastName) as "ArcherName",
			End.RangeRound as "Round",
			sum(a1.Value + a2.Value + a3.Value + a4.Value + a5.Value + a6.Value) as "Score",
			sum(End.Arrow1 = "X") + sum(End.Arrow2 = "X") + sum(End.Arrow3 = "X") + sum(End.Arrow4 = "X") + sum(End.Arrow5 = "X") + sum(End.Arrow6 = "X") as XCount

			from End

			inner join CompetitionEnd on End.EndID = CompetitionEnd.EndID
			inner join Competition on CompetitionEnd.CategoryMappingCompetition = Competition.CompetitionID
			inner join ChampionshipRound on End.RangeRound = ChampionshipRound.Round and year(Competition.DateHeld) = ChampionshipRound.ChampionshipYear
			inner join Archer on End.Archer = Archer.AANumber
			inner join ArrowScore a1 on End.Arrow1 = a1.Name
			inner join ArrowScore a2 on End.Arrow2 = a2.Name
			inner join ArrowScore a3 on End.Arrow3 = a3.Name
			inner join ArrowScore a4 on End.Arrow4 = a4.Name
			inner join ArrowScore a5 on End.Arrow5 = a5.Name
			inner join ArrowScore a6 on End.Arrow6 = a6.Name

			where CompetitionEnd.CategoryMappingClass = ? and CompetitionEnd.CategoryMappingIsFemale = ? and CompetitionEnd.CategoryMappingDivision = ? and End.IsFinal and Competition.InChampionship and year(Competition.DateHeld) = year(now()) and ChampionshipRound.ChampionshipYear = ?

			group by Archer.AANumber, Competition.CompetitionID, End.RangeRound

			order by Archer.AANumber, End.RangeRound, Score desc, XCount desc
		) t

		inner join (select @archer := NULL, @round := NULL, @n := 0) as c
	) t

	where Necessity > 0

	group by Archer, Round, n

	order by Archer, Round, n
;
');

list($champ_class, $champ_division) = json_decode($_GET['champ_class_division']);

$champ_year = $_GET["champ_year"];

$champ_gender = $_GET['champ_gender'];

$stmt->bind_param("sisi", $champ_class, $champ_gender, $champ_division, $champ_year);

$stmt->execute();

$results = $stmt->get_result();

$champ_results = $results->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Championship Scoring</h1>

		<p style="font-size:150%">Results for the <strong><?php echo "$champ_year $champ_class $champ_gender $champ_division"; ?> Championship</strong></p>

		<p><a href="comp-scoring.php">Select another championship</a></p>

		<table>
			<tbody>
			</tbody>
		</table>

		<?php
			if (!$champ_results) {
				echo "<em>No scores have yet been recorded for this championship.</em>";
			}
		?>
	</body>
</html>
