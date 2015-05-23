<?php
require_once('./source/ApiSite.class.php');
class Flvxz extends ApiSite {
	public function getxml() {
		$TOKEN = array( "sdfsdfsdfsdfsdsf",  // imzker.com
						"sdfsdfsefsf",  // other site
						"sdfsefsfefsef",
						"sfefsfefefefe",
						"sdfsfefefefe");
		$loop_times = 2;
		for ($flag = false, $i = 0; $i < $loop_times; ++$i) { 
			foreach ($TOKEN as $token) {
				$apiurl =  "http://api.flvxz.com/token/$token/url/" . $this->b64url;
				//$apiurl =  "http://www.flvxz.com/api/url/" . $this->b64url . "/verify/$token/t/1426576745";//1426560986085
				//echo $apiurl . "<P>";
				@$xml_page = file_get_contents($apiurl);
				if(empty($xml_page)) {
					echo '<font size="5" face="arial">Timeout, click <a href="javascript:top.location.reload();">here</a> to retry.</font>';
					echo '</body></html>';
					die();
				}
				else if (preg_match('/<video>.*?<title>.+?<\/title>.+?<\/video>/ism', $xml_page)) { 
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
