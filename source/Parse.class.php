<?php

require_once('./source/ApiSite.class.php');
require_once('./source/Flvxz.class.php');
require_once('./source/Id97.class.php');
require_once('./source/d-l.top.class.php');

//

class Parse {
	private $CACHEFILE = '';
	private $b64url = '';
	private $apisite;
	private $isBase64Data = false;
	private $url_arg = 'url';
	/*
	function __construct() {
		//$apisite = new ApiSite();
	}*/
	
	public function init() {
		if (empty($_GET[$this->url_arg])) {
				echo "<script> location.href='/'</script>";
				echo "</body></html>";
				die();
		}
		
		// 接受 script.php?url=BalahBalah 的脚本文件名
		if (isset($_SERVER['SCRIPT_NAME'])) {
			$script_name = $_SERVER['SCRIPT_NAME'];
		}
		else if (isset($_SERVER['PHP_SELF'])) {
			$script_name = $_SERVER['PHP_SELF'];
		}
		else { // default
			$script_name = '/ab/p.php';
		}
		$prefix = $script_name . '?' . $this->url_arg . '='; // '/ab/p.php?url='
	
		// 为了完整匹配$url: '/ab/p.php?url=www.ytb.com/watch?v=yLNuNoMdaAk' 或 '/www.ytb.com/watch?v=yLNuNoMdaAk'
		// 并考虑到php、urlRewrite及服务器版本不同，优先次序: 
		// $_SERVER['HTTP_X_REWRITE_URL'] -> $_SERVER['REQUEST_URI'] -> isset($_SERVER['QUERY_STRING'] -> $_GET["url"]
		if ($this->is_key('HTTP_X_REWRITE_URL', $_SERVER)) {
			//'/ab/p.php?url=www.ytb.com/watch?v=yLNuNoMdaAk'
			//'/www.ytb.com/watch?v=yLNuNoMdaAk'
			$url = str_replace($prefix, "", $_SERVER['HTTP_X_REWRITE_URL']);
			$url = preg_replace("/^\//ism", "", $url);
		}
		else if ($this->is_key('REQUEST_URI', $_SERVER)) {
			//'/ab/p.php?url=www.ytb.com/watch?v=yLNuNoMdaAk'
			//'/ab/p.php?url=www.ytb.com/watch'
			//'/ab/p.php?url=www.ytb.com/watch?v=yLNuNoMdaAk'
			//'/www.ytb.com/watch?v=yLNuNoMdaAk'
			$url = str_replace($prefix, "", $_SERVER['REQUEST_URI']);
			$url = preg_replace("/^\//ism", "", $url);
		}
		else if ($this->is_key('QUERY_STRING', $_SERVER)) {
			//'url=www.ytb.com/watch?v=yLNuNoMdaAk'
			//'url=www.ytb.com/watch'
			//'url=www.ytb.com/watch?v=yLNuNoMdaAk'
			//'url=www.ytb.com/watch&v=yLNuNoMdaAk'
			$prefix = $this->url_arg . '=';
			$url = str_replace($prefix, "", $_SERVER['QUERY_STRING']);
			$url = preg_replace("/(\/[^\?]+?)(&)/ism", "\\1?", $url, 1);
		}
		else {
			$url = trim($_GET[$this->url_arg]);
		}
		
		
		//$url = preg_replace('/^https*:\/\//i', '', $url);
		$url = preg_replace("/^(.{0,5})ytb\.com\/(.*)/i", "\\1youtube.com/\\2", $url); // 将 ytb.com/ 转成 youtube.com/
		if (preg_match('/^.{0,5}?youtube\.com\//i', $url) or 
			preg_match('/^.{0,5}?youtu\.be\//i', $url)) {
			// youtube 是https://
			$url = 'https://' . $url;
			$this->isBase64Data = true;
		}
		else {
			$url = 'http://' . $url;
			$this->isBase64Data = false;
		}
		// 有些视频网站是https://， 如youtube, 幸运的是有的SSL网站会自动跳转到https
		$this->b64url = strtr(base64_encode(str_replace('://', ':##', $url)), '+/', '-_');

		//define('CACHEFILE', './cache/' . $b64url);
		$this->CACHEFILE = './cache/' . $this->b64url;
		ob_start();
	}
	public function readcache() {
		if (!is_dir('./cache')) {
			@unlink('./cache');
			@mkdir('./cache');
			@$handle=fopen('./cache/index.htm', "wb");
			@fclose($handle);
		}
		if (!(!empty($_POST['refresh']) && $_POST['refresh'] == 'true')) {
			@$handle = fopen($this->CACHEFILE, 'r');
			if ($handle) {
				$content = '';
				while(false != ($a = fread($handle, 8080))) {
					$content .= $a;
				}
				fclose($handle);
				echo $content;
				unset($content);
				if (file_exists($this->CACHEFILE)) {
					touch($this->CACHEFILE);
				}
				return true;
			}
		}
		return false;
	}
	private function _getxml(ApiSite &$apisite) {
		$this->apisite = $apisite;
		return $this->apisite->getxml();
	}
	public function getxml() { 
		// 如果有新站点api可用，在此用“||”添加即可
		/*
		$ret = $this->_getxml(new Flvxz($this->b64url)) ||
				$this->_getxml(new Id97($this->b64url)); // Id97 暂时不可用
		*/
		//$ret = $this->_getxml(new Flvxz($this->b64url));
		$ret = $this->_getxml(new Simplevideo($this->b64url, $this->isBase64Data)) ||
				$this->_getxml(new Flvxz($this->b64url));
		if ($ret == false) {
			echo '<font size="5" face="arial">Too frequent requests,  Please <a href="javascript:top.location.reload();">try</a>  again later.</font>';
			echo '</body></html>';
			die();
		}
	}
	public function loadxml() {
		$this->apisite->loadxml();
	}
	public function show() {
		$this->apisite->show();
	}
	public function writecache() {
		$cur_page_contents = ob_get_contents();
		ob_end_flush();
		if (!file_exists($this->CACHEFILE) || (!empty($_POST['refresh']) && $_POST['refresh'] == 'true')) {
			if (!preg_match('/(Undefined)|(variable)|(Notice)|(Warning)|(error)/ism', $cur_page_contents)) {
				@$handle=fopen($this->CACHEFILE, "wb");  
				@fputs($handle, $cur_page_contents);  
				@fclose($handle);
			}
		}
		else {
			unset($cur_page_contents);
		}
	}
	
	
	// tool 判断数组中key是否存在	
	private function is_key($key, array $search)  {
		if (version_compare(PHP_VERSION, "4.0.7", ">=")) {
			//array_key_exists
			return array_key_exists($key , $search);
		}
		else {
			//key_exists
			return key_exists($key , $search);
		}
	}
}; // class Parse

?>