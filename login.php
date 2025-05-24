
<?php
include('db_connection.php');
// 啟用錯誤報告
ini_set('display_errors', 1);
session_start();
error_reporting(E_ALL);

// 測試資料庫連接
if (isset($conn)) {
    echo "<!-- 資料庫連接成功 -->";
} else {
    echo "<!-- 資料庫連接失敗 -->";
}

// 專門用於註冊的處理邏輯
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register_username'])) {
    try {
        
        
        // 收集表單數據
        $username = $_POST['register_username'];
        $email = $_POST['register_email'];
        $password = $_POST['register_password'];
        $phone = $_POST['register_tel'];
        date_default_timezone_set("Asia/Taipei");
        $register_date = date("Y-m-d H:i:s");
        
        // 取得新會員ID
        $stmt_id = $conn->prepare("SELECT COALESCE(MAX(member_id), 0) + 1 AS new_id FROM members");
        $stmt_id->execute();
        $result_id = $stmt_id->get_result();
        $row = $result_id->fetch_assoc();
        $new_member_id = $row['new_id'];
        
        // 寫入資料庫
        $stmt = $conn->prepare("INSERT INTO members (member_id, username, email, password, phone, register_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $new_member_id, $username, $email, $password, $phone, $register_date);
        
        $success = $stmt->execute();
        if ($success) {
            echo "<script>alert('註冊成功！');</script>";
        } else {
            echo "<script>alert('註冊失敗: " . $stmt->error . "');</script>";
        }
    } catch (Exception $e) {
        file_put_contents('error.log', $e->getMessage(), FILE_APPEND);
        echo "<script>alert('發生錯誤: " . $e->getMessage() . "');</script>";
    }
}

//專門用於登入的處理邏輯
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login_username'])){
	
	// 輸出除錯信息
	file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);
	
	$username = $_POST['login_username'];
	$password = $_POST['login_password'];

	// 查詢資料表確認帳號密碼是否存在
	$stmt = $conn->prepare("SELECT username, password, member_id FROM members WHERE username = ? AND password = ?");
	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc(); // 取得查詢結果
		
		$_SESSION['username'] = $row['username'];
		$_SESSION['member_id'] = $row['member_id']; 
		
		// 檢查是否為管理者
		if ($row['username'] === 'admin' && $password === 'admin123456') {
			$_SESSION['role'] = 'admin';
		} else {
			$_SESSION['role'] = 'user';
		}

		setcookie("cart", "", time() - 3600, "/");
		header("Location: index.php");
		exit();
	} else {
		echo "<script>alert('帳號或密碼錯誤');</script>";
	}

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
							<a href="index.php"><img src="images/home/logo.png" alt="" /></a>
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
								<li><a href="index.php" class="active">首頁</a></li>
								<!-- <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a> -->
								<li class="dropdown"><a href="#">購物資訊<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
										<!-- <li><a href="shop.php">Products</a></li> -->
										<li><a href="shop.php">商品</a></li>
									</ul>
								</li>
								<li class="dropdown"><a href="#">評價<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
										<li><a href="blog.html">商品評價列表</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-bottom-->
	</header><!--/header-->
	
	<section id="form"><!--form-->
		<div class="container">
			<div class="container_login">
				<div class="form-box login">
					<form method="POST" action="login.php" id="login_form">
						<h1>登入</h1>
						<div class="input-box">
							<input type="text"  id="account_login" name="login_username" placeholder="請輸入使用者名稱" required minlength="4" maxlength="10">
							<i class='bx bxs-user'></i>
							<span class="error-message"></span>
						</div>
						<div class="input-box">
							<input type="password" id="pwd_login" name="login_password" placeholder="請輸入密碼" required minlength="6" maxlength="12">
							<i class='bx bxs-lock-alt' ></i>
							<span class="error-message"></span>
						</div>
						<div class="checkbox-container">
							<input type="checkbox" class="checkbox">
							<span>記住帳號密碼</span>
						</div>
						<button type="submit" class="btn" name="login">登入</button>
					</form>
				</div>
				<!--註冊-->
				<div class="form-box register">
                <form action="login.php" method="POST" id="input_form">
                    <h1>註冊</h1>
                    <div class="input-box">
                        <input type="text" id="account_input" name="register_username" placeholder="請輸入使用者名稱" required minlength="4" maxlength="10">
                        <i class='bx bxs-user'></i>
                        <span class="error-message"></span>
                    </div>
                    <div class="input-box">
                        <input type="email" id="email_input" name="register_email" placeholder="請輸入電子郵件地址" required>
                        <i class='bx bxs-envelope'></i>
                        <span class="error-message"></span>
                    </div>
                    <div class="input-box">
                        <input type="tel" id="tel_input" name="register_tel" placeholder="請輸入電話號碼" required>
                        <i class='bx bxs-envelope'></i>
                        <span class="error-message"></span>
                    </div>
                    <div class="input-box">
                        <input type="password" id="pwd_input" name="register_password" placeholder="請輸入密碼" required maxlength="16">
                        <i class='bx bxs-lock-alt'></i>
                        <span class="error-message"></span>
                    </div>
                    <div class="input-box">
                        <input type="password" id="pwd2_input" placeholder="確認密碼" required maxlength="16">
                        <i class='bx bxs-lock-alt'></i>
                        <span class="error-message"></span>
                    </div>
                    <button type="submit" class="btn" name="register">註冊</button>
                </form>
				</div>
				<!--HI-->
				<div class="toggle-box">
					<div class="toggle-panel toggle-left">
						<h1>初次見面!</h1>
						<p>還沒註冊?</p>
						<button class="btn register-btn">註冊</button>
					</div>
		
					<div class="toggle-panel toggle-right">
						<h1>歡迎回來!</h1>
						<p>已經有帳號?</p>
						<button class="btn login-btn">登入</button>
					</div>
				</div>
			</div>
		</div>
	</section><!--/form-->
	
	
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
