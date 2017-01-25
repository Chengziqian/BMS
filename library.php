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
                    display: block;
                }
                figcaption div{
                    position: absolute;
                    left:20px;
                    width: 0%;
                    height: 30px;
                    margin: 0 auto;
                    background-color: cornflowerblue;
                    transition: width 1.0s;
                }
                figure:hover figcaption div{
                    width: 40%;
                }
                .guide div{
                    display: inline-block;
                    width: 45%;
                    height: 70px;
                    box-shadow: 5px 5px 5px gray;
                    text-align: center;
                    margin-top: 8px;
                    opacity: 0.7;
                }
                .guide div:hover{
                    opacity: 1;
                    transition: opacity 0.3s;
                }
                .guide div span{
                    position: relative;top: 20px;font-size: 150%;color: black;
                }
                .btn btn-info{
                     margin-top: 10px;
                }
        </style>
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg1.jpg);">
            <div style="text-align: left; display: inline-block;width: 250px;height: 400px;background-color: whitesmoke;position: fixed;top: 30%;left: 0;box-shadow: 5px 5px 5px gray;z-index: 1000;">
                <div style="width: 100%;height: 50px;background-color: grey;text-align: center;"><span style="position: relative;top: 10px;font-size: 150%;color: black">导航栏</span></div>
                <div class="guide" style="width: 100%;height: 200px;margin-left: 10px;margin-top: 10px;">
                    <div style="width: 90%;background-color: darkgray;"><span>欢迎，<a href="user_index.php?id=<?php echo $id ?>"><?php echo $user_name ?></a>！</span></div>
                    <a href="#"><div style=" background-color: salmon;"><span>文学</span></div></a>
                    <a href="#"><div style=" background-color: cornflowerblue;"><span>科技</span></div></a>
                    <a href="#"><div style=" background-color: green;"><span>军事</span></div></a>
                    <a href="#"><div style=" background-color: gold"><span>历史、地理</span></div></a>
                    <a href="#"><div style=" background-color: lightblue;"><span>医药、卫生</span></div></a>
                    <a href="#"><div style=" background-color: rosybrown;"><span>综合性图书</span></div></a>
                </div>
            </div>
                <ul>
                    <li class="book1">
                        <figure>
                            <div style="z-index: 1000;position: absolute; width:120px;height: 30px;background-color: rgba(255,0,0,0.6);margin: 0;top: 10px;left: 20px;">
                                <span style="margin-top: 5px;display: inline-block;">库存已空</span>
                            </div>
                            <div>
                                <img src="./cover/Big Data.jpg">
                            </div>
                            <figcaption>
                                <h3>一本书读懂大数据</h3>
                                <span>--黄颖 著</span>
                                <div></div>
                                <span style="margin-top: 30px;">已借/总数：100%</span>
                                <span>分类：科技</span>
                                <button class="btn btn-info">查看详情</button>
                            </figcaption>
                        </figure>
                    </li>
                    <li class="book2">
                        <figure>
                            <div>
                                <img src="./cover/Big Data.jpg">
                            </div>
                            <figcaption>
                                <h3>一本书读懂大数据</h3>
                                <span>--黄颖 著</span>
                                <div></div>
                                <span style="margin-top: 30px;">已借/总数：100%</span>
                                <span>分类：科技</span>
                                <button class="btn btn-info">查看详情</button>
                            </figcaption>
                        </figure>
                    </li>
                    <li class="book1">
                        <figure>
                            <div>
                                <img src="./cover/Big Data.jpg">
                            </div>
                            <figcaption>
                                <h3>一本书读懂大数据</h3>
                                <span>--黄颖 著</span>
                                <div></div>
                                <span style="margin-top: 30px;">已借/总数：100%</span>
                                <span>分类：科技</span>
                                <button class="btn btn-info">查看详情</button>
                            </figcaption>
                        </figure>
                    </li>
                    <li class="book1">
                        <figure>
                            <div>
                                <img src="./cover/Big Data.jpg">
                            </div>
                            <figcaption>
                                <h3>一本书读懂大数据</h3>
                                <span>--黄颖 著</span>
                                <div></div>
                                <span style="margin-top: 30px;">已借/总数：100%</span>
                                <span>分类：科技</span>
                                <button class="btn btn-info">查看详情</button>
                            </figcaption>
                        </figure>
                    </li>
                    <li class="book1">
                        <figure>
                            <div>
                                <img src="./cover/Big Data.jpg">
                            </div>
                            <figcaption>
                                <h3>一本书读懂大数据</h3>
                                <span>--黄颖 著</span>
                                <div></div>
                                <span style="margin-top: 30px;">已借/总数：100%</span>
                                <span>分类：科技</span>
                                <button class="btn btn-info">查看详情</button>
                            </figcaption>
                        </figure>
                    </li>
                    <li class="book1">
                        <figure>
                            <div>
                                <img src="./cover/Big Data.jpg">
                            </div>
                            <figcaption>
                                <h3>一本书读懂大数据</h3>
                                <span>--黄颖 著</span>
                                <div></div>
                                <span style="margin-top: 30px;">已借/总数：100%</span>
                                <span>分类：科技</span>
                                <button class="btn btn-info">查看详情</button>
                            </figcaption>
                        </figure>
                    </li>
                </ul>
        </div>
    </body>
</html>