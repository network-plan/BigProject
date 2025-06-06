<?php
include('db_connection.php');
session_start();

// 檢查用戶是否已登入
include('check_login.php');//檢查登入

$username = $_SESSION['username'];
$error_message = "";
$success_message = "";

// 處理密碼修改表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'change_password') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 驗證當前密碼是否正確
    $query = "SELECT password FROM members WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("資料庫查詢失敗: " . mysqli_error($conn));
    }
    
    $user_data = mysqli_fetch_assoc($result);
    
    // 驗證當前密碼是否正確
    if ($user_data['password'] != $current_password) {
        $error_message = "當前密碼不正確";
    } 
    // 確認新密碼與確認密碼是否相符
    else if ($new_password != $confirm_password) {
        $error_message = "新密碼與確認密碼不相符";
    } 
    // 檢查新密碼長度
    else if (strlen($new_password) < 6) {
        $error_message = "新密碼至少需要6個字符";
    } 
    // 更新密碼
    else {
        $update_query = "UPDATE members SET password = '$new_password' WHERE username = '$username'";
        $update_result = mysqli_query($conn, $update_query);
        
        if ($update_result) {
            $success_message = "密碼已成功更新";
        } else {
            $error_message = "密碼更新失敗: " . mysqli_error($conn);
        }
    }
    
    mysqli_free_result($result);
}

// 處理個人資料修改表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
    $new_username = trim($_POST['new_username']);
    $new_email = trim($_POST['new_email']);
    $new_phone = trim($_POST['new_phone']);
    
    // 驗證輸入
    $validation_errors = array();
    
    // 驗證客戶名稱
    if (empty($new_username)) {
        $validation_errors[] = "客戶名稱不能為空";
    } else if (strlen($new_username) < 3) {
        $validation_errors[] = "客戶名稱至少需要4個字符";
    } else if ($new_username != $username) {
        // 檢查新用戶名是否已存在
        $check_username_query = "SELECT username FROM members WHERE username = '$new_username' AND username != '$username'";
        $check_result = mysqli_query($conn, $check_username_query);
        if (mysqli_num_rows($check_result) > 0) {
            $validation_errors[] = "此客戶名稱已被使用";
        }
        mysqli_free_result($check_result);
    }
    
    // 驗證電子郵件
    if (empty($new_email)) {
        $validation_errors[] = "電子郵件不能為空";
    } else if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $validation_errors[] = "請輸入有效的電子郵件格式";
    } else {
        // 檢查新郵件是否已存在（排除當前用戶）
        $current_user_query = "SELECT email FROM members WHERE username = '$username'";
        $current_user_result = mysqli_query($conn, $current_user_query);
        $current_user_data = mysqli_fetch_assoc($current_user_result);
        
        if ($new_email != $current_user_data['email']) {
            $check_email_query = "SELECT email FROM members WHERE email = '$new_email' AND username != '$username'";
            $check_email_result = mysqli_query($conn, $check_email_query);
            if (mysqli_num_rows($check_email_result) > 0) {
                $validation_errors[] = "此電子郵件已被使用";
            }
            mysqli_free_result($check_email_result);
        }
        mysqli_free_result($current_user_result);
    }
    
    // 驗證電話號碼
    if (empty($new_phone)) {
        $validation_errors[] = "電話號碼不能為空";
    } else if (!preg_match('/^[0-9]{10}$/', $new_phone)) {
        $validation_errors[] = "電話號碼格式不正確（需要10位數字）";
    }
    
    // 如果有驗證錯誤，顯示錯誤訊息
    if (!empty($validation_errors)) {
        $error_message = implode("<br>", $validation_errors);
    } else {
        // 更新個人資料
        $update_profile_query = "UPDATE members SET username = '$new_username', email = '$new_email', phone = '$new_phone' WHERE username = '$username'";
        $update_profile_result = mysqli_query($conn, $update_profile_query);
        
        if ($update_profile_result) {
            // 如果用戶名有變更，需要更新session
            if ($new_username != $username) {
                $_SESSION['username'] = $new_username;
                $username = $new_username;
            }
            $success_message = "個人資料已成功更新";
        } else {
            $error_message = "個人資料更新失敗: " . mysqli_error($conn);
        }
    }
}

// 獲取用戶信息
$query = "SELECT * FROM members WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("資料庫查詢失敗: " . mysqli_error($conn));
}

$user_data = mysqli_fetch_assoc($result);

// 釋放結果集
mysqli_free_result($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>資料 | 彰化小禮坊</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link href="css/personal_info.css" rel="stylesheet">
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
								echo "<li><a href=\"logout.php\"><i class=\"fa fa-lock\"></i> 登出</a></li>"; //若有登入導入到登出頁面
								echo "<li><a href=\"historical_orders.php\"><i class=\"fa fa-crosshairs\"></i> 查看歷史訂單</a></li>"; //若有登入導入到歷史訂單頁面
								echo "<li><a href=\"cart.php\"><i class=\"fa fa-shopping-cart\"></i> 購物車</a></li>"; //若有登入導入到購物車頁面
								echo "<li><a href=\"profile.php\"><i class=\"fa fa-user\"></i> " . $_SESSION['username'] . "</a></li>"; //顯示會員名稱 點下去即到個人資料頁面(未做)
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
                                <span class="sr-only">個人資料</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="mainmenu pull-left">
                            <ul class="nav navbar-nav collapse navbar-collapse">
                                <!-- <li><a href="index.html" class="active">Home</a></li> -->
                                <li><a href="index.php" class="active">首頁</a></li>
                                <!-- <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a> -->
                                <li class="dropdown"><a href="#">購物資訊<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <!-- <li><a href="shop.html">Products</a></li> -->
                                        <li><a href="shop.php">商品</a></li>
                                        <!-- <li><a href="checkout.html">Checkout</a></li> -->
                                        <li><a href="historical_orders.php">歷史訂單</a></li>
                                        <!-- <li><a href="cart.html">Cart</a></li> -->
                                        <li><a href="cart.html">購物車</a></li>
                                        <!-- <li><a href="login.html">Login</a></li> -->
                                        <!-- <li><a href="login.php">登入</a></li> -->
                                    </ul>
                                </li>
                                <!-- <li class="dropdown"><a href="#">評價<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
										<li><a href="blog.html">商品評價列表</a></li>
										<li><a href="blog-single.html">單一商品評價</a></li>
									</ul>
								</li> -->
                                <!-- <li><a href="contact-us.html">Contact</a></li> -->
                                <!-- <li><a href="contact-us.html">聯絡我們</a></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="search_box pull-right">
                            <!-- <input type="text" placeholder="search" /> -->
                            <!-- <input type="text" placeholder="搜尋" /> -->
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
                <h2 class="title text-center">個人資料</h2>
                <div class="col-sm-3">
                    <!-- 左側欄位 - 可以考慮增加其他會員功能 -->
                </div>
                <div class="col-sm-9">
                    <div class="blog-post-area">
                        <div class="single-blog-post">
                            <!-- 顯示成功或錯誤訊息 -->
                            <?php if(!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                            <?php endif; ?>

                            <?php if(!empty($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo $success_message; ?>
                            </div>
                            <?php endif; ?>

                            <h3>個人資料</h3>
                            <div class="profile-info">
                                <div class="info-item">
                                    <label>客戶名稱：</label>
                                    <span><?php echo htmlspecialchars($user_data['username']); ?></span>
                                </div>
                                <div class="info-item">
                                    <label>密碼：</label>
                                    <span id="password">********</span>
                                    <button type="button" class="btn btn-xs btn-default" onclick="togglePassword()"
                                        data-password="<?php echo htmlspecialchars($user_data['password']); ?>">查看</button>
                                    <button type="button" class="btn btn-xs btn-default"
                                        onclick="togglePasswordForm()">修改</button>
                                </div>
                                <div class="info-item">
                                    <label>電子郵件：</label>
                                    <span><?php echo htmlspecialchars($user_data['email']); ?></span>
                                </div>
                                <div class="info-item">
                                    <label>電話號碼：</label>
                                    <span><?php echo htmlspecialchars($user_data['phone']); ?></span>
                                </div>
                                <div class="info-item">
                                    <label>註冊日期：</label>
                                    <span><?php echo htmlspecialchars($user_data['register_date']); ?></span>
                                </div>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-primary" onclick="toggleEditForm()">編輯個人資料</button>
                                </div>
                            </div>

                            <!-- 個人資料編輯表單 -->
                            <div id="editForm" class="edit-form">
                                <h3>編輯個人資料</h3>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="action" value="update_profile">
                                    <div class="form-group">
                                        <label for="new_username">客戶名稱</label>
                                        <input type="text" class="form-control" id="new_username"
                                            name="new_username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                                        <small class="form-text text-muted">客戶名稱至少4個字符</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_email">電子郵件</label>
                                        <input type="email" class="form-control" id="new_email"
                                            name="new_email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_phone">電話號碼</label>
                                        <input type="tel" class="form-control" id="new_phone"
                                            name="new_phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
                                        <small class="form-text text-muted">請輸入10位數字</small>
                                    </div>
                                    <div class="btn-container">
                                        <button type="submit" class="btn btn-primary">更新資料</button>
                                        <button type="button" class="btn btn-default"
                                            onclick="toggleEditForm()">取消</button>
                                    </div>
                                </form>
                            </div>

                            <!-- 密碼修改表單 -->
                            <div id="passwordForm" class="password-form">
                                <h3>修改密碼</h3>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="action" value="change_password">
                                    <div class="form-group">
                                        <label for="current_password">當前密碼</label>
                                        <input type="password" class="form-control" id="current_password"
                                            name="current_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">新密碼</label>
                                        <input type="password" class="form-control" id="new_password"
                                            name="new_password" required>
                                        <small class="form-text text-muted">密碼至少6個字符</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">確認新密碼</label>
                                        <input type="password" class="form-control" id="confirm_password"
                                            name="confirm_password" required>
                                    </div>
                                    <div class="btn-container">
                                        <button type="submit" class="btn btn-primary">更新密碼</button>
                                        <button type="button" class="btn btn-default"
                                            onclick="togglePasswordForm()">取消</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--/blog-post-area-->
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
    <script src="js/profile.js"></script>
    
</body>

</html>