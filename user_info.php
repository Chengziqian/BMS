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
    $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name;');
    $sql->bindValue(':user_name',$user_name);
    $sql->execute();
    $info=$sql->fetch(PDO::FETCH_ASSOC);
    if($info === false) {
            echo '<h1>404</h1>';
            return;
    }
    $flag=0;
    if (isset($_POST['action'])){
        if($_POST['action']==='modify'){
            $flag=1;
        }
        if($_POST['action']==='save'){
            $user_name_modify=$_POST['user_name'];
            $user_email_modify=$_POST['user_email']==''?'未编辑':$_POST['user_email'];
            $user_rel_name_modify=$_POST['user_rel_name']=='未编辑'?'':$_POST['user_rel_name'];
            $user_school_modify=$_POST['user_school']=='未编辑'?'':$_POST['user_school'];
            $sql=$pdo->prepare('UPDATE BMS_users SET `user_name`=:user_name_1,`user_email`=:user_email,`user_rel_name`=:user_rel_name,`user_school`=:user_school WHERE `user_name`=:user_name_old;');
            $sql->bindValue(':user_name_1',$user_name_modify);
            $sql->bindValue(':user_email',$user_email_modify);
            $sql->bindValue(':user_rel_name',$user_rel_name_modify);
            $sql->bindValue(':user_school',$user_school_modify);
            $sql->bindValue(':user_name_old',$user_name);
            $res=$sql->execute();
            if($res==true){
                $flag=2;
                $_SESSION['user_name']=$user_name_modify;
                $_SESSION['user_email']=$user_email_modify;
                $user_name=$_SESSION['user_name'];
                $user_email=$_SESSION['user_email'];
                $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name;');
                $sql->bindValue(':user_name',$user_name);
                $sql->execute();
                $info=$sql->fetch(PDO::FETCH_ASSOC);
                if($info === false) {
                        echo '<h1>404</h1>';
                        return;
                }
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
        </style>
        <script>
            var judge=0;
            judge=<?php echo $flag ?>;
            if (judge==2){
                alert('修改成功！');
            }
            if (judge==3){
                alert('抱歉，修改失败！');
            }
            function check(){
                if(document.getElementById('user_name').value==""){
                    alert("用户名不能为空！");
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
                    <div style="position: relative;top: 30px;left: 10px;"><span>·等待还书：<?php echo $info['user_waiting_books'] ?> 本</span></div>
                </div>
            </div>
            <form action="user_info.php?id=<?php echo $id ?>" method="post" onsubmit="return check()">
            <table style="width: 40%;margin: 0 auto;height: 50%;background-color: white;float: right;position: absolute;right: 350px;top: 30px;" border="4px solid black">
                <tr style="height: 10%;"> 
                    <td>用户名</td>
                    <td><input id="user_name" name="user_name" type="text" value="<?php echo $info['user_name'];?>" style="width: 100%;height: 100%;text-align: center;" <?php echo $flag==1?'':'disabled="disabled"'?>></td>
                </tr>
                <tr style="height: 10%">
                    <td>邮箱</td>
                    <td><input name="user_email" type="text" value="<?php echo $info['user_email'];?>" style="width: 100%;height: 100%;text-align: center;" <?php echo $flag==1?'':'disabled="disabled"'?>></td>
                </tr>
                <tr style="height: 10%">
                    <td>用户组</td>
                    <td><input name="user_type" type="text" value="<?php echo $info['user_type']==1?'管理员':'普通用户';?>" style="width: 100%;height: 100%;text-align: center;" disabled="disabled"></td>
                </tr>
                <tr style="height: 10%">
                    <td>真实姓名</td>
                    <td><input name="user_rel_name" type="text" value="<?php echo $info['user_rel_name']==""?'未编辑':$info['user_rel_name'];?>" style="width: 100%;height: 100%;text-align: center;"<?php echo $flag==1?'':'disabled="disabled"'?>></td>
                </tr>
                <tr style="height: 10%">
                    <td>所在学校</td>
                    <td><input name="user_school" type="text" value="<?php echo $info['user_school']==""?'未编辑':$info['user_school'];?>" style="width: 100%;height: 100%;text-align: center;" <?php echo $flag==1?'':'disabled="disabled"'?>></td>
                </tr>
            </table>
            <div style="position: absolute;top: 530px;right: 600px;">
                <div style="margin-top: 30px;display: inline-block;"><a href="change_password.php?id=<?php echo $id ?>"><button class="btn btn-warning" style="width: 100px;" type="button">修改密码</button></a></div>
                <div style="margin-top: 30px;display: inline-block;"><button class="<?php echo $flag==1?'btn btn-success':'btn btn-info'?>" style="width: 100px;" type="submit" value="<?php echo $flag==1?'save':'modify'?>" name="action"><?php echo $flag==1?'保存':'修改'?></button></div>
                <div style="margin-top: 30px;display: inline-block;"><a href="user_index.php?id=<?php echo $id ?>"><button class="btn btn-danger" style="width: 100px;" type="button">返回</button></a></div>
            </div>
            </form>
        </div>
    </body>
</html>
