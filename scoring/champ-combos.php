<?php
header("content-type: application/json");

$server = "feenix-mariadb.swin.edu.au";
$username = "s105926680";
$password = "xx";
$DBName = $username . "_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($server, $username, $password, $DBName);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$stmt = $conn->prepare('
select distinct
    CompetitionEnd.CategoryMappingDivision as Division,
	CompetitionEnd.CategoryMappingClass as Class

    from End

    inner join CompetitionEnd on End.EndID = CompetitionEnd.EndID
    inner join Competition on Competition.CompetitionID = CompetitionEnd.CategoryMappingCompetition
    inner join ChampionshipRound on ChampionshipRound.Round = End.RangeRound

    where Competition.InChampionship and CompetitionEnd.CategoryMappingIsFemale = ? and year(Competition.DateHeld) = ?
;
');

$champ_year = $_GET["champ_year"];

$champ_gender = $_GET['champ_gender'];

$stmt->bind_param("si", $champ_gender, $champ_year);

$stmt->execute();

$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($results);
