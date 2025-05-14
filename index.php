<?php
include('db_connection.php');
// 啟用錯誤報告
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 測試資料庫連接
if (isset($conn)) {
    echo "<!-- 資料庫連接成功 -->";
} else {
    echo "<!-- 資料庫連接失敗 -->";
}
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>彰化小禮坊</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
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
                                <li><a href="#"><i class="fa fa-phone"></i> 04 7263460</a></li>
                                <li><a href="#"><i class="fa fa-envelope"></i> hello@gm.ncue.edu.tw</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="social-icons pull-right">
                            <ul class="nav navbar-nav">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
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
                                } else {//若沒有登入 不管點甚麼都導入到登入頁面
                                    echo "<li><a href=\"login.php\"><i class=\"fa fa-lock\"></i> 登入</a></li>";
                                }
                                ?>
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
                                <li><a href="index.php" class="active">首頁</a></li>
                                <li class="dropdown"><a href="#">購物資訊<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <?php
                                        if(isset($_SESSION['username'])) {//若有登入導入到對應頁面
                                            echo "<li><a href=\"shop.php\">商品</a></li>";
                                            echo "<li><a href=\"checkout.html\">歷史訂單</a></li>";
                                            echo "<li><a href=\"cart.php\">購物車</a></li>";
                                        } else {
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
                                <?php
                                if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin')
                                    echo "<li><a href=\"db_admin.php\">資料庫管理</a></li>" //管理者才看的到這個
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="search_box pull-right">
                            <!-- <input type="text" placeholder="search" /> -->
                            <input type="text" placeholder="搜尋" />
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/header-bottom-->
    </header><!--/header-->

    <section id="slider"><!--slider-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#slider-carousel" data-slide-to="1"></li>
                            <li data-target="#slider-carousel" data-slide-to="2"></li>
                        </ol>

                        <div class="carousel-inner">
                            <div class="item active">
                                <div class="col-sm-6">
                                    <h1><span>彰化</span>小禮坊</h1>
                                    <h2>紅薏仁蕎麥</h2>
                                    <p>紅薏仁蕎麥兩盒超值組，養生加倍、優惠加倍，健康生活從每天開始！</p>
                                    <p>ฅ^•ﻌ•^ฅ</p>
                                    <a href="product-details.php?id=P_0057">
                                        <button type="button" class="btn btn-default get">心動價 180 NTD</button>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <img src="images/product-details/img1.png" class="girl img-responsive" alt="" />
                                    <!-- <img src="images/home/pricing.png" class="pricing" alt="" /> -->
                                </div>
                            </div>
                            <div class="item">
                                <div class="col-sm-6">
                                    <h1><span>彰化</span>小禮坊</h1>
                                    <h2>紅薏仁蕎麥x雪花片</h2>
                                    <p>紅薏仁蕎麥結合雪花片，營養豐富，口感酥脆，是健康又美味的每日首選！</p>
                                    <p>(=^-ω-^=)</p>
                                    <a href="product-details.php?id=P_0056">
                                        <button type="button" class="btn btn-default get">心動價 729 NTD</button>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <img src="images/product-details/img2.png" class="girl img-responsive" alt="" />
                                    <!-- <img src="images/home/pricing.png" class="pricing" alt="" /> -->
                                </div>
                            </div>
                        </div>

                        <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section><!--/slider-->

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <!-- <h2>Category</h2> -->
                        <h2>商品分類</h2>
                        <div class="panel-group category-products" id="accordian">
                            <!-- <div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordian" href="#sportswear">
											<span class="badge pull-right"><i class="fa fa-plus"></i></span>
											Sportswear
										</a>
									</h4>
								</div>
								<div id="sportswear" class="panel-collapse collapse">
									<div class="panel-body">
										<ul>
											<li><a href="#">Nike </a></li>
											<li><a href="#">Under Armour </a></li>
											<li><a href="#">Adidas </a></li>
											<li><a href="#">Puma</a></li>
											<li><a href="#">ASICS </a></li>
										</ul>
									</div>
								</div>
							</div> -->
                            <!-- <div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordian" href="#mens">
											<span class="badge pull-right"><i class="fa fa-plus"></i></span>
											Mens
										</a>
									</h4>
								</div>
								<div id="mens" class="panel-collapse collapse">
									<div class="panel-body">
										<ul>
											<li><a href="#">Fendi</a></li>
											<li><a href="#">Guess</a></li>
											<li><a href="#">Valentino</a></li>
											<li><a href="#">Dior</a></li>
											<li><a href="#">Versace</a></li>
											<li><a href="#">Armani</a></li>
											<li><a href="#">Prada</a></li>
											<li><a href="#">Dolce and Gabbana</a></li>
											<li><a href="#">Chanel</a></li>
											<li><a href="#">Gucci</a></li>
										</ul>
									</div>
								</div>
							</div> -->

                            <!-- <div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordian" href="#womens">
											<span class="badge pull-right"><i class="fa fa-plus"></i></span>
											Womens
										</a>
									</h4>
								</div>
								<div id="womens" class="panel-collapse collapse">
									<div class="panel-body">
										<ul>
											<li><a href="#">Fendi</a></li>
											<li><a href="#">Guess</a></li>
											<li><a href="#">Valentino</a></li>
											<li><a href="#">Dior</a></li>
											<li><a href="#">Versace</a></li>
										</ul>
									</div>
								</div>
							</div> -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="shop.php"><b>全部商品</b></a></h4>
                                    <hr />
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="shop.php?category=禮盒專區"><b>禮盒專區</b></a></h4>
                                    <hr />
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="shop.php?category=酒莊產品"><b>酒莊產品</b></a></h4>
                                    <hr />
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="shop.php?category=果汁系列"><b>果汁系列</b></a></h4>
                                    <hr />
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="shop.php?category=醬菜類(罐頭食品)"><b>醬菜類(罐頭食品)</b></a></h4>
                                </div>
                            </div>
                        </div>

                        <!--price-range-->
                        <!-- <div class="price-range">
							<h2>Price Range</h2>
							<div class="well text-center">
								<input type="text" class="span2" value="" data-slider-min="0" data-slider-max="600"
									data-slider-step="5" data-slider-value="[250,450]" id="sl2"><br />
								<b class="pull-left">$ 0</b> <b class="pull-right">$ 600</b>
							</div>
						</div> -->
                        <!--/price-range-->
                        <!-- 左側圖片 -->
                        <!-- <div class="shipping text-center">shipping -->
                        <img src="./images/home/vegetable.png" alt="" />
                        <!-- <img src="#" alt="這裡可以放圖片 只需將alt刪掉 src加上連結" /> -->
                        <!-- </div>/shipping -->

                    </div>
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="features_items">
                        <!-- <h2 class="title text-center">Features Items</h2> -->
                        <h2 class="title text-center">特色商品</h2>
                        <?php
                        // 查詢特色商品
                        // 自選6個特定商品ID來顯示
                        $featured_product_ids = array('P_0040', 'P_0041', 'P_0042', 'P_0043', 'P_0044', 'P_0045'); // 可以替換為任何您想要顯示的商品ID

                        // 將陣列轉換為SQL安全的格式 
                        $featured_ids_escaped = array();
                        foreach ($featured_product_ids as $id) {
                            $featured_ids_escaped[] = "'" . mysqli_real_escape_string($conn, $id) . "'";
                        }
                        $ids_list = implode(',', $featured_ids_escaped);

                        $feature_sql = "SELECT product_info.product_id, product_info.product_name, product_info.price, product_img.img_url 
                                        FROM product_info 
                                        LEFT JOIN product_img ON product_info.product_id = product_img.product_id
                                        WHERE product_info.product_id IN ($ids_list)
                                        ORDER BY FIELD(product_info.product_id, $ids_list)";

                        $feature_result = mysqli_query($conn, $feature_sql) or die("SQL錯誤：" . mysqli_error($conn));

                        // 顯示特色商品
                        while ($row = mysqli_fetch_assoc($feature_result)) {
                            echo '<div class="col-sm-4">';
                            echo '<div class="product-image-wrapper">';
                            echo '<div class="single-products">';
                            echo '<div class="productinfo text-center">';
                            echo '<img src="' . htmlspecialchars($row['img_url']) . '" alt="" style="width:250px; height:250px;" />';
                            echo '<h2>' . htmlspecialchars($row['price']) . ' NTD</h2>';
                            echo '<p>' . htmlspecialchars($row['product_name']) . '</p>';
                            echo '<a href="product-details.php?id=' . urlencode($row['product_id']) . '" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>加入購物車</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="choose">';
                            echo '<ul class="nav nav-pills nav-justified">';
                            echo '<li><a href="blog.html"><i class="fa fa-plus-square"></i>查看評價</a></li>';
                            echo '</ul>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                        <!-- <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/product-details/img1.png" alt="" />
                                        <h2>99 NTD</h2>
                                        <p>紅薏仁蕎麥x雪花片</p>
                                        <a href="product-details.php" class="btn btn-default add-to-cart">
                                            <i class="fa fa-shopping-cart"></i>加入購物車</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="blog.html"><i class="fa fa-plus-square"></i>查看評價</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/product-details/img2.png" alt="" />
                                        <h2>99 NTD</h2>
                                        <p>紅薏仁蕎麥</p>
                                        <a href="product-details.php" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>加入購物車</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="blog.html"><i class="fa fa-plus-square"></i>查看評價</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/product-details/img3.png" alt="" />
                                        <h2>99 NTD</h2>
                                        <p>蕎麥水果脆片x雪花片</p>
                                        <a href="product-details.php" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>加入購物車</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="blog.html"><i class="fa fa-plus-square"></i>查看評價</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/product-details/img4.png" alt="" />
                                        <h2>120 NTD</h2>
                                        <p>紅薏仁蕎麥x養生粉</p>
                                        <a href="product-details.php" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>加入購物車</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="blog.html"><i class="fa fa-plus-square"></i>查看評價</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/product-details/img5.png" alt="" />
                                        <h2>110 NTD</h2>
                                        <p>蕎麥x紅薏仁</p>
                                        <a href="product-details.php" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>加入購物車</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="blog.html"><i class="fa fa-plus-square"></i>查看評價</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/product-details/img6.png" alt="" />
                                        <h2>150 NTD</h2>
                                        <p>蕎麥x紅薏仁超值組合</p>
                                        <a href="product-details.php" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>加入購物車</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="blog.html"><i class="fa fa-plus-square"></i>查看評價</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="images/product-details/Pimg_0001.jpg" alt="" />
                                        <h2>850 NTD</h2>
                                        <p>年節禮盒</p>
                                        <a href="product-details.php" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>加入購物車</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="blog.html"><i class="fa fa-plus-square"></i>查看評價</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div> -->
                        


                    </div><!--features_items-->
                </div>
            </div>
        </div>
    </section>

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

    </footer><!--/Footer-->



    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/price-range.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>

</html>