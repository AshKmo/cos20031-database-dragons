<?php
require "../credentials.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$champ_rounds = $conn->query('
select
	ChampionshipRound.Round as Round,
	Round.Name as RoundName,
	ChampionshipRound.ScoreCount as ScoreCount

	from ChampionshipRound

	inner join Round on Round.RoundID = ChampionshipRound.Round

	order by ChampionshipRound.Round
;
')->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare('
select
	Archer,
	ArcherName,
	Round,
	Score,
	XCount,
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

class ArcherResult {
	public $id;
	public $name;
	public $scores;
	public $total_score;
	public $x_count;
	public $qualifying;

	function __construct($id, $name) {
		$this->id = $id;
		$this->name = $name;
		$this->scores = array();
		$this->total_score = 0;
		$this->x_count = 0;
		$this->qualifying = true;
	}
}

$archer_results = array();

$current_archer = new ArcherResult('', '');
foreach ($champ_results as $score_entry) {
	if ($score_entry['Archer'] !== $current_archer->id) {
		if ($current_archer->id !== '') {
			array_push($archer_results, $current_archer);
		}
		$current_archer = new ArcherResult($score_entry['Archer'], $score_entry['ArcherName']);
	}

	array_push($current_archer->scores, $score_entry);

	$current_archer->total_score += $score_entry['Score'];
	$current_archer->x_count += $score_entry['XCount'];
}

if ($current_archer->id !== '') {
	array_push($archer_results, $current_archer);
}

usort($archer_results, function($a, $b) {
	if (!$b->qualifying) {
		return -1;
	}

	if (!$a->qualifying) {
		return 1;
	}

	if ($a->total_score !== $b->total_score) {
		return $b->total_score - $a->total_score;
	}

	return $b->x_count - $a->x_count;
});

// ordinal number suffix function courtesy of Iacopo and neshkeev on StackOverflow
// https://stackoverflow.com/questions/3109978/display-numbers-with-ordinal-suffix-in-php
function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Championship Scoring</h1>

		<p style="font-size:150%">Results for the <strong><?php echo "$champ_year $champ_class ".($champ_gender ? "Female" : "Male")." $champ_division"; ?> Championship</strong></p>

		<p><a href="champ-scoring.php">Select another championship</a></p>

		<table>
			<tbody>
				<tr>
					<th rowspan="2">AA Number</th>
					<th rowspan="2">Name</th>

<?php
foreach ($champ_rounds as $round) {
	echo "<th colspan='${round['ScoreCount']}'>{$round['RoundName']}</th>";
}
?>

					<th rowspan="2">Total score</th>
					<th rowspan="2">Number of X scores</th>
				</tr>

				<tr>
<?php
foreach ($champ_rounds as $round) {
	foreach (range(1,$round['ScoreCount']) as $n) {
		echo "<th>".(ordinal($n))." best</th>";
	}
}
?>
				</tr>

<?php
foreach ($archer_results as $archer_result) {
	echo "<tr>";

	echo "<td>{$archer_result->id}</td>";
	echo "<td>{$archer_result->name}</td>";

	$i = 0;
	foreach ($champ_rounds as $c_round) {
		for ($n_left = $c_round['ScoreCount']; $n_left > 0; $n_left--) {
			$score = $archer_result->scores[$i];

			if ($score['Round'] != $c_round['Round']) {
				$archer_result->qualifying = false;
				for (; $n_left > 0; $n_left--) {
					echo "<td></td>";
					$i++;
				}
				break;
			}

			echo "<td>{$score['Score']}</td>";

			$i++;
		}
	}

	$score_style = $archer_result->qualifying ? "" : "color:red";

	echo "<td style='$score_style'>{$archer_result->total_score}</td>";
	echo "<td style='$score_style'>{$archer_result->x_count}</td>";

	echo "</tr>";
}
?>
			</tbody>
		</table>

		<?php
			if (!$champ_results) {
				echo "<em>No scores have yet been recorded for this championship.</em>";
			}
		?>
	</body>
</html>
