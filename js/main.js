/*price range*/

$('#sl2').slider();

var RGBChange = function() {
  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
};	
	
/*scroll to top*/

$(document).ready(function(){
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
				//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});
	});
	
	// 頁面加載完成後，將焦點設置回搜尋框
	if (document.getElementsByName('search')[0]) {
		var searchInput = document.getElementsByName('search')[0];
		searchInput.focus();
		// 將游標定位到文字末尾
		if (searchInput.value.length) {
			searchInput.selectionStart = searchInput.selectionEnd = searchInput.value.length;
		}
	}

	// 防止搜尋表單提交時頁面閃爍
	document.getElementById('searchForm').addEventListener('submit', function(e) {
		// 如果搜尋框為空，阻止提交
		if (document.getElementsByName('search')[0].value.trim() === '') {
			e.preventDefault();
		}
	});

	// 添加延遲搜尋，避免每次按鍵都觸發搜尋
	var searchTimeout = null;
	var searchInput = document.getElementsByName('search')[0];
	
	searchInput.addEventListener('keyup', function(e) {
		clearTimeout(searchTimeout);
		var self = this;
		
		// 按下Enter鍵立即搜尋
		if (e.key === 'Enter') {
			if (self.value.trim() !== '') {
				self.form.submit();
			}
			return;
		}
		
		// 設置延遲，500毫秒後才提交表單
		searchTimeout = setTimeout(function() {
			// 如果搜尋框為空，自動導向到不帶搜尋參數的頁面
			if (self.value.trim() === '') {
				// 獲取當前URL
				var currentUrl = window.location.href;
				// 移除URL中的search參數
				var newUrl = currentUrl.split('?')[0];
				// 保留其他參數（如page）
				var urlParams = new URLSearchParams(window.location.search);
				urlParams.delete('search');
				
				// 如果還有其他參數，則將它們添加回去
				if (urlParams.toString()) {
					newUrl += '?' + urlParams.toString();
				}
				
				// 如果新URL與當前URL不同，則導航到新URL
				if (newUrl !== currentUrl) {
					window.location.href = newUrl;
				}
			} else if (self.value.trim() !== '') {
				self.form.submit();
			}
		}, 500);
	});
	
	// 為搜尋框添加清除按鈕功能
	searchInput.addEventListener('input', function() {
		// 直接處理backspace/delete鍵清空搜尋框的情況
		if (this.value.trim() === '') {
			clearTimeout(searchTimeout);
			// 立即執行清除搜尋參數的動作
			var currentUrl = window.location.href;
			if (currentUrl.includes('search=')) {
				var newUrl = currentUrl.split('?')[0];
				var urlParams = new URLSearchParams(window.location.search);
				urlParams.delete('search');
				
				if (urlParams.toString()) {
					newUrl += '?' + urlParams.toString();
				}
				
				window.location.href = newUrl;
			}
		}
	});
});