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
    //-----------------------------price---------------------------
    //-------function-------
    //計算商品總價明細
    function updatemoney() {
        var total = 0;
        var itemTotal=parseInt($(".total_area li:first span").text().replace(/[^0-9.]/g, '')); // 取出數字部分
        var shippingCost=parseInt($(".total_area li:eq(1) span").text().replace(/[^0-9.]/g, '')); // 取出數字部分
        var discount=parseInt($(".total_area li:eq(2) span").text().replace(/[^0-9.]/g, '')); // 取出數字部分
        total = itemTotal + shippingCost - discount; // 計算總價
        $(".total_area li:last span").text(`${total} NTD`); // 更新總價顯示
        $(".total_area li:last span").css("font-weight", "bold"); // 設定總價加粗
    }

    //數量價錢變動後動作定義
    function changePrice(row, quantity) {
        var priceText = row.find(".cart_price p").text(); // 獲取價格
        var price = priceText.replace(/[^0-9.]/g, ''); // 去除 "$" 並轉數字
        var totalPrice = (price * quantity); // 計算總價
        row.find(".cart_total_price").text(`NT$${totalPrice}`); // 更新總價顯示
        updateTotalPrice();
       
    }

    // 計算購物車總價
    function updateTotalPrice() {
        var total = 0;
        $(".cart_total_price").each(function () {
            var itemTotal = parseFloat($(this).text().replace(/[^0-9.]/g, '')); // 取出數字部分
            total += itemTotal; // 加總
        });
        $(".total_area li:first span").text(`${total} NTD`); // 更新購物車總價
        updateDiscount(); // 更新優惠卷
        updateShippingCost(); // 更新運費，滿1000免運
        updatemoney(); // 更新總價
    }
    
    //計算運費總價
    function updateShippingCost() {
        var shippingCost = 0;
        //滿1000免運
        var totalprice=parseFloat($(".total_area li:first span").text().replace(/[^0-9.]/g, ''));
        if(totalprice>=1000){
            shippingCost = 0; // 滿1000免運
        }else{
            //運送方式
            // 0: 到店取貨, 1: 宅配, 2: 隔日到貨
            if ($('input[name="send-way"]:checked').val() === '0') {
                shippingCost = 0; // 到店取運費
            }else if ($('input[name="send-way"]:checked').val() === '1') {
                shippingCost = 50; // 宅配運費
            } else if ($('input[name="send-way"]:checked').val() === '2') {
                shippingCost = 100; // 隔日到貨運費
            }
            // 地區運費
            var regionValue = $('#country').val();  // 偵測選中的地區
            if (regionValue === '0') {
                shippingCost += 0; // 台灣本島運費
            } else if (regionValue === '1') {
                shippingCost += 50; // 台灣離島運費
            } else if (regionValue === '2') {
                shippingCost += 200; // 海外運費
            }
        }
        $(".total_area li:eq(1) span").text(`${shippingCost} NTD`); // 更新運費顯示
        updatemoney(); // 更新總價
    }
    
    //優惠卷減免
    function updateDiscount() {
        var discount = 0; // 取得總價
        var couponCode = $('#couponCode').val();
        if ((couponCode === 'S1254006' || couponCode === 'S1254051' || couponCode === 'S1122003')) {
            discount = parseInt(parseFloat($(".total_area li:first span").text().replace(/[^0-9.]/g, ''))*0.1); //用優惠卷打9折
        } 
        $(".total_area li:eq(2) span").text(`${discount} NTD`); // 更新優惠卷顯示
        updatemoney(); // 更新總價
    }

    //-------偵測變化-------
    function updateCartCookieFromDOM() {
         var cartData = {};

        $(".cart_quantity_input").each(function () {
            var productId = $(this).data("id");
            var quantity = parseInt($(this).val());

            if (quantity > 0) {
                cartData[productId] = quantity;
            }
        });

        document.cookie = "cart=" + JSON.stringify(cartData) + ";path=/;max-age=" + (7 * 24 * 60 * 60);
    }

    //商品數量變化
    $(".cart_quantity_input_pd").change(function(e) {
        e.preventDefault();
        var inputField = $(this);
        var currentValue = parseInt(inputField.val());
        // 如果數量小於 1，則設置為 1
        if (currentValue < 1) {
            inputField.val(1);
        }
        changePrice($(this).closest("tr"), currentValue); // 更新總價
    });
    //輸入框輸入
    $(".cart_quantity_input").change(function(e) {
        e.preventDefault();
        var inputField = $(this);
        var newValue = parseInt(inputField.val());
        
        var productId = inputField.data("id");

        if (isNaN(newValue) || newValue < 1) {// 如果數量小於 1，則設置為 1
            inputField.val(1);
            newValue = 1;
        }

        // 檢查庫存
        $.ajax({
            url: "check_stock.php",  // 根據實際路徑修改
            method: "POST",
            data: {
                oper: "checkStock",
                product_id: productId,
                requested_quantity: newValue
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // 更新總價與 cookie
                    changePrice(inputField.closest("tr"), newValue);
                    updateCartCookieFromDOM();
                } else {
                    alert("庫存不足，最多可購買 " + response.stock + " 件");
                    var correctedValue = Math.max(1, response.stock);
                    inputField.val(correctedValue);
                    changePrice(inputField.closest("tr"), correctedValue);
                    updateCartCookieFromDOM();
                }
            },
            error: function() {
                alert("無法驗證庫存，請稍後再試！");
            }
        });
    });

    //購物車後商品叉叉刪除
    $(".cart_info").on("click", ".cart_quantity_delete", function(e) {
        e.preventDefault(); // 防止 <a> 預設跳轉
    
        if (confirm("確定要刪除這個商品嗎？")) {
            $(this).closest("tr").remove(); // 移除該列
            updateTotalPrice(); // 更新總價
        }
        updateCartCookieFromDOM();
    });


    // 商品數量變化
    // 點擊數量輸入框

    //點擊 "+" 按鈕
    $(".cart_quantity_up").click(function(e) {
        e.preventDefault();  // 防止跳轉到其他頁面
        var inputField = $(this).siblings(".cart_quantity_input");  // 獲取對應的數量輸入框
        var currentValue = parseInt(inputField.val());  // 取得當前的數量
        // inputField.val(currentValue + 1);  // 增加數量
        // changePrice($(this).closest("tr"), currentValue + 1);  // 更新總價
        // updateCartCookieFromDOM();
        var productId = inputField.data("id");

        // 發送 AJAX 檢查庫存
        $.ajax({
            url: "check_stock.php",
            method: "POST",
            data: {
                oper: "checkStock", // 跟 PHP 對應
                product_id: productId,
                requested_quantity: currentValue + 1
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    inputField.val(currentValue + 1);
                    changePrice(inputField.closest("tr"), currentValue + 1);
                    updateCartCookieFromDOM();
                } else {
                    alert("庫存不足！目前最多只能購買 " + response.stock + " 件。");
                }
            },
            error: function () {
                alert("無法連線至伺服器，請稍後再試！");
            }
        });
    });
    $(".cart_quantity_up_pd").click(function(e) {
        e.preventDefault();  // 防止跳轉到其他頁面
        var inputField = $(this).siblings(".cart_quantity_input_pd");  // 獲取對應的數量輸入框
        var currentValue = parseInt(inputField.val());  // 取得當前的數量
        inputField.val(currentValue + 1);  // 增加數量
        changePrice($(this).closest("tr"), currentValue + 1);  // 更新總價
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
        changePrice($(this).closest("tr"), currentValue - 1);// 更新總價
        updateCartCookieFromDOM();
    });
    $(".cart_quantity_down_pd").click(function(e) {
        e.preventDefault();
        var inputField = $(this).siblings(".cart_quantity_input_pd");
        var currentValue = parseInt(inputField.val());
        
        // 如果數量大於 1，則減少數量
        if (currentValue > 1) {
            inputField.val(currentValue - 1);
        } else {
            // 如果數量小於等於 1，則維持1
            inputField.val(currentValue);
        }
        changePrice($(this).closest("tr"), currentValue - 1);// 更新總價
    });
    

    // 更新運費
    $('input[name="send-way"]').change(updateShippingCost);
    $("#country").change(updateShippingCost);

    // 更新優惠卷
    $('#applyCoupon').click(updateDiscount);

    //-------initialize--------
    updatemoney(); // 初始計算總價
    updateTotalPrice(); // 初始計算總價
    updateShippingCost(); // 初始計算運費
    updateDiscount(); // 初始計算優惠卷
});

document.addEventListener('DOMContentLoaded', function () {
    // ===== 電話驗證相關 =====
    const phoneInput = document.querySelector('input[type="tel"]');
    const wrapper = document.createElement('div');
    const errorSpan = document.createElement('span');

    if (phoneInput) {
        wrapper.style.display = 'flex';
        wrapper.style.alignItems = 'center';
        wrapper.style.gap = '8px';
        phoneInput.parentElement.insertBefore(wrapper, phoneInput);
        wrapper.appendChild(phoneInput);

        errorSpan.style.color = '#dc2626';
        errorSpan.style.fontSize = '12px';
        errorSpan.style.display = 'none';
        wrapper.appendChild(errorSpan);

        function validatePhone() {
            const phone = phoneInput.value.trim();
            const phoneRegex = /^09\d{8}$/;

            if (!phone) {
                showError('手機號碼為必填');
                return false;
            } else if (phone.length > 12) {
                showError('手機號碼不能超過12個字');
                return false;
            } else if (!phoneRegex.test(phone)) {
                showError('請輸入正確的手機號碼格式（如：0912345678）');
                return false;
            } else {
                hideError();
                return true;
            }
        }

        function showError(message) {
            errorSpan.textContent = message;
            errorSpan.style.display = 'block';
            phoneInput.style.borderColor = '#dc2626';
        }

        function hideError() {
            errorSpan.style.display = 'none';
            phoneInput.style.borderColor = '';
        }

        phoneInput.addEventListener('input', validatePhone);
        phoneInput.addEventListener('blur', validatePhone);

        // 提供全域使用的驗證方法
        window.validatePhoneBeforeSubmit = validatePhone;
    }

    // ===== 配送方式切換與驗證 =====
    const sendWayRadios = document.querySelectorAll('input[name="send-way"]');
    const contactInfo = document.querySelector(".contact-info");
    const deliveryInfo = document.querySelector(".delivery-info");
    const addressInput = document.querySelector('.delivery-info input');

    sendWayRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            const value = this.value;
            contactInfo.style.display = "block";

            if (value === "0") {
                deliveryInfo.style.display = "none";
                addressInput.required = false;
            } else {
                deliveryInfo.style.display = "block";
                addressInput.required = true;
            }
        });
    });

    // ===== 表單送出驗證與提交 =====
    document.querySelector(".btn.update").addEventListener("click", function (e) {
        const selectedMethod = document.querySelector('input[name="send-way"]:checked');
        const phone = phoneInput.value.trim();
        const address = addressInput.value.trim();

        if (!selectedMethod) {
            alert("請選擇配送方式！");
            e.preventDefault();
            return;
        }

        // 加上電話格式驗證
        if (!window.validatePhoneBeforeSubmit || !window.validatePhoneBeforeSubmit()) {
            alert("請正確填寫聯絡電話！");
            e.preventDefault();
            return;
        }

        if ((selectedMethod.value === "1" || selectedMethod.value === "2") && address === "") {
            alert("請填寫地址！");
            e.preventDefault();
            return;
        }

        // 建立表單送出
        const form = document.createElement("form");
        form.method = "post";
        form.action = "submit_order.php";

        const addHiddenInput = (name, value) => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = name;
            input.value = value;
            form.appendChild(input);
        };

        addHiddenInput("phone", phone);
        addHiddenInput("address", address || "彰化小禮坊"); // 門市取貨預設
        addHiddenInput("shipping_method", selectedMethod.nextElementSibling.textContent.trim());

        const totalText = document.querySelector("#total-amount").textContent;
        const totalPrice = parseInt(totalText.replace(/[^\d]/g, ""), 10);
        addHiddenInput("total_price", totalPrice);

        document.body.appendChild(form);
        form.submit();
    });
});