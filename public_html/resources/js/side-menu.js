document.addEventListener('DOMContentLoaded', async (event) => {

    const setCookie = (name, value, days = 7, path = '/') => {
        const expires = new Date(Date.now() + days * 864e5).toUTCString()
        document.cookie = `${name}=${value}; expires=${expires}; path=${path}`
    }

    const lienNoms = document.querySelectorAll('.lienNom');
    const lienBlocks = document.querySelectorAll('.lienBlock');
    const lienTooltips = document.querySelectorAll('.lienTooltip');
    const sectionAdds = document.querySelectorAll('.sectionAdd');
    const sectionTexts = document.querySelectorAll('.sectionText');
    const sidebarContainer = document.getElementById('sidebar-container');
    const sideButtonFlash1 = document.getElementById('sideButtonFlash1');
    const sideButtonFlash2 = document.getElementById('sideButtonFlash2');
    const logoBig = document.getElementById('logo-big');
    const logoSmall = document.getElementById('logo-small');
    const sidebarButton = document.getElementById('sidebar-button');
    let sidebarOpen = getCookie('sidebar_open');

    await updateSideMenu();

    sidebarButton.addEventListener("click", async function () {
        if (sidebarOpen === undefined) sidebarOpen = 'true';
        if (sidebarOpen === 'false') {
            setCookie('sidebar_open', 'true');
            sidebarOpen = 'true';

            await updateSideMenu();
        } else {
            setCookie('sidebar_open', 'false');
            sidebarOpen = 'false';
            await updateSideMenu();
        }
    });

    async function updateSideMenu() {
        if (sidebarOpen === 'false') {
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
                lienBlocks[i].classList.replace("w-full", "w-9");
                lienTooltips[i].classList.replace("hidden", "flex");
            }
        } else {
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
                lienBlocks[i].classList.replace("w-9", 'w-full');
                lienTooltips[i].classList.replace('flex', 'hidden');
            }

        }
    }

});

