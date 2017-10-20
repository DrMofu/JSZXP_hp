<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>报名网站</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            padding-top: 70px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">技术杂学铺——网站开发课程报名</a>
            </div>
        </div>
    </nav>
    <?php
    //$con=mysql_connect("w.rdc.sae.sina.com.cn:3307","yw35nyy303","wk0y1mx04z031myhj3iylw05yjxy0hww5z121y3z");//链接到我的数据库，第一个是数据库的地址，第二个是我的账户名，第三个是我的密码。大家把第二个和第三个改为自己的
    $con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    if(!$con){
        die('链接出错：'.mysql_error());
    }
    mysql_select_db("app_jishuzaxuepu", $con);//链接到一个名叫app_jishuzaxuepu的table
    $name=$_POST["name"];
    $grade=$_POST["grade"];
    $class=$_POST["class"];
    $phone=$_POST["phone"];
    $qq=$_POST["qq"];
    $q1=$_POST["q1"];
    $mysql_state ="INSERT INTO sign_html_class (Name, Grade, Class, Phone, QQ, Q1) VALUES ('$name', '$grade', '$class', '$phone', '$qq', '$q1')";//这里，我把数据插入到名叫sign_html_class的表中
    $mysql_result=mysql_query($mysql_state); //之前写的sql语句只是存在了mysql_state变量之中，这一句是执行mysql_state变量中的内容
    if($mysql_result){
        echo '
        <div class="container">
            <div class="text-center">
                <h1>提交成功</h1>
                <p>网站开发课QQ群：658230141</p>
                <p>试听课：9月27号19:00-20:30 软院108（东十二往东20米）</p>                
                <br>
                <img src="pic.jpg" width="200px">
                <p>本网页由技术杂学铺支持</p>
            </div>
        </div>
    ';
    }
    else{
        echo '
        <div class="container">
            <div class="text-center">
                <h1>提交失败</h1>
                <p>请重新尝试</p>
                <br>
                <img src="pic.jpg" width="200px">
            </div>
        </div>
    ';

    }
    mysql_close($con);
    ?>
</body>
</html>