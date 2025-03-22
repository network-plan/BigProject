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
    $(document).ready(function() {
        // 監聽寄送方式的變化
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
    });
    
});
