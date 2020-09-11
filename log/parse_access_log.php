<?php

$fp = fopen("access.log", "r");
$data = array();
while ($str = fgets($fp)) {
	$str = trim($str);
	$strArr = explode(" - - ", $str);
	if (count($strArr) == 2) {
		$domain_ip = explode(" ", $strArr[0]);
		$domain = trim(str_replace(":80", "", $domain_ip[0]));
		$ip = trim($domain_ip[1]);
		$strArr[1] = str_replace(array("]"), '"', $strArr[1]);
		$others = explode('" "', $strArr[1]);
		$ctime = str_replace("[", "", $others[0]);
		$ctime = strtotime($ctime);
		$arr = explode(" ", str_replace('"', '', $others[1]));
		$uri = $arr[1];
		if (strpos($uri, '/static/') !== false) {
			continue;
		}
		$http_code = $arr[3];
		$ua = str_replace('"', '', $others[2]);
		$logid = md5($ctime.$ua.$ip.$domain.$uri);
		$data = array(
			'domain' => $domain,
			'ip' => $ip,
			'http_code' => $http_code,
			'uri' => $uri,
			'ua' => $ua,
			'ctime' => $ctime,
			'logid' => $logid,
		);
    // do some thing here
	}
}
