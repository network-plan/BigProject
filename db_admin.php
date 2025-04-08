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
    } elseif ($_POST['table'] == 'members') {
        $stmt = $conn->prepare("INSERT INTO members (member_id, username, email, password, phone, register_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssds", $_POST['member_id'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['phone'], $_POST['register_date']);
    } elseif ($_POST['table'] == 'orders') {
        $stmt = $conn->prepare("INSERT INTO orders (order_id, member_id, username, order_date, total_price, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssds", $_POST['order_id'], $_POST['member_id'], $_POST['username'], $_POST['order_date'], $_POST['total_price'], $_POST['status']);
    } elseif ($_POST['table'] == 'reviews') {
        $stmt = $conn->prepare("INSERT INTO reviews (review_id, user_id, username, rating, content) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $_POST['review_id'], $_POST['user_id'], $_POST['username'], $_POST['rating'], $_POST['content']);
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
        $stmt->bind_param("sssdiss", $_POST['product_name'], $_POST['short_description'], $_POST['full_description'], $_POST['price'], $_POST['stock'], $_POST['category'], $_POST['product_id']);
    } elseif ($_POST['table'] == 'product_img') {
        $stmt = $conn->prepare("UPDATE product_img SET product_id=?, img_url=? WHERE img_id=?");
        $stmt->bind_param("sss", $_POST['product_id'], $_POST['img_url'], $_POST['img_id']);
    } elseif ($_POST['table'] == 'members') {
        $stmt = $conn->prepare("UPDATE members SET username=?, email=?, password=?, phone=?, register_date=? WHERE member_id=?");
        $stmt->bind_param("ssssss", $_POST['username'], $_POST['email'], $_POST['password'], $_POST['phone'], $_POST['register_date'], $_POST['member_id']);
    } elseif ($_POST['table'] == 'orders') {
        $stmt = $conn->prepare("UPDATE orders SET member_id=?, username=?, order_date=?, total_price=?, status=? WHERE order_id=?");
        $stmt->bind_param("sssdsi", $_POST['member_id'], $_POST['username'], $_POST['order_date'], $_POST['total_price'], $_POST['status'], $_POST['order_id']);
    } elseif ($_POST['table'] == 'reviews') {
        $stmt = $conn->prepare("UPDATE reviews SET user_id=?, username=?, rating=?, content=? WHERE review_id=?");
        $stmt->bind_param("ssis", $_POST['user_id'], $_POST['username'], $_POST['rating'], $_POST['content'], $_POST['review_id']);
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
    } elseif ($_GET['table'] == 'members') {
        $stmt = $conn->prepare("DELETE FROM members WHERE member_id=?");
        $stmt->bind_param("s", $_GET['delete']);
    } elseif ($_GET['table'] == 'orders') {
        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id=?");
        $stmt->bind_param("i", $_GET['delete']);
    } elseif ($_GET['table'] == 'reviews') {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id=?");
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
    } elseif ($current_table == 'product_img') {
        $result = $conn->query("SELECT * FROM product_img");
    } elseif ($current_table == 'members') {
        $result = $conn->query("SELECT * FROM members");
    } elseif ($current_table == 'orders') {
        $result = $conn->query("SELECT * FROM orders");
    } elseif ($current_table == 'reviews') {
        $result = $conn->query("SELECT * FROM reviews");
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>登入 | 彰化小禮坊</title>
	<link href="css/db_admin_style.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/login.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">

</head><!--/head-->

<body>
	<header id="header"><!--header-->
		<div class="header_top"><!--header_top-->
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="contactinfo">
							<ul class="nav nav-pills">
								<li><a href="#"><i class="fa fa-phone"></i> 04 1234567</a></li>
								<li><a href="#"><i class="fa fa-envelope"></i> hello@gm.ncue.edu.tw</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="social-icons pull-right">
							<ul class="nav navbar-nav">
								<li><a href=""><i class="fa fa-facebook"></i></a></li>
								<li><a href=""><i class="fa fa-twitter"></i></a></li>
								<li><a href=""><i class="fa fa-linkedin"></i></a></li>
								<li><a href=""><i class="fa fa-dribbble"></i></a></li>
								<li><a href=""><i class="fa fa-google-plus"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header_top-->
		
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<div class="logo pull-left">
							<a href="index.html"><img src="images/home/logo.png" alt="" /></a>
						</div>
						<div class="btn-group pull-right">
							<div class="btn-group">
								<button type="button" class="btn1 btn-default dropdown-toggle usa" data-toggle="dropdown">
									中文
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a href="">英文</a></li>
									<li><a href="">中文</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="shop-menu pull-right">
							<ul class="nav navbar-nav">
								<!-- <li><a href="#"><i class="fa fa-user"></i> Account</a></li> -->
								<li><a href="#"><i class="fa fa-user"></i> 帳號</a></li>
								<!-- <li><a href="checkout.html"><i class="fa fa-crosshairs"></i> Checkout</a></li> -->
								<li><a href="checkout.html"><i class="fa fa-crosshairs"></i> 查看歷史訂單</a></li>
								<!-- <li><a href="cart.html"><i class="fa fa-shopping-cart"></i> Cart</a></li> -->
								<li><a href="cart.html"><i class="fa fa-shopping-cart"></i> 購物車</a></li>
								<!-- <li><a href="login.html"><i class="fa fa-lock"></i> Login</a></li> -->
								<li><a href="login.html"><i class="fa fa-lock"></i> 登入</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-middle-->
	
		<div class="header-bottom"><!--header-bottom-->
			<div class="container">
				<div class="row">
					<div class="col-sm-9">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="mainmenu pull-left">
							<ul class="nav navbar-nav collapse navbar-collapse">
								<!-- <li><a href="index.html" class="active">Home</a></li> -->
								<li><a href="index.html" class="active">首頁</a></li>
								<!-- <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a> -->
								<li class="dropdown"><a href="#">購物資訊<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
										<!-- <li><a href="shop.html">Products</a></li> -->
										<li><a href="shop.html">商品</a></li>
										<!-- <li><a href="checkout.html">Checkout</a></li> -->
										<li><a href="checkout.html">歷史訂單</a></li>
										<!-- <li><a href="cart.html">Cart</a></li> -->
										<li><a href="cart.html">購物車</a></li>
										<!-- <li><a href="login.html">Login</a></li> -->
										<li><a href="login.html">登入</a></li>
									</ul>
								</li>
								<li class="dropdown"><a href="#">評價<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
										<li><a href="blog.html">商品評價列表</a></li>
										<li><a href="blog-single.html">單一商品評價</a></li>
									</ul>
								</li>
								<!-- <li><a href="contact-us.html">Contact</a></li> -->
								<li><a href="contact-us.html">聯絡我們</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="search_box pull-right">
							<input type="text" placeholder="搜尋"/>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-bottom-->
	</header><!--/header-->
	
	<div class="container">
        <h1>商品管理系統</h1>
        <div class="table-selector">
            <button
                onclick="location.href='?table=product_info'"
                class="<?= $current_table == 'product_info' ? 'active' : '' ?>">商品資訊</button>
            <button
                onclick="location.href='?table=product_img'"
                class="<?= $current_table == 'product_img' ? 'active' : '' ?>">商品圖片</button>
            <button
                onclick="location.href='?table=members'"
                class="<?= $current_table == 'members' ? 'active' : '' ?>">會員資訊</button>
            <button
                onclick="location.href='?table=orders'"
                class="<?= $current_table == 'orders' ? 'active' : '' ?>">訂單資訊</button>
            <button
                onclick="location.href='?table=reviews'"
                class="<?= $current_table == 'reviews' ? 'active' : '' ?>">評論資訊</button>
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
                        <td><?= htmlspecialchars($row['product_id']); ?></td>
                        <td><?= htmlspecialchars($row['product_name']); ?></td>
                        <td><?= htmlspecialchars($row['short_description']); ?></td>
                        <td><?= htmlspecialchars($row['price']); ?></td>
                        <td><?= htmlspecialchars($row['stock']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td>
                            <button onclick='editProduct(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['product_id']); ?>&table=product_info" onclick="return confirm('確定要刪除這個商品嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改商品</h2>
            <form method="POST" name="update">
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
        <?php elseif ($current_table == 'product_img'): ?>
            <h2>新增商品圖片</h2>
            <form method="POST">
                <input type="hidden" name="table" value="product_img">
                <input type="text" name="img_id" placeholder="圖片編號" required>
                <input type="text" name="product_id" placeholder="商品編號" required>
                <input type="text" name="img_url" placeholder="圖片URL" required>
                <input type="submit" name="create" value="新增圖片">
            </form>
            <h2>商品圖片列表</h2>
            <table>
                <tr>
                    <th>圖片編號</th>
                    <th>商品編號</th>
                    <th>圖片URL</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['img_id']); ?></td>
                        <td><?= htmlspecialchars($row['product_id']); ?></td>
                        <td><?= htmlspecialchars($row['img_url']); ?></td>
                        <td>
                            <button onclick='editImage(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['img_id']); ?>&table=product_img" onclick="return confirm('確定要刪除這個圖片嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改圖片</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="product_img">
                <input type="text" name="img_id" id="edit_img_id" placeholder="圖片編號" readonly required>
                <input type="text" name="product_id" id="edit_product_id_img" placeholder="商品編號" required>
                <input type="text" name="img_url" id="edit_img_url" placeholder="圖片URL" required>
                <input type="submit" name="update" value="更新圖片">
            </form>
        <?php elseif ($current_table == 'members'): ?>
            <h2>新增會員</h2>
            <form method="POST">
                <input type="hidden" name="table" value="members">
                <input type="text" name="member_id" placeholder="會員編號" required>
                <input type="text" name="username" placeholder="使用者名稱" required>
                <input type="email" name="email" placeholder="電子郵件" required>
                <input type="password" name="password" placeholder="密碼" required>
                <input type="text" name="phone" placeholder="電話" required>
                <input type="date" name="register_date" placeholder="註冊日期" required>
                <input type="submit" name="create" value="新增會員">
            </form>
            <h2>會員列表</h2>
            <table>
                <tr>
                    <th>會員編號</th>
                    <th>使用者名稱</th>
                    <th>電子郵件</th>
                    <th>電話</th>
                    <th>註冊日期</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['member_id']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><?= htmlspecialchars($row['register_date']); ?></td>
                        <td>
                            <button onclick='editMember(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['member_id']); ?>&table=members" onclick="return confirm('確定要刪除這個會員嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改會員</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="members">
                <input type="text" name="member_id" id="edit_member_id" placeholder="會員編號" readonly required>
                <input type="text" name="username" id="edit_username" placeholder="使用者名稱" required>
                <input type="email" name="email" id="edit_email" placeholder="電子郵件" required>
                <div class="password-container">
                    <input type="password" name="password" id="edit_password" placeholder="密碼" required>
                    <button type="button" id="toggle_password_visibility" onclick="togglePasswordVisibility()">顯示密碼</button>
                </div>
                <input type="text" name="phone" id="edit_phone" placeholder="電話" required>
                <input type="date" name="register_date" id="edit_register_date" placeholder="註冊日期" required>
                <input type="submit" name="update" value="更新會員">
            </form>
        <?php elseif ($current_table == 'orders'): ?>
            <h2>新增訂單</h2>
            <form method="POST">
                <input type="hidden" name="table" value="orders">
                <input type="number" name="order_id" placeholder="訂單編號" required>
                <input type="text" name="member_id" placeholder="會員編號" required>
                <input type="text" name="username" placeholder="使用者名稱" required>
                <input type="date" name="order_date" placeholder="訂單日期" required>
                <input type="number" name="total_price" placeholder="總金額" required>
                <input type="text" name="status" placeholder="訂單狀態" required>
                <input type="submit" name="create" value="新增訂單">
            </form>
            <h2>訂單列表</h2>
            <table>
                <tr>
                    <th>訂單編號</th>
                    <th>會員編號</th>
                    <th>使用者名稱</th>
                    <th>訂單日期</th>
                    <th>總金額</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['order_id']); ?></td>
                        <td><?= htmlspecialchars($row['member_id']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['order_date']); ?></td>
                        <td><?= htmlspecialchars($row['total_price']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                        <td>
                            <button onclick='editOrder(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['order_id']); ?>&table=orders" onclick="return confirm('確定要刪除這個訂單嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改訂單</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="orders">
                <input type="number" name="order_id" id="edit_order_id" placeholder="訂單編號" readonly required>
                <input type="text" name="member_id" id="edit_order_member_id" placeholder="會員編號" required>
                <input type="text" name="username" id="edit_order_username" placeholder="使用者名稱" required>
                <input type="date" name="order_date" id="edit_order_date" placeholder="訂單日期" required>
                <input type="number" name="total_price" id="edit_order_total_price" placeholder="總金額" required>
                <input type="text" name="status" id="edit_order_status" placeholder="訂單狀態" required>
                <input type="submit" name="update" value="更新訂單">
            </form>
        <?php elseif ($current_table == 'reviews'): ?>
            <h2>新增評論</h2>
            <form method="POST">
                <input type="hidden" name="table" value="reviews">
                <input type="text" name="review_id" placeholder="評論編號" required>
                <input type="text" name="user_id" placeholder="使用者編號" required>
                <input type="text" name="username" placeholder="使用者名稱" required>
                <input type="number" name="rating" placeholder="評分" min="1" max="5" required>
                <textarea name="content" placeholder="評論內容" required></textarea>
                <input type="submit" name="create" value="新增評論">
            </form>
            <h2>評論列表</h2>
            <table>
                <tr>
                    <th>評論編號</th>
                    <th>使用者編號</th>
                    <th>使用者名稱</th>
                    <th>評分</th>
                    <th>評論內容</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['review_id']); ?></td>
                        <td><?= htmlspecialchars($row['user_id']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['rating']); ?></td>
                        <td><?= htmlspecialchars($row['content']); ?></td>
                        <td>
                            <button onclick='editReview(<?= json_encode($row); ?>)'>修改</button>
                            <a href="?delete=<?= urlencode($row['review_id']); ?>&table=reviews" onclick="return confirm('確定要刪除這個評論嗎？')">刪除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h2>修改評論</h2>
            <form method="POST" name="update">
                <input type="hidden" name="table" value="reviews">
                <input type="text" name="review_id" id="edit_review_id" placeholder="評論編號" readonly required>
                <input type="text" name="user_id" id="edit_review_user_id" placeholder="使用者編號" required>
                <input type="text" name="username" id="edit_review_username" placeholder="使用者名稱" required>
                <input type="number" name="rating" id="edit_review_rating" placeholder="評分" min="1" max="5" required>
                <textarea name="content" id="edit_review_content" placeholder="評論內容" required></textarea>
                <input type="submit" name="update" value="更新評論">
            </form>
            <?php endif; ?>
    </div>


    
	
	<footer id="footer"><!--Footer-->
		<div class="footer-top">
			<div class="container">
				<div class="row">
					<div class="col-sm-2">
						<div class="companyinfo">
							<h2><span>彰化</span>小禮坊</h2>
							<p>用購買支持在地小農</p>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="col-sm-3">
							<div class="video-gallery text-center">
								<a href="images/home/iframe1.jpg">
									<div class="iframe-img">
										<img src="images/home/iframe1.jpg" alt="" />
									</div>
								</a>
								<p>鯨魚魚</p>
								<h2>01 JULY 2024</h2>
							</div>
						</div>

						<div class="col-sm-3">
							<div class="video-gallery text-center">
								<a href="images/home/iframe2.jpg">
									<div class="iframe-img">
										<img src="images/home/iframe2.jpg" alt="" />
									</div>
								</a>
								<p>南瓜辰</p>
								<h2>32 DEC 2024</h2>
							</div>
						</div>

						<div class="col-sm-3">
							<div class="video-gallery text-center">
								<a href="images/home/iframe3.jpg">
									<div class="iframe-img">
										<img src="images/home/iframe3.jpg" alt="" />
									</div>
								</a>
								<p>台灣阿虹</p>
								<h2>06 JUNE 2024</h2>
							</div>
						</div>

						<div class="col-sm-3">
							<div class="video-gallery text-center">
								<a href="images/home/iframe4.jpg">
									<div class="iframe-img">
										<img src="images/home/iframe4.jpg" alt="" />
									</div>
								</a>
								<p>Rory</p>
								<h2>30 FEB 2023</h2>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="address">
							<img src="images/home/map.png" alt="" />
							<p>Taiwan</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<p class="pull-left">Copyright © 2025 彰化小禮坊 Inc. All rights reserved.</p>
					
				</div>
			</div>
		</div>

	</footer><!--/Footer-->
	

    <script src="js/db_admin_f.js"></script>
    <script src="js/jquery.js"></script>
	<script src="js/price-range.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
	<script src="js/login.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script> -->
    <!--additional method - for checkbox .. ,require_from_group method ...-->
    <!-- <script src="//jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/localization/messages_zh_TW.js "></script> -->
</body>
</html>
<?php $conn->close();?>