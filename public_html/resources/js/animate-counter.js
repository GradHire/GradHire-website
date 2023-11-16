function animateValue(obj, start, end, duration) {
    let frameRate = 1000 / 120;
    let totalFrames = (duration / frameRate);
    let currentFrame = 0;
    const countUp = () => {
        currentFrame++;
        let currentCount = Math.round(start + (end - start) * (currentFrame / totalFrames));
        if (currentFrame >= totalFrames) {
            clearInterval(interval);
            currentCount = end;
        }
        obj.textContent = currentCount;
    };

    let interval = setInterval(countUp, frameRate);
}

document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        animateValue(counter, 0, parseInt(counter.dataset.target, 10), 1500);
    });
});
