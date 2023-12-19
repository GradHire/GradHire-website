document.addEventListener('DOMContentLoaded', async () => {
    const setCookie = (name, value, days = 7, path = '/') => {
        const expires = new Date(Date.now() + days * 864e5).toUTCString()
        document.cookie = `${name}=${value}; expires=${expires}; path=${path}`
    }

    const {
        lienNoms, lienBlocks, lienTooltips, sectionAdds, sectionTexts, sidebarContainer,
        sideButtonFlash1, sideButtonFlash2, logoBig, logoSmall, sidebarButton
    } = getDOMElements();

    let isSidebarOpen = getCookie('sidebar_open');
    await updateSideMenu();
    sidebarButton.addEventListener("click", async function () {
        if (isSidebarOpen === undefined) isSidebarOpen = 'true';
        if (isSidebarOpen === 'false') {
            setCookie('sidebar_open', 'true');
            isSidebarOpen = 'true';
            await updateSideMenu();
        } else {
            setCookie('sidebar_open', 'false');
            isSidebarOpen = 'false';
            await updateSideMenu();
        }
    });

    async function updateSideMenu() {
        if (isSidebarOpen === 'false') closeSideBar();
        else openSideBar();
    }

    function getDOMElements() {
        return {
            lienNoms: document.querySelectorAll('.lienNom'),
            lienBlocks: document.querySelectorAll('.lienBlock'),
            lienTooltips: document.querySelectorAll('.lienTooltip'),
            sectionAdds: document.querySelectorAll('.sectionAdd'),
            sectionTexts: document.querySelectorAll('.sectionText'),
            sidebarContainer: document.getElementById('sidebar-container'),
            sideButtonFlash1: document.getElementById('sideButtonFlash1'),
            sideButtonFlash2: document.getElementById('sideButtonFlash2'),
            logoBig: document.getElementById('logo-big'),
            logoSmall: document.getElementById('logo-small'),
            sidebarButton: document.getElementById('sidebar-button')
        }
    }

    function closeSideBar() {
        sidebarContainer.classList.remove('max-w-[275px]');
        sidebarContainer.classList.add('max-w-[75px]');
        logoBig.style.display = 'none';
        logoSmall.style.display = 'block';
        sideButtonFlash1.classList.replace('group-hover:rotate-[15deg]', 'group-hover:-rotate-[15deg]');
        sideButtonFlash1.classList.replace('group-hover:translate-x-0.5', 'group-hover:-translate-x-0.5');
        sideButtonFlash2.classList.replace('group-hover:-rotate-[15deg]', 'group-hover:rotate-[15deg]');
        sideButtonFlash2.classList.replace('group-hover:translate-x-0.5', 'group-hover:-translate-x-0.5');
        for (let i = 0; i < sectionTexts.length; i++) {
            sectionTexts[i].classList.replace("text-[12px]", "text-[8px]")
            sectionAdds[i].style.display = 'none';
        }
        for (let i = 0; i < lienNoms.length; i++) {
            lienNoms[i].classList.add('hidden');
            lienBlocks[i].classList.replace("w-full", "w-10");
            lienBlocks[i].classList.replace("py-2", "py-1");
            lienTooltips[i].classList.replace("hidden", "flex");
        }
    }

    function openSideBar() {
        sidebarContainer.classList.replace("max-w-[75px]", "max-w-[275px]");
        logoBig.style.display = 'block';
        logoSmall.style.display = 'none';
        sideButtonFlash1.classList.replace('group-hover:-rotate-[15deg]', 'group-hover:rotate-[15deg]');
        sideButtonFlash1.classList.replace('group-hover:-translate-x-0.5', 'group-hover:translate-x-0.5');
        sideButtonFlash2.classList.replace('group-hover:rotate-[15deg]', 'group-hover:-rotate-[15deg]');
        sideButtonFlash2.classList.replace('group-hover:-translate-x-0.5', 'group-hover:translate-x-0.5');
        for (let i = 0; i < sectionTexts.length; i++) {
            sectionTexts[i].classList.replace("text-[8px]", "text-[12px]")
            sectionAdds[i].style.display = 'block';
        }
        for (let i = 0; i < lienNoms.length; i++) {
            lienNoms[i].classList.remove('hidden');
            lienBlocks[i].classList.replace("w-10", 'w-full');
            lienBlocks[i].classList.replace("py-1", "py-2");
            lienTooltips[i].classList.replace('flex', 'hidden');
        }
    }
});

function getCookie(name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

