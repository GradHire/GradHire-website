function nextStep(oldStepId, newStepId) {
    if (validateStep(oldStepId)) {
        document.getElementById(oldStepId).classList.add("hidden");
        document.getElementById(newStepId).classList.remove("hidden");
    } else {
        const invalidFields = document.querySelectorAll("#" + oldStepId + " input:invalid");
        invalidFields.forEach(field => field.classList.add("bg-red-200"));
        alert("Veuillez remplir les champs obligatoires");
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

if (memeAdresseOui && memeAdresseNon) {
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
        }
    });
}

const typeRecherche = document.querySelector('[name="typeRecherche"]');
if (typeRecherche) {
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
}
const interruptionOui = document.getElementById("Oui-interruption");
const interruptionNon = document.getElementById("Non-interruption");
if (interruptionOui && interruptionNon) {
    interruptionOui.addEventListener('change', function () {
        const selectedValue = this.value;
        if (selectedValue === "Oui") {
            document.getElementById("interr").classList.remove("hidden");
            const element = document.getElementById("interr");
            const inputs = element.getElementsByTagName('input');
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].setAttribute('required', '');
            }
        }
    });

    interruptionNon.addEventListener('change', function () {
        const selectedValue = this.value;
        if (selectedValue === "Non") {
            document.getElementById("interr").classList.add("hidden");
            const element = document.getElementById("interr");
            const inputs = element.getElementsByTagName('input');
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].removeAttribute('required');
            }
        }
    });
}
const gratificationOui = document.getElementById("Oui-gratification");
const gratificationNon = document.getElementById("Non-gratification");
if (gratificationOui && gratificationNon) {
    gratificationOui.addEventListener('change', function () {
        const selectedValue = this.value;
        if (selectedValue === "Oui") {
            document.getElementById("grat").classList.remove("hidden");
            const element = document.getElementById("grat");
            const inputs = element.getElementsByTagName('input');
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].setAttribute('required', '');
            }
        }
    });

    gratificationNon.addEventListener('change', function () {
        const selectedValue = this.value;
        if (selectedValue === "Non") {
            document.getElementById("grat").classList.add("hidden");
            const element = document.getElementById("grat");
            const inputs = element.getElementsByTagName('input');
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].removeAttribute('required');
            }
        }
    });
}
if (document.getElementById("signataire") != null) {
    document.getElementById("signataire").addEventListener("input", function () {
        if (document.getElementById("signataire").value === "Non renseigné") {
            document.getElementById("step2").classList.add("hidden");
        } else {
            document.getElementById("step2").classList.remove("hidden");
        }
    });
}
if (document.getElementById("accueil") != null) {
    document.getElementById("accueil").addEventListener("input", function () {
        if (document.getElementById("accueil").value === "Non renseigné") {
            document.getElementById("step2").classList.add("hidden");
        } else {
            document.getElementById("step2").classList.remove("hidden");
        }
    });
}
