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
    if(isset($_POST['action'])){
        if(substr($_POST['action'],0,3)=='SEL'){
            header('Location:detail_book.php?id='.substr($_POST['action'],4,8));
        }
        if(substr($_POST['action'],0,3)=='DEL'){
            $sql=$pdo->prepare('DELETE FROM BMS_books_user_history WHERE `id`=:id;');
            $sql->bindValue(':id',substr($_POST['action'],4));
            $sql->execute();
        }
    }
    $sql=$pdo->prepare('SELECT * FROM BMS_books_user_history WHERE `user_id`=:id;');
    $sql->bindValue(':id',$id);
    $sql->execute();
    $res=$sql->fetchall(PDO::FETCH_ASSOC);
    $i=0;
    foreach($res as $insert){
        $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index`=:book_code_index;');
        $sql->bindValue(':book_code_index',substr($insert['book_code'],0,8));
        $sql->execute();
        $res_index=$sql->fetch(PDO::FETCH_ASSOC);
        $res[$i]['book_name']=$res_index['book_name'];
        $res[$i]['book_author']=$res_index['book_author'];
        $sql=$pdo->prepare('SELECT * FROM BMS_books WHERE `book_code`=:book_code;');
        $sql->bindValue(':book_code',$insert['book_code']);
        $sql->execute();
        $res_status=$sql->fetch(PDO::FETCH_ASSOC);
        $res[$i]['book_status']=$res_status['book_status'];
        $i++;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="global.css">
        <style>
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
                .btn btn-info{
                     margin-top: 10px;
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
                table{
                    text-align: center;
                    width: 70%;
                    background-color: white;
                    position: absolute;
                    right: 150px;
                    top: 30px;
                }
                td{
                    font-size: 150%;
                    color: black;
                    border: 4px solid black;
            }
                tr{
                    border: 4px solid black;
            }
        </style>
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg1.jpg);display: table;min-width:1400px;">
        <div style="text-align: left; display: inline-block;width: 250px;height: 300px;background-color: whitesmoke;position: fixed;top: 30%;left: 0;box-shadow: 5px 5px 5px gray;z-index: 1000;">
                <div style="width: 100%;height: 50px;background-color: cornflowerblue;text-align: center;"><span style="position: relative;top: 10px;font-size: 150%;color: black">我的历史纪录</span></div>
                <div class="guide" style="width: 100%;height: 200px;margin-left: 10px;margin-top: 10px;">
                    <div class="user" style="width: 90%;background-color: lightblue;"><span>欢迎，<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
                    <div class="pro"></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>·已借书目/借书上限：<?php echo $info['user_lent_books']?>/<?php echo $info['user_allow_books']?>（<?php echo round($info['user_lent_books']/$info['user_allow_books']*100,1)?>%）</span></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>·等待还书：<?php echo $info['user_waiting_books'] ?> 本</span></div>
                </div>
        </div>
            <form action="user_history.php?id=<?php echo $id ?>" method="post">
                <table border="4px solid black">
                    <tr>
                        <td>书籍名称</td>
                        <td>书籍编号</td>
                        <td>书籍作者</td>
                        <td>借书时间</td>
                        <td>申请还书时间</td>
                        <td>状态</td>
                        <td>操作</td>
                    </tr>
                    <?php foreach($res as $history) {?>
                    <tr>
                        <td><?php echo $history['book_name'] ?></td>
                        <td><?php echo $history['book_code'] ?></td>
                        <td><?php echo $history['book_author'] ?></td>
                        <td><?php echo $history['lent_time'] ?></td>
                        <td><?php echo $history['apply_return_time']==NULL?'未申请':$history['apply_return_time'] ?></td>
                        <td><?php if($history['book_status']==0&&$history['flag']!=0) echo "正在被借用";if($history['book_status']==2&&$history['flag']==1) echo "等待管理员还书";if($history['book_status']==-1) echo "已逾期";if($history['flag']==0) echo "已归还";?></td>
                        <td><button class="btn btn-info" name="action" value="SEL-<?php echo $history['book_code'] ?>">查看该书详情</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-danger" name="action" value="DEL-<?php echo $history['id'] ?>">删除该条记录</button></td>
                    </tr>
                    <?php }?>
                </table>
                </form>
        </div>
    </body>
</html>