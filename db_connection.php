$link = mysqli_connect('localhost','root','root123456','BigProjectDB');

if ( !$link ) {
echo "連結錯誤代碼: ".mysqli_connect_errno()."<br>";//顯示錯誤代碼
echo "連結錯誤訊息: ".mysqli_connect_error()."<br>";//顯示錯誤訊息
exit();
}

/*
引用時使用以下程式碼
<?php
	        include 'db_connection.php'; // 引用資料庫連線檔案
	        if ($link) {
	            echo "<p>資料庫連線成功！</p>";
	        }
	    ?>
*/
