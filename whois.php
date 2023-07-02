<?php
error_reporting(0);
header('charset=utf-8');
if (!empty($_GET['domain'])) {//判断是否有get值
/*获取网页内容*/
		$curl = curl_init();
	$httpheader[] = "Accept:*/*";
	$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
	$httpheader[] = "Connection:close";
	$httpheader[] = "Referer:http://whois.chinaz.com";
	$httpheader[] = "User-agent:Mozilla/5.0 (iPhone; CPU iPhone OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/9B176 MicroMessenger/4.3.2";
	curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_URL, 'http://whois.chinaz.com/' . str_replace(array("http://", "https://"), "", $_GET['domain']));//去除http://和https://
	$text = curl_exec($curl);
	curl_close($curl);
/*对获取的网页内容截取*/
		preg_match('/注册商<\/div><div class="fr WhLeList-right"><div class="block ball"><span>(.*?)<\/span>/i', $text, $zhuceshang);
	preg_match('/联系人<\/div><div class="fr WhLeList-right block ball lh24"><span>(.*?)<\/span>/i', $text, $contacts);
	preg_match('/创建时间<\/div><div class="fr WhLeList-right"><span>(.*?)<\/span>/i', $text, $chuangjianshijian);
	preg_match('/过期时间<\/div><div class="fr WhLeList-right"><span>(.*?)<\/span>/i', $text, $guoqishijian);
	preg_match('/DNS<\/div><div class="fr WhLeList-right">(.*?)<br\/><\/div>/i', $text, $dns);
	preg_match('/联系电话<\/div><div class="fr WhLeList-right block ball lh24"><span>(.*?)<\/span>/i', $text, $phone);
	preg_match('/公司<\/div><div class="fr WhLeList-right"><div class="block ball"><span>(.*?)<\/span>/i', $text, $gongsi);
	preg_match('/<div class="fl WhLeList-left">联系邮箱<\/div><div class="fr WhLeList-right block ball lh24"><span>(.*?)<\/span>/i', $text, $mail);
	$dnsarray = explode("<br/>", $dns[1]); //对多个dns分组
/*对几个可能为空的值判断*/
		if (!empty($gongsi[1])) {
		$gongsi = $gongsi[1];
	} else {
		$gongsi = '-';
	}
	if (!empty($phone[1])) {
		$phone = $phone[1];
	} else {
		$phone = '-';
	}
	if (!empty($zhuceshang[1])) {
		$zhuceshang = $zhuceshang[1];
	} else {
		$zhuceshang = '-';
	}
	/*输出并结束程序*/
	exit('
<p><strong>注册商：</strong>' . $zhuceshang . '</p>
<p><strong>联系人：</strong>' . $contacts[1] . '</p>
<p><strong>公司：</strong>' . $gongsi . '</p>
<p><strong>邮箱：</strong>' . $mail[1] . '</p>
<p><strong>电话：</strong>' . $phone . '</p>
<p><strong>创建时间：</strong>' . $chuangjianshijian[1] . '</p>
<p><strong>过期时间：</strong>' . $guoqishijian[1] . '</p>
<p><strong>DNS：</strong>' . "$dnsarray[0]\t$dnsarray[1]" . '</p>');
} else {
//如果get值为空则显示'Error'并结束程序
	exit('Error');
}