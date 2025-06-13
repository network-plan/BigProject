document.addEventListener('DOMContentLoaded', function() {
    // 切換面板
    const container = document.querySelector('.container_login');
    const registerBtn = document.querySelector('.register-btn');
    const loginBtn = document.querySelector('.login-btn');

    if (registerBtn) {
        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.classList.add('active');
        });
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.classList.remove('active');
        });
    }

    // 獲取表單元素
    const usernameInputs = document.querySelectorAll('.input-box input[type="text"]');
    const passwordInputs = document.querySelectorAll('.input-box input[type="password"]');
    const registerForm = document.querySelector('.form-box.register form');
    const loginForm = document.querySelector('.form-box.login form');
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
            if (this.placeholder === "確認密碼") {
                validateConfirmPassword(this);
            }
        });
    });

    // 電子郵件驗證
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            validateEmail(this);
        });
    }
    
    // 手機號碼驗證
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            validatePhone(this);
        });
    }

    // 驗證函數
    function validateUsername(input) {
        const errorElement = input.parentElement.querySelector('.error-message');
        const username = input.value.trim();
        
        errorElement.textContent = '';
        
        if (username.length > 0) {
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

    function validatePhone(input) {
        const errorElement = input.parentElement.querySelector('.error-message');
        const phone = input.value.trim();
        
        errorElement.textContent = '';
        
        if (phone.length > 0) {
            const phoneRegex = /^09\d{8}$/;
            if (!phoneRegex.test(phone)) {
                errorElement.textContent = '請輸入有效的手機號碼 (例如:0912345678)';
            }
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^09\d{8}$/;
        return phoneRegex.test(phone);
    }

    // 登入表單處理
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

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
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('錯誤:', error);
                alert('登入失敗，請稍後再試');
            });
        });
    }

    // 註冊表單驗證和提交
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const username = this.querySelector('input[name="register_username"]').value.trim();
            const email = this.querySelector('input[name="register_email"]').value.trim();
            const password = this.querySelector('input[name="register_password"]').value;
            const confirmPassword = this.querySelectorAll('input[type="password"]')[1].value;
            const phone = this.querySelector('input[type="tel"]').value.trim();

            // 前端驗證
            if (username.length < 4 || username.length > 10) {
                e.preventDefault();
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

            if (phone && !isValidPhone(phone)) {
                e.preventDefault();
                alert('請輸入有效的手機號碼 (例如:0912345678)');
                return;
            }
        });
    }

    // 帳號重複檢查功能 (使用原生 JavaScript 替代 jQuery)
    const accountInput = document.getElementById('account_input');
    if (accountInput) {
        accountInput.addEventListener('blur', function() {
            const username = this.value.trim();
            const errorSpan = this.parentElement.querySelector('.error-message');
            
            if (username.length >= 4) {
                checkUsernameAvailability(username)
                    .then(response => {
                        if (response.exists) {
                            errorSpan.textContent = '此帳號已被使用';
                            errorSpan.style.color = 'red';
                            errorSpan.style.display = 'block';
                            this.classList.add('error');
                        } else if (response.error) {
                            errorSpan.textContent = response.error;
                            errorSpan.style.color = 'red';
                            errorSpan.style.display = 'block';
                        } else {
                            errorSpan.textContent = '帳號可以使用';
                            errorSpan.style.color = 'green';
                            errorSpan.style.display = 'block';
                            this.classList.remove('error');
                        }
                    })
                    .catch(error => {
                        errorSpan.textContent = '檢查帳號時發生錯誤';
                        errorSpan.style.color = 'red';
                        errorSpan.style.display = 'block';
                    });
            }
        });

        // 當用戶重新輸入時清除錯誤訊息
        accountInput.addEventListener('input', function() {
            const errorSpan = this.parentElement.querySelector('.error-message');
            errorSpan.style.display = 'none';
            this.classList.remove('error');
        });
    }

    // 註冊表單提交前的最終檢查
    const inputForm = document.getElementById('input_form');
    if (inputForm) {
        inputForm.addEventListener('submit', function(e) {
            const usernameInput = document.getElementById('account_input');
            
            if (usernameInput && usernameInput.classList.contains('error')) {
                e.preventDefault();
                alert('請選擇不同的帳號名稱');
                return false;
            }
        });
    }
});

// 帳號重複檢查功能 (原生 JavaScript 版本)
function checkUsernameAvailability(username) {
    return fetch('check_username.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'username=' + encodeURIComponent(username)
    })
    .then(response => response.json());
}