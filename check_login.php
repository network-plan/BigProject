<?php
// 檢查用戶是否已登入
if (!isset($_SESSION['username'])) {
    // 重定向到登入頁面
    header("Location: login.php");
    exit();
}
?>