<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="/ab/js/jquery.min.js"></script>
<style>
body { background-color: #f0f0f0; }
hr {border: 2px solid #F6F6F6;}
font {font-family: 'Microsoft Yahei';}
.videotitle {
	font-weight: 600;
	font-size: 25px;
}
</style>
<link href="/ab/css/logo.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="logo" id="logoid">SimpleVideo <span class="ext">alpha<!--beta--></span></div>
<?php
	if (empty($_POST['flvurl'])) {
		echo "<script> location.href='/'</script>";
		echo "</body></html>";
		die();
	}
?>
<div>
<hr>
<div style="text-align: center"><font class="videotitle"><?php echo $_POST['title']." - [".$_POST['quality']."]";?></font></div>
<div style="text-align: center">
<object type="application/x-shockwave-flash" data="/player.swf" width="680" height="480">
    <param name="movie" value="/player.swf" />
    <param name="allowFullScreen" value="true" />
    <param name="FlashVars" value="autoplay=1&amp;showvolume=1&amp;showtime=1&amp;showfullscreen=1&amp;ondoubleclick=fullscreen&amp;flv=<?php echo urlencode($_POST['flvurl']);?>" />
</object>
</div>
<hr>
</div>
<script src="/ab/js/logo.js"></script>
</body></html>
