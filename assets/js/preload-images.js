function preload (images) {
	for (var i = 0; i < images.length; i++) {
		var img_path = images[i];
		var img_obj = new Image();
		img_obj.src = img_path;
    }
}

preload(["1.jpg", "2.jpg", "icons/icon.png"]);