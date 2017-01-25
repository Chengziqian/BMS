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
    if(isset($_POST['action'])){
        if($_POST['action']==='signin'){
            $user_name=$_POST['user_name'];
            $user_password=$_POST['user_password'];
            $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name');
            $sql->bindValue(':user_name',$user_name);
            $sql->execute();
            $info=$sql->fetch(PDO::FETCH_ASSOC);
            if($info === false&&$user_name!="") {
                $flag=3;
            }
            else {
                $real_user_password=$info['user_password'];
                if($real_user_password===$user_password) {
                    $flag=1;
                    $_SESSION['user_name']=$user_name;
                    $_SESSION['user_email']=$info['user_email'];
                    $_SESSION['user_type']=$info['user_type'];
                    $_SESSION['user_id']=$info['id'];
                }
                else $flag=2;
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script>
            var flag=0;
            flag=<?php echo $flag ?>;
            if (flag==1){
                alert("登录成功！");
                window.location.href="user_index.php?id=<?php echo $info['id']; ?>";
            }
            if (flag==2){
                alert("密码或用户名错误！");
            }
            if (flag==3){
                alert("该用户名不存在！");
            }
            function tip(x,y){
                if(document.getElementById(x).value==""){
                    document.querySelector(y).innerHTML="内容不能为空";
                }
                else {
                    document.querySelector(y).innerHTML="";
                }
            }
            function check(){
                if(document.getElementById("user_name").value==""||document.getElementById("user_password").value==""){
                    alert("您的输入不合法，请检查无误再登录。");
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
    <body style="background-image:url(./pictures/book2.jpg);">
        <div style="width:100%;height:100px;background-color:rgba(255,255,255,0.7);overflow:hidden;white-space:nowrap;">
                <div style="text-align:center;width:100%;height:100px;">
                    <div style="vertical-align:top;display:inline-block;margin-left:10px;margin-right:200px;width:510px;height:100px;"><span style="font-size:400%;font-family:Microsoft YaHei;">图书管理系统&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;用户登录<sapn></div>
                    <div style="vertical-align:top;display:inline-block;width:510px;height:56px;margin-top:20px;"><span style="font-size:200%;font-family:Microsoft YaHei;">我还没有账号，现在就</sapn>&nbsp;&nbsp;&nbsp;<span><a href="register.php" ><button class="btn btn-success" style="width:80px;height:50px;font-size:80%;font-family:Microsoft YaHei;" >注册</button></a></span></div>
                    <div style="vertical-align:top;display:inline-block;margin-right:10px;width:100px;height:56px;margin-top:20px;"><span><a href="index.html"><button class="btn btn-danger" style="width:100px;height:50px;font-size:130%;font-family:Microsoft YaHei;">返回首页</button></a></span></div> 
                </div>
            </div>
            <form action="signin.php" method="post" onsubmit="return check()">
                <div style="align-text:center;width:400px;margin:0 auto;position:relative;top:70px">
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_name" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 10px;">用户名</label>
                        <input type="text" id="user_name" placeholder="请输入用户名" name="user_name" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;" onblur="tip('user_name','.user_name')">
                        <span class="user_name"></span>
                    </div>
                    <div style="width:400px;margin-bottom:40px;">
                        <label for="user_name" style="color:white;font-size:150%;font-family:Microsoft YaHei;margin-right: 31px;">密码</label>
                        <input type="password" id="user_password" placeholder="请输入密码" name="user_password" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;" onblur="tip('user_password','.user_password')">
                        <span class="user_password"></span>
                    </div>
                        <div>
                            <button type="submit" class="btn btn-warning" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:50px;" name="action" value="signin" >登录</button>
                        </div>
                    </div>
            </form>
        </div>
    </body>