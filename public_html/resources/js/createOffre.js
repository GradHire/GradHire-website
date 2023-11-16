<script>

    function showFormType() {
    var checkboxAlternance = document.getElementById("alternanceCheckbox");
    var checkboxStage = document.getElementById("stageCheckbox");
    var distancielDiv = document.getElementById('distancielDiv');
    const dureeSelectDiv = document.getElementById('dureeSelectDiv');
    const dureeNumberDiv = document.getElementById('dureeNumberDiv');
    const dureeNumber = document.getElementById('dureeStage');
    const dureeSelect = document.getElementById('dureeAlternance');

    distancielDiv.style.display = checkboxAlternance.checked ? 'block' : 'none';
    dureeSelectDiv.style.display = checkboxAlternance.checked ? 'block' : 'none';
    dureeNumberDiv.style.display = checkboxStage.checked ? 'block' : 'none';


    if (checkboxAlternance.checked && checkboxStage.checked) {
    distancielDiv.enable();
    dureeSelect.enable();
    dureeNumber.enable();
} else if (checkboxAlternance.checked && !checkboxStage.checked) {
    distancielDiv.enable();
    dureeSelect.enable();
    dureeNumber.disable();
} else if (checkboxStage.checked && !checkboxAlternance.checked) {
    distancielDiv.disable();
    dureeSelect.disable();
    dureeNumber.enable();
} else {
    distancielDiv.disable();
    dureeSelect.disable();
    dureeNumber.disable();
}
}

    window.addEventListener('DOMContentLoaded', (event) => { // This is added to ensure the script runs after the DOM is ready.
    var datedElem = document.getElementById('dated');
    var datefElem = document.getElementById('datef');

    var nbjour = document.getElementById('nbjour');
    var distanciel = document.getElementById('distanciel');

    datedElem.addEventListener('change', function () {
    if (datefElem.value && (this.value > datefElem.value)) {
    datefElem.setCustomValidity("Date de début ne peut pas être postérieure à la date de fin!");
} else {
    datefElem.setCustomValidity("");
}
});

    datefElem.addEventListener('change', function () {
    if (datedElem.value && (this.value < datedElem.value)) {
    this.setCustomValidity("Date de fin ne peut pas être antérieure à la date de début!");
} else {
    this.setCustomValidity("");
}
});

    nbjour.addEventListener('change', function () {
    if (distanciel.value && (this.value < distanciel.value)) {
    this.setCustomValidity("Insérer une valeur valide");
} else {
    this.setCustomValidity("");
}
});
    distanciel.addEventListener('change', function () {
    if (nbjour.value && (this.value > nbjour.value)) {
    this.setCustomValidity("Insérer une valeur valide");
} else {
    this.setCustomValidity("");
}
});

});

    function saveForm() {
    var elements = document.querySelectorAll('[required]');
    for (var i = 0; i < elements.length; i++) {
    elements[i].removeAttribute('required');
    if (elements[i].id === "nbheure" || elements[i].id === "dated" || elements[i].id === "salaire" || elements[i].id === "datef") elements[i].setAttribute('required', 'required');
}
}


    function refreshPageWithNewOffer() {
    var selectElement = document.getElementById('entreprise');
    var selectedValue = selectElement.value;
    localStorage.setItem('selectedValue', selectedValue);

    document.getElementById('offreForm').submit();
}

    window.onload = function () {
    var selectElement = document.getElementById('entreprise');

    var storedValue = localStorage.getItem('selectedValue');

    if (storedValue) {
    selectElement.value = storedValue;
}
}

</script>