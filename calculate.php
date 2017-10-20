<?php
	// 启动 Session
	session_start();
?>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
</head>
</html>
<?php
	$class='00';
	$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
	if(!$con){
		die('链接出错：'.mysql_error());
	}
	mysql_query("SET NAMES 'UTF8'");//防止乱码  
	mysql_select_db("app_jishuzaxuepu",$con); 
	$mysql_state="SELECT * FROM hp_".$class."_all";
	$result=mysql_query($mysql_state,$con);
	while($find_user=mysql_fetch_array($result)){
        $user=$find_user['User'];
        //算取平均值
        $mysql_user_state="SELECT AVG(Grade) AS grade FROM hp_".$class."_".$user."_getUser";
        $mysql_user_resule=mysql_query($mysql_user_state,$con);
        $mysql_user_find=mysql_fetch_array($mysql_user_resule);
        $grade=round($mysql_user_find['grade'],2);
        
        $mysql_user_state="SELECT AVG(g1) AS g1 FROM hp_".$class."_".$user."_getUser";
        $mysql_user_resule=mysql_query($mysql_user_state,$con);
        $mysql_user_find=mysql_fetch_array($mysql_user_resule);
        $g1=round($mysql_user_find['g1'],2);
        
        $mysql_user_state="SELECT AVG(g2) AS g2 FROM hp_".$class."_".$user."_getUser";
        $mysql_user_resule=mysql_query($mysql_user_state,$con);
        $mysql_user_find=mysql_fetch_array($mysql_user_resule);
        $g2=round($mysql_user_find['g2'],2);
        
        $mysql_user_state="SELECT AVG(g3) AS g3 FROM hp_".$class."_".$user."_getUser";
        $mysql_user_resule=mysql_query($mysql_user_state,$con);
        $mysql_user_find=mysql_fetch_array($mysql_user_resule);
        $g3=round($mysql_user_find['g3'],2);
        //更新赋值
        $mysql_user_state="UPDATE hp_".$class."_all SET Grade = '$grade', g1 = '$g1', g2 = '$g2', g3='$g3' WHERE User = '$user'";
        $mysql_user_resule=mysql_query($mysql_user_state,$con);
        //echo $g1." ".$g2." ".$g3." ".$grade." ";
    	//echo $find_user['User']."</br>";
    }    	
    $mysql_sort_state="SELECT * FROM hp_".$class."_all ORDER BY Grade DESC";
    $mysql_sort_resule=mysql_query($mysql_sort_state,$con);	
    for($i=5;$i>0;$i--){
        $mysql_sort_find=mysql_fetch_array($mysql_sort_resule);   
        $mysql_user_state="SELECT * FROM hp_user WHERE User='".$mysql_sort_find['User']."'";
        $mysql_user_resule=mysql_query($mysql_user_state,$con);     
        $mysql_user_find=mysql_fetch_array($mysql_user_resule);
    	echo $mysql_user_find['Name']." ".$mysql_sort_find['User']." ".$mysql_sort_find['Grade']." "."</br>";
    }
	


	mysql_free_result($result);
	mysql_free_result($mysql_user_resule);
	mysql_free_result($mysql_sort_resule);
	mysql_close($con);
?>