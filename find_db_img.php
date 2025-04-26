<?php
$product_id = "P_0001";
$sql = "SELECT img_url FROM product_img WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<img src='{$row['img_url']}' alt=''>";
}
?>

