function isMobile() {
	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
	var iOS = /(iPad|iPhone|iPod)/g.test(navigator.userAgent);
	if(iOS == true || isAndroid) return true;
	return false;
}