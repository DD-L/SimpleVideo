<?php
abstract class ApiSite { 

	protected $title = '';
	protected $img = '';
	protected $site = '';

	protected $b64url = '';
	protected $xml = '';
	
	protected $isBase64Data = false;
	
	function __construct($b64url, $isBase64Data = false) {
		$this->b64url = $b64url;
		$this->isBase64Data = $isBase64Data;
	}

	abstract public function getxml(); // 成功返回true, 失败返回false

	public function loadxml() {
		$this->xml = $this->uncdata($this->xml);
		@$this->xml = simplexml_load_string($this->xml);
		if (empty($this->xml)) {
			echo '<font size="5" face="arial">Parse error: xml. sorry, please click <a href="javascript:window.close();">here</a> to close.</font>';
			echo '</body></html>';
			die();
		}
	}
	
	abstract public function show();
	
	protected function show_title() {
		echo $con_title = <<<STR
<div style="text-align: center">
	<div style="text-align: center">
		<img src="{$this->img}" alt="{$this->title}" title="{$this->title}"/>
		<div style="text-align:center; padding:2px">
			<h5>{$this->title} ( from {$this->site} ):</h5>
		</div>
	</div>
</div>
STR;
	}
	
	protected function show_play_button($flvurl, $quality = '') {
		echo $play_button = <<<PLAY_BUTTON
<form action="/play" method="post" target="_blank">
	<input type="hidden" name="flvurl" value="{$flvurl}"></input>
	<input type="hidden" name="quality" value="{$quality}"></input>
	<input type="hidden" name="title" value="{$this->title}"></input>
	<input type="submit" value=" Play "></input>
</form>
PLAY_BUTTON;
	}
	
	// tools functions:
	public static function uncdata($xml) {
		// States:
		//
		//     'out'
		//     '<'
		//     '<!'
		//     '<!['
		//     '<![C'
		//     '<![CD'
		//     '<![CDAT'
		//     '<![CDATA'
		//     'in'
		//     ']'
		//     ']]'
		//
		// (Yes, the states a represented by strings.) 
		//
		$state = 'out';
		$a = str_split($xml);
		$new_xml = '';
		foreach ($a AS $k => $v) {
			// Deal with "state".
			switch ( $state ) {
				case 'out':
					if ( '<' == $v ) {
						$state = $v;
					} else {
						$new_xml .= $v;
					}
				break;
				case '<':
					if ( '!' == $v  ) {
						$state = $state . $v;
					} else {
						$new_xml .= $state . $v;
						$state = 'out';
					}
				break;
				case '<!':
					if ( '[' == $v  ) {
						$state = $state . $v;
					} else {
						$new_xml .= $state . $v;
						$state = 'out';
					}
				break;
				case '<![':
					if ( 'C' == $v  ) {
						$state = $state . $v;
					} else {
						$new_xml .= $state . $v;
						$state = 'out';
					}
				break;
				case '<![C':
					if ( 'D' == $v  ) {
						$state = $state . $v;
					} else {
						$new_xml .= $state . $v;
						$state = 'out';
					}
				break;
				case '<![CD':
					if ( 'A' == $v  ) {
						$state = $state . $v;
					} else {
						$new_xml .= $state . $v;
						$state = 'out';
					}
				break;
				case '<![CDA':
					if ( 'T' == $v  ) {
						$state = $state . $v;
					} else {
						$new_xml .= $state . $v;
						$state = 'out';
					}
				break;
				case '<![CDAT':
					if ( 'A' == $v  ) {
						$state = $state . $v;
					} else {
						$new_xml .= $state . $v;
						$state = 'out';
					}
				break;
				case '<![CDATA':
					if ( '[' == $v  ) {
						$cdata = '';
						$state = 'in';
					} else {
						$new_xml .= $state . $v;
						$state = 'out';
					}
				break;
				case 'in':
					if ( ']' == $v ) {
						$state = $v;
					} else {
						$cdata .= $v;
					}
				break;
				case ']':
					if (  ']' == $v  ) {
						$state = $state . $v;
					} else {
						$cdata .= $state . $v;
						$state = 'in';
					}
				break;
				case ']]':
					if (  '>' == $v  ) {
						$new_xml .= str_replace('>','&gt;',
									str_replace('>','&lt;',
									str_replace('"','&quot;',
									str_replace('&','&amp;',
									$cdata))));
						$state = 'out';
					} else {
						$cdata .= $state . $v;
						$state = 'in';
					}
				break;
			} // switch
		}
		return $new_xml;
	}

	// time interval addition, 03:08 + 03:53 = 07:01
	public static function interval_add($t1, $t2) {
		if (empty($t2)) return $t1;
		$t1 = explode(":", $t1);
		$t2 = explode(":", $t2);
		$sec = $t1[1] + $t2[1];
		$quotient = (int)($sec / 60);
		$sec = $sec % 60;
		$sec = $sec < 10 ? ( '0' . $sec ) : $sec;
		$min = $t1[0] + $t2[0] + $quotient;
		$min = $min < 10 ? ( '0' . $min ) : $min;
		return ($min . ':' . $sec);
	}
};
?>