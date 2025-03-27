// 編輯商品資訊的函數
function editProduct(product) {
    const data = JSON.parse(product);
    document.getElementById("edit_product_id").value = data.product_id;
    document.getElementById("edit_product_name").value = data.product_name;
    document.getElementById("edit_short_description").value = data.short_description;
    document.getElementById("edit_full_description").value = data.full_description;
    document.getElementById("edit_price").value = data.price;
    document.getElementById("edit_stock").value = data.stock;
    document.getElementById("edit_category").value = data.category;
}


// 編輯商品圖片的函數
function editImage(image) {
    const data = JSON.parse(image);
    document.getElementById("edit_img_id").value = data.img_id;
    document.getElementById("edit_product_id_img").value = data.product_id;
    document.getElementById("edit_img_url").value = data.img_url;
}

// 編輯會員資訊的函數
function editMember(member) {
    const data = JSON.parse(member);
    document.getElementById("edit_member_id").value = data.member_id;
    document.getElementById("edit_username").value = data.username;
    document.getElementById("edit_email").value = data.email;
    document.getElementById("edit_password").value = data.password;
    document.getElementById("edit_phone").value = data.phone;
    document.getElementById("edit_register_date").value = data.register_date;
}

// 切換密碼可見性的函數
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('edit_password');
    const toggleButton = document.getElementById('toggle_password_visibility');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.textContent = '隱藏密碼';
    } else {
        passwordInput.type = 'password';
        toggleButton.textContent = '顯示密碼';
    }
}

// 編輯訂單資訊的函數
function editOrder(order) {
    const data = JSON.parse(order);
    document.getElementById("edit_order_id").value = data.order_id;
    document.getElementById("edit_order_member_id").value = data.member_id;
    document.getElementById("edit_order_username").value = data.username;
    document.getElementById("edit_order_date").value = data.order_date;
    document.getElementById("edit_order_total_price").value = data.total_price;
    document.getElementById("edit_order_status").value = data.status;
}

// 編輯評論的函數
function editReview(review) {
    const data = JSON.parse(review);
    document.getElementById("edit_review_id").value = data.review_id;
    document.getElementById("edit_review_user_id").value = data.user_id;
    document.getElementById("edit_review_username").value = data.username;
    document.getElementById("edit_review_rating").value = data.rating;
    document.getElementById("edit_review_content").value = data.content;
}