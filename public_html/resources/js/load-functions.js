function toggleLocalStorageItem(itemName, value1, value2) {
    const itemValue = localStorage.getItem(itemName);
    if (itemValue === null || itemValue === value2) localStorage.setItem(itemName, value1);
    else localStorage.setItem(itemName, value2);
}

function getCookie (name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}
