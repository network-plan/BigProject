<?php
session_start();
require_once 'db_connection.php'; 

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login_username'])) {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    $stmt = $conn->prepare("SELECT username, password, member_id FROM members WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $_SESSION['username'] = $row['username'];
        $_SESSION['member_id'] = $row['member_id'];

        $_SESSION['role'] = ($row['username'] === 'admin' && $password === 'admin123456') ? 'admin' : 'user';

        setcookie("cart", "", time() - 3600, "/");

        echo json_encode(['success' => true, 'role' => $_SESSION['role']]);
    } else {
        echo json_encode(['success' => false, 'message' => '帳號或密碼錯誤']);
    }
    exit();
}
?>
