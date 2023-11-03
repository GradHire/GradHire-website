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