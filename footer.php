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
</body>
</html>

