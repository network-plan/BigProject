<?php
include('db_connection.php');

// 新增商品
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    $stmt = $conn->prepare("INSERT INTO product_info (product_id, product_name, short_description, full_description, price, stock, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdis", $_POST['product_id'], $_POST['product_name'], $_POST['short_description'], $_POST['full_description'], $_POST['price'], $_POST['stock'], $_POST['category']);
    
    if ($stmt->execute()) {
        echo "<p class='success'>商品新增成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// 更新商品
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE product_info SET product_name=?, short_description=?, full_description=?, price=?, stock=?, category=? WHERE product_id=?");
    $stmt->bind_param("sssdisi", $_POST['product_name'], $_POST['short_description'], $_POST['full_description'], $_POST['price'], $_POST['stock'], $_POST['category'], $_POST['product_id']);
    
    if ($stmt->execute()) {
        echo "<p class='success'>商品更新成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// 刪除商品
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM product_info WHERE product_id=?");
    $stmt->bind_param("s", $_GET['delete']);
    
    if ($stmt->execute()) {
        echo "<p class='success'>商品刪除成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// 取得所有商品
$result = $conn->query("SELECT * FROM product_info");
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品管理系統</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        @media (max-width: 600px) {
            table {
                font-size: 14px;
            }
            input, textarea {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>商品管理系統</h1>
        <h2>新增商品</h2>
        <form method="POST">
            <input type="text" name="product_id" placeholder="商品編號" required>
            <input type="text" name="product_name" placeholder="商品名稱" required>
            <textarea name="short_description" placeholder="簡短描述" required></textarea>
            <textarea name="full_description" placeholder="詳細描述" required></textarea>
            <input type="number" name="price" placeholder="價格" required>
            <input type="number" name="stock" placeholder="庫存" required>
            <input type="text" name="category" placeholder="類別" required>
            <input type="submit" name="create" value="新增商品">
        </form>
        
        <h2>商品列表</h2>
        <table>
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
                <td><?= $row['product_id']; ?></td>
                <td><?= $row['product_name']; ?></td>
                <td><?= $row['short_description']; ?></td>
                <td><?= $row['price']; ?></td>
                <td><?= $row['stock']; ?></td>
                <td><?= $row['category']; ?></td>
                <td>
                    <button onclick="editProduct('<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>')">修改</button>
                    <a href="?delete=<?= $row['product_id']; ?>" onclick="return confirm('確定要刪除這個商品嗎？')">刪除</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <h2>修改商品</h2>
        <form method="POST">
            <input type="hidden" name="product_id" id="edit_product_id">
            <input type="text" name="product_name" id="edit_product_name" placeholder="商品名稱" required>
            <textarea name="short_description" id="edit_short_description" placeholder="簡短描述" required></textarea>
            <textarea name="full_description" id="edit_full_description" placeholder="詳細描述" required></textarea>
            <input type="number" name="price" id="edit_price" placeholder="價格" required>
            <input type="number" name="stock" id="edit_stock" placeholder="庫存" required>
            <input type="text" name="category" id="edit_category" placeholder="類別" required>
            <input type="submit" name="update" value="更新商品">
        </form>
    </div>
    <script>
        function editProduct(productData) {
            let product = JSON.parse(productData);
            document.getElementById('edit_product_id').value = product.product_id;
            document.getElementById('edit_product_name').value = product.product_name;
            document.getElementById('edit_short_description').value = product.short_description;
            document.getElementById('edit_full_description').value = product.full_description;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_stock').value = product.stock;
            document.getElementById('edit_category').value = product.category;
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
