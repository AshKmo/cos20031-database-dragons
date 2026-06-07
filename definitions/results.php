<?php require "../credentials.php";
require "header.php";

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$round_select = $_GET["round_select"] + 1;
$sql = "SELECT `Name` FROM Round WHERE RoundID = $round_select";
$round_name = $conn->query($sql)->fetch_array()[0];

$sql = "SELECT Round, Distance, TargetIsLarge, EndCount FROM ArcheryRange WHERE Round = $round_select ORDER BY Distance DESC";
$result = $conn->query($sql);
$length = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Round Definitions</h1>
        <h2>Round: <?php echo $round_name ?> <br>
            Ranges: <?php echo $length ?></h2>

        <?php
        echo "<table><tr><th>Distances</th><th>No. ends at distance</th><th>Target face</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>".$row["Distance"]."</td>
            <td>".$row["EndCount"]."</td>
            <td>".($row["TargetIsLarge"] ? "Large" : "Small")."</td>
            </tr>";
        }
        echo "</table>";
        ?>

        <p><a href="index.php">Back</a></p>
	</body>
</html>