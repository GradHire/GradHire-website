function toggleLocalStorageItem(itemName, value1, value2) {
    const itemValue = localStorage.getItem(itemName);
    if (itemValue === null || itemValue === value2) localStorage.setItem(itemName, value1);
    else localStorage.setItem(itemName, value2);
}
