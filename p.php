<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="/ab/js/jquery.min.js"></script>
<link href="/ab/css/comm.css" rel="stylesheet" type="text/css">
<link href="/ab/css/logo.css" rel="stylesheet" type="text/css">
<script language="javascript">
//<!--
if (top.location != location) {
	top.location.href = location.href;
}
//-->
</script>
</head><body>
<div class="logo" id="logoid">SimpleVideo <span class="ext">alpha<!--beta--></span></div>

<?php
	require_once('./source/Parse.class.php');
	$parse = new Parse();
	$parse->init();
	if ($parse->readcache()) {
		die();
	}
	$parse->getxml();
// Refresh Cache, it must be behind <"Timeout, click here to retry." & die();>
?>
<div style="border:1px solid #F1DFE1; width:50px; height:50px; position:fixed;right:0px;top:0px;">
	<div style="text-align: center">
		<form action="" method="post">
			<input type="hidden" name="refresh" value="true"></input>
			<input type="image" src="/refresh.png" height="30" width="30" title="Refresh Cache" onclick="this.form.submit()">
		</form>
	</div>
	<div style="text-align: center; font-size:12px; margin:1 0 0 0">&#x5237;&#x65B0;&#x7F13;&#x5B58;</div>
</div>

<?php
	$parse->loadxml();
	$parse->show(); // body
?>

<script src="/ab/js/tips.js"></script>
<script src="/ab/js/logo.js"></script>
</body></html>

<?php
	$parse->writecache();
?>