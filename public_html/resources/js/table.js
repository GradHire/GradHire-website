let oldTh = null;
const tables = {}
const nbRows = 20;

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
        //add hidden
        if (found)
            tr[i].classList.remove("hidden")
        else
            tr[i].classList.add("hidden")
    }

    if (res)
        document.getElementById(empty).style.display = 'none'
    else
        document.getElementById(empty).style.display = 'block'

    const pagination = document.getElementById(tableId + '-pagination');

    if (search === '') {
        for (let i = 1; i < tr.length; i++) {
            tr[i].style.display = "";
        }
        document.getElementById(clearId).style.display = 'none'
        pagination.style.display = 'flex';
        initTable(tableId)
        goToTable(tableId, tables[tableId].currentPage)
    } else {
        document.getElementById(clearId).style.display = 'block'
        pagination.style.display = 'none';
    }
}

function clearInput(inputId) {
    document.getElementById(inputId).value = '';
}

function initTable(id) {
    if (tables[id]) return;
    const table = document.getElementById(id);
    const rows = table.getElementsByTagName("tr");
    tables[id] = {
        currentPage: 1,
        pages: Math.ceil((rows.length - 1) / nbRows)
    };
}

function updateTablePagination(id) {
    const pagination = document.getElementById(id + '-pagination');
    pagination.innerHTML = ''

    const previous = document.createElement('button');
    previous.innerText = '<';
    previous.disabled = !(tables[id].currentPage > 1 && tables[id].pages > 1);
    previous.onclick = function () {
        previousTable(id)
    }
    previous.className = 'h-[40px] w-[40px] border-2 border-zinc-500 rounded-md enabled:hover:text-white enabled:hover:bg-zinc-500 disabled:opacity-50'
    pagination.appendChild(previous)

    let start = tables[id].currentPage - 2;
    if (start < 1)
        start = 1

    if (start > 1) {
        const p = document.createElement('p');
        p.innerText = '...';
        pagination.appendChild(p)
    }

    for (let i = start; i <= start + 4 && i <= tables[id].pages; i++) {
        const button = document.createElement('button');
        button.innerText = i.toString();
        button.onclick = function () {
            goToTable(id, i)
        }
        button.className = 'h-[40px] w-[40px] border-2 border-zinc-500 rounded-md hover:text-white hover:bg-zinc-500 [&.active]:text-white [&.active]:bg-zinc-500'

        if (i === tables[id].currentPage)
            button.classList.add('active')
        pagination.appendChild(button)
    }

    if (tables[id].pages - tables[id].currentPage > 2) {
        const p = document.createElement('p');
        p.innerText = '...';
        pagination.appendChild(p)
    }

    const next = document.createElement('button');
    next.innerText = '>';
    next.disabled = tables[id].currentPage >= tables[id].pages;
    next.onclick = function () {
        nextTable(id)
    }
    next.className = 'h-[40px] w-[40px] border-2 border-zinc-500 rounded-md enabled:hover:text-white enabled:hover:bg-zinc-500 disabled:opacity-50'
    pagination.appendChild(next)
}

function goToTable(id, page) {
    initTable(id)
    tables[id].currentPage = page;

    const table = document.getElementById(id);
    const rows = table.getElementsByTagName("tr");
    const max = page * nbRows;
    const min = max - nbRows;
    for (let i = 1; i < rows.length; i++) {
        if (i > min && i <= max)
            rows[i].classList.remove('hidden');
        else
            rows[i].classList.add('hidden');
    }
    updateTablePagination(id)
}

function nextTable(id) {
    initTable(id)
    if (tables[id].currentPage <= tables[id].pages)
        goToTable(id, tables[id].currentPage + 1)
}

function previousTable(id) {
    initTable(id)
    if (tables[id].currentPage > 1)
        goToTable(id, tables[id].currentPage - 1)
}
