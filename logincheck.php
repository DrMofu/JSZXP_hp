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

    if(isset($_POST["submit"]) && $_POST["submit"] == "submit")  
    {  
        $user = $_POST["user"];  
        $psw = $_POST["pass"];  
        if($user == "" || $psw == "")  
        {  
            echo "<script>alert('请输入用户名或密码！'); history.go(-1);</script>";  
        }  
        else  
        {  $con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
            mysql_query("SET NAMES 'UTF8'");//防止乱码  
            mysql_select_db("app_jishuzaxuepu",$con); 

            $sql = "select User,Pass from hp_user where User = '$_POST[user]' and Pass = '$_POST[pass]'";  
            $result = mysql_query($sql);  
            $num = mysql_num_rows($result);  
            if($num)  
            {  
                $row = mysql_fetch_array($result);  //将数据以索引方式储存在数组中  
                $_SESSION["login"] = true;
                $_SESSION['user'] = $_POST[user];
                 echo "<script> history.go(-1);</script>";
            }  
            else  
            {  
                echo "<script>alert('用户名或密码不正确！');history.go(-1);</script>";  
            }  
         	mysql_free_result($result);
            mysql_close($mysqli);
        }  
    }else if($_SESSION["login"] = true){
    	session_destroy();
        echo "<script>alert('退出成功！'); history.go(-1);</script>"; 
    }  
    else  
    {  
        echo "<script>alert('提交未成功！'); history.go(-1);</script>";  
    }  
  
?>  