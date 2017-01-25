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
    $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name');
    $sql->bindValue(':user_name',$user_name);
    $sql->execute();
    $info=$sql->fetch(PDO::FETCH_ASSOC);
    if($info === false) {
            echo '<h1>404</h1>';
            return;
    }
    if (isset($_POST['action'])){
        if($_POST['action']==='sure'){
            $flag=0;
            if($_POST['user_old_password']==$info['user_password']){
                $sql="UPDATE BMS_users SET `user_password`='".$_POST['user_new_password']."'WHERE `user_name`='".$user_name."';";
                $res=$pdo->query($sql);
                if($res==true){
                    $flag=1;
                }
                else $flag=2;
            }
            else $flag=3;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="global.css">
        <style>
            td{
                font-size: 150%;
                color: black;
                border: 4px solid black;
            }
            tr{
                border: 4px solid black;
            }
            .guide div span{
                    position: relative;top: 20px;font-size: 150%;color: black;
                }
            .guide .user{
                    display: inline-block;
                    width: 45%;
                    height: 70px;
                    box-shadow: 5px 5px 5px gray;
                    text-align: center;
                    margin-top: 8px;
                    opacity: 0.7;
                }
            .pro{
                    position: absolute;
                    left:10px;
                    top:150px;
                    width: <?php echo round($info['user_lent_books']/$info['user_allow_books']*90,1)?>%;
                    height: 30px;
                    margin: 0 auto;
                    background-color: palegreen;
                }
            form span{
                    font-size:150%;
                    color:red;
                }
        </style>
        <script>
            var flag=0;
            var judge=0;
            judge=<?php echo $flag ?>;
            if (judge==1){
                alert('修改密码成功');
                window.location.href='user_info.php?id=<?php echo $id?>';
                judge=0;
            }
            if (judge==2){
                alert('抱歉，修改失败，请重试。');
                judge=0;
            }
            if (judge==3){
                alert('原密码错误！');
                judge=0;
            }
            function tip(x,y){
                if(document.getElementById(x).value==""){
                    document.querySelector(y).innerHTML="密码不能为空";
                }
                else {
                    document.querySelector(y).innerHTML="";
                }
                if(document.getElementById("user_new_password").value!=""&&document.getElementById("user_password_repeat").value!=""){
                    if(document.getElementById("user_new_password").value!=document.getElementById("user_password_repeat").value){
                        document.querySelector(".user_password_repeat").innerHTML="两次输入密码不一致";
                        flag=0;
                    }
                    else {
                        document.querySelector(".user_password_repeat").innerHTML="";
                        flag=1;
                    }
                }
            }
            function check(){
                if(flag==0||document.getElementById("user_old_password").value==""||document.getElementById("user_new_password").value==""||document.getElementById("user_password_repeat").value==""){
                    alert("您的输入不合法，请检查无误再更改。");
                    return false;
                }
                else return true;
            }
        </script>
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg1.jpg);">
        <div style="text-align: left; display: inline-block;width: 250px;height: 300px;background-color: whitesmoke;position: fixed;top: 30%;left: 0;box-shadow: 5px 5px 5px gray;z-index: 1000;">
                <div style="width: 100%;height: 50px;background-color: darkorange;text-align: center;"><span style="position: relative;top: 10px;font-size: 150%;color: black">我的资料</span></div>
                <div class="guide" style="width: 100%;height: 200px;margin-left: 10px;margin-top: 10px;">
                    <div class="user" style="width: 90%;background-color: lightsalmon;"><span>欢迎，<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
                    <div class="pro"></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>·已借书目/借书上限：<?php echo $info['user_lent_books']?>/<?php echo $info['user_allow_books']?>（<?php echo round($info['user_lent_books']/$info['user_allow_books']*100,1)?>%）</span></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>·等待还书：0 本</span></div>
                </div>
            </div>
            <form action="change_password.php?id=<?php echo $id ?>" method="post" onsubmit="return check()">
                <div style="position: absolute;right:700px;top: 100px;">
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_oid_password" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 31px;">输入原密码</label>
                        <input type="password" id="user_old_password" placeholder="输入原密码" name="user_old_password" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;"onblur="tip('user_old_password','.user_old_password')">
                        <span class="user_old_password"></span>
                    </div>
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_new_password" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 31px;">输入新密码</label>
                        <input type="password" id="user_new_password" placeholder="输入新密码" name="user_new_password" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;"onblur="tip('user_new_password','.user_new_password')">
                        <span class="user_new_password"></span>
                    </div>
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_password_repeat" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 31px;">确认新密码</label>
                        <input type="password" id="user_password_repeat" placeholder="确认新密码" name="user_password_repeat" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;"onblur="tip('user_password_repeat','.user_password_repeat')">
                        <span class="user_password_repeat"></span>
                    </div>
                    </div>
            <div style="position: absolute;top: 530px;right: 800px;">
                <div style="margin-top: 30px;display: inline-block;"><a href="user_index.php?id=<?php echo $id ?>"><button class="btn btn-warning" style="width: 100px;" type="submit" value="sure" name="action">确认修改</button></a></div>
                <div style="margin-top: 30px;display: inline-block;"><a href="user_index.php?id=<?php echo $id ?>"><button class="btn btn-danger" style="width: 100px;" type="button">返回</button></a></div>
            </div>
            </form>
        </div>
    </body>
</html>