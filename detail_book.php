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
    $flag=0;
    $book_code_index=$_GET['id'];
    $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name;');
    $sql->bindValue(':user_name',$user_name);
    $sql->execute();
    $info=$sql->fetch(PDO::FETCH_ASSOC);
    if(isset($_POST['action'])){
        if($_POST['action']==='lent'){
            if($info['user_allow_books']==$info['user_lent_books']){
                $flag=-3;
            }
            else{
                $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index`=:book_code_index');
                $sql->bindValue(':book_code_index',$book_code_index);
                $sql->execute();
                $res=$sql->fetch(PDO::FETCH_ASSOC);
                $book_num=$res['book_num'];
                $lent_num=$res['book_lent'];
                if($book_num==$lent_num){
                    $flag=-2;
                }
                else{
                    $lent_num++;
                    $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_lent`=:lent_num WHERE `book_code_index`=:book_code_index');
                    $sql->bindValue(':lent_num',$lent_num);
                    $sql->bindValue(':book_code_index',$book_code_index);
                    $ju3=$sql->execute();

                    $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `id`=:id');
                    $sql->bindValue(':id',$id);
                    $sql->execute();
                    $res=$sql->fetch(PDO::FETCH_ASSOC);
                    $lent_num=$res['user_lent_books'];
                    $lent_num++;
                    $sql=$pdo->prepare('UPDATE BMS_users SET `user_lent_books`=:lent_num WHERE `id`=:id ');
                    $sql->bindValue(':lent_num',$lent_num);
                    $sql->bindValue(':id',$id);
                    $ju1=$sql->execute();
                    
                    $sql=$pdo->prepare('UPDATE BMS_books SET `book_status`=0,`user_id`=:user_id,`lent_time`=:lent_time WHERE `book_code`=:book_code ');
                    $sql->bindValue(':book_code',$_POST['select']);
                    $sql->bindValue(':user_id',$id);
                    $sql->bindValue(':lent_time',date('Y-m-d H:i:s',time()));
                    $ju2=$sql->execute();

                    $sql=$pdo->prepare('INSERT INTO BMS_books_user_history (`book_code`,`lent_time`,`user_id`,`flag`) VALUES (:book_code,:lent_time,:user_id,1);');
                    $sql->bindValue(':book_code',$_POST['select']);
                    $sql->bindValue(':lent_time',date('Y-m-d H:i:s',time()));
                    $sql->bindValue(':user_id',$id);
                    $ju4=$sql->execute();

                    $sql=$pdo->prepare('INSERT INTO BMS_books_history (`book_code`,`lent_time`,`user_id`,`flag`) VALUES (:book_code,:lent_time,:user_id,1);');
                    $sql->bindValue(':book_code',$_POST['select']);
                    $sql->bindValue(':lent_time',date('Y-m-d H:i:s',time()));
                    $sql->bindValue(':user_id',$id);
                    $ju5=$sql->execute();

                    if($ju1==true&&$ju2==true&&$ju3==true&&$ju4==true&&$ju5==true) $flag=1;
                    else $flag=-1;
                }
            }
        }
    }
    $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index`=:book_code_index;');
    $sql->bindValue(':book_code_index',$book_code_index);
    $sql->execute();
    $book_index=$sql->fetch(PDO::FETCH_ASSOC);
    $sql=$pdo->prepare('SELECT * FROM BMS_books WHERE `book_code` LIKE :book_code_index AND `book_status`=:book_status;');
    $sql->bindValue(':book_code_index',$book_code_index."%");
    $sql->bindValue(':book_status',1);
    $sql->execute();
    $book_left=$sql->fetchall(PDO::FETCH_ASSOC);
    $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name;');
    $sql->bindValue(':user_name',$user_name);
    $sql->execute();
    $info=$sql->fetch(PDO::FETCH_ASSOC);
    if($info === false) {
            echo '<h1>404</h1>';
            return;
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
            var flag=<?php echo $flag ?>;
            if(flag==1) 
            {
                alert("借书成功");
                flag=0;
            }
            if(flag==-1) {
                alert("借书失败");
                flag=0;
            }
            if(flag==-2) {
                alert("库存已空");
                flag=0;
            }
            if(flag==-3) {
                alert("您的书库已满");
                flag=0;
            }
        </script>
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg1.jpg);">
        <div style="text-align: left; display: inline-block;width: 250px;height: 300px;background-color: whitesmoke;position: fixed;top: 30%;left: 0;box-shadow: 5px 5px 5px gray;z-index: 1000;">
                <div style="width: 100%;height: 50px;background-color: lightgreen;text-align: center;"><span style="position: relative;top: 10px;font-size: 150%;color: black">我的书库</span></div>
                <div class="guide" style="width: 100%;height: 200px;margin-left: 10px;margin-top: 10px;">
                    <div class="user" style="width: 90%;background-color: lightgoldenrodyellow;"><span>欢迎，<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
                    <div class="pro"></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>·已借书目/借书上限：<?php echo $info['user_lent_books']?>/<?php echo $info['user_allow_books']?>（<?php echo round($info['user_lent_books']/$info['user_allow_books']*100,1)?>%）</span></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>·等待还书：<?php echo $info['user_waiting_books'] ?> 本</span></div>
                </div>
            </div>
            <form action="detail_book.php?id=<?php echo $book_code_index ?>" method="post">
            <table style="width: 70%;margin: 0 auto;height: 500px;background-color: white;float: right;position: relative;right: 50px;top: 30px;" border="4px solid black">
                <tr style="height: 5%;"> 
                    <td>书籍名称</td>
                    <td><?php echo $book_index['book_name'] ?></td>
                </tr>
                <tr style="height: 5%">
                    <td>书籍作者</td>
                    <td><?php echo $book_index['book_author'] ?></td>
                </tr>
                <tr style="height: 5%">
                    <td>书籍出版社</td>
                    <td><?php echo $book_index['book_pub'] ?></td>
                </tr>
                <tr style="height: 5%">
                    <td>书籍类型</td>
                    <td><?php switch ($book_index['book_type']){
                                        case 'I-00' : echo '文学理论'; break;
                                        case 'I-01' : echo '世界文学'; break;
                                        case 'I-02' : echo '中国文学'; break;
                                        case 'I-03' : echo '各国文学'; break;
                                        case 'G-00' : echo '科学研究'; break;
                                        case 'G-01' : echo '自然科学'; break;
                                        case 'G-02' : echo '天文学、地理科学'; break;
                                        case 'G-03' : echo '生物科学'; break;
                                        case 'G-04' : echo '农业科学'; break;
                                        case 'G-05' : echo '工业技术'; break;
                                        case 'G-06' : echo '航空、航天'; break;
                                        case 'G-07' : echo '环境科学、劳动保护科学(安全科学)'; break;
                                        case 'E-00' : echo '军事理论'; break;
                                        case 'E-01' : echo '世界军事'; break;
                                        case 'E-02' : echo '中国军事'; break;
                                        case 'E-03' : echo '各国军事'; break;
                                        case 'E-04' : echo '战略、战役、战术'; break;
                                        case 'E-05' : echo '军事地形学、军事地理学'; break;
                                        case 'E-06' : echo '军事技术'; break;
                                        case 'K-00' : echo '史学理论'; break;
                                        case 'K-01' : echo '世界史'; break;
                                        case 'K-02' : echo '中国史'; break;
                                        case 'K-03' : echo '亚洲史'; break;
                                        case 'K-04' : echo '非洲史'; break;
                                        case 'K-05' : echo '欧洲史'; break;
                                        case 'K-06' : echo '大洋洲史'; break;
                                        case 'K-07' : echo '美洲史'; break;
                                        case 'K-08' : echo '传记'; break;
                                        case 'K-09' : echo '文学考古'; break;
                                        case 'K-10' : echo '风俗习惯'; break;
                                        case 'K-11' : echo '地理'; break;
                                        case 'R-00' : echo '预防医学、卫生学'; break;
                                        case 'R-01' : echo '中国医学'; break;
                                        case 'R-02' : echo '基础医学'; break;
                                        case 'R-03' : echo '临床医学'; break;
                                        case 'R-04' : echo '内科学'; break;
                                        case 'R-05' : echo '外科学'; break;
                                        case 'R-06' : echo '特种医学'; break;
                                        case 'R-07' : echo '药学'; break;
                                        case 'Z-00' : echo '丛书'; break;
                                        case 'Z-01' : echo '百科全书、类书'; break;
                                        case 'Z-02' : echo '辞典'; break;
                                        case 'Z-03' : echo '论文集、全集、选集、杂著'; break;
                                        case 'Z-04' : echo '年签、年刊'; break;
                                        case 'Z-05' : echo '期刊、连续性出版物'; break;
                                        case 'Z-06' : echo '图书目录、文摘、索引'; break;
                                    }?></td>
                </tr>
                <tr style="height: 50%">
                    <td>书籍简介</td>
                    <td><textarea style="width: 100%;height: 100%;resize: none;" readonly><?php echo $book_index['book_desc'] ?></textarea></td>
                </tr>
                <tr style="height: 10%">
                    <td>可借书籍编号</td>
                    <td>
                        <select name="select">
                            <?php foreach($book_left as $book) {?>
                                <option  value="<?php echo $book['book_code']?>"><?php echo $book['book_code']?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
            </table>
            <div style="position:relative;height:40px;width:210px;margin: 0 auto;top:200px;">
                <div style="display: inline-block;"><button class="btn btn-success" style="width: 100px;" type="submit" name="action" value="lent">确认借书</button></div>
                <div style="display: inline-block;"><a href="library.php?id=<?php echo $id ?>"><button class="btn btn-danger" style="width: 100px;" type="button">返回书库</button></a></div>
            </div>
            </form>
        </div>
    </body>
</html>