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

    <label>
        Archer First Name
    </label>

    <input
        type="text"
        id="firstName"
        readonly
        class="readonly-box"
    >

</div>

<!-- LAST NAME -->
<div class="form-group">

    <label>
        Archer Last Name
    </label>

    <input
        type="text"
        id="lastName"
        readonly
        class="readonly-box"
    >

</div>

<!-- EQUIPMENT -->
<div class="form-group">

    <label for="equipment">
        Equipment
    </label>

    <select name="equipment" id="equipment">

        <option value="Recurve">Recurve</option>
        <option value="Compound">Compound</option>
        <option value="Longbow">Longbow</option>
        <option value="Barebow">Barebow</option>

    </select>

</div>

<!-- JAVASCRIPT -->
<script>

function updateArcherInfo() {

    const archerSelect =
        document.getElementById("archer");

    const selectedOption =
        archerSelect.options[archerSelect.selectedIndex];

    const firstName =
        selectedOption.getAttribute("data-first");

    const lastName =
        selectedOption.getAttribute("data-last");

    const equipment =
        selectedOption.getAttribute("data-equipment");

    document.getElementById("firstName").value =
        firstName || "";

    document.getElementById("lastName").value =
        lastName || "";

    document.getElementById("equipment").value =
        equipment || "Recurve";
}

</script>