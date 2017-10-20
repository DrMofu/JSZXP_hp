<?php
    // 启动会话，这步必不可少
    session_start();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>互评网站</title>
    <!-- Bootstrap -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="css/huping.css" rel="stylesheet">
</head>
<body>
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top bluebar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="icon-bar" style="background-color:#ffffff;" ></span>
                <span class="icon-bar" style="background-color:#ffffff;"></span>
                <span class="icon-bar" style="background-color:#ffffff;"></span>
            </button>
            <a class="navbar-brand" href="#" style="color: #FCFCFC;font-weight:bold;">互评网站 <small style="font-weight:normal">alpha版</small></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">首页</a></li>
                <li><a href="huping.php" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">互评</a></li>
                <li><a href="group.php" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">小组</a></li>
                <li class="active "><a href="#" class="bluebar_font" style="padding-left:30px;padding-right: 30px;">个人</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    // 判断是否登陆
                    if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
                    	echo '
                        <li><a href="#" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">'.$_SESSION['user'].'</a></li>
                        <li><a href="logincheck.php" class="bluebar_font" style="padding-left:10px;padding-right: 30px;color: #FCFCFC;">退出</a></li>
                        ';
                    } else {
                    echo '
                    <form class="form-inline hidden-xs" style="margin-top: 7px;" action="logincheck.php" method="post">
                        <div class="form-group">
                            <!--<label for="exampleInputName2" style="color: #FCFCFC;">学号</label>-->
                            <input type="text" name="user" class="form-control" id="exampleInputName2" style="width: 100px;" placeholder="学号">
                        </div>
                        <div class="form-group">
                            <!--<label for="exampleInputEmail2" style="color: #FCFCFC;">密码</label>-->
                            <input type="password" name="pass" class="form-control" id="exampleInputEmail2" style="width: 100px;" placeholder="密码">
                        </div>
                        <button type="submit" name="submit" value="submit" class="btn btn-info">登录</button>
                    </form>';
                    $_SESSION["login"] = false;
                    }
                ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<div class="mainbox">
    <div class="pj_box">
         <?php
        	if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
                $class='00';
                $user=$_SESSION['user'];
                $con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                mysql_query("SET NAMES 'UTF8'");//防止乱码  
                mysql_select_db("app_jishuzaxuepu",$con); 
                $mysql_state = "select * from hp_user WHERE User = '".$user."'"; 
            	$mysql_result = mysql_query($mysql_state);
                $mysql_find = mysql_fetch_array($mysql_result); 
                $h=date("h");
                $a=date("a");
                if($a=='am'&&$h>=9){
                	$time="上午好";
                }else if($a=='am'&&$h>=6){
                	$time="早上好";
                }else if($a=='pm'&&$h<=7){
                	$time="下午好";
                }else{
                	$time="晚上好";
                }
                echo '<h2 style="text-align: center;padding-top:60px;">'.$time." ".$mysql_find['Name'].'</h2><br/>';
                $mysql_state="SELECT * FROM hp_".$class."_all WHERE User='".$user."'";                
            	$mysql_result = mysql_query($mysql_state);
                $mysql_find = mysql_fetch_array($mysql_result); 
                if($mysql_find['Grade']==0){
                echo '<p style="text-align: center;">您未上交起始课作业，无法查看个人成绩</p>';
                }else{
                    echo '<p style="text-align: center;">起始课作业</p>';
                    echo '<p style="text-align: center;">总分：'.$mysql_find['Grade'].'</p>';
                    echo '<p style="text-align: center;">基础功能：'.$mysql_find['g1'].'</p>';
                    echo '<p style="text-align: center;">构图排版：'.$mysql_find['g2'].'</p>';
                    echo '<p style="text-align: center;">创新创意：'.$mysql_find['g3'].'</p>';
                }
                
                $mysql_sort_state="SELECT * FROM hp_".$class."_all ORDER BY Grade DESC";
                $mysql_sort_resule=mysql_query($mysql_sort_state,$con);	
                
                ?>
        <h2  style="text-align: center;padding-top:60px;">排行榜（前20名）</h2>
        <br/>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            <table class="table table-hover table-bordered" style="background-color:#eee;">
                <tr>
                    <th>排名</th>
                    <th>姓名</th>
                    <!--<th>学号</th>-->
                    <th>总成绩</th>
                    <th>基础功能</th>
                    <th>构图排版</th>
                    <th>创新创意</th>
                    <th>链接</th>
                </tr>            
                <?php
                for($i=1;$i<21;$i++){
                    $mysql_sort_find=mysql_fetch_array($mysql_sort_resule);   
                    $mysql_user_state="SELECT * FROM hp_user WHERE User='".$mysql_sort_find['User']."'";
                    $mysql_user_resule=mysql_query($mysql_user_state,$con);     
                    $mysql_user_find=mysql_fetch_array($mysql_user_resule);
                    echo '<tr><td>'.$i.'</td>';
                    echo '<td>'.$mysql_user_find['Name'].'</td>';
                    //echo '<td>'.$mysql_sort_find['User'].'</td>';
                    echo '<td>'.$mysql_sort_find['Grade'].'</td>';
                    echo '<td>'.$mysql_sort_find['g1'].'</td>';
                    echo '<td>'.$mysql_sort_find['g2'].'</td>';
                    echo '<td>'.$mysql_sort_find['g3'].'</td>';
                    echo '<td><a href="data/class/00/'.$mysql_user_find['User'].'">链接</a></td></tr>';
                }
                ?>            
            </table>
            </div>
        </div>
        <?php
                
            }else{
                echo'<form class="form-horizontal" style="padding-top: 100px;" action="logincheck.php" method="post">
                        <h1 style="text-align: center">用户登录</h1>
                        <div class="form-group"  style="margin-top: 50px;">
                            <label for="inputUser" class="col-sm-2 control-label col-sm-offset-3 hidden-xs">学号</label>
                            <div class="col-sm-3">
                                <input type="text"  name="user" class="form-control" id="inputUser" placeholder="学号">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 20px;">
                            <label for="inputPassword" class="col-sm-2 control-label col-sm-offset-3 hidden-xs">密码</label>
                            <div class="col-sm-3">
                                <input type="password" name="pass" class="form-control" id="inputPassword" placeholder="密码">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 50px;">
                            <div class="col-sm-offset-4 col-sm-4">
                                <button type="submit" name="submit" value="submit" class="btn btn-primary btn-lg btn-block">登录</button>
                            </div>
                        </div>
                    </form>';
            }
		?>
    </div>
</div>    
<div class="copyright">
    <p>©技术杂学铺</p>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>