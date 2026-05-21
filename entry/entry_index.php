<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Archery Score Entry</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h1>🏹 Archery Score Entry</h1>

        <form action="submit.php" method="POST">

            <!-- ROUND SECTION -->
            <div class="section-title">
                CHOOSE ROUND
            </div>

            <div class="form-group">

                <label for="round">
                    Location / Number of Arrows
                </label>

                <select
                    name="round"
                    id="round"
                    required
                >

                    <option value="">
                        -- Select Round --
                    </option>

                    <option value="Indoor Range - 30 Arrows">
                        Indoor Range - 30 Arrows
                    </option>

                    <option value="Outdoor Range - 60 Arrows">
                        Outdoor Range - 60 Arrows
                    </option>

                    <option value="Competition Round - 72 Arrows">
                        Competition Round - 72 Arrows
                    </option>

                    <option value="Practice Round - 36 Arrows">
                        Practice Round - 36 Arrows
                    </option>

                </select>

            </div>

            <!-- ARCHER SECTION -->
            <div class="section-title">
                CHOOSE ARCHER
            </div>

            <div class="form-group">

                <label for="archer">
                    Archer
                </label>

                <select
                    name="archer"
                    id="archer"
                    required
                    onchange="updateArcherInfo()"
                >

                    <option value="">
                        -- Select Archer --
                    </option>

                    <option
                        value="John Smith"
                        data-first="John"
                        data-last="Smith"
                        data-equipment="Recurve"
                    >
                        John Smith
                    </option>

                    <option
                        value="Emily Johnson"
                        data-first="Emily"
                        data-last="Johnson"
                        data-equipment="Compound"
                    >
                        Emily Johnson
                    </option>

                    <option
                        value="Michael Lee"
                        data-first="Michael"
                        data-last="Lee"
                        data-equipment="Longbow"
                    >
                        Michael Lee
                    </option>

                    <option
                        value="Sarah Brown"
                        data-first="Sarah"
                        data-last="Brown"
                        data-equipment="Barebow"
                    >
                        Sarah Brown
                    </option>

                </select>

            </div>

            <!-- FIRST NAME -->
            <div class="form-group">

                <label for="firstName">
                    Archer First Name
                </label>

                <input
                    type="text"
                    id="firstName"
                    class="readonly-box"
                    readonly
                >

            </div>

            <!-- LAST NAME -->
            <div class="form-group">

                <label for="lastName">
                    Archer Last Name
                </label>

                <input
                    type="text"
                    id="lastName"
                    class="readonly-box"
                    readonly
                >

            </div>

            <!-- EQUIPMENT -->
            <div class="form-group">

                <label for="equipment">
                    Equipment
                </label>

                <select
                    name="equipment"
                    id="equipment"
                >

                    <option value="Recurve">
                        Recurve
                    </option>

                    <option value="Compound">
                        Compound
                    </option>

                    <option value="Longbow">
                        Longbow
                    </option>

                    <option value="Barebow">
                        Barebow
                    </option>

                </select>

            </div>

            <!-- SUBMIT BUTTON -->
            <button
                type="submit"
                class="submit-btn"
            >
                DONE
            </button>

        </form>

    </div>

    <!-- JAVASCRIPT -->
    <script src="script.js"></script>

</body>
</html>