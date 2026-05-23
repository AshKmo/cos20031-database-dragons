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
			<p><label for="champ_year">Championship year: </label><input autocomplete="off" onchange="updateValidCombos()" id="champ_year" name="champ_year" id="champ_year" type="number" min="1900" step="1" max="<?php echo date("Y"); ?>" value="<?php echo date("Y"); ?>"></p>

			<p><label for="champ_gender">Gender: </label><select onchange="updateValidCombos()" id="champ_gender" name="champ_gender" id="champ_gender"><option value="0">Male</option><option value="1">Female</option></select></p>

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
						$class = $class[0];

						echo "<tr><td>$class</td>";

						foreach ($divisions as $division) {
							$division = $division[0];
							echo "<td class='champ_class_division_input' id='ccd_{$class}_$division'><input type='radio' name='champ_class_division' value='".(json_encode(array($class,$division)))."'></td>";
						}

						echo "</tr>";
					}
				?>
			</table>

			<p><input type="submit" value="Retrieve"></p>
		</form>

		<script>
			async function updateValidCombos() {
				const champYear = document.getElementById("champ_year");
				const champGender = document.getElementById("champ_gender");

				document.querySelectorAll(`.champ_class_division_input`).forEach(e => {
					e.style.backgroundColor = "";
				});

				(await (await fetch(`champ-combos.php?champ_year=${champYear.value}&champ_gender=${champGender.value}`)).json()).forEach(c => {
					const target = document.getElementById(`ccd_${c["Class"]}_${c["Division"]}`);
					target.style.backgroundColor = "lime";
				});
			}

			updateValidCombos();
		</script>
	</body>
</html>
