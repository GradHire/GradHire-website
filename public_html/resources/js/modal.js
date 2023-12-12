let bg = document.getElementById("blur-background");

let currentModal = null;

bg.addEventListener("click", () => {
    bg.classList.add("hidden");
    if (currentModal !== null)
        closeModal(currentModal);
});

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add("hidden");
    modal.classList.remove("block");
    bg.classList.add("hidden");
}

function showModal(modalId, action) {
    if (currentModal !== null)
        closeModal(currentModal);
    currentModal = modalId;
    const modal = document.getElementById(modalId);
    modal.classList.remove("hidden");
    modal.classList.add("block");
    bg.classList.remove("hidden");
    if (action !== "") {
        const btn = modal.querySelector(".action-btn");
        btn.href = action;
    }
}