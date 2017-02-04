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
    if (isset($_POST['action'])){
        if($_POST['action']==='register'){
            if($_POST['user_name']!=""&&$_POST['user_email']!=""&&$_POST['user_password']!=""&&$_POST['user_password_repeat']!="")
            {
                $user_name=$_POST['user_name'];
                $user_email=$_POST['user_email'];
                $sql="SELECT * FROM BMS_users WHERE `user_name`='".$_POST['user_name']."'";
                $res=$pdo->query($sql);
                $rowCount=$res->rowCount();
                if ($rowCount==0){
                    $sql=$pdo->prepare('INSERT INTO BMS_users(`user_name`,`user_password`,`user_email`,`user_type`,`user_lent_books`,`user_allow_books`,`user_reg_time`) VALUES(:user_name,:user_password,:user_email,:user_type,:user_lent_books,:user_allow_books,:user_reg_time);');
                    $sql->bindValue(':user_name',$_POST['user_name']);
                    $sql->bindValue(':user_password',$_POST['user_password']);
                    $sql->bindValue(':user_email',$_POST['user_email']);
                    $sql->bindValue(':user_type',0);
                    $sql->bindValue(':user_lent_books',0);
                    $sql->bindValue(':user_allow_books',30);
                    $sql->bindValue(':user_reg_time',date('Y-m-d H:i:s',time()));
                    $execute_res=$sql->execute();
                    if ($execute_res==true){
                        $judge=1;
                        $_SESSION['user_name']=$user_name;
                        $_SESSION['user_email']=$user_email;
                        $_SESSION['user_type']=0;
                        $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name');
                        $sql->bindValue(':user_name',$user_name);
                        $sql->execute();
                        $info=$sql->fetch(PDO::FETCH_ASSOC);
                        if($info === false) {
                                echo '<h1>404</h1>';
                                return;
                            }
                            else {
                                $_SESSION['user_id']=$info['id'];
                            }
                        }
                    else{
                            $judge=3;
                        }
                }
                else{
                    $judge=2;
                }
            }
            else $judge=-1;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script>
            var judge=0;
            judge=<?php echo $judge?>;
            if (judge==1){
                alert("恭喜，注册成功！");
                judge=0;
                window.location.href="user_index.php?id=<?php echo $info['id']; ?>"; 
            }
            if(judge==2){
                alert("该用户名已经被注册！");
                judge=0;
            }
            if(judge==3){
                alert("抱歉，注册失败");
                judge=0;
            }
            if(judge==-1){
                alert("内容不能为空！");
                judge=0;
            }
            var flag=0;
            function tip(x,y){
                if(document.getElementById(x).value==""){
                    document.querySelector(y).innerHTML="内容不能为空";
                }
                else {
                    document.querySelector(y).innerHTML="";
                }
                if(document.getElementById("user_password").value!=""&&document.getElementById("user_password_repeat").value!=""){
                    if(document.getElementById("user_password").value!=document.getElementById("user_password_repeat").value){
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
                if(flag==0||document.getElementById("user_name").value==""||document.getElementById("user_email").value==""||document.getElementById("user_password").value==""||document.getElementById("user_password_repeat").value==""){
                    alert("您的输入不合法，请检查无误再注册。");
                    return false;
                }
                else return true;
            }
        </script>
        <style>
         form span{
             font-size:150%;
             color:red;
         }
        </style>
    </head>
    <body style="background-image:url(./pictures/book3.jpg);min-width:1400px;">
        <div id="body1">
            <div style="width:100%;height:100px;background-color:rgba(255,255,255,0.7);overflow:hidden;white-space:nowrap;">
                <div style="text-align:center;width:100%;height:100px;">
                    <div style="vertical-align:top;display:inline-block;margin-left:10px;margin-right:200px;width:510px;height:100px;"><span style="font-size:400%;font-family:Microsoft YaHei;">图书管理系统&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;用户注册</sapn></div>
                    <div style="vertical-align:top;display:inline-block;width:510px;height:56px;margin-top:20px;"><span style="font-size:200%;font-family:Microsoft YaHei;">我已注册，现在就</sapn>&nbsp;&nbsp;&nbsp;<span><a href="signin.php" ><button class="btn btn-warning" style="width:80px;height:50px;font-size:80%;font-family:Microsoft YaHei;" >登录</button></a></span></div>
                    <div style="vertical-align:top;display:inline-block;margin-right:10px;width:100px;height:56px;margin-top:20px;"><span><a href="index.html"><button class="btn btn-danger" style="width:100px;height:50px;font-size:130%;font-family:Microsoft YaHei;">返回首页</button></a></span></div> 
                </div>
            </div>
            <form action="register.php" onsubmit="return check()" method="post">
                <div style="align-text:center;width:400px;margin:0 auto;position:relative;top:70px">
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_name" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 10px;">用户名</label>
                        <input type="text" id="user_name" placeholder="请输入用户名" name="user_name" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;" onblur="tip('user_name','.user_name')">
                        <span class="user_name"></span>
                    </div>
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_email" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 31px;">邮箱</label>
                        <input type="text" id="user_email" placeholder="请输入邮箱地址" name="user_email" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;"onblur="tip('user_email','.user_email')">
                        <span class="user_email"></span>
                    </div>
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_password" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 31px;">密码</label>
                        <input type="password" id="user_password" placeholder="请输入密码" name="user_password" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;"onblur="tip('user_password','.user_password')">
                        <span class="user_password"></span>
                    </div>
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_password_repeat" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 31px;">确认密码</label>
                        <input type="password" id="user_password_repeat" placeholder="请再次输入密码" name="user_password_repeat" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;"onblur="tip('user_password_repeat','.user_password_repeat')">
                        <span class="user_password_repeat"></span>
                    </div>
                        <div>
                            <button type="submit" class="btn btn-success" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:50px;" name="action" value="register" >注册</button>
                        </div>
                    </div>
            </form>
        </div>
    </body>