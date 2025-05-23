<?php
session_start();
if (isset($_COOKIE['cart'])) {
    setcookie("cart", "", time() - 3600, "/"); // 指定路徑才能確保清除
}
session_unset(); // 清除所有 session 變數
session_destroy(); // 銷毀 session
header("Location: index.php"); // 導回登入頁面
exit();
?>