function togglePasswordVisibility(button) {
    const passwordInput = button.previousElementSibling;
    let isPasswordVisible = (passwordInput.type === 'text');

    if (isPasswordVisible) {
        passwordInput.type = 'password';
        button.classList.remove('fa-eye-slash');
        button.classList.add('fa-eye');
    } else {
        passwordInput.type = 'text';
        button.classList.remove('fa-eye');
        button.classList.add('fa-eye-slash');
    }
}