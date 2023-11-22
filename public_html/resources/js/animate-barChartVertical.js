document.addEventListener('DOMContentLoaded', (event) => {
        const bars = document.querySelectorAll(".barChartRect");
        const animationStartDelay = 0;

        bars.forEach((bar, i) => {
            setTimeout(function() {
                bar.style.transformOrigin = "bottom";
                bar.style.animation = "fadeInAnimationVerticalBarChart 1s ease-out forwards";
            }, i * animationStartDelay);
        });
});