<?php
include('db_connection.php');

// 決定當前操作的資料表
$current_table = isset($_GET['table']) ? $_GET['table'] : 'product_info';
// 新增商品或圖片
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    if ($_POST['table'] == 'product_info') {
        $stmt = $conn->prepare("INSERT INTO product_info (product_id, product_name, short_description, full_description, price, stock, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdis", $_POST['product_id'], $_POST['product_name'], $_POST['short_description'], $_POST['full_description'], $_POST['price'], $_POST['stock'], $_POST['category']);
    } elseif ($_POST['table'] == 'product_img') {
        $stmt = $conn->prepare("INSERT INTO product_img (img_id, product_id, img_url) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['img_id'], $_POST['product_id'], $_POST['img_url']);
    } elseif ($_POST['table'] == 'members') {
        $stmt = $conn->prepare("INSERT INTO members (member_id, username, email, password, phone, register_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssds", $_POST['member_id'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['phone'], $_POST['register_date']);
    } elseif ($_POST['table'] == 'orders') {
        $stmt = $conn->prepare("INSERT INTO orders (order_id, member_id, username, order_date, total_price, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssds", $_POST['order_id'], $_POST['member_id'], $_POST['username'], $_POST['order_date'], $_POST['total_price'], $_POST['status']);
    } elseif ($_POST['table'] == 'reviews') {
        $stmt = $conn->prepare("INSERT INTO reviews (review_id, user_id, username, rating, content) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $_POST['review_id'], $_POST['user_id'], $_POST['username'], $_POST['rating'], $_POST['content']);
    }
    if ($stmt->execute()) {
        echo "<p class='success'>新增成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
// 更新商品或圖片
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    if ($_POST['table'] == 'product_info') {
        $stmt = $conn->prepare("UPDATE product_info SET product_name=?, short_description=?, full_description=?, price=?, stock=?, category=? WHERE product_id=?");
        $stmt->bind_param("sssdiss", $_POST['product_name'], $_POST['short_description'], $_POST['full_description'], $_POST['price'], $_POST['stock'], $_POST['category'], $_POST['product_id']);
    } elseif ($_POST['table'] == 'product_img') {
        $stmt = $conn->prepare("UPDATE product_img SET product_id=?, img_url=? WHERE img_id=?");
        $stmt->bind_param("sss", $_POST['product_id'], $_POST['img_url'], $_POST['img_id']);
    } elseif ($_POST['table'] == 'members') {
        $stmt = $conn->prepare("UPDATE members SET username=?, email=?, password=?, phone=?, register_date=? WHERE member_id=?");
        $stmt->bind_param("ssssss", $_POST['username'], $_POST['email'], $_POST['password'], $_POST['phone'], $_POST['register_date'], $_POST['member_id']);
    } elseif ($_POST['table'] == 'orders') {
        $stmt = $conn->prepare("UPDATE orders SET member_id=?, username=?, order_date=?, total_price=?, status=? WHERE order_id=?");
        $stmt->bind_param("sssdsi", $_POST['member_id'], $_POST['username'], $_POST['order_date'], $_POST['total_price'], $_POST['status'], $_POST['order_id']);
    } elseif ($_POST['table'] == 'reviews') {
        $stmt = $conn->prepare("UPDATE reviews SET user_id=?, username=?, rating=?, content=? WHERE review_id=?");
        $stmt->bind_param("ssis", $_POST['user_id'], $_POST['username'], $_POST['rating'], $_POST['content'], $_POST['review_id']);
    }
    if ($stmt->execute()) {
        echo "<p class='success'>更新成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
// 刪除商品或圖片
if (isset($_GET['delete']) && isset($_GET['table'])) {
    if ($_GET['table'] == 'product_info') {
        $stmt = $conn->prepare("DELETE FROM product_info WHERE product_id=?");
        $stmt->bind_param("s", $_GET['delete']);
    } elseif ($_GET['table'] == 'product_img') {
        $stmt = $conn->prepare("DELETE FROM product_img WHERE img_id=?");
        $stmt->bind_param("s", $_GET['delete']);
    } elseif ($_GET['table'] == 'members') {
        $stmt = $conn->prepare("DELETE FROM members WHERE member_id=?");
        $stmt->bind_param("s", $_GET['delete']);
    } elseif ($_GET['table'] == 'orders') {
        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id=?");
        $stmt->bind_param("i", $_GET['delete']);
    } elseif ($_GET['table'] == 'reviews') {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id=?");
        $stmt->bind_param("s", $_GET['delete']);
    }
    if ($stmt->execute()) {
        echo "<p class='success'>刪除成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
// 取得資料表資料
$result = false;
$rows = [];
try {
    if ($current_table == 'product_info') {
        $result = $conn->query("SELECT * FROM product_info");
    } elseif ($current_table == 'product_img') {
        $result = $conn->query("SELECT * FROM product_img");
    } elseif ($current_table == 'members') {
        $result = $conn->query("SELECT * FROM members");
    } elseif ($current_table == 'orders') {
        $result = $conn->query("SELECT * FROM orders");
    } elseif ($current_table == 'reviews') {
        $result = $conn->query("SELECT * FROM reviews");
    }
    // 檢查查詢是否成功
    if ($result === false) {
        throw new Exception("查詢資料表失敗: " . $conn->error);
    }
    // 如果有結果，則提取所有資料列
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} catch (Exception $e) {
    echo "<p class='error'>" . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>資料庫管理系統</title>
    <link href="css/db_admin_style.css" rel="stylesheet">
    <script>
    // 將 JavaScript 函數直接內嵌，以避免外部引用問題
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
</script>
</head>

<body>
    <div class="container">
        <h1>商品管理系統</h1>
        <div class="table-selector">
            <button
                onclick="location.href='?table=product_info'"
                class="<?= $current_table == 'product_info' ? 'active' : '' ?>">商品資訊</button>
            <button
                onclick="location.href='?table=product_img'"
                class="<?= $current_table == 'product_img' ? 'active' : '' ?>">商品圖片</button>
            <button
                onclick="location.href='?table=members'"
                class="<?= $current_table == 'members' ? 'active' : '' ?>">會員資訊</button>
            <button
                onclick="location.href='?table=orders'"
                class="<?= $current_table == 'orders' ? 'active' : '' ?>">訂單資訊</button>
            <button
                onclick="location.href='?table=reviews'"
                class="<?= $current_table == 'reviews' ? 'active' : '' ?>">評論資訊</button>
        </div>
        <?php if ($current_table == 'product_info'): ?>
            <h2>新增商品</h2>
            <form method="POST">
                <input type="hidden" name="table" value="product_info">
                <input type="text" name="product_id" placeholder="商品編號" required>
                <input type="text" name="product_name" placeholder="商品名稱" required>
                <textarea name="short_description" placeholder="簡短描述" required></textarea>
                <textarea name="full_description" placeholder="詳細描述" required></textarea>
                <input type="number" name="price" placeholder="價格" required>
                <input type="number" name="stock" placeholder="庫存" required>
                <input type="text" name="category" placeholder="類別" required>
                <input type="submit" name="create" value="新增商品">
            </form>
            <h2>商品列表</h2>
            <table>
                <tr>
                    <th>商品編號</th>
                    <th>商品名稱</th>
                    <th>簡短描述</th>
                    <th>價格</th>
                    <th>庫存</th>
                    <th>類別</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['product_id']); ?></td>
                        <td><?= htmlspecialchars($row['product_name']); ?></td>
                        <td><?= htmlspecialchars($row['short_description']); ?></td>
                        <td><?= htmlspecialchars($row['price']); ?></td>
                        <td><?= htmlspecialchars($row['stock']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td>
                            <!-- 修正: 使用單引號包裹JSON字符串 -->
                            <button onclick='editProduct(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['product_id']); ?>&table=product_info" onclick="return confirm('確定要刪除這個商品嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改商品</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="product_info">
                <input type="text" name="product_id" id="edit_product_id" placeholder="商品編號" readonly required>
                <input type="text" name="product_name" id="edit_product_name" placeholder="商品名稱" required>
                <textarea name="short_description" id="edit_short_description" placeholder="簡短描述" required></textarea>
                <textarea name="full_description" id="edit_full_description" placeholder="詳細描述" required></textarea>
                <input type="number" name="price" id="edit_price" placeholder="價格" required>
                <input type="number" name="stock" id="edit_stock" placeholder="庫存" required>
                <input type="text" name="category" id="edit_category" placeholder="類別" required>
                <input type="submit" name="update" value="更新商品">
            </form>
        <?php elseif ($current_table == 'product_img'): ?>
            <h2>新增商品圖片</h2>
            <form method="POST">
                <input type="hidden" name="table" value="product_img">
                <input type="text" name="img_id" placeholder="圖片編號" required>
                <input type="text" name="product_id" placeholder="商品編號" required>
                <input type="text" name="img_url" placeholder="圖片URL" required>
                <input type="submit" name="create" value="新增圖片">
            </form>
            <h2>商品圖片列表</h2>
            <table>
                <tr>
                    <th>圖片編號</th>
                    <th>商品編號</th>
                    <th>圖片URL</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['img_id']); ?></td>
                        <td><?= htmlspecialchars($row['product_id']); ?></td>
                        <td><?= htmlspecialchars($row['img_url']); ?></td>
                        <td>
                            <!-- 修正: 使用單引號包裹JSON字符串 -->
                            <button onclick='editImage(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['img_id']); ?>&table=product_img" onclick="return confirm('確定要刪除這個圖片嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改圖片</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="product_img">
                <input type="text" name="img_id" id="edit_img_id" placeholder="圖片編號" readonly required>
                <input type="text" name="product_id" id="edit_product_id_img" placeholder="商品編號" required>
                <input type="text" name="img_url" id="edit_img_url" placeholder="圖片URL" required>
                <input type="submit" name="update" value="更新圖片">
            </form>
        <?php elseif ($current_table == 'members'): ?>
            <h2>新增會員</h2>
            <form method="POST">
                <input type="hidden" name="table" value="members">
                <input type="text" name="member_id" placeholder="會員編號" required>
                <input type="text" name="username" placeholder="使用者名稱" required>
                <input type="email" name="email" placeholder="電子郵件" required>
                <input type="password" name="password" placeholder="密碼" required>
                <input type="text" name="phone" placeholder="電話" required>
                <input type="date" name="register_date" placeholder="註冊日期" required>
                <input type="submit" name="create" value="新增會員">
            </form>
            <h2>會員列表</h2>
            <table>
                <tr>
                    <th>會員編號</th>
                    <th>使用者名稱</th>
                    <th>電子郵件</th>
                    <th>電話</th>
                    <th>註冊日期</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['member_id']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><?= htmlspecialchars($row['register_date']); ?></td>
                        <td>
                            <!-- 修正: 使用單引號包裹JSON字符串 -->
                            <button onclick='editMember(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['member_id']); ?>&table=members" onclick="return confirm('確定要刪除這個會員嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改會員</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="members">
                <input type="text" name="member_id" id="edit_member_id" placeholder="會員編號" readonly required>
                <input type="text" name="username" id="edit_username" placeholder="使用者名稱" required>
                <input type="email" name="email" id="edit_email" placeholder="電子郵件" required>
                <div class="password-container">
                    <input type="password" name="password" id="edit_password" placeholder="密碼" required>
                    <button type="button" id="toggle_password_visibility" onclick="togglePasswordVisibility()">顯示密碼</button>
                </div>
                <input type="text" name="phone" id="edit_phone" placeholder="電話" required>
                <input type="date" name="register_date" id="edit_register_date" placeholder="註冊日期" required>
                <input type="submit" name="update" value="更新會員">
            </form>
        <?php elseif ($current_table == 'orders'): ?>
            <h2>新增訂單</h2>
            <form method="POST">
                <input type="hidden" name="table" value="orders">
                <input type="number" name="order_id" placeholder="訂單編號" required>
                <input type="text" name="member_id" placeholder="會員編號" required>
                <input type="text" name="username" placeholder="使用者名稱" required>
                <input type="date" name="order_date" placeholder="訂單日期" required>
                <input type="number" name="total_price" placeholder="總金額" required>
                <input type="text" name="status" placeholder="訂單狀態" required>
                <input type="submit" name="create" value="新增訂單">
            </form>
            <h2>訂單列表</h2>
            <table>
                <tr>
                    <th>訂單編號</th>
                    <th>會員編號</th>
                    <th>使用者名稱</th>
                    <th>訂單日期</th>
                    <th>總金額</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['order_id']); ?></td>
                        <td><?= htmlspecialchars($row['member_id']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['order_date']); ?></td>
                        <td><?= htmlspecialchars($row['total_price']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                        <td>
                            <!-- 修正: 使用單引號包裹JSON字符串 -->
                            <button onclick='editOrder(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['order_id']); ?>&table=orders" onclick="return confirm('確定要刪除這個訂單嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改訂單</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="orders">
                <input type="number" name="order_id" id="edit_order_id" placeholder="訂單編號" readonly required>
                <input type="text" name="member_id" id="edit_order_member_id" placeholder="會員編號" required>
                <input type="text" name="username" id="edit_order_username" placeholder="使用者名稱" required>
                <input type="date" name="order_date" id="edit_order_date" placeholder="訂單日期" required>
                <input type="number" name="total_price" id="edit_order_total_price" placeholder="總金額" required>
                <input type="text" name="status" id="edit_order_status" placeholder="訂單狀態" required>
                <input type="submit" name="update" value="更新訂單">
            </form>
        <?php elseif ($current_table == 'reviews'): ?>
            <h2>新增評論</h2>
            <form method="POST">
                <input type="hidden" name="table" value="reviews">
                <input type="text" name="review_id" placeholder="評論編號" required>
                <input type="text" name="user_id" placeholder="使用者編號" required>
                <input type="text" name="username" placeholder="使用者名稱" required>
                <input type="number" name="rating" placeholder="評分" min="1" max="5" required>
                <textarea name="content" placeholder="評論內容" required></textarea>
                <input type="submit" name="create" value="新增評論">
            </form>
            <h2>評論列表</h2>
            <table>
                <tr>
                    <th>評論編號</th>
                    <th>使用者編號</th>
                    <th>使用者名稱</th>
                    <th>評分</th>
                    <th>評論內容</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['review_id']); ?></td>
                        <td><?= htmlspecialchars($row['user_id']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['rating']); ?></td>
                        <td><?= htmlspecialchars($row['content']); ?></td>
                        <td>
                            <!-- 修正: 使用單引號包裹JSON字符串 -->
                            <button onclick='editReview(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['review_id']); ?>&table=reviews" onclick="return confirm('確定要刪除這個評論嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改評論</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="reviews">
                <input type="text" name="review_id" id="edit_review_id" placeholder="評論編號" readonly required>
                <input type="text" name="user_id" id="edit_review_user_id" placeholder="使用者編號" required>
                <input type="text" name="username" id="edit_review_username" placeholder="使用者名稱" required>
                <input type="number" name="rating" id="edit_review_rating" placeholder="評分" min="1" max="5" required>
                <textarea name="content" id="edit_review_content" placeholder="評論內容" required></textarea>
                <input type="submit" name="update" value="更新評論">
            </form>
            <?php endif; ?>
    </div>
</body>
</html>