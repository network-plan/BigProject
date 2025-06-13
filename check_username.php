<?php
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    
    // 驗證使用者名稱長度
    if (strlen($username) < 4 || strlen($username) > 10) {
        echo json_encode(['exists' => false, 'error' => '使用者名稱長度不符合要求']);
        exit();
    }
    
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM members WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo json_encode(['exists' => true]);
        } else {
            echo json_encode(['exists' => false]);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['exists' => false, 'error' => '資料庫錯誤']);
    }
} else {
    echo json_encode(['exists' => false, 'error' => '無效的請求']);
}

$conn->close();
?>