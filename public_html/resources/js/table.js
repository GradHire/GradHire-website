let oldTh = null;

function sortTable(tableId, th, n) {
    if (oldTh != null) {
        oldTh.querySelector('.fa-solid').className = 'fa-solid fa-sort';
    }
    oldTh = th;
    let table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById(tableId);
    switching = true;
    dir = "asc";

    while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];

            if (dir === "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir === "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }

        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;

            let icon = th.querySelector('.fa-solid');
            if (icon) {
                icon.className = dir === 'asc' ? 'fa-solid fa-sort-up' : 'fa-solid fa-sort-down';
            }
        } else {
            if (switchcount === 0 && dir === "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}

function search(input, tableId, columns, empty, clearId) {
    const search = document.getElementById(input).value.trim();
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName("tr");

    let res = false;
    for (let i = 1; i < tr.length; i++) {
        let found = false;
        for (let j = 0; j < columns; j++) {
            const td = tr[i].getElementsByTagName("td")[j];
            if (td) {
                const txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(search.toLowerCase()) > -1) {
                    found = true;
                    res = true;
                    break;
                }
            }
        }
        tr[i].style.display = found ? "" : "none";
    }

    if (res)
        document.getElementById(empty).style.display = 'none'
    else
        document.getElementById(empty).style.display = 'block'


    if (search === '') {
        for (let i = 1; i < tr.length; i++) {
            tr[i].style.display = "";
        }
        document.getElementById(clearId).style.display = 'none'
    } else {
        document.getElementById(clearId).style.display = 'block'
    }
}

function clearInput(inputId) {
    document.getElementById(inputId).value = '';
}

