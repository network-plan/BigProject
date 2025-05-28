<?php 
if (isset($_POST['oper']) && $_POST['oper'] == "checkStock") {
    include("db_connection.php"); // 使用共用的資料庫連線檔案

    $product_id = $_POST['product_id'];
    $requested_quantity = (int)$_POST['requested_quantity'];

    $response = array("success" => false, "stock" => 0);

    $sql = "SELECT stock FROM product_info WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $stock);

    if (mysqli_stmt_fetch($stmt)) {
        if ($requested_quantity <= $stock) {
            $response["success"] = true;
        } else {
            $response["stock"] = $stock;
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo json_encode($response);
}
?>
