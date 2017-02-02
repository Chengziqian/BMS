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
    if(isset($_POST['action'])){
        if(substr($_POST['action'],0,6)==='return'){
            $sql=$pdo->prepare('UPDATE BMS_books_history SET `return_time`=:return_time,`flag`=0 WHERE `book_code`=:book_code AND `flag`=1;');
            $sql->bindValue(':book_code',substr($_POST['action'],7));
            $sql->bindValue(':return_time',date('Y-m-d H:i:s',time()));
            $sql->execute();

            $sql=$pdo->prepare('UPDATE BMS_books_user_history SET `flag`=0 WHERE `book_code`=:book_code AND `flag`=1;');
            $sql->bindValue(':book_code',substr($_POST['action'],7));
            $sql->execute();

            $sql=$pdo->prepare('UPDATE BMS_books SET `book_status`=1,`user_id`=NULL,`lent_time`=NULL WHERE `book_code`=:book_code');
            $sql->bindValue(':book_code',substr($_POST['action'],7));
            $sql->execute();

            $sql="SELECT * FROM `BMS_books` WHERE `book_code` LIKE '".substr($_POST['action'],7,8)."%' AND `book_status`!=1;";
            $res_lent_num_sel=$pdo->query($sql);
            $res_lent_num=$res_lent_num_sel->rowCount();
            $sql=$pdo->prepare('UPDATE `BMS_books_index` SET `book_lent`=:book_lent WHERE `book_code_index`=:book_code_index;');
            $sql->bindValue(':book_code_index',substr($_POST['action'],7,8));
            $sql->bindValue(':book_lent',$res_lent_num);
            $sql->execute();

            $sql="SELECT * FROM `BMS_books` WHERE `user_id`='".$id."';";
            $user_lent=$pdo->query($sql);
            $user_lent_num=$user_lent->rowCount();
            $sql=$pdo->prepare('UPDATE `BMS_users` SET `user_lent_books`=:lent WHERE `id`=:id;');
            $sql->bindValue(':id',$id);
            $sql->bindValue(':lent',$user_lent_num);
            $sql->execute();
        }
    }
    $sql='SELECT * FROM BMS_books WHERE `book_status`=2;';
    $res_num=$pdo->query($sql);
    $num=$res_num->rowCount();
    $sql=$pdo->prepare('SELECT * FROM BMS_books WHERE `book_status`=2;');
    $sql->execute();
    $res=$sql->fetchall(PDO::FETCH_ASSOC);
    $i=0;
    $now_time=date('Y-m-d H:i:s',time());
    foreach($res as $select){
        $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index`=:book_code_index;');
        $sql->bindValue(':book_code_index',substr($select['book_code'],0,8));
        $sql->execute();
        $book_info=$sql->fetch(PDO::FETCH_ASSOC);
        $res[$i]['book_name']=$book_info['book_name'];
        $res[$i]['book_author']=$book_info['book_author'];

        $sql=$pdo->prepare('SELECT * FROM BMS_books_history WHERE `book_code`=:book_code; AND `flag`=1;');
        $sql->bindValue(':book_code',$select['book_code']);
        $sql->execute();
        $book_info_time=$sql->fetch(PDO::FETCH_ASSOC);
        $res[$i]['apply_return_time']=$book_info_time['apply_return_time'];

        $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `id`=:user_id;'); 
        $sql->bindValue(':user_id',$select['user_id']);
        $sql->execute();
        $book_info_user=$sql->fetch(PDO::FETCH_ASSOC);
        $res[$i]['user_name']=$book_info_user['user_name'];

        $res[$i]['day']=(strtotime($now_time)-strtotime($select['lent_time']))/86400-0.5<=0?0:round((strtotime($now_time)-strtotime($select['lent_time']))/86400-0.5,0);
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
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg1.jpg);">
            <div style="text-align: left; display: inline-block;width: 250px;height: 300px;background-color: whitesmoke;position: fixed;top: 30%;left: 0;box-shadow: 5px 5px 5px gray;z-index: 1000;">
                <div style="width: 100%;height: 50px;background-color: darkred;text-align: center;"><span style="position: relative;top: 10px;font-size: 150%;color: black">管理员</span></div>
                <div class="guide" style="width: 100%;height: 200px;margin-left: 10px;margin-top: 10px;">
                    <div class="user" style="width: 90%;background-color: lightcoral;"><span>欢迎，管理员<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>还书申请：<?php echo $num?> 本</span></div>
                </div>
            </div>
                <form action="return_books.php?id=<?php echo $id ?>" method="post">
                <table border="4px solid black">
                    <tr>
                        <td>书籍名称</td>
                        <td>书籍编号</td>
                        <td>书籍作者</td>
                        <td>借书人</td>
                        <td>借书时间</td>
                        <td>申请还书时间</td>
                        <td>是否逾期</td>
                        <td>操作</td>
                    </tr>
                    <?php foreach($res as $book){ ?>
                        <tr>
                            <td><?php echo $book['book_name']?></td>
                            <td><?php echo $book['book_code']?></td>
                            <td><?php echo $book['book_author']?></td>
                            <td><?php echo $book['user_name']?></td>
                            <td><?php echo $book['lent_time']?></td>
                            <td><?php echo $book['apply_return_time']?></td>
                            <td><?php echo $book['book_status']==-1?'是(已逾期:'.($book['day']-45).'天)':'否'?></td>
                            <td><button name="action" value="return-<?php echo $book['book_code'] ?>" type="submit" class="btn btn-info">还书</button></td>
                        </tr>
                    <?php }?>
                </table>
                </form>
        </div>
    </body>
</html>