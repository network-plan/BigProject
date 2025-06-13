<?php
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login_username'])) {
    $username = trim($_POST['login_username']);
    $password = $_POST['login_password'];
    
    try {
        // 先檢查使用者名稱是否存在
        $check_user_stmt = $conn->prepare("SELECT username, password, member_id FROM members WHERE username = ?");
        $check_user_stmt->bind_param("s", $username);
        $check_user_stmt->execute();
        $user_result = $check_user_stmt->get_result();
        
        if ($user_result->num_rows === 0) {
            // 使用者名稱不存在
            echo json_encode(['success' => false, 'message' => '使用者名稱不存在']);
            $check_user_stmt->close();
            exit();
        }
        
        // 使用者名稱存在，檢查密碼
        $user_row = $user_result->fetch_assoc();
        
        if ($user_row['password'] === $password) {
            // 登入成功
            $_SESSION['username'] = $user_row['username'];
            $_SESSION['member_id'] = $user_row['member_id'];
            
            // 設定角色權限
            $_SESSION['role'] = ($user_row['username'] === 'admin' && $password === 'admin123456') ? 'admin' : 'user';
            
            // 清除購物車 cookie
            setcookie("cart", "", time() - 3600, "/");
            
            echo json_encode(['success' => true, 'role' => $_SESSION['role']]);
        } else {
            // 密碼錯誤
            echo json_encode(['success' => false, 'message' => '密碼錯誤']);
        }
        
        $check_user_stmt->close();
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => '登入時發生錯誤，請稍後再試']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '無效的請求']);
}

$conn->close();
?>