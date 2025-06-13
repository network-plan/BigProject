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
    <title>彰化小禮坊</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/price-range.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</head>
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
                                    echo "<li><a href=\"historical_orders.php\"><i class=\"fa fa-crosshairs\"></i> 查看歷史訂單</a></li>";//若有登入導入到歷史訂單頁面
                                    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
                                    $total_items = 0;
                                    // 統計購物車中所有商品的「數量總和」
                                    foreach ($cart as $quantity) {
                                        $total_items += $quantity;
                                    }
                                    echo "<li><a href=\"cart.php\"><i class=\"fa fa-shopping-cart\"></i> 購物車" . " (" . $total_items . ")</a></li>";//若有登入導入到購物車頁面
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
                                <li><a href="index.php" class="active">首頁</a></li>
                                <li class="dropdown"><a href="#">購物資訊<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <?php
                                        if(isset($_SESSION['username']) && $_SESSION['role'] != 'admin') {//若一般會員登入導入到對應頁面
                                            echo "<li><a href=\"shop.php\">商品</a></li>";
                                            echo "<li><a href=\"historical_orders.php\">歷史訂單</a></li>";
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
                                    //     echo "<li class=\"dropdown\"><a href=\"#\">評價<i class=\"fa fa-angle-down\"></i></a>";
                                    //     echo "<ul role=\"menu\" class=\"sub-menu\">";
                                    //     echo "    <li><a href=\"blog.html\">商品評價列表</a></li>";
                                    //     echo "</ul>";
                                    // echo "</li>";
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
</body>
</html>