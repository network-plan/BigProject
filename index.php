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
<!--/head-->

<body>
    <?php include('header.php'); ?>

    <section id="slider">
        <!--slider-->
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
                                    <a href="product-details.php?id=P_0056">
                                        <button type="button" class="btn btn-default get">心動價 160 NTD</button>
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
                                    <a href="product-details.php?id=P_0057">
                                        <button type="button" class="btn btn-default get">心動價 160 NTD</button>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <img src="images/product-details/img2.png" class="girl img-responsive" alt="" />
                                    <!-- <img src="images/home/pricing.png" class="pricing" alt="" /> -->
                                </div>
                            </div>
                            <div class="item">
                                <div class="col-sm-6">
                                    <h1><span>彰化</span>小禮坊</h1>
                                    <h2>紅薏仁x水果脆片</h2>
                                    <p>紅薏仁搭配天然水果脆片，香脆可口，營養滿分，養生與美味一次滿足！</p>
                                    <p>ฅ^•ﻌ•^ฅ</p>
                                    <a href="product-details.php?id=P_0058">
                                        <button type="button" class="btn btn-default get">心動價 160 NTD</button>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <img src="images/product-details/img3.png" class="girl img-responsive" alt="" />
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
    </section>
    <!--/slider-->

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <!-- <h2>Category</h2> -->
                        <h2>商品分類</h2>
                        <div class="panel-group category-products" id="accordian">
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
                                    <h4 class="panel-title"><a href="shop.php?category=醬菜類(罐頭食品)"><b>醬菜類(罐頭食品)</b></a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <img src="./images/home/vegetable.png" alt="" />

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
                            //echo '<a href="product-details.php?id=' . urlencode($row['product_id']) . '" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>加入購物車</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="choose">';
                            echo '<ul class="nav nav-pills nav-justified">';
                            echo '<li><a href="product-details.php?id=' . urlencode($row['product_id']) . '" class="btn btn-default add-to-cart"><i class="fa fa-plus-square"></i>詳細資料</a></li>';
                            echo '</ul>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <!--features_items-->
                </div>
            </div>
        </div>
    </section>
    <?php include('footer.php'); ?>
</body>

</html>
