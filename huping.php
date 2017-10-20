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
                <li class="active "><a href="#" class="bluebar_font" style="padding-left:30px;padding-right: 30px;">互评</a></li>
                <li><a href="group.php" class="bluebar_font" style="padding-left:30px;padding-right: 30px;color: #FCFCFC;">小组</a></li>
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

<div class="mainbox">
    <div class="pj_box">
        <?php
        	if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
                $user=$_SESSION['user'];
                $class='00';
                $con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                if(!$con){
                    die('链接出错：'.mysql_error());
                }
                mysql_query("SET NAMES 'UTF8'");//防止乱码  
                mysql_select_db("app_jishuzaxuepu",$con); 
                $mysql_state="SELECT people FROM hp_class_all WHERE class = 0";
                $mysql_results=mysql_query($mysql_state,$con);
                $find=mysql_fetch_array($mysql_results);
                
                $mysql_state = "select count(ID) from hp_".$class."_".$user."_toUser"; 
            	$mysql_result = mysql_query($mysql_state);
                $mysql_find = mysql_fetch_array($mysql_result); 
                echo '
                <br>        
                    <span style="margin-top: 10px;font-size: 42px;">起始课作业</span>
                    <span>总作品数：'.$find["people"].' /</span>
                    <span>已提交评价：'.$mysql_find[0].'</span>
            	';
		?>
		<div class="pj_table">
        <?
                //开始遍历作品
                $mysql_state="SELECT * FROM hp_00_all";
                $mysql_results=mysql_query($mysql_state,$con);
                
                
                while($row = mysql_fetch_array($mysql_results))//通过循环读取数据内容
                {
                    $sub=0;
                    $mysql_for_state="SELECT * FROM hp_00_".$user."_toUser Where toUser='".$row["User"]."'";
                    $mysql_for_result=mysql_query($mysql_for_state,$con);
                    if($mysql_for_find=mysql_fetch_array($mysql_for_result))
                    {
                        $g1=$mysql_for_find["g1"];
                        $g2=$mysql_for_find["g2"];
                        $g3=$mysql_for_find["g3"];
                        $sub=1;
                    }
                ?>
                <div class="pj_exm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="pj_img">
                                <?php 
                                	echo '<a target="_blank" href="data/class/00/'.$row["User"].'/"><img src="data/class/00/'.$row["User"].'/'.$row["Url"].'.jpg" class="img-responsive"></a>';
                    				//echo $row['ID'];
                                ?>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pj_oth">
                                <br>
                                <br>
                                <form class="form-horizontal" action="submit_grade.php" method="post">
                                    <div class="row">
                                        <div class="form-inline">
                                            <label class="col-sm-4 control-label">基础功能</label>
                                            <div class="col-sm-offset-1 col-sm-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <?php
                    										if($sub){
                    											echo '<input name="g1" class="form-control" placeholder="'.$g1.'">';
                                                            }else{
                                                            	echo '<input name="g1" class="form-control">';
                                                            }
                    									?>
                                                        <div class="input-group-addon">/10分</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="form-inline">
                                            <label class="col-sm-4 control-label">构图排版</label>
                                            <div class="col-sm-offset-1 col-sm-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <?php
                    										if($sub){
                    											echo '<input name="g2" class="form-control" placeholder="'.$g2.'">';
                                                            }else{
                                                            	echo '<input name="g2" class="form-control">';
                                                            }
                    									?>
                                                        <div class="input-group-addon">/10分</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="form-inline">
                                            <label class="col-sm-4 control-label">新意创意</label>
                                            <div class="col-sm-offset-1 col-sm-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <?php
                    										if($sub){
                    											echo '<input name="g3" class="form-control" placeholder="'.$g3.'">';
                                                            }else{
                                                            	echo '<input name="g3" class="form-control" >';
                                                            }
                    									?>
                                                        <div class="input-group-addon">/05分</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-7">
                                            <?php 
                    							if($sub){
                    								echo '<button type="submit" name="submit_touser" value="'.$row["User"].'" class="btn btn-success btn-block">更新</button>';
                                                }else{
                                                	echo '<button type="submit" name="submit_touser" value="'.$row["User"].'" class="btn btn-primary btn-block">提交</button>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="background-color:#fff;height: 1px;"/>
			<?php
                }
                //关闭对数据库的连接
                mysql_free_result($mysql_results);
                mysql_free_result($result);
            	mysql_close($con);
                
            }else{
                echo'
            		<form class="form-horizontal" style="padding-top: 100px;" action="logincheck.php" method="post">
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
                    </form>
           		';
            	
            } 
        ?>
        </div>
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