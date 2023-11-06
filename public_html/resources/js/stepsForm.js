function nextStep(oldStepId, newStepId) {
    if (validateStep(oldStepId)) {
        document.getElementById(oldStepId).classList.add("hidden");
        document.getElementById(newStepId).classList.remove("hidden");
    }

}

function prevStep(oldStepId, newStepId) {
    document.getElementById(oldStepId).classList.add("hidden");
    document.getElementById(newStepId).classList.remove("hidden");
}

function validateStep(stepId) {
    const stepForm = document.getElementById(stepId);
    const inputs = stepForm.getElementsByTagName("input");
    const invalidFields = [];

    for (let i = 0; i < inputs.length; i++) {
        const field = inputs[i];
        if (!field.checkValidity()) {
            field.classList.add("bg-red-200");
            invalidFields.push(field);
        } else {
            field.classList.remove("bg-red-200");
        }
    }
    return invalidFields.length === 0;
}

const memeAdresseOui = document.getElementById("Oui-memeAdresse");
const memeAdresseNon = document.getElementById("Non-memeAdresse");
memeAdresseOui.addEventListener('change', function () {
    const selectedValue = this.value;
    if (selectedValue === "Oui") {
        document.getElementById("adr").classList.add("hidden");
    }
});
memeAdresseNon.addEventListener('change', function () {
    const selectedValue = this.value;
    if (selectedValue === "Non") {
        document.getElementById("adr").classList.remove("hidden");
        document.getElementById()
    }
});

document.querySelector('[name="typeRecherche"]').addEventListener('change', function () {
    const selectedValue = this.value;

    ["nomEnt", "numsiret", "numTel", "adresse"].forEach(id => {
        const element = document.getElementById(id);
        const inputs = element.getElementsByTagName('input');

        if (selectedValue === id) {
            element.classList.remove("hidden");
            for (let i = 0; i < inputs.length; i++) {
                if (!inputs[i].classList.contains('hidden')) {
                    inputs[i].setAttribute('required', '');
                }
            }
        } else {
            element.classList.add("hidden");
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].removeAttribute('required');
            }
        }
    });
});
