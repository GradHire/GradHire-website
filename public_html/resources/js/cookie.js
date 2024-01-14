let cookieOff = () => {
    let cookieDiv = document.getElementById('cookie');
    if (cookieDiv) cookieDiv.style.display = 'none';
    sessionStorage.setItem('cookieAccept', 'true');
}

document.addEventListener('DOMContentLoaded', async () => {
    let cookieDiv = document.getElementById('cookie');
    let [boutonAccepter, boutonRefuser] = ['#bouton-accepter', '#bouton-refuser'].map(s => document.querySelector(s));
    if (sessionStorage.getItem('cookieAccept') === 'true' && cookieDiv) cookieDiv.style.display = 'none';
    [boutonAccepter, boutonRefuser].forEach(b => b.addEventListener('click', cookieOff));
})