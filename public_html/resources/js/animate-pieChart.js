document.addEventListener('DOMContentLoaded', (event) => {

    const legendItems = document.querySelectorAll(".pie-chart-legend > div");
    legendItems.forEach((item) => {
        item.classList.add("opacity-0");
    });
    legendItems[0].classList.add("fadeIn");

    for (let i = 1; i < legendItems.length; i++) {
        setTimeout(() => {
            legendItems[i].classList.add("fadeIn");
        }, i * 200);
    }
});