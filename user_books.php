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
        if(substr($_POST['action'],0,5)==='apply'){
            $sql=$pdo->prepare('UPDATE BMS_books SET `book_status`=2 WHERE `book_code`=:book_code');
            $sql->bindValue(':book_code',substr($_POST['action'],6));
            $sql->execute();
            $sql=$pdo->prepare('UPDATE BMS_books_user_history SET `apply_return_time`=:apply_return_time WHERE `book_code`=:book_code AND `flag`=1;');
            $sql->bindValue(':book_code',substr($_POST['action'],6));
            $sql->bindValue(':apply_return_time',date('Y-m-d H:i:s',time()));
            $sql->execute();
            $sql=$pdo->prepare('UPDATE BMS_books_history SET `apply_return_time`=:apply_return_time WHERE `book_code`=:book_code AND `flag`=1;');
            $sql->bindValue(':book_code',substr($_POST['action'],6));
            $sql->bindValue(':apply_return_time',date('Y-m-d H:i:s',time()));
            $sql->execute();
        }
    }
    $sql=$pdo->prepare('SELECT * FROM BMS_books WHERE `user_id`=:user_id;');
    $sql->bindValue(':user_id',$id);
    $sql->execute();
    $lent_info=$sql->fetchall(PDO::FETCH_ASSOC);
    $i=0;
    $now_time=date('Y-m-d H:i:s',time());
    foreach($lent_info as $select){
        $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index`=:book_code_index;');
        $sql->bindValue(':book_code_index',substr($select['book_code'],0,8));
        $sql->execute();
        $res=$sql->fetch(PDO::FETCH_ASSOC);
        $lent_info[$i]['book_name']=$res['book_name'];
        $lent_info[$i]['book_author']=$res['book_author'];
        $lent_info[$i]['book_type']=$res['book_type'];
        $lent_info[$i]['d']=(strtotime($now_time)-strtotime($select['lent_time']))/86400-0.5<=0?0:round((strtotime($now_time)-strtotime($select['lent_time']))/86400-0.5,0);
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
                ul{
                    float: right;
                    list-style: none;
                    margin: 0;
                    padding: 0;
                    text-align: left;
                    width: 80%;
                    height: 100%;
                }
                li{
                    text-align: center;
                    display: inline-block;
                    width: 220px;
                    background-color: lightgrey;
                    margin: 30px;
                    box-shadow: 5px 5px 5px gray;
                }
                figure{
                    width: 220px;
                    height: 265px;
                    transform-style: preserve-3d;
                }
                img{
                    width: 200px;
                    height: 250px;
                }
                figcaption{
                    z-index: 2000;
                    height: 100%;
                    width: 100%;
                    position: absolute;
                    top:0;
                    left: 0;
                    opacity: 0;
                    background: #2c3f52;
                    transition: transform 0.3s,opacity 0.3s;
                }
                figure:hover figcaption{
                    opacity: 0.8;
                    transform: translate(15px,15px);
                }
                li div{
                    margin-top: 10px;
                }
                figcaption h3{
                    color:white;
                }
                figcaption span{
                    color:white;
                    text-align: right;
                    padding: 10px;
                    display: inline-block;
                }
                <?php foreach($lent_info as $book) {?>
                figcaption .d<?php echo $book['id'] ?>{
                    position: absolute;
                    left:20px;
                    width: 0%;
                    height: 30px;
                    margin: 0 auto;
                    background-color: cornflowerblue;
                    transition: width 1.0s,background-color 1.0s;
                }
                figure:hover .d<?php echo $book['id'] ?>{
                    width:
                    <?php if (round($book['d']/45,1)>1.0) 
                     echo 80;
                     else 
                     echo round($book['d']/45*80,1)?>%;
                    <?php if (round($book['d']/45,1)<0.8&&round($book['d']/45,1)>=0.6) echo 'background-color: yellow;';
                          if (round($book['d']/45,1)>=0.8) echo 'background-color: red;'?>
                }
                <?php } ?>
                
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
        </style>
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg1.jpg);display: table;">
            <div style="text-align: left; display: inline-block;width: 250px;height: 300px;background-color: whitesmoke;position: fixed;top: 30%;left: 0;box-shadow: 5px 5px 5px gray;z-index: 1000;">
                <div style="width: 100%;height: 50px;background-color: lightgreen;text-align: center;"><span style="position: relative;top: 10px;font-size: 150%;color: black">我的书库</span></div>
                <div class="guide" style="width: 100%;height: 200px;margin-left: 10px;margin-top: 10px;">
                    <div class="user" style="width: 90%;background-color: lightgoldenrodyellow;"><span>欢迎，<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
                    <div class="pro"></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>·已借书目/借书上限：<?php echo $info['user_lent_books']?>/<?php echo $info['user_allow_books']?>（<?php echo round($info['user_lent_books']/$info['user_allow_books']*100,1)?>%）</span></div>
                    <div style="position: relative;top: 30px;left: 10px;"><span>·等待还书：<?php echo $info['user_waiting_books'] ?> 本</span></div>
                </div>
            </div>
            <form action="user_books.php?id=<?php echo $id ?>" method="post">
                <ul>
                    <?php foreach($lent_info as $book) {?>
                    <li>
                        <figure>
                            <div style=" border-radius: 1.5em 0.5em 1.5em 0.5em; display:<?php echo $book['book_status']!=0?'':'none' ?>; z-index: 1000;position: absolute; width:120px;height: 30px;background-color: rgba(255,0,0,0.6);margin: 0;top: 10px;left: 20px;">
                                <span style="margin-top: 5px;display: inline-block;"><?php if($book['book_status']==2) echo '等待管理员还书';if($book['book_status']==-1) echo '逾期'?> </span>
                            </div>
                            <div>
                                <img src="./upload/<?php echo substr($book['book_code'],0,8);?>">
                            </div>
                            <figcaption>
                                <h3><?php echo $book['book_name'] ?></h3>
                                <span style="display: block;">--<?php echo $book['book_author']?> 著</span>
                                <div class="d<?php echo $book['id'] ?>"></div>
                                <span style="margin-top: 30px;">已借天数：<?php echo $book['d']?>/45（<?php echo round($book['d']/45*100,1)?>%）</span>
                                <span style="color: red;text-align: left;display:<?php echo $book['d']>45?'':'none'?>">逾期：<?php echo $book['d']-45 ?>天</span>
                                <span>分类：<?php 
                                    switch ($book['book_type']){
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
                                    }?></span>
                                    <span style="padding:0;">书籍编号:<?php echo $book['book_code'] ?></span>
                                <button class="btn btn-info" name="action" value="apply-<?php echo $book['book_code']?>" type="submit" <?php echo $book['book_status']==2?'disabled="disabled"':''?>>申请还书</button>
                            </figcaption>
                        </figure>
                    </li>
                    <?php } ?>
                </ul>
                </form>
        </div>
    </body>
</html>