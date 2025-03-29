document.addEventListener('DOMContentLoaded', function() {
    // 切換面板
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

    // 獲取所有使用者名稱輸入框
    const usernameInputs = document.querySelectorAll('.input-box input[type="text"]');
    const passwordInputs = document.querySelectorAll('.input-box input[type="password"]');
    const registerForm = document.querySelector('.form-box.register form');
    
    // 為每個使用者名稱輸入框添加即時驗證
    usernameInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateUsername(this);
        });
    });

    // 密碼驗證
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            validatePassword(this);
            // 如果是註冊表單的確認密碼框，需要特別處理
            if (this.placeholder === "確認密碼") {
                validateConfirmPassword(this);
            }
        });
    });

    function validateUsername(input) {
        const errorElement = input.parentElement.querySelector('.error-message');
        const username = input.value.trim();
        
        // 清空錯誤訊息
        errorElement.textContent = '';
        
        // 檢查長度
        if (username.length > 0) {  // 只有在使用者開始輸入後才顯示訊息
            if (username.length < 4) {
                errorElement.textContent = '使用者名稱至少需要4個字';
            } else if (username.length > 10) {
                errorElement.textContent = '使用者名稱不能超過10個字';
            }
        }
    }

    function validatePassword(input) {
        const errorElement = input.parentElement.querySelector('.error-message');
        const password = input.value;
        
        errorElement.textContent = '';
        
        if (password.length > 0) {
            if (password.length < 6) {
                errorElement.textContent = '密碼至少需要6個字符';
            }
        }
    }

    function validateConfirmPassword(input) {
        const errorElement = input.parentElement.querySelector('.error-message');
        const password = registerForm.querySelectorAll('input[type="password"]')[0].value;
        const confirmPassword = input.value;
        
        errorElement.textContent = '';
        
        if (confirmPassword.length > 0) {
            if (password !== confirmPassword) {
                errorElement.textContent = '兩次輸入的密碼不一致';
            }
        }
    }

    // 原有的表單提交驗證
    const loginForm = document.querySelector('.form-box.login form');
    // const registerForm = document.querySelector('.form-box.register form');

    // 登入表單驗證
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const username = this.querySelector('input[type="text"]').value;
        const password = this.querySelector('input[type="password"]').value;
        
        if (username.length < 4 || username.length > 10) {
            alert('使用者名稱必須介於4-10個字之間');
            return;
        }
        
        if (password.length < 6) {
            alert('密碼長度至少需要6個字符');
            return;
        }

        console.log('登入表單驗證通過');
    });

    // 註冊表單驗證
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const username = this.querySelector('input[type="text"]').value;
        const email = this.querySelector('input[type="email"]').value;
        const password = this.querySelectorAll('input[type="password"]')[0].value;
        const confirmPassword = this.querySelectorAll('input[type="password"]')[1].value;
        
        if (username.length < 4 || username.length > 10) {
            alert('使用者名稱必須介於4-10個字之間');
            return;
        }
        
        if (!isValidEmail(email)) {
            alert('請輸入有效的電子郵件地址');
            return;
        }
        
        if (password.length < 6) {
            alert('密碼長度至少需要6個字符');
            return;
        }
        
        if (password !== confirmPassword) {
            alert('兩次輸入的密碼不一致');
            return;
        }

        console.log('註冊表單驗證通過');
    });

    // 電子郵件驗證函數
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
});
