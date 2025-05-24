<?php
session_start();
include('db_connection.php');
include('check_login.php'); // 確保使用者已登入

$username = $_SESSION['username'];
$member_id = $_SESSION['member_id'];

// 取得購物車
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

if (empty($cart)) {
    echo "購物車是空的，無法送出訂單。";
    exit();
}

// ===== 1. 產生新的 order_id =====
$result = $conn->query("SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
if ($row = $result->fetch_assoc()) {
    $last_id = intval(substr($row['order_id'], 3)); // 去掉 ORD 並轉成數字
    $new_id_num = $last_id + 1;
} else {
    $new_id_num = 1; // 如果沒有訂單，就從 1 開始
}
$order_id = 'ORD' . str_pad($new_id_num, 7, '0', STR_PAD_LEFT); // 格式 ORD0000001

// ===== 2. 插入 orders 表 =====
$total_price = intval($_POST['total_price']);
foreach ($cart as $product_id => $quantity) {
    // 你可能需要查價格
    $stmt = $conn->prepare("SELECT price FROM product_info WHERE product_id = ?");
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();
}


$address = $_POST['address'];
$phone = $_POST['phone'];
if($_POST['shipping_method']==="寄送至彰化小禮坊商店  +0元"){
    $shipping_method = "彰化小禮坊商店";
}else if($_POST['shipping_method']==="宅配到家 +50元"){
    $shipping_method = "宅配到家";
}else if($_POST['shipping_method']==="宅配到家-隔日到貨 +100元"){
    $shipping_method = "宅配到家-隔日到貨";
}
$status = "備貨中";
date_default_timezone_set('Asia/Taipei');
$order_date = date('Y-m-d');

// 寫入 orders 表
$stmt = $conn->prepare("INSERT INTO orders (order_id, member_id, username, order_date, total_price, status, address, phone, shipping_method)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssissss", $order_id, $member_id, $username, $order_date, $total_price, $status, $address, $phone, $shipping_method);
$stmt->execute();
$stmt->close();

// ===== 3. 插入 order_items 表 =====
$stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
foreach ($cart as $product_id => $quantity) {
    $stmt->bind_param("ssi", $order_id, $product_id, $quantity);
    $stmt->execute();
}
$stmt->close();

// ===== 4. 清空購物車 cookie =====
setcookie('cart', '', time() - 3600, '/');

// ===== 5. 回傳成功訊息 =====
echo "<script>alert('訂單已成功送出！'); window.location.href='index.php';</script>";

exit();
?>



