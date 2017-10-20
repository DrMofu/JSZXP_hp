<?php
/*
    技术杂学铺 出品
*/

header('Content-type:text');
define("TOKEN", "weixin");

$content = "";
$Ans_Typ=0;
$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
	$wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}
class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){//如果成立，有返回值。不成立无返回值，返回给微信服务器的内容就为空，微信服务器则会判定传输出错
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()//检验前面
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $GLOBALS['Ans_Typ'] = 0;//0为未处理，1为文本，2为推文
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R ".$postStr);//记录log文件
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                case "voice":
                    $result = $this->receiveText($postObj);
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }
    
    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "欢迎关注";
                if(isset($object->EventKey)){
                	$content = "欢迎扫码关注";
                    /*switch($object->EventKey){
                		case "qrscene_1000"://扫码关注后处理
                    	break;
                    }*/
                }
                $GLOBALS['Ans_Typ']=1;
                break;
            case "unsubscribe":
                $content = "取消关注";
                $GLOBALS['Ans_Typ']=1;
                break;
            case "CLICK"://点击底部菜单接口
       			$user=trim($object->FromUserName);//用户的OpenID
                switch($object->EventKey){
                    case "test"://此处做各种各样的处理，比如推推文等等
                        $content="点击事件";
                        $GLOBALS['Ans_Typ']=1;
                        break;
                }
                break;
            case "VIEW":
                switch($object->EventKey){
                    case "testview":
                        $content="一般view不回复，直接跳转";
                        $GLOBALS['Ans_Typ']=1;
                        break;                        
                }
                break;
            case "SCAN"://扫码，获得场景值。未关注的会先关注、后获得场景值
                switch($object->EventKey){
                    case "1000":
                        $content="场景值为数字";
                        $GLOBALS['Ans_Typ']=1;
                        break;                        
                }
                break;
                
        }
        if($GLOBALS['Ans_Typ']==0){//未处理
            $content="点击暂时无服务 key:".$object->EventKey;
            $result = $this->transmitText($object, $content);
            //}
        }else if($GLOBALS['Ans_Typ']==1){//文字信息
        	$result = $this->transmitText($object, $content);
        }
        else if($GLOBALS['Ans_Typ']==2){//推文
        	$result = $this->transmitNews($object, $content);
        }
        return $result;
    }
    
    //接收文本消息
    
    
    private function receiveText($object)
    {
        $content=""; 
        if(isset($object->Recognition)){//如果用户输入的是语音
        	$keyword = trim($object->Recognition);
            $mediaid = trim($object->MediaID);
            $content="识别到您说的是：".$keyword."\n";
        }else{//用户输入的是文本
        	$keyword = trim($object->Content);
        }
        $user=trim($object->FromUserName);//用户的OpenID               
        $key_low= strtolower($keyword);
        //开始进入依次识别
        $content .= $this->textHtml($keyword,$key_low,$user);//网站学习模块
        if($GLOBALS['Ans_Typ']==0){
            $content .= $this->html_sign($keyword,$user);//报名端口，已完成
            if($GLOBALS['Ans_Typ']==0){
                $content .= $this->test_System($keyword);            
                if($keyword == "时间"){
                    $content .= date("Y-m-d H:i:s",time());
                    $GLOBALS['Ans_Typ']=1;
                }
            }
        }
        if($GLOBALS['Ans_Typ']==0){//未处理
            $content .= "我们会在第一时间回复您";
            /*if(is_array($content)){
                if (isset($content[0]['PicUrl'])){
                    $result = $this->transmitNews($object, $content);
                }else if (isset($content['MusicUrl'])){
                    $result = $this->transmitMusic($object, $content);
                }
            }else{*/
            $result = $this->transmitText($object, $content);
            //}
        }else if($GLOBALS['Ans_Typ']==1){//文字信息
        	$result = $this->transmitText($object, $content);
        }
        else if($GLOBALS['Ans_Typ']==2){//推文
        	$result = $this->transmitNews($object, $content);
        }
        return $result;
    }
    
    private function test_System($keyword)
    {
        if($keyword=="系统"){
        	$GLOBALS['Ans_Typ']=2;
            $content = array();
            $content[] = array("Title"=>"学习系统",
                               "Description"=>"技术杂学铺学习系统",
                               "PicUrl"=>"http://chuantu.biz/t5/167/1502348968x2890173873.jpg",
                              "Url"=>"http://1.jishuzaxuepu.applinzi.com/Test_System/index.html");
            return $content;
        }
    }
    private function html_sign($keyword,$user){
        $con=mysql_connect("w.rdc.sae.sina.com.cn:3307","yw35nyy303","wk0y1mx04z031myhj3iylw05yjxy0hww5z121y3z");
        if(!$con){
           	die('链接出错：'.mysql_error());
        }
        mysql_query("SET NAMES 'UTF8'");//防止乱码  
        mysql_select_db("app_jishuzaxuepu",$con);        
        $mysql_state="SELECT * FROM sign_html WHERE OpenID = '".$user."'";
        $mysql_results=mysql_query($mysql_state,$con);
        $find=mysql_fetch_row($mysql_results);
        if($keyword=="报名"){
            if($find==false){
            	$content="请输入您的姓名";
                $mysql_state="INSERT INTO sign_html (OpenID,Pro) VALUES ('$user','1')";
                mysql_query($mysql_state); 
            }else{//有信息，中途、已经完成
            	if($find[4]==1){
                	$content="检测到上次注册未完成\n请输入您的姓名";
                }else if($find[4]==2){
                	$content="检测到上次注册未完成\n请输入您的学号";
                }else if($find[4]==3){
                	$content="您已注册成功";
                }else{
                	$content="系统出错".$find;
                }
            }
        	$GLOBALS['Ans_Typ']=1;
        }else if($find[4]==1){
        	$mysql_check=mysql_query("UPDATE sign_html SET Name = '".$keyword."', Pro = '2' WHERE OpenID = '".$user."'");
            if($mysql_check==false){
            	$content="数据出错，请重新输入姓名";
            }else{
            	$content="请输入您的学号";
            }
            $GLOBALS['Ans_Typ']=1;
        }else if($find[4]==2){
        	$mysql_check=mysql_query("UPDATE sign_html SET StuID = '".$keyword."', Pro = '3' WHERE OpenID = '".$user."'");
            if($mysql_check==false){
            	$content="数据出错，请重新输入学号";
            }else{
                $content="注册成功！";
            }
            $GLOBALS['Ans_Typ']=1;
        }
        mysql_close($con);
    	return $content;
    }
	private function textHtml($keyword,$key_low,$user){
        if($keyword=="网站学习"){
            //$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
            //链接sae主机
            $con=mysql_connect("w.rdc.sae.sina.com.cn:3306","yw35nyy303","wk0y1mx04z031myhj3iylw05yjxy0hww5z121y3z");
            if(!$con){
            	die('链接出错：'.mysql_error());
            }
            //选择数据库
            mysql_query("SET NAMES 'UTF8'");//防止乱码  
            mysql_select_db("app_jishuzaxuepu", $con);          
            //从数据库中获取用户数据
            $mysql_state="SELECT * FROM Stu_web WHERE OpenID = '".$user."'";
            $mysql_result = mysql_query($mysql_state,$con); 
            $find=mysql_fetch_row($mysql_result);
            if($find==""){
       			$content ="开始网站学习";
                $mysql_state="INSERT INTO Stu_web (OpenID, Progress) VALUES ('$user', '1')";
                mysql_query($mysql_state,$con);
            }else{
                $content ="您已经报名学习\n请输入html开始学习"."$find[1]";
            }
            mysql_close($con);
            $GLOBALS['Ans_Typ']=1;
        }
        else if($key_low=="html"){
            $content ="HTML学习地址\nhttp://www.runoob.com/html/html-tutorial.html";
            $GLOBALS['Ans_Typ']=1;
        }
        return $content;
    }
    
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    private function transmitNews($object, $arr_item)
    {
        if(!is_array($arr_item))
            return;

        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item));
        return $result;
    }

    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }
    
    private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 10000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
}


?>