<?php
// 從資料庫撈商品資料
include('db_connection.php');
session_start();

// 放在最前面處理「加入購物車請求」
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity']));

    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

    if (isset($cart[$product_id])) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }

    setcookie('cart', json_encode($cart), time() + (7 * 24 * 60 * 60), "/");

    // 重新導向讓 cookie 生效
    header("Location: cart.php");
    exit();
}

// 取得商品 ID，若沒有則預設為 P_0001
$product_id = isset($_GET['id']) ? $_GET['id'] : "P_0001";

// 查詢商品資料
$sql = "SELECT * FROM product_info WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();

// 只呼叫一次 fetch_assoc()
$product = $product_result->fetch_assoc();
if (!$product) {
    echo "<h2>找不到該商品</h2>";
    exit();
}

// 獲取產品圖片
$img_sql = "SELECT img_url FROM product_img WHERE product_id = ?";
$img_stmt = $conn->prepare($img_sql);
$img_stmt->bind_param("s", $product_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();

$images = [];
while ($img_row = $img_result->fetch_assoc()) {
    $images[] = $img_row['img_url'];
}

$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>產品詳細資料 | 彰化小禮坊</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://jci.book.com.tw/css/books/product/overlay-n.css">
    <link rel="stylesheet" href="https://jci.book.com.tw/css/css.css">
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
    <script src="js/cart_js.js"></script>
</head>
<!--/head-->

<body>
    <header id="header">
        <!--header-->
        <div class="header_top">
            <!--header_top-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="contactinfo">
                            <ul class="nav nav-pills">
                                <li><a href="#"><i class="fa fa-phone"></i> 04 7263460</a></li>
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
        </div>
        <!--/header_top-->

        <div class="header-middle">
            <!--header-middle-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="logo pull-left">
                            <a href="index.php"><img src="images/home/logo.png" alt="" /></a>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="shop-menu pull-right">
                            <ul class="nav navbar-nav">
                                <?php
                                if(isset($_SESSION['username']) && $_SESSION['role'] != 'admin') {
                                    echo "<li><a href=\"logout.php\"><i class=\"fa fa-lock\"></i> 登出</a></li>";//若有登入導入到登出頁面
                                    echo "<li><a href=\"checkout.html\"><i class=\"fa fa-crosshairs\"></i> 查看歷史訂單</a></li>";//若有登入導入到歷史訂單頁面
                                    echo "<li><a href=\"cart.php\"><i class=\"fa fa-shopping-cart\"></i> 購物車</a></li>";//若有登入導入到購物車頁面
                                    echo "<li><a href=\"profile.php\"><i class=\"fa fa-user\"></i> " . $_SESSION['username'] . "</a></li>";//顯示會員名稱 點下去即到個人資料頁面
                                }else if(isset($_SESSION['username']) && $_SESSION['role'] === 'admin'){
                                    echo "<li><a href=\"logout.php\"><i class=\"fa fa-lock\"></i> 登出</a></li>";//若有登入導入到登出頁面
                                    echo "<li><a href=\"profile.php\"><i class=\"fa fa-user\"></i> " . $_SESSION['username'] . "</a></li>";//顯示會員名稱 點下去即到個人資料頁面
                                }else{//若沒有登入 不管點甚麼都導入到登入頁面
                                    echo "<li><a href=\"login.php\"><i class=\"fa fa-lock\"></i> 登入</a></li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/header-middle-->

        <div class="header-bottom">
            <!--header-bottom-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="mainmenu pull-left">
                            <ul class="nav navbar-nav collapse navbar-collapse">

                                <!-- <li><a href="index.php" class="active">Home</a></li> -->
                                <li><a href="index.php" class="active">首頁</a></li>
                                <li class="dropdown"><a href="#">購物資訊<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <?php
                                        if(isset($_SESSION['username']) && $_SESSION['role'] != 'admin') {//若一般會員登入導入到對應頁面
                                            echo "<li><a href=\"shop.php\">商品</a></li>";
                                            echo "<li><a href=\"checkout.html\">歷史訂單</a></li>";
                                            echo "<li><a href=\"cart.php\">購物車</a></li>";
                                        }else{
                                            echo "<li><a href=\"shop.php\">商品</a></li>";
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                                //若為一般會員才看的到(評價連結未改)
                                if(isset($_SESSION['username']) && $_SESSION['role'] != 'admin'){
                                        echo "<li class=\"dropdown\"><a href=\"#\">評價<i class=\"fa fa-angle-down\"></i></a>";
                                        echo "<ul role=\"menu\" class=\"sub-menu\">";
                                        echo "    <li><a href=\"blog.html\">商品評價列表</a></li>";
                                        echo "</ul>";
                                    echo "</li>";
                                }
                                ?>
                                <?php
                                if(isset($_SESSION['username']) && $_SESSION['role'] === 'admin')
                                    echo "<li><a href=\"db_admin.php\">資料庫管理</a></li>" //管理者才看的到這個
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/header-bottom-->
    </header>
    <!--/header-->

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <h2>商品分類</h2>
                        <div class="panel-group category-products" id="accordian">
                            <!--category-productsr-->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="#"><b>禮盒專區</b></a></h4>
                                    <hr />
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="#"><b>酒莊產品</b></a></h4>
                                    <hr />
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="#"><b>果汁系列</b></a></h4>
                                    <hr />
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="#"><b>醬菜類(罐頭食品)</b></a></h4>
                                </div>
                            </div>

                        </div>
                        <!--/category-products-->

                        <!-- <div class="shipping text-center">shipping -->
                        <img src="./images/home/vegetable.png" alt="images/home/shipping.jpg" />
                        <!-- </div>/shipping -->

                    </div>
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="product-details">
                        <!--product-details-->
                        <div class="col-sm-5">
                            <!-- 主圖 Carousel -->
                            <div id="main-carousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?php if (!empty($images)): ?>
                                        <?php foreach ($images as $idx => $img): ?>
                                            <div class="item <?php echo $idx === 0 ? 'active' : ''; ?>" style="padding-left:0;">
                                                <img src="<?php echo htmlspecialchars($img); ?>" alt="Product Image" style="object-fit: cover; display: block;">
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="item active">
                                            <img src="images/product-details/no-image.jpg" alt="No image available" style="width:100%; height:auto;">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Controls -->
                                <a class="left carousel-control" href="#main-carousel" data-slide="prev">
                                    <i class="fa fa-angle-left"></i>
                                </a>
                                <a class="right carousel-control" href="#main-carousel" data-slide="next">
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>

                            <!-- 縮圖列表 -->
                            <div class="mt-3 text-center">
                                <?php if (!empty($images)): ?>
                                    <?php foreach ($images as $idx => $img): ?>
                                        <img
                                            src="<?php echo $img; ?>"
                                            data-target="#main-carousel"
                                            data-slide-to="<?php echo $idx; ?>"
                                            style="width:60px; height:60px; object-fit:cover; margin:0 5px; cursor:pointer; border:2px solid #ddd;"
                                            class="<?php echo $idx === 0 ? 'active-thumb' : ''; ?>"
                                            alt="thumb-<?php echo $idx; ?>">
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <img src="images/product-details/no-image.jpg" alt="No image available" style="width:60px; height:60px; object-fit:cover;">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-sm-7">
                            <div class="product-information">
                                <!--/product-information-->
                                <img src="images/product-details/new.jpg" class="newarrival" alt="" />
                                <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
                                <img src="images/product-details/rating.png" alt="" />
                                <span>
                                    <span>NTD <?php echo number_format($product['price']); ?></span>
                                </span>
                                <hr/> 
                                <form method="post" action="product-details.php?id=<?php echo urlencode($product['product_id']); ?>" style="display:inline;">
                                    <div class="cart_quantity_button">
                                        <p>數量： </p>
                                        <a class="cart_quantity_up" href="#"> + </a>
                                        <input class="cart_quantity_input" type="text" name="quantity" value="1" autocomplete="off" size="2" data-id="1">
                                        <a class="cart_quantity_down" href="#"> - </a>
                                    </div>
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                    <button type="submit" class="btn btn-default cart"><i class="fa fa-shopping-cart"></i> 加入購物車</button>
                                </form>
                                <p><b>存貨狀態:</b>剩 <?php echo intval($product['stock']); ?> 盒</p>
                                <p><b>商品簡述:</b></p>
                                <p><?php echo nl2br(htmlspecialchars($product['short_description'])); ?></p>
                                <a href=""><img src="images/product-details/share.png" class="share img-responsive" alt="" /></a>
                            </div>
                            <!--/product-information-->
                        </div>
                    </div>
                    <!--/product-details-->

                    <div class="category-tab shop-details-tab">
                        <!--category-tab-->
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#details" data-toggle="tab">商品詳細介紹</a></li>
                                <li><a href="#reviews" data-toggle="tab">評論區</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="details">
                                <?php echo nl2br(htmlspecialchars($product['full_description'])); ?>
                            <div class="tab-pane fade" id="reviews" >
								<div class="col-sm-12">
									<div class="review-list">
										<div class="review-item">
											<div class="review-header">
												<span><i class="fa fa-user"></i> 王小明</span><br>
												<span><i class="fa fa-clock-o"></i> 2024-03-15 14:30</span><br>
												<span>評價：
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-o"></i>
												</span>
											</div>
											<div class="review-content">
												商品品質非常好，包裝精美，送禮很體面。蜂蜜味道純正，木製蜂蜜棒也很實用。
											</div>
										</div>
										<hr>
										<div class="review-item">
											<div class="review-header">
												<span><i class="fa fa-user"></i> 李小華</span><br>
												<span><i class="fa fa-clock-o"></i> 2024-03-10 09:15</span><br>
												<span>評價：
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
												</span>
											</div>
											<div class="review-content">
												蜂蜜很香醇，但價格稍貴。整體來說是很好的送禮選擇。
											</div>
										</div>
										<hr>
										<div class="review-item">
											<div class="review-header">
												<span><i class="fa fa-user"></i> 張小美</span><br>
												<span><i class="fa fa-clock-o"></i> 2024-03-05 16:45</span><br>
												<span>評價：
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-o"></i>
													<i class="fa fa-star-o"></i>
													<i class="fa fa-star-o"></i>
												</span>
											</div>
											<div class="review-content">
												包裝很精緻，蜂蜜品質優良，送給長輩很受歡迎。
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div><!--/category-tab-->

                        </div>
                    </div>
                    <!--/category-tab-->

                    <div class="recommended_items">
                        <!--recommended_items-->
                        <h2 class="title text-center">推薦產品</h2>

                        <?php
                        // 直接在PHP中定義推薦產品ID，無需額外資料表
                        // 您可以根據需要自行修改此陣列
                        // 格式: 商品ID => 推薦商品ID陣列
                        $recommendations = [
                            'product001' => ['product005', 'product008', 'product012', 'product015', 'product020', 'product025'],
                            'product002' => ['product010', 'product015', 'product022', 'product023', 'product024', 'product026'],
                            'global'     => ['P_0040', 'P_0041', 'P_0042', 'P_0043', 'P_0044', 'P_0045'],
                        ];

                        // 決定使用哪組推薦產品
                        if (isset($recommendations[$product_id]) && !empty($recommendations[$product_id])) {
                            $recommended_products_ids = $recommendations[$product_id];
                        } else {
                            $recommended_products_ids = $recommendations['global'];
                        }

                        // 最多取6筆
                        $recommended_products_ids = array_slice($recommended_products_ids, 0, 6);

                        // 沒有推薦時初始化空結果
                        if (empty($recommended_products_ids)) {
                            $recommended_result = new mysqli_result($conn);
                        } else {
                            // 準備 SQL 和占位符
                            $placeholders = implode(',', array_fill(0, count($recommended_products_ids), '?'));
                            $recommended_sql = "
                            SELECT p.*, 
                            (SELECT img_url FROM product_img WHERE product_id = p.product_id LIMIT 1) AS main_image
                            FROM product_info p
                            WHERE p.product_id IN ($placeholders)
                            ORDER BY FIELD(p.product_id, $placeholders)";

                            // 建立預處理
                            $recommended_stmt = $conn->prepare($recommended_sql);

                            // 合併兩次要綁定的參數
                            $params = array_merge($recommended_products_ids, $recommended_products_ids);
                            // 類型字串，全部為s
                            $types = str_repeat('s', count($params));

                            // 組合 bind_param 所需的參數陣列
                            $bind_params = [];
                            $bind_params[] = $types;
                            foreach ($params as $p) {
                                $bind_params[] = $p;
                            }

                            // 將參數轉為參考引用
                            $refs = [];
                            foreach ($bind_params as $key => $value) {
                                $refs[$key] = &$bind_params[$key];
                            }

                            // 綁定並執行
                            call_user_func_array([$recommended_stmt, 'bind_param'], $refs);
                            $recommended_stmt->execute();
                            $recommended_result = $recommended_stmt->get_result();
                        }

                        // 顯示結果
                        if ($recommended_result->num_rows > 0) {
                            $totalItems   = $recommended_result->num_rows;
                            $itemsPerSlide = 3;
                            $totalSlides  = ceil($totalItems / $itemsPerSlide);
                        ?>

                            <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?php
                                    $recommended_products = [];
                                    while ($rec_product = $recommended_result->fetch_assoc()) {
                                        $recommended_products[] = $rec_product;
                                    }

                                    for ($i = 0; $i < $totalSlides; $i++) {
                                        $isActive = ($i === 0) ? 'active' : '';
                                        echo "<div class='item $isActive'>";

                                        for ($j = 0; $j < $itemsPerSlide; $j++) {
                                            $index = $i * $itemsPerSlide + $j;
                                            if ($index < $totalItems) {
                                                $rec = $recommended_products[$index];
                                    ?>
                                                <div class="col-sm-4">
                                                    <div class="product-image-wrapper">
                                                        <div class="single-products">
                                                            <div class="productinfo text-center">
                                                                <?php if (!empty($rec['main_image'])): ?>
                                                                    <img src="<?php echo htmlspecialchars($rec['main_image']); ?>" alt="<?php echo htmlspecialchars($rec['product_name']); ?>" />
                                                                <?php else: ?>
                                                                    <img src="images/product-details/no-image.jpg" alt="No image available" />
                                                                <?php endif; ?>

                                                                <h2>NTD <?php echo number_format($rec['price']); ?></h2>
                                                                <p><?php echo htmlspecialchars($rec['product_name']); ?></p>

                                                                <!-- 點擊按鈕直接連到商品詳細頁 -->
                                                                <button type="button" class="btn btn-default add-to-cart"
                                                                    onclick="location.href='product-details.php?id=<?php echo $rec['product_id']; ?>'">
                                                                    <i class="fa fa-shopping-cart"></i> 加入購物車
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php
                                            }
                                        }
                                        echo "</div>"; // .item
                                    }
                                    ?>
                                </div>

                                <?php if ($totalSlides > 1): ?>
                                    <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                    <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                        <?php
                        } else {
                            echo "<p class='text-center'>暫無推薦產品</p>";
                        }

                        if (!empty($recommended_products_ids)) {
                            $recommended_stmt->close();
                        }
                        ?>
                    </div>
                    <!--/recommended_items-->


                </div>
            </div>
        </div>
    </section>

    <footer id="footer">
        <!--Footer-->
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
                                <h2>17 OCT 2024</h2>
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

    </footer>
    <!--/Footer-->



    <script src="js/jquery.js"></script>
    <script src="js/price-range.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>

</html>