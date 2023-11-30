document.addEventListener('DOMContentLoaded', async (event) => {

    const setCookie = (name, value, days = 7, path = '/') => {
        const expires = new Date(Date.now() + days * 864e5).toUTCString()
        document.cookie = `${name}=${value}; expires=${expires}; path=${path}`
    }

    const sidebarPathToSpin = document.querySelector('.sidebar-path-to-spin');
    const lienNoms = document.querySelectorAll('.lienNom');
    const lienBlocks = document.querySelectorAll('.lienBlock');
    const lienTooltips = document.querySelectorAll('.lienTooltip');
    const sectionAdds = document.querySelectorAll('.sectionAdd');
    const sectionTexts = document.querySelectorAll('.sectionText');
    const sidebarContainer = document.getElementById('sidebar-container');
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
            sidebarContainer.classList.add('max-w-[100px]');
            sidebarPathToSpin.style.rotate = '180deg';
            sidebarPathToSpin.style.transform = 'translate(-28px, -20px)';
            logoBig.style.display = 'none';
            logoSmall.style.display = 'block';
            for (let i = 0; i < sectionTexts.length; i++) sectionTexts[i].classList.replace("text-[12px]", "text-[8px]")
            for (let i = 0; i < lienNoms.length; i++) {
                lienNoms[i].classList.add('hidden');
                lienBlocks[i].classList.replace("w-full", "w-9");
                lienTooltips[i].classList.replace("hidden", "flex");
                sectionAdds[i].style.display = 'none';
            }
        } else {
            sidebarContainer.classList.replace("max-w-[100px]", "max-w-[275px]");
            sidebarPathToSpin.style.rotate = '0deg';
            sidebarPathToSpin.style.transform = 'translate(0, 0)';
            logoBig.style.display = 'block';
            logoSmall.style.display = 'none';
            for (let i = 0; i < sectionTexts.length; i++) sectionTexts[i].classList.replace("text-[8px]", "text-[12px]")
            for (let i = 0; i < lienNoms.length; i++) {
                lienNoms[i].classList.remove('hidden');
                lienBlocks[i].classList.replace("w-9", 'w-full');
                lienTooltips[i].classList.replace('flex', 'hidden');
                sectionAdds[i].style.display = 'block';
            }

        }
    }

});

