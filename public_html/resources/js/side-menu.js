document.addEventListener('DOMContentLoaded', (event) => {

    const getCookie = (name) => {
        const value = "; " + document.cookie;
        const parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
    }

    const setCookie = (name, value, days = 7, path = '/') => {
        const expires = new Date(Date.now() + days * 864e5).toUTCString()
        document.cookie = `${name}=${value}; expires=${expires}; path=${path}`
    }

    const sidebarPathToSpin = document.querySelector('.sidebar-path-to-spin');
    const sidebarContainer = document.getElementById('sidebar-container');
    const sidebarButton = document.getElementById('sidebar-button');
    let sidebarOpen = getCookie('sidebar_open');

    sidebarButton.addEventListener("click", function () {
        if (sidebarOpen === undefined) sidebarOpen = 'true';
        if (sidebarOpen === 'false') {
            setCookie('sidebar_open', 'true');
            updateSideMenu();
            sidebarOpen = 'true';
        } else {
            setCookie('sidebar_open', 'false');
            updateSideMenu();
            sidebarOpen = 'false';
        }
    });

    function updateSideMenu() {
        if (sidebarOpen === 'true') {
            sidebarContainer.classList.remove('max-w-[275px]');
            sidebarContainer.classList.add('max-w-[75px]');
        } else {
            sidebarContainer.classList.remove('max-w-[75px]');
            sidebarContainer.classList.add('max-w-[275px]');
        }

        if (sidebarOpen === 'true') {
            sidebarPathToSpin.style.rotate = '180deg';
            sidebarPathToSpin.style.transform = 'translate(-28px, -20px)';
        } else {
            sidebarPathToSpin.style.rotate = '0deg';
            sidebarPathToSpin.style.transform = 'translate(0, 0)';
        }
    }

});
