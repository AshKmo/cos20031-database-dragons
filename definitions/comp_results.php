<?php require "../credentials.php";
require "header.php";

$comp_select = $_GET["comp_select"];
$sql = "SELECT Competition.CompetitionID AS ID, Competition.Name, Competition.DateHeld, Competition.InChampionship, CompetitionRound.Class, CompetitionRound.IsFemale, CompetitionRound.Division
FROM Competition JOIN CompetitionRound ON Competition.CompetitionID = CompetitionRound.Competition
WHERE CompetitionID = $comp_select + 1";
$result = $conn->query($sql);
$first_row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Competition Lookup</h1>
        <h2>Competition: <?php echo $first_row["Name"] ?></h2>

        <?php
        echo "<table><tr><th>Competition ID</th><th>Date held</th><th>Championship status</th></tr>
        <tr><td>".$first_row["ID"]."</td>
        <td>".$first_row["DateHeld"]."</td>
        <td>".($first_row["InChampionship"] ? "Yes" : "No")."</td></tr></table>";
        ?>

        <h2>Available rounds</h2>
        <?php
        $result->data_seek(0); 
        echo "<table><tr><th>Class</th><th>Division</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".($row["IsFemale"] ? "Female" : "Male")." ".$row["Class"]."</td>
            <td>".$row["Division"]."</td></tr>";
            }
        echo "</table>";
        ?>

        <p><a href="index.php">Back</a></p>
	</body>
</html>