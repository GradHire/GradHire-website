function togglePasswordVisibility(button) {
    const eye = button.firstElementChild;
    const slash = eye.nextElementSibling;
    const passwordInput = button.previousElementSibling;
    let isPasswordVisible = (passwordInput.type === 'text');

    if (isPasswordVisible) {
        passwordInput.type = 'password';
        eye.style.display = 'block';
        slash.style.display = 'none';
    } else {
        passwordInput.type = 'text';
        eye.style.display = 'none';
        slash.style.display = 'block';
    }
}