const durations = document.getElementById("durations")
const distanciel = document.getElementById("time").children[2]
const dureeAlternance = durations.children[0]
const dureeStage = durations.children[1]

dureeAlternance.classList.add("hidden");
dureeStage.classList.add("hidden");
distanciel.classList.add("hidden");

update()

function update() {
    if (document.getElementById("stage").checked) {
        dureeStage.classList.remove("hidden");
    } else {
        dureeStage.classList.add("hidden");
    }
    if (document.getElementById("alternance").checked) {
        dureeAlternance.classList.remove("hidden");
        distanciel.classList.remove("hidden");
    } else {
        dureeAlternance.classList.add("hidden");
        distanciel.classList.add("hidden");
    }
}

document.getElementById("stage").addEventListener("change", function () {
    update()
})

document.getElementById("alternance").addEventListener("change", function () {
    update()
})

const draft = document.getElementById("draftSelect")
draft.addEventListener("change", function () {
    if (draft.value !== "new")
        window.location.href = "/offres/create/" + draft.value
})