document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.container_login');
    const registerBtn = document.querySelector('.register-btn');
    const loginBtn = document.querySelector('.login-btn');

    console.log('Container:', container);
    console.log('Register button:', registerBtn);
    console.log('Login button:', loginBtn);

    if (registerBtn) {
        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('Register button clicked');
            container.classList.add('active');
        });
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('Login button clicked');
            container.classList.remove('active');
        });
    }
});