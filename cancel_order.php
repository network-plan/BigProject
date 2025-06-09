<?php
include('db_connection.php');
session_start();
include('check_login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $username = $_SESSION['username'];
    
    // 驗證訂單是否屬於當前用戶且狀態為備貨中
    $sql_check = "SELECT status FROM orders WHERE order_id = ? AND username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $order_id, $username);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $order = $result->fetch_assoc();
    
    if ($order && $order['status'] === '備貨中') {
        // 更新訂單狀態為已取消訂單
        $sql_update = "UPDATE orders SET status = '已取消訂單' WHERE order_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("s", $order_id);
        
        if ($stmt_update->execute()) {
            header("Location: historical_orders.php?message=order_cancelled");
        } else {
            header("Location: historical_orders.php?error=cancel_failed");
        }
    } else {
        header("Location: historical_orders.php?error=invalid_order");
    }
} else {
    header("Location: historical_orders.php");
}
exit();
?>