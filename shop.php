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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>所有商品列表 | 彰化小禮坊</title>
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
                    <div class="col-sm-6 ">
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

                                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                                    <!-- language -->
                                    語言

                                    <span class="caret"></span>
                                </button>
                                <!-- <ul class="dropdown-menu">
									<li><a href="">Chinese</a></li>
									<li><a href="">USA</a></li>
								</ul> -->
                                <ul class="dropdown-menu">

                                    <li><a href="">中文</a></li>
                                    <!-- <li><a href="">英文</a></li> -->

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
                                <li><a href="login.php"><i class="fa fa-lock"></i> 登入</a></li>
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
                                        <!-- <li><a href="blog-single.html">單一商品評價</a></li> -->
                                    </ul>
                                </li>
                                <!-- <li><a href="contact-us.html">Contact</a></li> -->
                                <li><a href="contact-us.html">聯絡我們</a></li>
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
    </header>

    <section id="advertisement">
        <div class="container">
            <img src="images/shop/advertisement.jpg" alt="" />
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <h2>商品分類</h2>
                        <div class="panel-group category-products" id="accordian"><!--category-productsr-->
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
                        </div><!--/category-productsr-->

                        <!-- <div class="shipping text-center">shipping -->
                        <img src="./images/home/vegetable.png" alt="images/home/shipping.jpg" />
                        <!-- </div>/shipping -->

                    </div>
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="features_items"><!--features_items-->
                        <h2 class="title text-center">特色項目</h2>
                        
                        <?php
                        $sql = "SELECT product_info.product_id, product_info.product_name, product_info.price, product_img.img_url FROM product_info LEFT JOIN product_img ON product_info.product_id = product_img.product_id";
                        $result = mysqli_query($conn, $sql) or die("SQL錯誤：" . mysqli_error($conn));

                        while ($row = mysqli_fetch_assoc($result)) {
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

                        
                    </div><!--features_items-->
                    <ul class="pagination">
                            <li class="active"><a href="">1</a></li>
                            <li><a href="">2</a></li>
                            <li><a href="">3</a></li>
                            <li><a href="">&raquo;</a></li>
                        </ul>
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
    <script src="js/price-range.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>

</html>