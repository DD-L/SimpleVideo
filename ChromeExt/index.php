<?php
// ?file=sv.crx
if (empty($_GET['file'])) {
	$file = './sv.crx';
}
else {
	$file = htmlspecialchars($_GET['file']);
}
if (is_file(basename($file))) {
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".basename($file));
	readfile(basename($file));
	exit;
}
else {
	echo "Access Forbidden: $file.";
	exit;
}
?>