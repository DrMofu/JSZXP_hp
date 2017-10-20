<?php
$appid="wx11591561e823c78f";
$appsecret = "17db24d808f2413ba5302401e90dbb0a";//机密
$url_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
//开始确认token
$access_token=get_token($appid,$appsecret,$url_token);
$url_create= "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;//创建
$url_get= "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$access_token;//查询 要放在确定了access_token之后
$url_delete= "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$access_token;//删除

$jsonmenu = '{
	"button":[
    	{
        	"name":"主菜单一",
            "sub_button":[
            	{
                	"type":"click",
                    "name":"点击事件",
                    "key":"event_click"
                },
                {
                	"type":"view",
                    "name":"跳转网页",
                    "url":"http://www.baidu.com"
                }
            ]
        },
        {
        	"name":"扫码",
            "sub_button":[
            	{
                	"type":"scancode_waitmsg",
                    "name":"扫码&提示",
                    "key":"event_scan_waitmsg"
                },
                {
                	"type":"scancode_push",
                    "name":"扫码&推送",
                    "key":"event_scan_push"
                }
            ]
        }
    ]
}
';

$result=https_request($url_create,$jsonmenu);
//$result=https_request($url_get);
var_dump($result);//检测是否创建成功



function get_token($appid,$appsecret,$url_token){//curl
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_token);
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

function https_request($url, $data=null){//curl
    $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    if(!empty($data)){//当有data数据的时候,提交data
      	curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
   		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output=curl_exec($ch);//var_dump($output);//可以查看获取的全部信息
      	curl_close($ch);        
    return $output;
}
?>