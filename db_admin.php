<?php
include('db_connection.php');

// 決定當前操作的資料表
$current_table = isset($_GET['table']) ? $_GET['table'] : 'product_info';

// 新增商品或圖片
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    if ($_POST['table'] == 'product_info') {
        $stmt = $conn->prepare("INSERT INTO product_info (product_id, product_name, short_description, full_description, price, stock, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdis", $_POST['product_id'], $_POST['product_name'], $_POST['short_description'], $_POST['full_description'], $_POST['price'], $_POST['stock'], $_POST['category']);
    } elseif ($_POST['table'] == 'product_img') {
        $stmt = $conn->prepare("INSERT INTO product_img (img_id, product_id, img_url) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['img_id'], $_POST['product_id'], $_POST['img_url']);
    }
    
    if ($stmt->execute()) {
        echo "<p class='success'>新增成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// 更新商品或圖片
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    if ($_POST['table'] == 'product_info') {
        $stmt = $conn->prepare("UPDATE product_info SET product_name=?, short_description=?, full_description=?, price=?, stock=?, category=? WHERE product_id=?");
        $stmt->bind_param("sssdisi", $_POST['product_name'], $_POST['short_description'], $_POST['full_description'], $_POST['price'], $_POST['stock'], $_POST['category'], $_POST['product_id']);
    } elseif ($_POST['table'] == 'product_img') {
        $stmt = $conn->prepare("UPDATE product_img SET product_id=?, img_url=? WHERE img_id=?");
        $stmt->bind_param("sss", $_POST['product_id'], $_POST['img_url'], $_POST['img_id']);
    }
    
    if ($stmt->execute()) {
        echo "<p class='success'>更新成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// 刪除商品或圖片
if (isset($_GET['delete']) && isset($_GET['table'])) {
    if ($_GET['table'] == 'product_info') {
        $stmt = $conn->prepare("DELETE FROM product_info WHERE product_id=?");
        $stmt->bind_param("s", $_GET['delete']);
    } elseif ($_GET['table'] == 'product_img') {
        $stmt = $conn->prepare("DELETE FROM product_img WHERE img_id=?");
        $stmt->bind_param("s", $_GET['delete']);
    }
    
    if ($stmt->execute()) {
        echo "<p class='success'>刪除成功!</p>";
    } else {
        echo "<p class='error'>錯誤: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// 取得資料表資料
$result = false;
$rows = [];
try {
    if ($current_table == 'product_info') {
        $result = $conn->query("SELECT * FROM product_info");
    } else {
        $result = $conn->query("SELECT * FROM product_img");
    }

    // 檢查查詢是否成功
    if ($result === false) {
        throw new Exception("查詢資料表失敗: " . $conn->error);
    }

    // 如果有結果，則提取所有資料列
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} catch (Exception $e) {
    echo "<p class='error'>" . $e->getMessage() . "</p>";
}
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
        .table-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .table-selector button {
            padding: 10px;
            background-color: <?= $current_table == 'product_info' ? '#4CAF50' : '#f1f1f1' ?>;
            color: <?= $current_table == 'product_info' ? 'white' : 'black' ?>;
            border: none;
            cursor: pointer;
        }
        .table-selector button:last-child {
            background-color: <?= $current_table == 'product_img' ? '#4CAF50' : '#f1f1f1' ?>;
            color: <?= $current_table == 'product_img' ? 'white' : 'black' ?>;
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
        form input, form textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
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
        
        <div class="table-selector">
            <button onclick="location.href='?table=product_info'">商品資訊</button>
            <button onclick="location.href='?table=product_img'">商品圖片</button>
        </div>

        <?php if ($current_table == 'product_info'): ?>
        <h2>新增商品</h2>
        <form method="POST">
            <input type="hidden" name="table" value="product_info">
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
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row['product_id']; ?></td>
                <td><?= $row['product_name']; ?></td>
                <td><?= $row['short_description']; ?></td>
                <td><?= $row['price']; ?></td>
                <td><?= $row['stock']; ?></td>
                <td><?= $row['category']; ?></td>
                <td>
                    <button onclick="editProduct('<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>')">修改</button>
                    <a href="?delete=<?= $row['product_id']; ?>&table=product_info" onclick="return confirm('確定要刪除這個商品嗎？')">刪除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <h2>修改商品</h2>
        <form method="POST">
            <input type="hidden" name="table" value="product_info">
            <input type="text" name="product_id" id="edit_product_id" placeholder="商品編號" readonly required>
            <input type="text" name="product_name" id="edit_product_name" placeholder="商品名稱" required>
            <textarea name="short_description" id="edit_short_description" placeholder="簡短描述" required></textarea>
            <textarea name="full_description" id="edit_full_description" placeholder="詳細描述" required></textarea>
            <input type="number" name="price" id="edit_price" placeholder="價格" required>
            <input type="number" name="stock" id="edit_stock" placeholder="庫存" required>
            <input type="text" name="category" id="edit_category" placeholder="類別" required>
            <input type="submit" name="update" value="更新商品">
        </form>

        <?php else: ?>
        <h2>新增商品圖片</h2>
        <form method="POST">
            <input type="hidden" name="table" value="product_img">
            <input type="text" name="img_id" placeholder="圖片編號" required>
            <input type="text" name="product_id" placeholder="商品編號" required>
            <input type="text" name="img_url" placeholder="圖片網址" required>
            <input type="submit" name="create" value="新增圖片">
        </form>
        
        <h2>商品圖片列表</h2>
        <table>
            <tr>
                <th>圖片編號</th>
                <th>商品編號</th>
                <th>圖片網址</th>
                <th>操作</th>
            </tr>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row['img_id']; ?></td>
                <td><?= $row['product_id']; ?></td>
                <td><?= $row['img_url']; ?></td>
                <td>
                    <button onclick="editImage('<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>')">修改</button>
                    <a href="?delete=<?= $row['img_id']; ?>&table=product_img" onclick="return confirm('確定要刪除這個圖片嗎？')">刪除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <h2>修改商品圖片</h2>
        <form method="POST">
            <input type="hidden" name="table" value="product_img">
            <input type="text" name="img_id" id="edit_img_id" placeholder="圖片編號" readonly required>
            <input type="text" name="product_id" id="edit_img_product_id" placeholder="商品編號" required>
            <input type="text" name="img_url" id="edit_img_url" placeholder="圖片網址" required>
            <input type="submit" name="update" value="更新圖片">
        </form>
        <?php endif; ?>
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

        function editImage(imageData) {
            let image = JSON.parse(imageData);
            document.getElementById('edit_img_id').value = image.img_id;
            document.getElementById('edit_img_product_id').value = image.product_id;
            document.getElementById('edit_img_url').value = image.img_url;
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
