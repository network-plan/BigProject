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
    const emailInput = document.querySelector('.input-box input[type="email"]');
    const phoneInput = document.querySelector('.input-box input[type="tel"]');
    
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

    // 電子郵件驗證
    emailInput.addEventListener('input', function() {
        validateEmail(this);
    });
    
    // 手機號碼驗證
    phoneInput.addEventListener('input', function() {
        validatePhone(this);
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

    function validateEmail(input) {
        const errorElement = input.parentElement.querySelector('.error-message');
        const email = input.value.trim();
        
        errorElement.textContent = '';
        
        if (email.length > 0) {
            if (!isValidEmail(email)) {
                errorElement.textContent = '請輸入有效的電子郵件地址';
            }
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // 電話號碼驗證函數
    function validatePhone(input) {
        const errorElement = input.parentElement.querySelector('.error-message');
        const phone = input.value.trim();
        
        // 清空錯誤訊息
        errorElement.textContent = '';
        
        // 檢查電話號碼格式
        if (phone.length > 0) {  // 只有在使用者開始輸入後才顯示訊息
            // 使用台灣手機號碼格式 (09xxxxxxxx)
            const phoneRegex = /^09\d{8}$/;
            if (!phoneRegex.test(phone)) {
                errorElement.textContent = '請輸入有效的手機號碼 (例如:0912345678)';
            }
        }
    }

    // 原有的表單提交驗證
    const loginForm = document.querySelector('.form-box.login form');
    // const registerForm = document.querySelector('.form-box.register form');

    // 登入表單驗證
    // loginForm.addEventListener('submit', function(e) {
    //     //e.preventDefault();
    //     const username = this.querySelector('input[type="text"]').value;
    //     const password = this.querySelector('input[type="password"]').value;
        
    //     if (username.length < 4 || username.length > 10) {
    //         e.preventDefault();
    //         alert('使用者名稱必須介於4-10個字之間');
    //         return;
    //     }
        
    //     if (password.length < 6) {
    //         e.preventDefault();
    //         alert('密碼長度至少需要6個字符');
    //         return;
    //     }

    //     console.log('登入表單驗證通過');
    // });



    document.getElementById('login_form').addEventListener('submit', function(e) {
        e.preventDefault(); // 阻止表單預設送出行為

        const formData = new FormData(this);

        fetch('login_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('登入成功');
                window.location.href = 'index.php';
            } else {
                alert(data.message); // 顯示錯誤訊息
            }
        })
        .catch(error => {
            console.error('錯誤:', error);
            alert('登入失敗，請稍後再試');
        });
    });

    // 註冊表單驗證
    registerForm.addEventListener('submit', function(e) {
        //e.preventDefault();
        const username = this.querySelector('input[name="register_username"]').value;
        const email = this.querySelector('input[name="register_email"]').value;
        const password = this.querySelector('input[name="register_password"]').value;
        const confirmPassword = this.querySelectorAll('input[type="password"]')[1].value;
        const phone = this.querySelector('input[type="tel"]') ? this.querySelector('input[type="tel"]').value : '';

        
        if (username.length < 4 || username.length > 10) {
            e.preventDefault(); // 只有在驗證失敗時阻止提交
            alert('使用者名稱必須介於4-10個字之間');
            return;
        }
        
        if (!isValidEmail(email)) {
            e.preventDefault();
            alert('請輸入有效的電子郵件地址');
            return;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('密碼長度至少需要6個字符');
            return;
        }
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('兩次輸入的密碼不一致');
            return;
        }

        // 電話號碼驗證
        if (phone && !isValidPhone(phone)) {
            e.preventDefault();
            alert('請輸入有效的手機號碼 (例如:0912345678)');
            return;
        }

        console.log('註冊表單驗證通過');
        
    });
    
});
