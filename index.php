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
<body style="background-color:#E0EEEE;">
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
                <li><a href="#" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">首页</a></li>
                <li><a href="huping.php" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">互评</a></li>
                <li><a href="#" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">小组</a></li>
                <li><a href="user.php" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">个人</a></li>
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
<div class="container" style="text-align: center;margin-top: 180px;">
    <h1>技术杂学铺</h1>
    <br>
    <p>大学生的知识分享平台</p>
    <br>
    <br>
    <?php
		if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
			echo'
            <a href="huping.php">
                <button type="button" class="btn btn-primary btn-lg">开始互评作品</button>
            </a>
            ';
        } else {
            echo'            
            <a href="login.php">
                <button type="button" class="btn btn-primary btn-lg">开始奇幻之旅</button>
            </a>
            ';
        }
	?>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>