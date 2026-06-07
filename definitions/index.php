<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<link rel="stylesheet" href="styles.css">
	</head>

	<body>
		<h1>Round Definitions</h1>

        <form action="results.php">
        <p>Round: <select id="round_select" name="round_select">
            <option value="0">WA90/1440</option>
            <option value="1">WA70/1440</option>
            <option value="2">WA60/1440</option>
            <option value="3">AA50/1440</option>
            <option value="4">AA40/1440</option>
            <option value="5">Long Sydney</option>
            <option value="6">Sydney</option>
            <option value="7">Long Brisbane</option>
            <option value="8">Brisbane</option>
            <option value="9">Adelaide</option>
            <option value="10">Short Adelaide</option>
            <option value="11">Hobart</option>
            <option value="12">Perth</option>
            <option value="13">Canberra WA60/900</option>
            <option value="14">Short Canberra</option>
            <option value="15">Junior Canberra</option>
            <option value="16">Mini Canberra</option>
            <option value="17">Grange</option>
            <option value="18">Melbourne</option>
            <option value="19">Darwin</option>
            <option value="20">Geelong</option>
            <option value="21">Newcastle</option>
            <option value="22">Holt</option>
            <option value="23">Samford</option>
            <option value="24">Drake</option>
            <option value="25">Wollongong</option>
            <option value="26">Townsville</option>
            <option value="27">Launceston</option>
            <option value="28">WA70/720</option>
            <option value="29">WA60/720</option>
            <option value="30">WA50/720</option>
            <option value="31">AA50/720</option>
            <option value="32">AA40/720</option>
            <option value="33">AA30/720</option>
            <option value="34">AA20/720</option>
            <option value="35">VI Outdoor</option>
            <option value="36">VI 30m</option>
        </p>
        <p><input type="submit" value="Submit"></input></p>
        </form>

        <h1>Competition Lookup</h1>

        <form action="comp_results.php">
        <p>Competition: <select id="comp_select" name="comp_select">
            <option value="0">Spring shooting</option>
            <option value="1">Quick quivers</option>
            <option value="2">2023 Championships</option>
            <option value="3">Kid's round</option>
            <option value="4">2024 club open</option>
            <option value="5">Women's comp</option>
            <option value="6">2025 club open</option>
            <option value="7">2025 Champions Round</option>
            <option value="8">Field round</option>
            <option value="9">Target Thrashers</option>
            <option value="10">Bullseye Busters</option>
            <option value="11">Lawn Mowers</option>
        </p>
        <p><input type="submit" value="Submit"></input></p>
        </form>
	</body>
</html>