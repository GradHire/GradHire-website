const burgerBtn = document.getElementById("burger-btn");
const navContainer = document.getElementById("nav-container");
burgerBtn.addEventListener("click", function () {
    if (navContainer.classList.contains('animate-slide-out') || navContainer.classList.contains('hidden')) {
        navContainer.classList.remove('animate-slide-out', 'hidden');
        navContainer.classList.add('animate-slide-in');
    } else {
        navContainer.classList.remove('animate-slide-in');
        navContainer.classList.add('animate-slide-out');
        setTimeout(function () {
            navContainer.classList.add('hidden');
        }, 500);
    }
});