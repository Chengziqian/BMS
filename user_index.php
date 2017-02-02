<?php
    date_default_timezone_set('PRC');
    session_start();
    $conn_hostname='localhost';
    $conn_database='BMS';
    $conn_username='root';
    $conn_psaaword='root';
    try{
        $pdo=new PDO('mysql:host='.$conn_hostname.';dbname='.$conn_database,$conn_username,$conn_psaaword);
        $pdo->exec('SET NAMES UTF8');
    }
    catch (Exception $e){
    echo '<h1>数据库链接错误！</h1>';
    return;
    }
    if($_SESSION['user_name']==''&&$_SESSION['user_email']=='')
        header("Location:signin.php");
    else{
        $user_name=$_SESSION['user_name'];
        $user_email=$_SESSION['user_email'];
        $user_type=$_SESSION['user_type'];
        $id=$_SESSION['user_id'];
    }
    if(isset($_POST['action'])){
        if($_POST['action']==='signout'){
            session_destroy();
            $flag=1;
        }
        if($_POST['action']==='user_history'){
            header('Location:user_history.php?id='.$id);
        }
        if($_POST['action']==='user_info'){
            header('Location:user_info.php?id='.$id);
        }
        if($_POST['action']==='user_books'){
            header('Location:user_books.php?id='.$id);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="global.css">
        <script>
            var flag=0;
            flag=<?php echo $flag ?>;
            if (flag==1){
                alert("您已经成功退出登录！");
                window.location.href="index.html";
            }
        </script>
    </head>
    <body>
        <div id="body1" style="white-space: nowrap;overflow: hidden;min-width: 1002px;min-height: 600px;background-image:url(./pictures/bg2.jpg);">
            <div style="width:350px;height:100%;background-color: black;margin: 0;float: left;display: inline-block;overflow: hidden;white-space: nowrap;vertical-align:top;margin: 0;padding: 0;">
                <div style="text-align:center;width: 100%;height: 150px;">
                    <div style="position: relative;top: 30px; margin:0 auto; width: 210px; height: 70px"><sapn style="font-size:200%;font-family:Microsoft YaHei;color: white"><?php echo $user_name;?></sapn></div>
                    <div style="position: relative;top: 40px; margin:0 auto; width: 210px; height: 35px"><sapn style="font-size:100%;font-family:Microsoft YaHei;color: white"><?php echo $user_email;?></sapn></div>
                </div>
                <div class="p1" style="text-align:center;width: 100%;height: 100px;background-color: gray;cursor:pointer;"onmouseover="this.style.backgroundColor='darkorange'" onmouseout="this.style.backgroundColor='gray'" onclick="document.getElementById('user_info').click();">
                    <div style="height:100px;width:100px;background-color: darkorange;display: inline-block;vertical-align: top;float: left;">
                        <div id="info" style="background-image: url(./icons/info_stroke.png);width:49px;height:41px;position:relative;top:25%;margin:0 auto;"><img src="./icons/info_stroke.png"width="49px" height="41px" ></div>
                    </div><!--
                    --><div style="position: relative;top: 30%; margin:0 auto; width: 210px; height: 70px;display: inline-block;vertical-align: top;"><sapn style="font-size:200%;font-family:Microsoft YaHei;color: white">个人资料</sapn></div>
                </div>
                <div class="p2" style="text-align:center;width: 100%;height: 100px;background-color: gray;cursor:pointer;"onmouseover="this.style.backgroundColor='cornflowerblue'" onmouseout="this.style.backgroundColor='gray'"onclick="document.getElementById('user_history').click();">
                    <div style="height:100px;width:100px;background-color: cornflowerblue;display: inline-block;vertical-align: top;float: left;">
                        <div style="width:49px;height:49px;position:relative;top:25%;margin:0 auto;"><img src="./icons/p6.png"width="49px" height="49px" 
                    ></div>
                    </div><!--
                    --><div style="position: relative;top: 30%; margin:0 auto; width: 210px; height: 70px;display: inline-block;vertical-align: top;"><sapn style="font-size:200%;font-family:Microsoft YaHei;color: white">借书记录</sapn></div>
                </div>
                 <div class="p2" style="text-align:center;width: 100%;height: 100px;background-color: gray;cursor:pointer;"onmouseover="this.style.backgroundColor='lightgreen'" onmouseout="this.style.backgroundColor='gray'"onclick="document.getElementById('user_books').click();">
                    <div style="height:100px;width:100px;background-color: lightgreen;display: inline-block;vertical-align: top;float: left;">
                        <div style="width:49px;height:49px;position:relative;top:25%;margin:0 auto;"><img src="./icons/p4.png"width="49px" height="49px" 
                    ></div>
                    </div><!--
                    --><div style="position: relative;top: 30%; margin:0 auto; width: 210px; height: 70px;display: inline-block;vertical-align: top;"><sapn style="font-size:200%;font-family:Microsoft YaHei;color: white">我的图书</sapn></div>
                </div>
                <div class="p3" style="text-align:center;width: 100%;height: 100px;background-color: gray;cursor:pointer;"onmouseover="this.style.backgroundColor='mediumpurple'" onmouseout="this.style.backgroundColor='gray'" onclick="document.getElementById('user_signout').click();">
                    <div style="height:100px;width:100px;background-color: mediumpurple;display: inline-block;vertical-align: top;float: left;">
                        <div style="width:54px;height:46px;position:relative;top:25%;margin:0 auto;"><img src="./icons/signout_stroke.png"width="54px" height="46px"></div>
                    </div><!--
                    --><div style="position: relative;top: 30%; margin:0 auto; width: 210px; height: 70px;display: inline-block;vertical-align: top;"><sapn style="font-size:200%;font-family:Microsoft YaHei;color: white">退出登录</sapn></div>
                </div>
        </div><!--
        --><div style="display: inline-block;width: 700px;height: 100%;overflow: hidden;white-space: nowrap;vertical-align:top;margin: 0;padding: 0;">
            <div style="width: 292px;height: 336px;display: inline-block;position: relative;left: 70px;top:50px;z-index: 3"><a href="library.php?id=<?php echo $id ?>"><img src="./icons/fun1.png"width="292px" height="336px" onmouseover="this.src='./icons/fun1_l.png'"onmouseout="this.src='./icons/fun1.png'" ></a></div>
            <div style="width: 292px;height: 336px;position: relative;top: 314px;left: -75px;display: inline-block;z-index: 2"><a href="user_books.php?id=<?php echo $id?>"><img src="./icons/fun3.png"width="292px" height="336px" onmouseover="this.src='./icons/fun3_l.png'"onmouseout="this.src='./icons/fun3.png'" ></a></div>
            <div style="width: 292px;height: 336px;position: relative;top:50px;left: -220px;display: inline-block;z-index: 3;"><?php if($user_type==1) echo '<a href="management.php?id=<?php echo $id?>">';?><img src="<?php if($user_type==1) echo'./icons/fun2.png'; else echo './icons/fun2_disabled.png';?>"width="292px" height="336px" onmouseover="<?php if($user_type==1) echo "this.src='./icons/fun2_l.png'"; else echo ""?>"  onmouseout="<?php if($user_type==1) echo "this.src='./icons/fun2.png'"; else echo ""?>" ><?php if($user_type==1) echo '</a>';?></div>
            </div>
            <form action="user_index.php?id=<?php echo $id ?>" method="post">
                <button type="submit" style="display:none" name="action" value="user_info" id="user_info"></button>
                <button type="submit" style="display:none" name="action" value="user_history" id="user_history"></button>
                <button type="submit" style="display:none" name="action" value="user_books" id="user_books"></button>
                <button type="submit" style="display:none" name="action" value="signout" id="user_signout"></button>
            </form>
        </div>
    </body>
</html>