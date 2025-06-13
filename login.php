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
// 專門用於註冊的處理邏輯 (放在 login.php 的 PHP 部分)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register_username'])) {
    try {
        // 收集並清理表單數據
        $username = trim($_POST['register_username']);
        $email = trim($_POST['register_email']);
        $password = $_POST['register_password'];
        $phone = trim($_POST['register_tel']);
        
        // 設定時區並獲取註冊時間
        date_default_timezone_set("Asia/Taipei");
        $register_date = date("Y-m-d H:i:s");

        // 伺服器端驗證
        if (strlen($username) < 4 || strlen($username) > 10) {
            echo "<script>alert('使用者名稱必須介於4-10個字之間');</script>";
            goto end_register;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('請輸入有效的電子郵件地址');</script>";
            goto end_register;
        }

        if (strlen($password) < 6) {
            echo "<script>alert('密碼長度至少需要6個字符');</script>";
            goto end_register;
        }

        if (!preg_match('/^09\d{8}$/', $phone)) {
            echo "<script>alert('請輸入有效的手機號碼');</script>";
            goto end_register;
        }

        // 檢查帳號是否已存在
        $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM members WHERE username = ?");
        if (!$check_stmt) {
            throw new Exception("準備查詢語句失敗: " . $conn->error);
        }
        
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_row = $check_result->fetch_assoc();

        if ($check_row['count'] > 0) {
            echo "<script>alert('此帳號已被使用，請選擇其他帳號名稱');</script>";
            $check_stmt->close();
            goto end_register;
        }
        $check_stmt->close();

        // 檢查電子郵件是否已存在
        $check_email_stmt = $conn->prepare("SELECT COUNT(*) as count FROM members WHERE email = ?");
        if (!$check_email_stmt) {
            throw new Exception("準備查詢語句失敗: " . $conn->error);
        }
        
        $check_email_stmt->bind_param("s", $email);
        $check_email_stmt->execute();
        $email_result = $check_email_stmt->get_result();
        $email_row = $email_result->fetch_assoc();

        if ($email_row['count'] > 0) {
            echo "<script>alert('此電子郵件已被註冊');</script>";
            $check_email_stmt->close();
            goto end_register;
        }
        $check_email_stmt->close();

        // 取得新會員ID
        $stmt_id = $conn->prepare("SELECT COALESCE(MAX(member_id), 0) + 1 AS new_id FROM members");
        if (!$stmt_id) {
            throw new Exception("準備查詢語句失敗: " . $conn->error);
        }
        
        $stmt_id->execute();
        $result_id = $stmt_id->get_result();
        $row = $result_id->fetch_assoc();
        $new_member_id = $row['new_id'];
        $stmt_id->close();

        // 密碼加密 (建議使用 password_hash)
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // 如果你的系統還沒有準備使用加密，可以暫時保持明文，但建議盡快改為加密
        
        // 寫入資料庫
        $stmt = $conn->prepare("INSERT INTO members (member_id, username, email, password, phone, register_date) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("準備插入語句失敗: " . $conn->error);
        }
        
        $stmt->bind_param("isssss", $new_member_id, $username, $email, $password, $phone, $register_date);

        if ($stmt->execute()) {
            echo "<script>
                alert('註冊成功！請使用您的帳號密碼登入');
                // 自動切換到登入面板
                document.addEventListener('DOMContentLoaded', function() {
                    const container = document.querySelector('.container_login');
                    if (container) {
                        container.classList.remove('active');
                    }
                });
            </script>";
        } else {
            throw new Exception("執行插入失敗: " . $stmt->error);
        }
        
        $stmt->close();

    } catch (Exception $e) {
        // 記錄錯誤到日誌檔
        error_log("註冊錯誤: " . $e->getMessage(), 3, 'error.log');
        echo "<script>alert('註冊時發生錯誤，請稍後再試');</script>";
    }
    
    end_register:
    // 標記結束，避免重複處理
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

</head>
<!--/head-->

<body>
    <?php include('header.php'); ?>

    <section id="form">
        <!--form-->
        <div class="container">
            <div class="container_login">
                <div class="form-box login">
                    <form method="POST" action="login.php" id="login_form">
                        <h1>登入</h1>
                        <div class="input-box">
                            <input type="text" id="account_login" name="login_username" placeholder="請輸入使用者名稱" required
                                minlength="4" maxlength="10">
                            <i class='bx bxs-user'></i>
                            <span class="error-message"></span>
                        </div>
                        <div class="input-box">
                            <input type="password" id="pwd_login" name="login_password" placeholder="請輸入密碼" required
                                minlength="6" maxlength="12">
                            <i class='bx bxs-lock-alt'></i>
                            <span class="error-message"></span>
                        </div>
                        <!-- <div class="checkbox-container">
                            <input type="checkbox" class="checkbox">
                            <span>記住帳號密碼</span>
                        </div> -->
                        <button type="submit" class="btn" name="login">登入</button>
                    </form>
                </div>
                <!--註冊-->
                <div class="form-box register">
                    <form action="login.php" method="POST" id="input_form">
                        <h1>註冊</h1>
                        <div class="input-box">
                            <input type="text" id="account_input" name="register_username" placeholder="請輸入使用者名稱"
                                required minlength="4" maxlength="10">
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
                            <input type="password" id="pwd_input" name="register_password" placeholder="請輸入密碼" required
                                maxlength="16">
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
    </section>
    <!--/form-->


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

    </footer>
    <!--/Footer-->



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
<?php $conn->close(); ?>