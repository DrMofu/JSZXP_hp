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
	if(isset($_POST["submit_touser"]))  
    {  
        $g1 = $_POST["g1"];  
        $g2 = $_POST["g2"];  
        $g3 = $_POST["g3"];  
        $grade=$g1+$g2+$g3;
        $thisUser=$_SESSION['user'];
        $toUser=$_POST["submit_touser"];
        if($g1<=0||$g1>10||$g2<=0||$g2>10||$g3<=0||$g3>5)  
        {  
            echo "<script>alert('分数输入有误'); history.go(-1);</script>";  
        }  
        else  
        {  $con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
            mysql_query("SET NAMES 'UTF8'");//防止乱码  
            mysql_select_db("app_jishuzaxuepu",$con); 

            $sql = "select * from hp_00_".$thisUser."_toUser WHERE toUser ='".$toUser."'";  
            $result = mysql_query($sql);
         	if($result==false){//创建表
            	$mysql_sql="CREATE TABLE  `app_jishuzaxuepu`.`hp_00_".$thisUser."_toUser` (
                `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `toUser` VARCHAR( 32 ) NOT NULL ,
                `Grade` FLOAT NOT NULL ,
                `g1` FLOAT NOT NULL ,
                `g2` FLOAT NOT NULL ,
                `g3` FLOAT NOT NULL
                ) ENGINE = INNODB;";                
                mysql_query($mysql_sql,$con);
            }
         	if($row == mysql_fetch_array($result)){
                
                $mysql_state ="INSERT INTO hp_00_".$thisUser."_toUser (toUser, Grade, g1, g2, g3) VALUES ('$toUser', '$grade', '$g1', '$g2', '$g3')";
   				$mysql_result=mysql_query($mysql_state); 
                $mysql_state="SELECT * FROM hp_00_user_all WHERE User = '".$thisUser."'";
                $mysql_result=mysql_query($mysql_state,$con);
                $find_submit=mysql_fetch_array($mysql_result);
                //$mysql_state="UPDATE hp_00_user_all SET Submit = ".($find_submit["Submit"]+1)." Where User = '".$thisUser."'";
                $mysql_result=mysql_query($mysql_state,$con);
            }
         	else{            
                $mysql_state ="UPDATE hp_00_".$thisUser."_toUser SET Grade = '$grade', g1 = '$g1', g2 = '$g2', g3='$g3' WHERE toUser = '$toUser'";
                $mysql_result=mysql_query($mysql_state); 
            	
            }
         
         	$sql = "select * from hp_00_".$toUser."_getUser WHERE getUser ='".$thisUser."'";  
            $result = mysql_query($sql);
         	if($result==false){//创建表
            	$mysql_sql="CREATE TABLE  `app_jishuzaxuepu`.`hp_00_".$toUser."_getUser` (
                `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `getUser` VARCHAR( 32 ) NOT NULL ,
                `Grade` FLOAT NOT NULL ,
                `g1` FLOAT NOT NULL ,
                `g2` FLOAT NOT NULL ,
                `g3` FLOAT NOT NULL
                ) ENGINE = INNODB;";                
                mysql_query($mysql_sql,$con);
            }
         	if($row == mysql_fetch_array($result)){
                $mysql_state ="INSERT INTO hp_00_".$toUser."_getUser (getUser, Grade, g1, g2, g3) VALUES ('$thisUser', '$grade', '$g1', '$g2', '$g3')";
   				$mysql_result=mysql_query($mysql_state); 
                echo '评价成功';
                echo "<script>history.go(-1);</script>";  
            }
         	else{           
                $mysql_state ="UPDATE hp_00_".$toUser."_getUser SET Grade = '$grade', g1 = '$g1', g2 = '$g2', g3='$g3' WHERE getUser = '$thisUser'";
                $mysql_result=mysql_query($mysql_state); 
                echo '更新成功';
                echo "<script>history.go(-1);</script>"; 
            	
            }
         	
            
         
         	mysql_free_result($result);
            mysql_close($mysqli);
         	//echo "history.go(-1);</script>";
        }
        
    }
    else  
    {  
        echo "<script>alert('不正常访问'); history.go(-1);</script>";  
    }  
?>