document.addEventListener('DOMContentLoaded', (event) => {
    setTimeout(() => {
        const bars = document.querySelectorAll(".animated-bar");
        bars.forEach(bar => {
            const percentageWidth = bar.getAttribute('data-percentage');
            bar.style.width = percentageWidth + '%';
        });
    }, 100);
});