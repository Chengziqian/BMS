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
    $sql='SELECT * FROM BMS_books WHERE `book_status`=2;';
    $res=$pdo->query($sql);
    $num=$res->rowCount();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="global.css">
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg3.jpg);">
            <div style="position: relative;top: 100px;"><span style="font-size: 300%;color: white">欢迎管理员，<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
            <div style="position: relative;top: 100px;"><h2 style="color: white;">您可能想要：</h2></div>
            <div style="margin-top: 40px;position: relative;top: 100px;">
            <div style="display: inline-block;width: 200px;margin: 30px">
                <a href="add_book.php?id=<?php echo $id?>"><img src="./icons/m1.png" onmouseover="this.src='./icons/m1_hover.png'"onmouseout="this.src='./icons/m1.png'"></a>
            </div>
            <div style="display: inline-block;width: 200px;margin: 30px;">
                <div style="z-index: 1000px;position: absolute;"><span style="display: block;position: absolute;left: 7px;top: 5px; font-size: 100%;color: white;"><?php echo $num ?></span><img src="./icons/info.png"></div>
                <a href="return_books.php?id=<?php echo $id?>"><img src="./icons/m2.png" onmouseover="this.src='./icons/m2_hover.png'"onmouseout="this.src='./icons/m2.png'">
            </div>
            <div style="display: inline-block;width: 200px;margin: 30px">
                <a href="all_hisroty.php?id=<?php echo $id?>"><img src="./icons/m3.png" onmouseover="this.src='./icons/m3_hover.png'"onmouseout="this.src='./icons/m3.png'"></a>
            </div>
            </div>
        </div>
    </body>
</html>