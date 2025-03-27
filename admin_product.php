<?php
include('db_connection.php');

// 處理新增商品
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $short_description = $_POST['short_description'];
    $full_description = $_POST['full_description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    $sql = "INSERT INTO product_info (product_id, product_name, short_description, full_description, price, stock, category)
            VALUES ('$product_id', '$product_name', '$short_description', '$full_description', '$price', '$stock', '$category')";

    if ($conn->query($sql) === TRUE) {
        echo "商品新增成功!";
    } else {
        echo "錯誤: " . $sql . "<br>" . $conn->error;
    }
}

// 處理更新商品
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $short_description = $_POST['short_description'];
    $full_description = $_POST['full_description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    $sql = "UPDATE product_info SET product_name='$product_name', short_description='$short_description', full_description='$full_description',
            price='$price', stock='$stock', category='$category' WHERE product_id='$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "商品更新成功!";
    } else {
        echo "錯誤: " . $sql . "<br>" . $conn->error;
    }
}

// 處理刪除商品
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $sql = "DELETE FROM product_info WHERE product_id='$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "商品刪除成功!";
    } else {
        echo "錯誤: " . $sql . "<br>" . $conn->error;
    }
}

// 取得所有商品
$result = $conn->query("SELECT * FROM product_info");

if (!$result) {
    die("查詢錯誤: " . $conn->error);  // 顯示 SQL 查詢錯誤
}

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>商品管理系統</title>
</head>
<body>
    <h1>商品管理系統</h1>

    <!-- 新增商品表單 -->
    <h2>新增商品</h2>
    <form action="admin_product.php" method="POST">
        <label for="product_id">商品編號:</label><br>
        <input type="text" id="product_id" name="product_id" required><br><br>

        <label for="product_name">商品名稱:</label><br>
        <input type="text" id="product_name" name="product_name" required><br><br>

        <label for="short_description">簡短描述:</label><br>
        <textarea id="short_description" name="short_description" required></textarea><br><br>

        <label for="full_description">詳細描述:</label><br>
        <textarea id="full_description" name="full_description" required></textarea><br><br>

        <label for="price">價格:</label><br>
        <input type="number" id="price" name="price" required><br><br>

        <label for="stock">庫存:</label><br>
        <input type="number" id="stock" name="stock" required><br><br>

        <label for="category">類別:</label><br>
        <input type="text" id="category" name="category" required><br><br>

        <input type="submit" name="create" value="新增商品">
    </form>

    <!-- 商品列表 -->
    <h2>商品列表</h2>
    <table border="1">
        <tr>
            <th>商品編號</th>
            <th>商品名稱</th>
            <th>簡短描述</th>
            <th>價格</th>
            <th>庫存</th>
            <th>類別</th>
            <th>操作</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['short_description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td>
                <!-- 修改商品 -->
                <a href="admin_product.php?edit=<?php echo $row['product_id']; ?>">修改</a> |
                <!-- 刪除商品 -->
                <a href="admin_product.php?delete=<?php echo $row['product_id']; ?>" onclick="return confirm('確定要刪除這個商品嗎？')">刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>

<?php
$conn->close();
?>
