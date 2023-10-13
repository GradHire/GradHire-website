const rangeSliders = document.querySelectorAll('.range-slider');

rangeSliders.forEach(slider => {
    const sliders = slider.querySelectorAll('[type="range"]');
    const minRange = sliders[0];
    const maxRange = sliders[1];
    const hidden = slider.querySelector('[type="text"]');
    const sliderTrack = slider.querySelector('.slider-track');
    const spans = slider.querySelectorAll('.range-spans span');

    minRange.addEventListener('input', updateRange);
    maxRange.addEventListener('input', updateRange);

    updateRange();

    function updateRange() {
        const min = parseFloat(minRange.value);
        const max = parseFloat(maxRange.value);
        if (min > max)
            minRange.value = max;
        if (max < min)
            maxRange.value = min;
        spans[0].textContent = min;
        spans[1].textContent = max;
        const percent1 = (min - minRange.min) / (maxRange.max - minRange.min) * 100;
        const percent2 = (max - minRange.min) / (maxRange.max - minRange.min) * 100;
        sliderTrack.style.background = `linear-gradient(to right, #dadae5 ${percent1}%, #71717a ${percent1}%, #71717a ${percent2}%, #dadae5 ${percent2}%)`;
        hidden.value = `${min},${max}`;
    }
});