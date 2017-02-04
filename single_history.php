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
    if($user_type!=1)
    header('Location:identity_error.php');
    $flag=0;
    $book_code=$_GET['id'];
    if(isset($_POST['action'])){
        if(substr($_POST['action'],0,4)==='DELB'){
            $sql="SELECT * FROM BMS_books WHERE `book_code` = '".$book_code."' AND `book_status`!=1";
            $res_judge=$pdo->query($sql);
            $lent_num=$res_judge->rowCount();
            if($lent_num==0)
            {
                $sql=$pdo->prepare('DELETE FROM BMS_books WHERE `book_code`=:book_code;');
                $sql->bindValue(':book_code',$book_code);
                $sql->execute();
                $sql="SELECT * FROM BMS_books WHERE `book_code` LIKE '".substr($book_code,0,8)."%'";
                $res=$pdo->query($sql);
                $book_num=$res->rowCount();
                $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_num`=:book_num WHERE `book_code_index`=:book_code_index;');
                $sql->bindValue(':book_num',$book_num);
                $sql->bindValue(':book_code_index',substr($book_code,0,8));
                $sql->execute();
                header('Location:all_history.php');
            }
            else $flag=1;
        }
        if(substr($_POST['action'],0,4)==='DELH'){
                $sql=$pdo->prepare('DELETE FROM BMS_books_history WHERE `id`=:id;');
                $sql->bindValue(':id',substr($_POST['action'],5));
                $sql->execute();
        }
    }
    $sql='SELECT * FROM BMS_books WHERE `book_status`=2;';
    $res_num=$pdo->query($sql);
    $num=$res_num->rowCount();

    $book_code=$_GET['id'];
    $sql=$pdo->prepare('SELECT * FROM BMS_books_history WHERE `book_code`=:book_code;');
    $sql->bindValue(':book_code',$book_code);
    $sql->execute();
    $book_history=$sql->fetchall(PDO::FETCH_ASSOC);
    $j=0;
    foreach($book_history as $select){
        $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `id`=:id;');
        $sql->bindValue(':id',$select['user_id']);
        $sql->execute();
        $user=$sql->fetch(PDO::FETCH_ASSOC);
        $book_history[$j]['user_name']=$user['user_name'];
        $j++;
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
                    width: 90%;
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
        </style>
        <script>
            var flag=0;
            flag=<?php echo $flag?>;
            if(flag==1){
                alert("该书未还入，无法删除");
            }
        </script>
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg3.jpg);min-width:1400px;">
        <form action="single_history.php?id=<?php echo $book_code ?>" method="post">
        <div style="text-align: left; display: inline-block;width: 250px;height: 300px;background-color: whitesmoke;position: fixed;top: 30%;left: 0;box-shadow: 5px 5px 5px gray;z-index: 1000;">
                <div style="width: 100%;height: 50px;background-color: darkred;text-align: center;"><span style="position: relative;top: 10px;font-size: 150%;color: black">管理员</span></div>
                <div class="guide" style="width: 100%;height: 200px;margin-left: 10px;margin-top: 10px;">
                    <div class="user" style="width: 90%;background-color: lightcoral;"><span>欢迎，管理员<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>还书申请：<?php echo $num?> 本</span></div>
                    <div style="position:absolute;top:220px;">
                        <button class="btn btn-danger" style="margin-left:65px;" name="action" value="DELB-<?php echo $book_code?>">删除此书籍</button>
                        <span style="font-size:120%;display:block;">此书编号为:<?php echo $book_code?></span>
                    </div>
                </div>
            </div>
                <table border="4px solid black">
                    <tr>
                        <td>序号</td>
                        <td>书籍编号</td>
                        <td>借书者</td>
                        <td>借书时间</td>
                        <td>申请还书</td>
                        <td>还书时间</td>
                        <td>状态</td>
                        <td>操作</td>
                    </tr>
                    <?php $i=1;?>
                    <?php foreach($book_history as $history){?>
                    <tr>
                        <td><?php echo $i?></td>
                        <td><?php echo $history['book_code']?></td>
                        <td><?php echo $history['user_name']?></td>
                        <td><?php echo $history['lent_time']?></td>
                        <td><?php echo $history['apply_return_time']==NULL?'----':$history['apply_return_time']?></td>
                        <td><?php echo $history['return_time']==NULL?'----':$history['return_time']?></td>
                        <td><?php if($history['apply_return_time']==NULL) echo '正在借用'; if($history['apply_return_time']!=NULL&&$history['return_time']==NULL) echo '等待还书';if($history['apply_return_time']!=NULL&&$history['return_time']!=NULL) echo '已还书'?></td>
                        <td><button <?php echo $history['flag']==0?'':'disabled=disabled;'?> class="btn btn-danger" name="action" value="DELH-<?php echo $history['id']?>">删除该条记录</button></td>
                        <?php $i++;?>
                    </tr>
                    <?php } ?>
                </table>
                </form>
        </div>
    </body>
</html>