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
    $sql='SELECT * FROM BMS_books WHERE `book_status`=2;';
    $res_num=$pdo->query($sql);
    $num=$res_num->rowCount();

    $sql=$pdo->prepare('SELECT * FROM BMS_books_index;');
    $sql->execute();
    $books=$sql->fetchall(PDO::FETCH_ASSOC);
    
    $i=0;
    foreach($books as $book){
        $sql=$pdo->prepare('SELECT * FROM BMS_books WHERE `book_code` LIKE :book_code;');
        $sql->bindValue(':book_code',$book['book_code_index']."%");
        $sql->execute();
        $book_info=$sql->fetchall(PDO::FETCH_ASSOC);
        $books[$i]['book_info']=$book_info;
        $j=0;
        foreach($books[$i]['book_info'] as $user){
            $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `id`=:user_id;'); 
            $sql->bindValue(':user_id',$user['user_id']);
            $sql->execute();
            $book_info_user=$sql->fetch(PDO::FETCH_ASSOC);
            $books[$i]['book_info'][$j]['user_name']=$book_info_user['user_name'];
            $j++;
        }
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
                    width: 80%;
                    background-color: white;
                    position: absolute;
                    right: 20px;
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
            var info= new Array();
            <?php foreach ($books as $book){?>
                <?php foreach ($book['book_info'] as $book_info){?>
                info['<?php echo $book_info['book_code']?>']=<?php echo "'".$book_info['user_name']."'"?>;
                <?php } ?>
            <?php }?>
            function lable_absolute_left(obj){
                document.getElementById('lable').style.display='';
                var lable=document.getElementById("lable");
                var left_set=obj.getBoundingClientRect().left;
                var top_set=obj.getBoundingClientRect().top;
                lable.style.left=(left_set-90)+"px";
                lable.style.top=(top_set-50)+"px";
                document.getElementById('lable_info').innerHTML=obj.id+(info[obj.id]==''?'':'&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;'+info[obj.id]);
            }
            function hide(){
                document.getElementById('lable').style.display='none';
            }
        </script>
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg3.jpg);">
        <?php var_dump($books)?>
        <div style="text-align: left; display: inline-block;width: 250px;height: 300px;background-color: whitesmoke;position: fixed;top: 30%;left: 0;box-shadow: 5px 5px 5px gray;z-index: 1000;">
                <div style="width: 100%;height: 50px;background-color: darkred;text-align: center;"><span style="position: relative;top: 10px;font-size: 150%;color: black">管理员</span></div>
                <div class="guide" style="width: 100%;height: 170px;margin-left: 10px;margin-top: 10px;">
                    <div class="user" style="width: 90%;background-color: lightcoral;"><span>欢迎，管理员<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>还书申请：<?php echo $num?> 本</span></div>
                </div>
                <svg style="width: 200px;height:70px;padding: 10px;z-index:1000;">
                    <g transform="translate(0,0)">
                        <rect width="15px" height="15px" fill="gray" x="13" y="0"></rect>
                        <rect width="15px" height="15px" fill="green" x="13" y="35"></rect>
                    </g>
                    
                    <g transform="translate(100,0)">
                        <rect width="15px" height="15px" fill="red" x="13" y="0"></rect>
                        <rect width="15px" height="15px" fill="orange" x="13" y="35"></rect>
                    </g>
                </svg>
                <span style="display:block;position:absolute;top:236px;left:50px;font-size:120%;">可借</span>
                <span style="display:block;position:absolute;top:236px;left:150px;font-size:120%;">已逾期</span>
                <span style="display:block;position:absolute;top:272px;left:50px;font-size:120%;">正在借用</span>
                <span style="display:block;position:absolute;top:272px;left:150px;font-size:120%;">等待还书</span>
            </div>
            <div id="lable" style="display:none;position: absolute;top:300px;left:1169px;;background-color: black;opacity: 0.7;z-index:10000;border: 10px solid rgba(0, 0, 0, 0.7);border-radius: 1.5em 0.5em 1.5em 0.5em;">
                <span id="lable_info" style="font-size: 130%;color: white;">G-01-001-1&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;Admin</span>
            </div>
        <table>
            <tr style="height: 34px;">
                <td>书籍名称</td>
                <td>书籍索引号</td>
                <td>已借/总数</td>
                <td>书籍状态</td>
            </tr>
            <tr>
                <?php foreach($books as $book) {?>
                <td><?php echo $book['book_name']?></td>
                <td><?php echo $book['book_code_index']?></td>
                <td><?php echo $book['book_lent'] ?>/<?php echo $book['book_num'] ?></td>
                <td style="height: 110px;">
                    <svg style="width: 100%;height: 100%;padding: 10px;">
                    <?php $col=floor($book['book_num']/4);$j=0;?>
                    <?php for ($i=0,$count=0;$i<=$col;$i++,$count+=20) {?>
                        <g transform="translate(<?php  echo $count ?>,0)">
                        <?php for ($j_c=0,$d=0;$book['book_info'][$j]!=NULL&&$j_c<4;$j++,$j_c++,$d+=20){?>
                        <a href="single_history.php?id=<?php echo $book['book_info'][$j]['book_code'] ?>"><rect width="15px" height="15px" fill="<?php if($book['book_info'][$j]['book_status']==1) echo 'gray';if($book['book_info'][$j]['book_status']==0) echo 'green';if($book['book_info'][$j]['book_status']==-1) echo 'red';if($book['book_info'][$j]['book_status']==2) echo 'orange'?>" x="13" y="<?php echo $d?>" id="<?php echo $book['book_info'][$j]['book_code'] ?>" onmouseover="lable_absolute_left(this)" onmouseout="hide()"></rect></a>
                        <?php }?>
                        </g>
                    <?php }?>
                    </svg>
                </td>
            </tr>
                <?php }?>
            </table>  
        </div>
    </body>
</html>
