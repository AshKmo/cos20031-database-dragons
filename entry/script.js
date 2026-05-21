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