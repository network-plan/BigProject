<?php
include('db_connection.php');
session_start();
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
                                if(isset($_SESSION['username'])) {
                                    echo "<li><a href=\"logout.php\"><i class=\"fa fa-lock\"></i> 登出</a></li>";//若有登入導入到登出頁面
                                    echo "<li><a href=\"checkout.html\"><i class=\"fa fa-crosshairs\"></i> 查看歷史訂單</a></li>";//若有登入導入到歷史訂單頁面
                                    echo "<li><a href=\"cart.php\"><i class=\"fa fa-shopping-cart\"></i> 購物車</a></li>";//若有登入導入到購物車頁面
                                    echo "<li><a href=\"profile.php\"><i class=\"fa fa-user\"></i> " . $_SESSION['username'] . "</a></li>";//顯示會員名稱 點下去即到個人資料頁面(未做)
                                } else {
                                    echo "<li><a href=\"shop.php\"><i class=\"fa fa-lock\"></i> 登入</a></li>";
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
                                <!-- <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a> -->
                                <li class="dropdown"><a href="#">購物資訊<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <?php
                                        if(isset($_SESSION['username'])) {//若有登入導入到對應頁面
                                            echo "<li><a href=\"shop.php\">商品</a></li>";
                                            echo "<li><a href=\"checkout.html\">歷史訂單</a></li>";
                                            echo "<li><a href=\"cart.php\">購物車</a></li>";
                                        } else {//若沒有登入 不管點甚麼都導入到登入頁面
                                            echo "<li><a href=\"shop.php\">商品</a></li>";
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <li class="dropdown"><a href="#">評價<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="blog.html">商品評價列表</a></li>
                                        <!-- <li><a href="blog-single.html">單一商品評價</a></li> -->
                                    </ul>
                                </li>
                                <!-- <li><a href="contact-us.html">Contact</a></li> -->
                                <li><a href="contact-us.html">聯絡我們</a></li>
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
                                    <!-- <button type="button" class="btn btn-default cart">
                                        <i class="fa fa-shopping-cart"></i>
                                        加入購物車
                                    </button> -->
                                    <?php
                                    if(isset($_SESSION['username'])) {
                                        echo "<button type=\"button\" class=\"btn btn-default cart\"><i class=\"fa fa-shopping-cart\"></i>  加入購物車</button>";
                                    } else {
                                        echo "<button type=\"button\" class=\"btn btn-default cart\" onclick=\"location.href='login.php'\"><i class=\"fa fa-shopping-cart\"></i>  加入購物車</button>";
                                    }
                                    ?>
                                </span>
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
                                <!-- <div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery1.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery2.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery3.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery4.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div> -->
                            </div>

                            <!-- <div class="tab-pane fade" id="companyprofile" >
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery1.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery3.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery2.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery4.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
							</div> -->

                            <!-- <div class="tab-pane fade" id="tag" >
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery1.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery2.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery3.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<img src="images/home/gallery4.jpg" alt="" />
												<h2>$56</h2>
												<p>Easy Polo Black Edition</p>
												<button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
											</div>
										</div>
									</div>
								</div>
							</div> -->

                            <div class="tab-pane fade" id="reviews">
                                <div class="col-sm-12">
                                    <!-- <h2>發表評論</h2><br> -->
                                    <ul>
                                        <li><a href=""><i class="fa fa-user"></i>匿名</a></li>
                                        <li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
                                        <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>

                                    </ul>
                                    <form action="#">
                                        <span>
                                            <input type="text"
                                                placeholder="你的名字     =͟͟͞͞ʕ•̫͡•ʔ=͟͟͞͞ʕ•̫͡•ʔ=͟͟͞͞ʕ•̫͡•ʔ" />
                                            <input type="email" placeholder="ʕ·ᴥ·ʔ 電子郵件" />
                                        </span>
                                        <textarea name="" placeholder="/ᐠ｡ꞈ｡ᐟ\   想說什麼..."></textarea>
                                        <b>評分: </b>
                                        <div class="rating-box">
                                            <fieldset class="rating">
                                                <input type="radio" id="star5" class="star_rating" name="star_rating"
                                                    value="5"><label class="full" for="star5" title="5 Stars"></label>
                                                <input type="radio" id="star4" class="star_rating" name="star_rating"
                                                    value="4"><label class="full" for="star4" title="4 Stars"></label>
                                                <input type="radio" id="star3" class="star_rating" name="star_rating"
                                                    value="3"><label class="full" for="star3" title="3 Stars"></label>
                                                <input type="radio" id="star2" class="star_rating" name="star_rating"
                                                    value="2"><label class="full" for="star2" title="2 Stars"></label>
                                                <input type="radio" id="star1" class="star_rating" name="star_rating"
                                                    value="1"><label class="full" for="star1" title="1 Stars"></label>
                                            </fieldset>
                                        </div>
                                        <button type="button" class="btn btn-default pull-right">
                                            提交
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--/category-tab-->

                    <div class="recommended_items">
                        <!--recommended_items-->
                        <h2 class="title text-center">推薦產品</h2>

                        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <div class="item active">
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="images/product-details/Pimg_0040.jpg" alt="" />
                                                    <h2>NTD 56</h2>
                                                    <p>桂花釀</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i
                                                            class="fa fa-shopping-cart"></i>加入購物車</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="images/product-details/Pimg_0038.jpg" alt="" />
                                                    <h2>NTD 56</h2>
                                                    <p>百香果汁</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i
                                                            class="fa fa-shopping-cart"></i>加入購物車</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="images/product-details/Pimg_0039.jpg" alt="" />
                                                    <h2>NTD 56</h2>
                                                    <p>玫瑰花釀</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i
                                                            class="fa fa-shopping-cart"></i>加入購物車</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="images/product-details/Pimg_0044.jpg" alt="" />
                                                    <h2>NTD 56</h2>
                                                    <p>百香果豆腐乳</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i
                                                            class="fa fa-shopping-cart"></i>加入購物車</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="images/product-details/Pimg_0045.jpg" alt="" />
                                                    <h2>NTD 56</h2>
                                                    <p>梅子豆腐乳</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i
                                                            class="fa fa-shopping-cart"></i>加入購物車</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="images/product-details/Pimg_0046.jpg" alt="" />
                                                    <h2>NTD 56</h2>
                                                    <p>鳳梨豆腐乳</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i
                                                            class="fa fa-shopping-cart"></i>加入購物車</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="left recommended-item-control" href="#recommended-item-carousel"
                                data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="right recommended-item-control" href="#recommended-item-carousel"
                                data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
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