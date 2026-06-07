<?php
require "../credentials.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

$aanumber = $_GET["aanumber"];

$stmt = $conn->prepare('select concat(FirstName, " ", LastName) from Archer where AANumber = ?;');
$stmt->bind_param("s", $aanumber);
$stmt->execute();
$archer_name = $stmt->get_result()->fetch_array()[0];

if (!$archer_name) {
	header("location: my-scores.php?invalid");
	die();
}

// Build query with optional date filters
$params = [$aanumber];
$param_types = "s";
$date_conditions = "";

$date_from = $_GET["date_from"] ?? "";
$date_to   = $_GET["date_to"]   ?? "";

if ($date_from !== "") {
	$date_conditions .= " AND e.TimeRecorded >= ?";
	$params[] = $date_from;
	$param_types .= "s";
}
if ($date_to !== "") {
	$date_conditions .= " AND e.TimeRecorded <= ?";
	$params[] = $date_to;
	$param_types .= "s";
}

$stmt = $conn->prepare("
SELECT
    r.Name AS `Round`,
    e.RangeDistance AS `Distance (m)`,
    DATE(e.TimeRecorded) AS `Date`,
    SUM(
        s1.Value + s2.Value + s3.Value + s4.Value + s5.Value + s6.Value
    ) AS `End Total`
FROM End e
JOIN Round r ON r.RoundID = e.RangeRound
JOIN ArrowScore s1 ON s1.Name = e.Arrow1
JOIN ArrowScore s2 ON s2.Name = e.Arrow2
JOIN ArrowScore s3 ON s3.Name = e.Arrow3
JOIN ArrowScore s4 ON s4.Name = e.Arrow4
JOIN ArrowScore s5 ON s5.Name = e.Arrow5
JOIN ArrowScore s6 ON s6.Name = e.Arrow6
WHERE e.Archer = ?
  AND e.IsFinal = TRUE
  $date_conditions
GROUP BY r.Name, e.RangeRound, e.RangeDistance, e.TimeRecorded
ORDER BY e.TimeRecorded DESC;
");

$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$score_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>My Scores Over Time</h1>

		<p style="font-size:150%">End scores for <strong><?php echo htmlspecialchars($archer_name); ?></strong></p>

		<?php if ($date_from !== "" || $date_to !== ""): ?>
		<p>
			Filtered:
			<?php if ($date_from !== ""): ?> from <strong><?php echo htmlspecialchars($date_from); ?></strong><?php endif; ?>
			<?php if ($date_to   !== ""): ?> to <strong><?php echo htmlspecialchars($date_to);   ?></strong><?php endif; ?>
		</p>
		<?php endif; ?>

		<p><a href="my-scores.php">Look up another archer</a></p>

		<table>
			<tbody>
				<tr>
					<th>Round</th>
					<th>Distance (m)</th>
					<th>Date</th>
					<th>End Total</th>
				</tr>

				<?php
					foreach ($score_results as $row) {
						echo "<tr>";
						foreach ($row as $value) {
							echo "<td>" . htmlspecialchars($value) . "</td>";
						}
						echo "</tr>";
					}
				?>
			</tbody>
		</table>

		<?php
			if (!$score_results) {
				echo "<em>No final scores have been recorded for this archer in the selected period.</em>";
			}
		?>
	</body>
</html>
