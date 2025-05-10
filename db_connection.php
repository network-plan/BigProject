<?php
$conn = mysqli_connect('localhost', 'root', 'root123456', 'group_05'); // 將 $link 改為 $conn

if (!$conn) {
    echo "連結錯誤代碼: " . mysqli_connect_errno() . "<br>"; // 顯示錯誤代碼
    echo "連結錯誤訊息: " . mysqli_connect_error() . "<br>"; // 顯示錯誤訊息
    exit();
}

// 啟用錯誤報告
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 測試資料庫連接
if (isset($conn)) {
    echo "<!-- 資料庫連接成功 -->";
} else {
    echo "<!-- 資料庫連接失敗 -->";
}
?>
