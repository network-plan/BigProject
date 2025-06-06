<?php
require_once 'db_connection.php';
session_start();
include('check_login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? '';
    $product_id = $_POST['product_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $username = $_POST['username'] ?? '';
    $review_content = $_POST['review_content'] ?? '';
    $rating = intval($_POST['star_rating'] ?? 0);

    if (empty($user_id) || empty($username) || empty($review_content) || empty($product_id) || empty($order_id) || $rating < 1 || $rating > 5) {
        die('請填寫所有欄位，並給予正確評分。');
    }

    // 檢查是否已經評論過這個商品（針對該筆訂單）
    $check_sql = "SELECT 1 FROM reviews WHERE user_id = ? AND product_id = ? AND order_id = ?";
    $stmt_check = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt_check, "sss", $user_id, $product_id, $order_id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        die('您已經對此商品評論過了。');
    }
    mysqli_stmt_close($stmt_check);

    // 建立唯一的 review_id
    $review_id = 'REV' . date('YmdHis') . rand(100, 999);

    // 插入新的評論
    $stmt = mysqli_prepare($conn, "INSERT INTO reviews (review_id, user_id, username, product_id, order_id, rating, content) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssis", $review_id, $user_id, $username, $product_id, $order_id, $rating, $review_content);
        if (mysqli_stmt_execute($stmt)) {
            // 更新 order_items 的 has_review 欄位
            $update_sql = "UPDATE order_items SET has_review = 1 WHERE order_id = ? AND product_id = ?";
            $stmt_update = mysqli_prepare($conn, $update_sql);
            if ($stmt_update) {
                mysqli_stmt_bind_param($stmt_update, "ss", $order_id, $product_id);
                mysqli_stmt_execute($stmt_update);
                mysqli_stmt_close($stmt_update);
            }

            echo "評論提交成功！<a href='historical_orders.php'>返回訂單紀錄</a>";
        } else {
            echo "評論提交失敗：" . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "資料庫錯誤：" . mysqli_error($conn);
    }
} else {
    echo "非法請求。";
}
?>
