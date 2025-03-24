<?php
include('db_connection.php');

// 處理新增商品
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $sql = "INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $name, $description, $price, $image_url);
    $stmt->execute();

    echo "商品已成功新增！<br>";
    echo "<a href='admin.php'>返回商品管理頁面</a>";
}

// 處理編輯商品
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image_url = $_POST['image_url'];

        $sql = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $product_id);
        $stmt->execute();

        echo "商品已更新！<br>";
        echo "<a href='admin.php'>返回商品管理頁面</a>";
    }
}

// 處理刪除商品
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    echo "商品已刪除！<br>";
    echo "<a href='admin.php'>返回商品管理頁面</a>";
}

// 預設顯示商品列表
if (!isset($_GET['action']) || $_GET['action'] == 'list') {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    echo "<h1>商品列表</h1>";
    echo "<a href='admin.php?action=add_form'>新增商品</a><br><br>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . $row['name'] . "</h2>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<p>價格: $" . $row['price'] . "</p>";
            echo "<img src='" . $row['image_url'] . "' alt='" . $row['name'] . "' width='100'><br><br>";
            echo "<a href='admin.php?action=edit&id=" . $row['id'] . "'>編輯</a> | ";
            echo "<a href='admin.php?action=delete&id=" . $row['id'] . "'>刪除</a>";
            echo "</div><hr>";
        }
    } else {
        echo "目前沒有商品";
    }
}

// 顯示新增商品表單
if (isset($_GET['action']) && $_GET['action'] == 'add_form') {
    ?>
    <h1>新增商品</h1>
    <form method="POST" action="admin.php">
        <input type="hidden" name="action" value="add">
        <label for="name">商品名稱：</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">商品描述：</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="price">價格：</label><br>
        <input type="number" step="0.01" id="price" name="price" required><br><br>

        <label for="image_url">商品圖片URL：</label><br>
        <input type="text" id="image_url" name="image_url" required><br><br>

        <input type="submit" value="新增商品">
    </form>
    <?php
}

// 顯示編輯商品表單
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    ?>
    <h1>編輯商品</h1>
    <form method="POST" action="admin.php?action=edit&id=<?php echo $product['id']; ?>">
        <input type="hidden" name="action" value="edit">
        <label for="name">商品名稱：</label><br>
        <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required><br><br>

        <label for="description">商品描述：</label><br>
        <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea><br><br>

        <label for="price">價格：</label><br>
        <input type="number" step="0.01" id="price" name="price" value="<?php echo $product['price']; ?>" required><br><br>

        <label for="image_url">商品圖片URL：</label><br>
        <input type="text" id="image_url" name="image_url" value="<?php echo $product['image_url']; ?>" required><br><br>

        <input type="submit" value="更新商品">
    </form>
    <?php
}

$conn->close();
?>
