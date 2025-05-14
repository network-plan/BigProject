function togglePassword() {
    var passwordSpan = document.getElementById('password');
    if (passwordSpan.textContent === '********') {
        passwordSpan.textContent = '12345678';
    } else {
        passwordSpan.textContent = '********';
    }
}