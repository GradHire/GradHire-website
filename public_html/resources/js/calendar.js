let currentWeek = 0;
let weeks = document.querySelectorAll(".calendar-week");

function enableArrow(arrow, state) {
    if (state && arrow.classList.contains("disabled")) arrow.classList.remove("disabled");
    if (!state && !arrow.classList.contains("disabled")) arrow.classList.add("disabled");
}

function updateCalendarArrows() {
    let calendarLeftArrow = document.getElementById("calendar-prev");
    let calendarRightArrow = document.getElementById("calendar-next");

    enableArrow(calendarLeftArrow, currentWeek > 0);
    enableArrow(calendarRightArrow, currentWeek < weeks.length - 1);
}

updateCalendarArrows()

function calendarPrev() {
    if (currentWeek > 0) {
        weeks[currentWeek].classList.add("hidden");
        currentWeek--;
        weeks[currentWeek].classList.remove("hidden");
        updateCalendarArrows();
    }
}

function calendarNext() {
    if (currentWeek < weeks.length - 1) {
        weeks[currentWeek].classList.add("hidden");
        currentWeek++;
        weeks[currentWeek].classList.remove("hidden");
        updateCalendarArrows();
    }
}