function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function editProduct(productData) {
    try {
        // 確保是字符串形式的JSON
        const product = typeof productData === 'string' ? JSON.parse(productData) : productData;
        
        // 設置表單的值
        document.getElementById('edit_product_id').value = product.product_id;
        document.getElementById('edit_product_name').value = product.product_name;
        document.getElementById('edit_short_description').value = product.short_description;
        document.getElementById('edit_full_description').value = product.full_description;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_stock').value = product.stock;
        document.getElementById('edit_category').value = product.category;
        
        // 滾動到修改表單
        document.querySelector('form[name="update"]').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        console.error("解析產品數據時發生錯誤:", e);
        alert("無法載入產品數據，請稍後再試");
    }
}

function editImage(imageData) {
    try {
        const image = typeof imageData === 'string' ? JSON.parse(imageData) : imageData;
        document.getElementById('edit_img_id').value = image.img_id;
        document.getElementById('edit_product_id_img').value = image.product_id;
        document.getElementById('edit_img_url').value = image.img_url;
        
        // 滾動到修改表單
        document.querySelector('form[name="update"]').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        console.error("解析圖片數據時發生錯誤:", e);
        alert("無法載入圖片數據，請稍後再試");
    }
}

function editMember(memberData) {
    try {
        const member = typeof memberData === 'string' ? JSON.parse(memberData) : memberData;
        document.getElementById('edit_member_id').value = member.member_id;
        document.getElementById('edit_username').value = member.username;
        document.getElementById('edit_email').value = member.email;
        document.getElementById('edit_password').value = member.password;
        document.getElementById('edit_phone').value = member.phone;
        document.getElementById('edit_register_date').value = member.register_date;
        
        // 滾動到修改表單
        document.querySelector('form[name="update"]').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        console.error("解析會員數據時發生錯誤:", e);
        alert("無法載入會員數據，請稍後再試");
    }
}

function editOrder(orderData) {
    try {
        const order = typeof orderData === 'string' ? JSON.parse(orderData) : orderData;
        document.getElementById('edit_order_id').value = order.order_id;
        document.getElementById('edit_order_member_id').value = order.member_id;
        document.getElementById('edit_order_username').value = order.username;
        document.getElementById('edit_order_date').value = order.order_date;
        document.getElementById('edit_order_total_price').value = order.total_price;
        document.getElementById('edit_order_status').value = order.status;
        document.getElementById('edit_order_address').value = order.address;
        document.getElementById('edit_order_phone').value = order.phone;
        document.getElementById('edit_order_shipping_method').value = order.shipping_method;

        console.log(order);

        // 滾動到修改表單
        document.querySelector('form[name="update"]').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        console.error("解析訂單數據時發生錯誤:", e);
        alert("無法載入訂單數據，請稍後再試");
    }
}

function editOrder_items(order_items_Data) {
    try {
        const order_items = typeof order_items_Data === 'string' ? JSON.parse(order_items_Data) : order_items_Data;
        document.getElementById('edit_order_items_number').value = order_items.number;
        document.getElementById('edit_order_items_order_id').value = order_items.order_id;
        document.getElementById('edit_order_items_product_id').value = order_items.product_id;
        document.getElementById('edit_order_items_quantity').value = order_items.quantity;

        // 滾動到修改表單
        document.querySelector('form[name="update"]').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        console.error("解析訂單數據時發生錯誤:", e);
        alert("無法載入訂單數據，請稍後再試");
    }
}

function editReview(reviewData) {
    try {
        const review = typeof reviewData === 'string' ? JSON.parse(reviewData) : reviewData;
        document.getElementById('edit_review_id').value = review.review_id;
        document.getElementById('edit_review_order_id').value = review.order_id;
        document.getElementById('edit_review_product_id').value = review.product_id;
        document.getElementById('edit_review_user_id').value = review.user_id;
        document.getElementById('edit_review_username').value = review.username;
        document.getElementById('edit_review_rating').value = review.rating;
        document.getElementById('edit_review_content').value = review.content;
        
        // 滾動到修改表單
        document.querySelector('form[name="update"]').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        console.error("解析評論數據時發生錯誤:", e);
        alert("無法載入評論數據，請稍後再試");
    }
}

function togglePasswordVisibility() {
    const passwordField = document.getElementById('edit_password');
    const toggleBtn = document.getElementById('toggle_password_visibility');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleBtn.textContent = '隱藏密碼';
    } else {
        passwordField.type = 'password';
        toggleBtn.textContent = '顯示密碼';
    }
}

// 改變每頁顯示數量
function changePerPage(perPage) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('per_page', perPage);
    urlParams.set('page', '1'); // 重置為第一頁
    window.location.search = urlParams.toString();
}

// 跳轉到指定頁面
function goToPage(page) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('page', page);
    window.location.search = urlParams.toString();
}