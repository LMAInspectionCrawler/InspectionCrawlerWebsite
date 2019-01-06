<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<img id="stream" src="FrameImages/Stream1Frame.jpg" />
<script>
	// https://stackoverflow.com/questions/4572193/how-to-reload-img-every-5-second-using-javascript
	setInterval(function() {
		var myImageElement = document.getElementById('stream');
		myImageElement.src = 'FrameImages/Stream1Frame.jpg?rand=' + Math.random();
	}, 32);		// 32 * 1000 ~ 30 fps
</script>
</body>
</html>