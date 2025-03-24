<<<<<<< HEAD
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
            $(this).closest("tr").remove();
        }
    });
    
    
});
=======
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
            $(this).closest("tr").remove();
        }
    });
    
    
});
>>>>>>> 0f7811897bd918e11a45204b062f9905bc9ca8ce
