<?php
include('db_connection.php'); // 連接資料庫
session_start();
include('check_login.php');//檢查登入
// 取得購物車內容（從 cookie 中）
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

if (empty($cart)) {
    echo '<script>alert("購物車是空的。開始購物吧！"); window.location.href="shop.php";</script>';
    exit();
}

// 取得所有 product_id
$product_ids = array_keys($cart);

// 準備 SQL 查詢
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$sql = "
    SELECT 
        p.product_id, 
        p.product_name, 
        p.price, 
        i.img_url 
    FROM 
        product_info p 
    LEFT JOIN 
        product_img i 
    ON 
        p.product_id = i.product_id 
    WHERE 
        p.product_id IN ($placeholders)
";

$stmt = $conn->prepare($sql);

// 綁定查詢參數
$types = str_repeat('s', count($product_ids));
$stmt->bind_param($types, ...$product_ids);
$stmt->execute();
$result = $stmt->get_result();

// 把查詢結果整理成陣列
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['product_id']] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cart | 彰化小禮坊</title><!-- <title>Cart | Chang-hua Gift House</title> -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link href="css/cart.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
    <script src="js/jquery-3.6.4.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
    <script src="js/cart_js.js"></script>
    <style>
    .check_title {
        font-size: 20px;
        font-weight: bold;
        margin-top: 20px;
        color: #83B1C9;
    }

    .cart_product img {
        width: 120px;
        height: auto;
        object-fit: cover;
    }
    </style>
</head>
<!--/head-->

<body>
    <?php include('header.php'); ?>

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <!-- <li><a href="index.html">Home</a></li>  -->
                    <li><a href="index.php">回首頁</a></li>
                    <!-- <li class="active">Shopping Cart</li> -->
                    <li class="active">購物車</li>
                </ol>
            </div>
            <div class="table-responsive cart_info">
                <table class="table table-condensed">
                    <thead>
                        <tr class="cart_menu">
                            <!-- <td class="image">Item</td> -->
                            <td class="image">商品</td>
                            <!-- <td class="description">name</td> -->
                            <td class="description">品名</td>
                            <!-- <td class="price">Price</td> -->
                            <td class="price">價格</td>
                            <!-- <td class="quantity">Quantity</td> -->
                            <td class="quantity">數量</td>
                            <!-- <td class="total">Total</td> -->
                            <td class="total">總金額</td>
                            <td></td>
                        </tr>
                    </thead>
                    <!-- 商品資訊 -->
                    <tbody>
                        <?php foreach ($products as $product_id => $product): 
							$quantity = $cart[$product_id];
							$total_price = $product['price'] * $quantity;
						?>
                        <tr>
                            <td class="cart_product" style="width: 150px;">
                                <a href="product-details.php?id=<?= htmlspecialchars($product_id) ?>">
                                    <img src="<?= htmlspecialchars($product['img_url']) ?>"
                                        alt="<?= htmlspecialchars($product['product_name']) ?>">
                                </a>
                            </td>
                            <td class="cart_description" style="width: 250px;">
                                <h4><a href="product-details.php?id=<?= htmlspecialchars($product_id) ?>">
                                        <?= htmlspecialchars($product['product_name']) ?>
                                    </a></h4>
                            </td>
                            <td class="cart_price">
                                <p>NT$<?= number_format($product['price']) ?></p>
                            </td>
                            <td class="cart_quantity">
                                <div class="cart_quantity_button">
                                    <a class="cart_quantity_up" href="#"> + </a>
                                    <input class="cart_quantity_input" type="text" name="quantity"
                                        value="<?= $quantity ?>" autocomplete="off" size="2"
                                        data-id="<?= htmlspecialchars($product_id) ?>">
                                    <a class="cart_quantity_down" href="#"> - </a>
                                </div>
                            </td>
                            <td class="cart_total">
                                <p class="cart_total_price">NT$<?= number_format($total_price) ?></p>
                            </td>
                            <td class="cart_delete">
                                <a class="cart_quantity_delete" href="#"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!--/#cart_items-->

    <section id="do_action">
        <div class="container">
            <div class="heading">
                <h3>確認訂單</h3>
                <p>配送方式、時間和費用(滿1000免運費)</p>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="chose_area">
                        <ul class="user_option">
                            <h3 class="check_title">專屬折扣碼</h3>
                            <li>
                                <input type="checkbox" id="coupon">
                                <!-- <label>Use Coupon Code</label> -->
                                <label name="coupon">使用折扣碼</label>
                                <!-- 折扣碼輸入框 -->
                                <div id="couponInputWrapper" style="display: none; margin-top: 8px;">
                                    <input type="text" id="couponCode" placeholder="請輸入折扣碼">
                                    <button id="applyCoupon">驗證</button>
                                </div>
                            </li>
                        </ul>
                        <ul id="send-way">
                            <h3 class="check_title">配送方式</h3>
                            <li>
                                <input type="radio" name="send-way" value="0">
                                <!-- <label>Sent To Chang-hua Gift Store</label> -->
                                <label>寄送至彰化小禮坊商店 +0元</label>
                            </li>
                            <li>
                                <input type="radio" name="send-way" value="1">
                                <!-- <label>Sent To Home</label> -->
                                <label>宅配到家 +50元</label>
                            </li>
                            <li>
                                <input type="radio" name="send-way" value="2">
                                <!-- <label>Sent To Home Tomorrow</label> -->
                                <label>宅配到家-隔日到貨 +100元</label>
                            </li>
                        </ul>
                        <!-- 聯絡電話（永遠顯示） -->
                        <ul class="contact-info" style="display: none;">
                            <h3 class="check_title">配送資訊</h3>
                            <li class="single_field">
                                <div class="input-container">
                                    <p>聯絡電話：</p>
                                    <input type="tel" name="phone" class="form-input" placeholder="請輸入聯絡電話" required
                                        minlength="8" maxlength="12">
                                    <!-- <span class="error-message"></span> -->
                                </div>
                            </li>
                        </ul>

                        <!-- 地區與郵遞區號（僅在選擇宅配時顯示） -->
                        <ul class="user_info delivery-info" style="display: none;">
                            <!-- single_field -->
                            <li class="zip-field">
                                <br />
                                <div class="zip-box">
                                    <label>地址：</label>
                                    <input type="text" size="50" placeholder="請輸入地址">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="total_area">
                        <ul>
                            <h3 class="check_title">訂單明細</h3>
                            <!-- <li>Cart Item Total <span>0 NTD</span></li> -->
                            <li>購物車物品總價 <span>0 NTD</span></li>
                            <!-- <li>Shipping Cost <span>2 NTD</span></li> -->
                            <li>運費 <span>0 NTD</span></li>
                            <!-- <li>coupon discount<span>- 60 NTD</span></li> -->
                            <li>優惠卷減免<span>0 NTD</span></li>
                            <hr />
                            <!-- <li>Total <span>65 NTD</span></li> -->
                            <li>總金額 <span id="total-amount">0 NTD</span></li>
                        </ul>
                        <button type="submit" class="btn update submit-order">確認訂單並送出</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/#do_action-->
    <?php include('footer.php'); ?>
</body>

</html>