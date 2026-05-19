<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $round = $_POST["round"];
    $archer = $_POST["archer"];
    $equipment = $_POST["equipment"];

} else {

    header("Location: index.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Submission Successful</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <h1>✅ Score Entry Submitted</h1>

        <div class="result-box">

            <p>
                <strong>Round:</strong>
                <?php echo htmlspecialchars($round); ?>
            </p>

            <p>
                <strong>Archer:</strong>
                <?php echo htmlspecialchars($archer); ?>
            </p>

            <p>
                <strong>Equipment:</strong>
                <?php echo htmlspecialchars($equipment); ?>
            </p>

        </div>

        <a href="index.html" class="back-button">
            Enter Another Score
        </a>

    </div>

</body>
</html>
