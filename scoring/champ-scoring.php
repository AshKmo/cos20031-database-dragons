<?php
$server = "feenix-mariadb.swin.edu.au";
$username = "s105926680";
$password = "xx";
$DBName = $username . "_db";

$conn = new mysqli($server, $username, $password, $DBName);

if ($conn->connect_error) {
	die("Database connection failed " . $conn->connect_error);
}

list($divisions, $classes) = array_map(function($sql) use ($conn) {
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	return $stmt->get_result()->fetch_all();
}, [
	"select Name from Division;",
	"select Name from Class;"
]);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Yearly Championship Results</h1>

		<p>Which class would you like to retrieve the championship results for?</p>

		<form action="champ-results.php">
			<p><label for="champ_year">Championship year: </label><input name="champ_year" id="champ_year" type="number" min="1900" step="1" max="<?php echo date("Y"); ?>" value="<?php echo date("Y"); ?>"></p>

			<p><label for="champ_gender">Gender: </label><select name="champ_gender" id="champ_gender"><option value="0">Male</option><option value="1">Female</option></select></p>

			<table>
				<tr style="writing-mode:vertical-lr">
					<td></td>
					
					<?php
						foreach ($divisions as $division) {
							echo "<td>$division[0]</td>";
						}
					?>
				</tr>
				
				<?php
					foreach ($classes as $class) {
						echo "<tr><td>$class</td>";

						foreach ($divisions as $division) {
							echo "<td><input type='radio' name='champ_class_division' value='".(json_encode(array($class,$division)))."'></td>";
						}

						echo "</tr>";
					}
				?>
			</table>

			<p><input type="submit" value="Retrieve"></p>
		</form>
	</body>
</html>
