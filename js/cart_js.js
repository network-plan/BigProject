$(document).ready(function() {
    // 偵測到"使用優惠卷"打勾
    $('#coupon').change(function() {
        if ($(this).is(':checked')) {
            $('#couponInputWrapper').slideDown(); // 用滑動方式顯示
        } else {
            $('#couponInputWrapper').slideUp(); // 用滑動方式隱藏
        }
    });
    // 優惠卷套用按鈕 折扣碼: S1254006、S1254051、S1122003會成功套用
    $('#applyCoupon').click(function() {
        var coupon=$('#couponCode').val();
        if(coupon==''){
            alert('請輸入優惠卷代碼');
            return;
        }else if(coupon=='S1254006' || coupon=='S1254051' || coupon=='S1122003'){
            alert('優惠卷代碼成功套用');
            return;
        }else{
            alert('優惠卷代碼錯誤');
            $('#couponCode').val('');
            $('#coupon').prop('checked',false);
            $('#couponInputWrapper').slideUp();
            return;
        }
    });
    
    
    // 取得寄送方式
    $('input[name="send-way"]').change(function() {
        var selectedValue = $('input[name="send-way"]:checked').val();
    
        // 顯示聯絡電話（無論選擇哪個寄送方式都要顯示）
        $('.contact-info').slideDown();

        if (selectedValue === '1' || selectedValue === '2') {
            // 如果選擇宅配，顯示地區與郵遞區號選項
            $('.delivery-info').slideDown();
        } else {
            // 隱藏地區與郵遞區號（但聯絡電話仍保留）
            $('.delivery-info').slideUp();
        }
    });

    $(".cart_quantity_up").click(function(e) {
        e.preventDefault();  // 防止跳轉到其他頁面
        var inputField = $(this).siblings(".cart_quantity_input");  // 獲取對應的數量輸入框
        var currentValue = parseInt(inputField.val());  // 取得當前的數量
        inputField.val(currentValue + 1);  // 增加數量
    });

    //商品數量變化
    // 點擊 "-" 按鈕
    $(".cart_quantity_down").click(function(e) {
        e.preventDefault();
        var inputField = $(this).siblings(".cart_quantity_input");
        var currentValue = parseInt(inputField.val());
        
        // 如果數量大於 1，則減少數量
        if (currentValue > 1) {
            inputField.val(currentValue - 1);
        } else {
            // 如果數量小於等於 1，則刪除該商品
            if(confirm("確定要刪除這個商品嗎？")) {
                $(this).closest("tr").remove();
            }
        }
    });
    //購物車後商品叉叉刪除
    $(".cart_quantity_delete").click(function(e) {
        e.preventDefault();
        $(this).closest("tr").remove();
    });
});

// 電話驗證相關代碼
document.addEventListener('DOMContentLoaded', function() {
    // 找到電話輸入框
    const phoneInput = document.querySelector('input[type="tel"]');
    if (!phoneInput) return;

    // 為電話輸入框添加外層容器
    const wrapper = document.createElement('div');
    wrapper.style.display = 'flex';
    wrapper.style.alignItems = 'center';
    wrapper.style.gap = '8px';
    phoneInput.parentElement.insertBefore(wrapper, phoneInput);
    wrapper.appendChild(phoneInput);

    // 創建錯誤信息元素
    const errorSpan = document.createElement('span');
    errorSpan.style.color = '#dc2626';
    errorSpan.style.fontSize = '12px';
    errorSpan.style.display = 'none';
    wrapper.appendChild(errorSpan);

    // 驗證函數
    function validatePhone() {
        const phone = phoneInput.value.trim();
        const phoneRegex = /^09\d{8}$/;

        if (!phone) {
            showError('手機號碼為必填');
        } else if (phone.length > 12) {
            showError('手機號碼不能超過12個字');
        } else if (!phoneRegex.test(phone)) {
            showError('請輸入正確的手機號碼格式');
        } else {
            hideError();
        }
    }

    // 顯示錯誤
    function showError(message) {
        errorSpan.textContent = message;
        errorSpan.style.display = 'block';
        phoneInput.style.borderColor = '#dc2626';
    }

    // 隱藏錯誤
    function hideError() {
        errorSpan.style.display = 'none';
        phoneInput.style.borderColor = '';
    }

    // 添加事件監聽
    phoneInput.addEventListener('input', validatePhone);
    phoneInput.addEventListener('blur', validatePhone);
});
