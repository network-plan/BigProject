
    // 密碼顯示與隱藏功能
    function togglePassword() {
        const passwordSpan = document.getElementById('password');
        const button = event.target;

        if (passwordSpan.textContent === '********') {
            passwordSpan.textContent = button.getAttribute('data-password');
            button.textContent = '隱藏';
        } else {
            passwordSpan.textContent = '********';
            button.textContent = '查看';
        }
    }

    // 顯示/隱藏密碼修改表單
    function togglePasswordForm() {
        const passwordForm = document.getElementById('passwordForm');
        const editForm = document.getElementById('editForm');
        
        if (passwordForm.style.display === 'block') {
            passwordForm.style.display = 'none';
        } else {
            passwordForm.style.display = 'block';
            editForm.style.display = 'none'; // 關閉編輯表單
            
            // 平滑滾動到密碼表單位置
            setTimeout(function() {
                passwordForm.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100); // 稍微延遲以確保表單已完全展開
        }
    }

    // 顯示/隱藏個人資料編輯表單
    function toggleEditForm() {
        const editForm = document.getElementById('editForm');
        const passwordForm = document.getElementById('passwordForm');
        
        if (editForm.style.display === 'block') {
            editForm.style.display = 'none';
        } else {
            editForm.style.display = 'block';
            passwordForm.style.display = 'none'; // 關閉密碼表單
            
            // 平滑滾動到編輯表單位置
            setTimeout(function() {
                editForm.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100); // 稍微延遲以確保表單已完全展開
        }
    }

// profile_validation.js - 個人資料即時驗證功能

// 全域變數
let validationStates = {
    username: false,
    email: false,
    phone: false,
    currentPassword: false,
    newPassword: false,
    confirmPassword: false
};

// // 模擬現有用戶數據（實際應用中需要透過AJAX從服務器獲取）
// const existingUsers = {
//     usernames: ['admin', 'test123', 'user456'], // 排除當前用戶
//     emails: ['admin@test.com', 'test@example.com', 'user@test.com'] // 排除當前用戶郵件
// };

// // 當前用戶資料（從PHP獲取）
// const currentUserData = {
//     username: document.querySelector('input[name="new_username"]')?.value || '',
//     email: document.querySelector('input[name="new_email"]')?.value || ''
// };

// 驗證函數
function validateUsername(username) {
    if (!username.trim()) {
        return { valid: false, message: '客戶名稱不能為空' };
    }
    if (username.length < 4) {
        return { valid: false, message: '客戶名稱至少需要4個字符' };
    }
    if (username.length > 10) {
        return { valid: false, message: '客戶名稱不能超過10個字符' };
    }
    if (!/^[a-zA-Z0-9\u4e00-\u9fa5_-]+$/.test(username)) {
        return { valid: false, message: '客戶名稱只能包含中文、英文、數字、底線和短橫線' };
    }
    // // 檢查是否與現有用戶重複（排除當前用戶）
    // if (username !== currentUserData.username && existingUsers.usernames.includes(username)) {
    //     return { valid: false, message: '此客戶名稱已被使用' };
    // }
    return { valid: true, message: '客戶名稱可以使用' };
}

function validateEmail(email) {
    if (!email.trim()) {
        return { valid: false, message: '電子郵件不能為空' };
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return { valid: false, message: '請輸入有效的電子郵件格式' };
    }
    // 檢查是否與現有郵件重複（排除當前用戶）
    // if (email !== currentUserData.email && existingUsers.emails.includes(email)) {
    //     return { valid: false, message: '此電子郵件已被使用' };
    // }
    return { valid: true, message: '電子郵件格式正確且可使用' };
}

function validatePhone(phone) {
    if (!phone.trim()) {
        return { valid: false, message: '電話號碼不能為空' };
    }
    // 台灣手機號碼格式：09開頭，共10位數字
    if (!/^09\d{8}$/.test(phone.replace(/\s|-/g, ''))) {
        return { valid: false, message: '請輸入正確的台灣手機號碼格式（09xxxxxxxx）' };
    }
    return { valid: true, message: '電話號碼格式正確' };
}

function validateCurrentPassword(password) {
    if (!password) {
        return { valid: false, message: '請輸入當前密碼' };
    }
    
    return { valid: true, message: '當前密碼格式正確' };
}

function validateNewPassword(password) {
    if (!password) {
        return { valid: false, message: '新密碼不能為空' };
    }
    else if (password.length < 6) {
        return { valid: false, message: '新密碼至少需要6個字符' };
    }
    else if (password.length > 50) {
        return { valid: false, message: '新密碼不能超過50個字符' };
    }
    
    // // 檢查密碼強度
    // let strength = 0;
    // if (/[a-z]/.test(password)) strength++;
    // if (/[A-Z]/.test(password)) strength++;
    // if (/[0-9]/.test(password)) strength++;
    // if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    // if (strength < 2) {
    //     return { valid: false, message: '密碼強度不足，建議包含大小寫字母、數字或特殊字符' };
    // }
    
    else return { valid: true, message: '新密碼格式正確' };
}

function validateConfirmPassword(newPassword, confirmPassword) {
    if (!confirmPassword) {
        return { valid: false, message: '請確認新密碼' };
    }
    else if (newPassword !== confirmPassword) {
        return { valid: false, message: '新密碼與確認密碼不相符' };
    }
    else return { valid: true, message: '密碼確認一致' };
}

// 顯示驗證結果的函數
function showValidationResult(inputElement, isValid, message) {
    // 移除舊的驗證樣式
    inputElement.classList.remove('validation-success', 'validation-error');
    
    // 尋找或創建驗證訊息元素
    let messageElement = inputElement.parentNode.querySelector('.validation-message');
    if (!messageElement) {
        messageElement = document.createElement('div');
        messageElement.className = 'validation-message';
        messageElement.style.cssText = `
            font-size: 12px;
            margin-top: 5px;
            min-height: 16px;
            transition: all 0.3s ease;
        `;
        inputElement.parentNode.appendChild(messageElement);
    }
    
    // 設置驗證狀態
    if (isValid) {
        inputElement.classList.add('validation-success');
        inputElement.style.borderColor = '#28a745';
        messageElement.style.color = '#28a745';
        messageElement.textContent = '✓ ' + message;
    } else {
        inputElement.classList.add('validation-error');
        inputElement.style.borderColor = '#dc3545';
        messageElement.style.color = '#dc3545';
        messageElement.textContent = '✗ ' + message;
    }
}

// 防抖函數，避免頻繁驗證
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// 模擬異步驗證（實際應用中用於AJAX請求）
function simulateAsyncValidation(value, type) {
    return new Promise((resolve) => {
        setTimeout(() => {
            if (type === 'username') {
                resolve(validateUsername(value));
            } else if (type === 'email') {
                resolve(validateEmail(value));
            }
        }, 300);
    });
}

// 初始化驗證功能
function initializeValidation() {
    // 個人資料表單驗證
    const usernameInput = document.querySelector('input[name="new_username"]');
    const emailInput = document.querySelector('input[name="new_email"]');
    const phoneInput = document.querySelector('input[name="new_phone"]');
    
    if (usernameInput) {
        const debouncedUsernameValidation = debounce(async (value) => {
            const result = await simulateAsyncValidation(value, 'username');
            showValidationResult(usernameInput, result.valid, result.message);
            validationStates.username = result.valid;
            updateSubmitButtonState('profile');
        }, 500);
        
        usernameInput.addEventListener('input', (e) => {
            debouncedUsernameValidation(e.target.value);
        });
        
        usernameInput.addEventListener('blur', (e) => {
            debouncedUsernameValidation(e.target.value);
        });
    }
    
    if (emailInput) {
        const debouncedEmailValidation = debounce(async (value) => {
            const result = await simulateAsyncValidation(value, 'email');
            showValidationResult(emailInput, result.valid, result.message);
            validationStates.email = result.valid;
            updateSubmitButtonState('profile');
        }, 500);
        
        emailInput.addEventListener('input', (e) => {
            debouncedEmailValidation(e.target.value);
        });
        
        emailInput.addEventListener('blur', (e) => {
            debouncedEmailValidation(e.target.value);
        });
    }
    
    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            const result = validatePhone(e.target.value);
            showValidationResult(phoneInput, result.valid, result.message);
            validationStates.phone = result.valid;
            updateSubmitButtonState('profile');
        });
    }
    
    // 密碼表單驗證
    const currentPasswordInput = document.querySelector('input[name="current_password"]');
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    
    if (currentPasswordInput) {
        currentPasswordInput.addEventListener('input', (e) => {
            const result = validateCurrentPassword(e.target.value);
            showValidationResult(currentPasswordInput, result.valid, result.message);
            validationStates.currentPassword = result.valid;
            updateSubmitButtonState('password');
        });
    }
    
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', (e) => {
            const result = validateNewPassword(e.target.value);
            showValidationResult(newPasswordInput, result.valid, result.message);
            validationStates.newPassword = result.valid;
            
            // 同時重新驗證確認密碼
            if (confirmPasswordInput && confirmPasswordInput.value) {
                const confirmResult = validateConfirmPassword(e.target.value, confirmPasswordInput.value);
                showValidationResult(confirmPasswordInput, confirmResult.valid, confirmResult.message);
                validationStates.confirmPassword = confirmResult.valid;
            }
            
            updateSubmitButtonState('password');
        });
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', (e) => {
            const newPassword = newPasswordInput ? newPasswordInput.value : '';
            const result = validateConfirmPassword(newPassword, e.target.value);
            showValidationResult(confirmPasswordInput, result.valid, result.message);
            validationStates.confirmPassword = result.valid;
            updateSubmitButtonState('password');
        });
    }
}

// 更新提交按鈕狀態
function updateSubmitButtonState(formType) {
    if (formType === 'profile') {
        const submitBtn = document.querySelector('form[action*="profile.php"] button[type="submit"]');
        if (submitBtn) {
            const isValid = validationStates.username && validationStates.email && validationStates.phone;
            submitBtn.disabled = !isValid;
            submitBtn.style.opacity = isValid ? '1' : '0.6';
            submitBtn.style.cursor = isValid ? 'pointer' : 'not-allowed';
        }
    } else if (formType === 'password') {
        const submitBtn = document.querySelector('form input[name="action"][value="change_password"]').closest('form').querySelector('button[type="submit"]');
        if (submitBtn) {
            const isValid = validationStates.currentPassword && validationStates.newPassword && validationStates.confirmPassword;
            submitBtn.disabled = !isValid;
            submitBtn.style.opacity = isValid ? '1' : '0.6';
            submitBtn.style.cursor = isValid ? 'pointer' : 'not-allowed';
        }
    }
}

// 表單提交前的最終驗證
function validateFormBeforeSubmit(formType) {
    if (formType === 'profile') {
        const username = document.querySelector('input[name="new_username"]').value;
        const email = document.querySelector('input[name="new_email"]').value;
        const phone = document.querySelector('input[name="new_phone"]').value;
        
        const usernameResult = validateUsername(username);
        const emailResult = validateEmail(email);
        const phoneResult = validatePhone(phone);
        
        if (!usernameResult.valid || !emailResult.valid || !phoneResult.valid) {
            alert('請修正所有驗證錯誤後再提交');
            return false;
        }
    } else if (formType === 'password') {
        const currentPassword = document.querySelector('input[name="current_password"]').value;
        const newPassword = document.querySelector('input[name="new_password"]').value;
        const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
        
        const currentResult = validateCurrentPassword(currentPassword);
        const newResult = validateNewPassword(newPassword);
        const confirmResult = validateConfirmPassword(newPassword, confirmPassword);
        
        if (!currentResult.valid || !newResult.valid || !confirmResult.valid) {
            alert('請修正所有驗證錯誤後再提交');
            return false;
        }
    }
    
    return true;
}

// 頁面載入完成後初始化
document.addEventListener('DOMContentLoaded', function() {
    initializeValidation();
    
    // 為表單添加提交前驗證
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const actionInput = this.querySelector('input[name="action"]');
            if (actionInput) {
                const formType = actionInput.value === 'update_profile' ? 'profile' : 'password';
                if (!validateFormBeforeSubmit(formType)) {
                    e.preventDefault();
                }
            }
        });
    });
    
    // 初始化時檢查現有值
    const inputs = document.querySelectorAll('input[name="new_username"], input[name="new_email"], input[name="new_phone"]');
    inputs.forEach(input => {
        if (input.value) {
            input.dispatchEvent(new Event('input'));
        }
    });
});

// 額外的CSS樣式（如果需要的話）
const validationStyles = `
<style>
.validation-success {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
}

.validation-error {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.validation-message {
    font-size: 12px;
    margin-top: 5px;
    min-height: 16px;
    transition: all 0.3s ease;
}

button:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
}
</style>
`;

// 將樣式添加到頁面
document.head.insertAdjacentHTML('beforeend', validationStyles);