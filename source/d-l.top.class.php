<?php
require_once('./source/ApiSite.class.php');
class Simplevideo extends ApiSite {
	public function getxml() {
		//$apisite = "http://simplevideo-deel.rhcloud.com";
		$apisite = "http://api.D-L.top";
		//$apisite = "http://localhost:8051"; // debug
		$TOKEN = array( "xxxxxxxxxxxxxx",
						"PublicToken");
		$loop_times = 2;
		for ($flag = false, $i = 0; $i < $loop_times; ++$i) { 
			foreach ($TOKEN as $token) {
				//$apiurl =  "http://api.flvxz.com/token/$token/url/" . $this->b64url;
				//$apiurl =  "http://www.flvxz.com/api/url/" . $this->b64url . "/verify/$token/t/1426576745";//1426560986085
				if ($this->isBase64Data) {
					$apiurl =  "$apisite/svencryptapi/token/$token/url/" . $this->b64url;
				}
				else {
					$apiurl =  "$apisite/svapi/token/$token/url/" . $this->b64url;
				}
				//echo $apiurl . "<P>"; // debug
				//die();
				
				//@$xml_page = file_get_contents($apiurl);
				//if(empty($xml_page)) {
				//	echo '<font size="5" face="arial">Timeout, click <a href="javascript:top.location.reload();">here</a> to retry.</font>';
				//	echo '</body></html>';
				//	die();
				//}
				
				if(function_exists('curl_init')) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $apiurl);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 13000); // 3s 尝试连接等待的时间
					curl_setopt($ch, CURLOPT_TIMEOUT_MS, 16000); // 5s 设置cURL允许执行的最长毫秒数。
					$xml_page = curl_exec($ch);
					
					$ret = curl_errno($ch);
					if ($ret) {
						$res = curl_error($ch);
						curl_close($ch);
						
						echo "debug -  error message:  $ret : $res . <p>";
						echo '<font size="5" face="arial">Timeout, click <a href="javascript:top.location.reload(true);">here</a> to retry.</font>';
						echo '</body></html>';
						die();
					}
					curl_close($ch);
				}
				else {
					@$xml_page = file_get_contents($apiurl);
					if(empty($xml_page)) {
						echo '<font size="5" face="arial">Timeout, click <a href="javascript: top.location.reload(true);">here</a> to retry.</font>';
						echo '</body></html>';
						die();
					}
				}
				
				if ($this->isBase64Data) { // 加密传输
					//if ($xml_page != 'Authentication failed')
					if (! preg_match('/^Err:.+?/ism', $xml_page))
						$xml_page = base64_decode($xml_page);
				}
				if (preg_match('/<video>.*?<title>.+?<\/title>.+?<\/video>/ism', $xml_page)) { 
					// <video>.*?<title>.+?</title>.+?</video>
					//break 2; // jump out of root loop
					$this->xml = $xml_page;
					return true;
				}
				else if (preg_match('/<root>\s*?<\/root>/ism', $xml_page)) { 
					// <root></root> Bad url
					echo '<font size="5" face="arial">Bad url, please click <a href="javascript:window.close();">here</a> to close.</font>';
					echo '</body></html>';
					die();
				}
				else if (preg_match('/^Err:.+?/ism', $xml_page)) {
					echo '<font size="5" face="arial">' . $xml_page. '</font><p>';
					echo '<font size="5" face="arial">API server Error, please click <a href="javascript:window.close();">here</a> to close.</font>';
					echo '</body></html>';
					die();
				}
				else { // not get the results what i want.
					$flag = true;
				}
			} // end the 2nd loop
			// status:502 Too frequent, Other circumstances also is 502
			if ($flag && ($i == $loop_times - 1)) { // if the last haven't get
				return false; // 交个Parse类的getxml处理
				/*
				echo '<font size="5" face="arial">Too frequent requests,  Please <a href="javascript:top.location.reload();">try</a>  again later.</font>';
				echo '</body></html>';
				die();
				*/
			}
			$flag = false;
		} // end root loop
		return false;
	}
	public function show() {
		$this->title = (string) $this->xml->video->title;
		$this->img = (string) $this->xml->video->img;
		$this->site = (string) $this->xml->video->site;
		
		$this->show_title();
		
		foreach ($this->xml as $video) {
			echo '<hr><div class="fragment">';
			$quality = (string)$video->quality;
			echo "<font><b>$quality</b></font>";
			$flvurl = "";
			$start_time = "00:00";
			$cnt = 0;
			foreach ($video->files->file as $file) {
				if (empty($flvurl)) {
					$ftype = trim((string)($file->ftype));
					echo " <font style=\"font-size:60%\">[$ftype]</font>: ";
				}
				else {
					$flvurl .= "|";
				}
				$furl = trim((string)($file->furl));
				$time = trim((string)($file->time));
				$size = trim((string)($file->size[1]));
				$time = $this->interval_add($start_time, $time);
				echo "<a href=\"$furl\" target=\"_blank\" title=\"[$start_time-&gt;$time, $size]\"  tooltips=\"[$start_time-&gt;$time, $size]\" >";
				printf("%02s", ++$cnt);
				echo "</a> ";
				$start_time = $time;
				$flvurl .= $furl;
			}
			$this->show_play_button($flvurl, $quality);
			echo "</div>";
		}
	}
};
?>
