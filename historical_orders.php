<?php
include('db_connection.php');
session_start();
include('check_login.php');

// 獲取當前登入用戶的訂單資料
$username = $_SESSION['username']; // 假設session中存儲了用戶名
$sql = "SELECT DISTINCT * FROM orders WHERE username = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

// 為每個訂單獲取商品詳細資訊
foreach ($orders as &$order) {
    //
    // 1. 抓取這筆訂單的 order_items 並關聯商品資訊
    //
$sql_items = <<<SQL
SELECT
    oi.number           AS item_number,
    oi.order_id         AS order_id,
    oi.product_id       AS product_id,
    oi.quantity         AS quantity,
    p.product_name      AS product_name,
    p.price             AS price,
    p.full_description  AS description  -- 或 p.short_description
FROM order_items AS oi
LEFT JOIN product_info AS p
    ON oi.product_id = p.product_id
WHERE oi.order_id = ?
ORDER BY oi.number
SQL;

    $stmt = $conn->prepare($sql_items);
    if ($stmt === false) {
        die('Prepare failed (order_items): ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $order['order_id']);
    $stmt->execute();
    $order['items'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    //
    // 2. 為每個商品獲取圖片和評論狀態
    //
    foreach ($order['items'] as &$item) {
        // 獲取商品圖片
        $sql_img = "SELECT img_url FROM product_img WHERE product_id = ? LIMIT 1";
        $stmt_img = $conn->prepare($sql_img);
        if ($stmt_img) {
            $stmt_img->bind_param("s", $item['product_id']);
            $stmt_img->execute();
            $img_result = $stmt_img->get_result();
            $img_row = $img_result->fetch_assoc();
            $item['image_path'] = $img_row ? $img_row['img_url'] : '';
            $stmt_img->close();
        } else {
            $item['image_path'] = '';
        }

        // 檢查是否已經評論過這個商品
        $sql_check_review = "SELECT COUNT(*) as review_count FROM reviews WHERE user_id = ? AND product_id = ?";
        $stmt_review = $conn->prepare($sql_check_review);
        if ($stmt_review) {
            $stmt_review->bind_param("ss", $order['member_id'], $item['product_id']);
            $stmt_review->execute();
            $review_result = $stmt_review->get_result();
            $review_row = $review_result->fetch_assoc();
            $item['has_review'] = $review_row['review_count'] > 0;
            $stmt_review->close();
        } else {
            $item['has_review'] = false;
        }
    }

    //
    // 3. 抓取這筆訂單使用者（member）在 reviews 裡的評論
    //
    $sql_reviews = <<<SQL
SELECT
    review_id   AS review_id,
    user_id     AS user_id,
    username    AS reviewer_name,
    rating      AS rating,
    content     AS content
FROM reviews
WHERE user_id = ?
ORDER BY review_id DESC
SQL;

    $stmt = $conn->prepare($sql_reviews);
    if ($stmt === false) {
        die('Prepare failed (reviews): ' . htmlspecialchars($conn->error));
    }
    // 用 orders.member_id 對應 reviews.user_id
    $stmt->bind_param("s", $order['member_id']);
    $stmt->execute();
    $order['reviews'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

//顯示$order陣列
// echo "<pre>";
// print_r($orders);
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>歷史訂單</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/prettyPhoto.css" rel="stylesheet">
	<link href="css/price-range.css" rel="stylesheet">
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
	<link href="css/product-details.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://jci.book.com.tw/css/books/product/overlay-n.css">
	<link rel="stylesheet" href="https://jci.book.com.tw/css/css.css">
	<link rel="stylesheet" href="css/historical_orders.css">
	<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
	<link rel="shortcut icon" href="images/ico/favicon.ico">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
	<style>
		.order-group {
			border: 1px solid #ddd;
			margin-bottom: 20px;
			padding: 15px;
			border-radius: 5px;
			background-color: #f9f9f9;
		}

		.order-header {
			background-color: #fff;
			padding: 15px;
			border-radius: 5px;
			margin-bottom: 15px;
			border: 1px solid #e0e0e0;
		}

		.order-header h3 {
			color: #333;
			margin-bottom: 10px;
		}

		.order-header p {
			margin: 5px 0;
			color: #666;
		}

		.status-shipped {
			color: #28a745;
			font-weight: bold;
		}

		.status-processing {
			color: #ffc107;
			font-weight: bold;
		}

		.status-preparing {
			color: #17a2b8;
			font-weight: bold;
		}

		.product-item {
			border: 1px solid #e0e0e0;
			margin-bottom: 10px;
			padding: 10px;
			background-color: #fff;
			border-radius: 5px;
		}

		.product-item .media-object {
			width: 80px;
			height: 80px;
			object-fit: cover;
		}

		.product-info {
			flex: 1;
		}

		.product-name {
			font-weight: bold;
			color: #333;
		}

		.product-price {
			color: #e74c3c;
			font-weight: bold;
		}

		.product-quantity {
			color: #666;
		}

		.review-btn {
			margin-top: 10px;
		}

		.review-form {
			display: none;
			margin-top: 15px;
			padding: 15px;
			background-color: #f8f9fa;
			border-radius: 5px;
			border: 1px solid #dee2e6;
		}

		.review-form.show {
			display: block;
		}

		.already-reviewed {
			background-color: #d4edda;
			color: #155724;
			padding: 5px 10px;
			border-radius: 5px;
			font-size: 12px;
		}

		/* 評分星星樣式 */
		.rating {
			display: inline-block;
			position: relative;
			height: 25px;
			line-height: 25px;
			font-size: 20px;
		}

		.rating input {
			display: none;
		}

		.rating label {
			color: #ddd;
			float: right;
			cursor: pointer;
			margin-left: 5px;
		}

		.rating label:before {
			content: '★';
		}

		.rating input:checked ~ label,
		.rating:not(:checked) > label:hover,
		.rating:not(:checked) > label:hover ~ label {
			color: #ffd700;
		}

		.rating input:checked + label:hover,
		.rating input:checked ~ label:hover,
		.rating label:hover ~ input:checked ~ label,
		.rating input:checked ~ label:hover ~ label {
			color: #ffed4e;
		}
	</style>
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
        </div><!--/header-middle-->

		<div class="header-bottom"><!--header-bottom-->
			<div class="container">
				<div class="row">
					<div class="col-sm-9">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">個人資料</span>
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
										<li><a href="shop.php">商品</a></li>
										<li><a href="historical_orders.php">歷史訂單</a></li>
										<li><a href="cart.php">購物車</a></li>
										<li><a href="login.php">登入</a></li>
									</ul>
								</li>
								<li class="dropdown"><a href="#">評價<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
										<li><a href="blog.html">商品評價列表</a></li>
									</ul>
								</li>
								<li><a href="contact-us.html">聯絡我們</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="search_box pull-right">
							<input type="text" placeholder="搜尋" />
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-bottom-->
	</header><!--/header-->

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
					</div>
				</div>
				<div class="col-sm-9">
					<div class="response-area">
						<h2>歷史訂單</h2><br>

						<?php if (empty($orders)): ?>
							<div class="alert alert-info">
								<p>您目前沒有任何訂單記錄。</p>
							</div>
						<?php else: ?>
							<?php
							$form_counter = 1;
							foreach ($orders as &$order):
							?>
								<div class="order-group">
									<div class="order-header">
										<h3>訂單日期：<?php echo htmlspecialchars($order['order_date']); ?></h3>
										<p>訂單編號：<?php echo htmlspecialchars($order['order_id']); ?></p>
										<p>訂單狀態：
											<span class="order-status <?php
																		echo $order['status'] == '已出貨' ? 'status-shipped' : ($order['status'] == '備貨中' ? 'status-preparing' : 'status-processing');
																		?>"><?php echo htmlspecialchars($order['status']); ?></span>
										</p>
										<p>總金額：NT$ <?php echo number_format($order['total_price']); ?></p>
										<p>配送方式：<?php echo htmlspecialchars($order['shipping_method']); ?></p>
										<p>配送地址：<?php echo htmlspecialchars($order['address']); ?></p>
										<p>聯絡電話：<?php echo htmlspecialchars($order['phone']); ?></p>
									</div>

									<h4>購買商品：</h4>
									<?php if (!empty($order['items'])): ?>
										<?php foreach ($order['items'] as $item): ?>
											<div class="product-item">
												<div class="media">
													<a class="pull-left" href="#">
														<img class="media-object"
															src="<?php echo !empty($item['image_path']) ? htmlspecialchars($item['image_path']) : 'images/product-details/default.png'; ?>"
															alt="<?php echo htmlspecialchars($item['product_name'] ?? 'Unknown Product'); ?>">
													</a>
													<div class="media-body">
														<div class="product-info">
															<h5 class="product-name"><?php echo htmlspecialchars($item['product_name'] ?? 'Unknown Product'); ?></h5>
															<p class="product-price">單價：NT$ <?php echo number_format($item['price'] ?? 0); ?></p>
															<p class="product-quantity">數量：<?php echo htmlspecialchars($item['quantity']); ?></p>
															<p class="product-subtotal">小計：NT$ <?php echo number_format(($item['price'] ?? 0) * $item['quantity']); ?></p>
														</div>

														<?php if ($order['status'] == '已出貨'): ?>
															<?php if ($item['has_review']): ?>
																<div class="already-reviewed">
																	<i class="fa fa-check"></i> 已評價
																</div>
															<?php else: ?>
																<button class="btn btn-primary review-btn" data-target="#reviewForm<?php echo $form_counter; ?>">
																	<i class="fa fa-star"></i> 評論此商品
																</button>
																<div class="review-section">
																	<div id="reviewForm<?php echo $form_counter; ?>" class="review-form">
																		<h5>評論 - <?php echo htmlspecialchars($item['product_name'] ?? 'Unknown Product'); ?></h5>
																		<form action="submit_review.php" method="POST" class="review-form-content">
																			<input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
																			<input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['product_id']); ?>">
																			<input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
																			<input type="hidden" name="user_id" value="<?php echo htmlspecialchars($order['member_id']); ?>">
																			<div class="row">
																				<div class="col-sm-6">
																					<input type="text" name="reviewer_name" placeholder="你的名字" value="<?php echo htmlspecialchars($username); ?>" required />
																				</div>
																				<div class="col-sm-6">
																					<input type="email" name="reviewer_email" placeholder="電子郵件" required />
																				</div>
																			</div>
																			<textarea name="review_content" placeholder="分享您對此商品的使用心得..." required></textarea>
																			<div class="rating-area">
																				<ul class="ratings">
																					<li class="rate-this">評分:</li>
																					<li>
																						<div class="rating-box">
																							<fieldset class="rating">
																								<input type="radio" id="star5_<?php echo $form_counter; ?>" class="star_rating" name="star_rating" value="5"><label class="full" for="star5_<?php echo $form_counter; ?>" title="5 Stars"></label>
																								<input type="radio" id="star4_<?php echo $form_counter; ?>" class="star_rating" name="star_rating" value="4"><label class="full" for="star4_<?php echo $form_counter; ?>" title="4 Stars"></label>
																								<input type="radio" id="star3_<?php echo $form_counter; ?>" class="star_rating" name="star_rating" value="3"><label class="full" for="star3_<?php echo $form_counter; ?>" title="3 Stars"></label>
																								<input type="radio" id="star2_<?php echo $form_counter; ?>" class="star_rating" name="star_rating" value="2"><label class="full" for="star2_<?php echo $form_counter; ?>" title="2 Stars"></label>
																								<input type="radio" id="star1_<?php echo $form_counter; ?>" class="star_rating" name="star_rating" value="1"><label class="full" for="star1_<?php echo $form_counter; ?>" title="1 Stars"></label>
																							</fieldset>
																						</div>
																					</li>
																				</ul>
																				<ul class="tag">
																					<button type="submit" class="btn btn-primary">
																						<i class="fa fa-paper-plane"></i> 發布評價
																					</button>
																					<button type="button" class="btn btn-default cancel-review" data-target="#reviewForm<?php echo $form_counter; ?>">
																						取消
																					</button>
																				</ul>
																			</div>
																		</form>
																	</div>
																</div>
															<?php endif; ?>
														<?php else: ?>
															<button class="btn btn-secondary" disabled>
																<i class="fa fa-clock-o"></i> 訂單未完成，無法評價
															</button>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<?php $form_counter++; 
											?>
										<?php endforeach; ?>
									<?php else: ?>
										<p class="text-muted">此訂單沒有商品資訊</p>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
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
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (typeof jQuery !== 'undefined') {
				// 處理評論按鈕點擊
				$('.review-btn').on('click', function(e) {
					e.preventDefault();
					var target = $(this).data('target');

					// 關閉其他打開的表單
					$('.review-form.show').not(target).removeClass('show');

					// 切換當前表單
					$(target).toggleClass('show');
				});

				// 處理取消按鈕
				$('.cancel-review').on('click', function(e) {
					e.preventDefault();
					var target = $(this).data('target');
					$(target).removeClass('show');
				});

				// 表單提交前驗證
				$('.review-form-content').on('submit', function(e) {
					var rating = $(this).find('input[name="star_rating"]:checked').val();
					if (!rating) {
						e.preventDefault();
						alert('請選擇評分！');
						return false;
					}
				});
			}
		});
	</script>
</body>

</html>