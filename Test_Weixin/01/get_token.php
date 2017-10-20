<?php
	$appid="wx11591561e823c78f";
	$appsecret = "17db24d808f2413ba5302401e90dbb0a";//机密
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
	
	$access_token=get_token($appid,$appsecret,$url);
	echo $access_token;
        
	function get_token($appid,$appsecret,$url){//curl
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output=curl_exec($ch);
    //var_dump($output);//可以查看获取的全部信息
    curl_close($ch);
    $jsoninfo=json_decode($output, true);
	$access_token=$jsoninfo["access_token"];
    return $access_token;
}
?>