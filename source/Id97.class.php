<?php
require_once('./source/ApiSite.class.php');
class Id97 extends ApiSite {
	public function getxml() {
		// MGIwYjlaRDZLSlJZeGRPcVNkNllUZFAxRWZqMktIa3ZubVhycG1keHFFbHNSbjU4Rg==
		// jixuchaoa
		$TOKEN = 'MGIwYjlaRDZLSlJZeGRPcVNkNllUZFAxRWZqMktIa3ZubVhycG1keHFFbHNSbjU4Rg==';
		$apiurl = "http://www.id97.com/videos/flashxml/token/$TOKEN/ykurl/" . $this->b64url;
		//echo $apiurl . "<br/>"; // ???????????????????
		@$xml_page = file_get_contents($apiurl);
		if(empty($xml_page)) {
			echo '<font size="5" face="arial">Timeout, click <a href="javascript:top.location.reload();">here</a> to retry.</font>';
			echo '</body></html>';
			die();
		}
		//echo $xml_page; // ???????????????????
		die();
		return true;
	}
	public function show() {
		echo ".hahahaha";
	}
};
?>