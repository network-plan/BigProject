<?php
session_start();
include('db_connection.php');

// 檢查會員是否登入（這裡假設帳號存在於 $_SESSION['username']）
include('check_login.php');//檢查登入

$order_id =$_SESSION['username']; // 用會員帳號作為 order_id

// 讀取購物車資料
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

if (empty($cart)) {
    echo "購物車是空的，無法送出訂單。";
    exit();
}

// 建立插入訂單的 SQL（一次一筆商品）
$sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

// 插入每筆商品資料
foreach ($cart as $product_id => $quantity) {
    $stmt->bind_param("ssi", $order_id, $product_id, $quantity);
    $stmt->execute();
}

// 清空購物車 cookie
setcookie('cart', '', time() - 3600, '/');

// 顯示成功訊息或導回首頁
echo "<script>alert('訂單已成功送出！'); window.location.href='index.php';</script>";
exit();
?>
