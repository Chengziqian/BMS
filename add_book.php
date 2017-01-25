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
        span{
            color: white;
        }
        img{
            width: 200px;
            height: 250px;
        }
    </style>
    <script>
        var tip;
        function sel(x){
            //document.getElementById("book_code").value=x+'-';
            tip=x;
            var parent=document.getElementById("book_type_sec");
            var a=document.getElementById("book_type_sec").getElementsByTagName("option");
            switch (x){
                case "NONE":{
                    if(a.length!=1){
                        for(var i=a.length-1;i>0;i--){
                            parent.removeChild(a[i]);
                        }
                    }
                    var child=document.getElementById("origin");
                    parent.removeChild(child);
                    var op=document.createElement("option");
                    var op_text=document.createTextNode("----请选择第一项----");
                    op.appendChild(op_text);
                    op.value='NONE';
                    op.id='origin';
                    parent.appendChild(op);
                }break;
                case "I":{
                    if(a.length!=1){
                        for(var i=a.length-1;i>0;i--){
                                parent.removeChild(a[i]);
                        }
                    }
                    var child=document.getElementById("origin");
                    parent.removeChild(child);
                    var op=document.createElement("option");
                    var op_text=document.createTextNode("----请选择----");
                    op.appendChild(op_text);
                    op.value='NONE';
                    op.id='origin';
                    parent.appendChild(op);

                    var op0=document.createElement("option");
                    var op0_text=document.createTextNode("文学理论");
                    op0.appendChild(op0_text);
                    op0.value='0';
                    parent.appendChild(op0);

                    var op1=document.createElement("option");
                    var op1_text=document.createTextNode("世界文学");
                    op1.appendChild(op1_text);
                    op1.value='1';
                    parent.appendChild(op1);

                    var op2=document.createElement("option");
                    var op2_text=document.createTextNode("中国文学");
                    op2.appendChild(op2_text);
                    op2.value='2';
                    parent.appendChild(op2);

                    var op3=document.createElement("option");
                    var op3_text=document.createTextNode("各国文学");
                    op3.appendChild(op3_text);
                    op3.value='3';
                    parent.appendChild(op3);
                }break;
                case 'G':{
                    if(a.length!=1){
                        for(var i=a.length-1;i>0;i--){
                            parent.removeChild(a[i]);
                        }
                    }
                    var child=document.getElementById("origin");
                    parent.removeChild(child);
                    var op=document.createElement("option");
                    var op_text=document.createTextNode("----请选择----");
                    op.appendChild(op_text);
                    op.value='NONE';
                    op.id='origin';
                    parent.appendChild(op);

                    var op0=document.createElement("option");
                    var op0_text=document.createTextNode("科学研究");
                    op0.appendChild(op0_text);
                    op0.value='0';
                    parent.appendChild(op0);

                    var op1=document.createElement("option");
                    var op1_text=document.createTextNode("自然科学");
                    op1.appendChild(op1_text);
                    op1.value='1';
                    parent.appendChild(op1);

                    var op2=document.createElement("option");
                    var op2_text=document.createTextNode("天文学、地理科学");
                    op2.appendChild(op2_text);
                    op2.value='2';
                    parent.appendChild(op2);

                    var op3=document.createElement("option");
                    var op3_text=document.createTextNode("生物科学");
                    op3.appendChild(op3_text);
                    op3.value='3';
                    parent.appendChild(op3);

                    var op4=document.createElement("option");
                    var op4_text=document.createTextNode("农业科学");
                    op4.appendChild(op4_text);
                    op4.value='4';
                    parent.appendChild(op4);

                    var op5=document.createElement("option");
                    var op5_text=document.createTextNode("工业技术");
                    op5.appendChild(op5_text);
                    op5.value='5';
                    parent.appendChild(op5);

                    var op6=document.createElement("option");
                    var op6_text=document.createTextNode("航空、航天");
                    op6.appendChild(op6_text);
                    op6.value='6';
                    parent.appendChild(op6);

                    var op7=document.createElement("option");
                    var op7_text=document.createTextNode("环境科学、劳动保护科学(安全科学)");
                    op7.appendChild(op7_text);
                    op7.value='7';
                    parent.appendChild(op7);
            }break;
            case 'E':{
                    if(a.length!=1){
                        for(var i=a.length-1;i>0;i--){
                            parent.removeChild(a[i]);
                        }
                    }
                    var child=document.getElementById("origin");
                    parent.removeChild(child);
                    var op=document.createElement("option");
                    var op_text=document.createTextNode("----请选择----");
                    op.appendChild(op_text);
                    op.value='NONE';
                    op.id='origin';
                    parent.appendChild(op);

                    var op0=document.createElement("option");
                    var op0_text=document.createTextNode("军事理论");
                    op0.appendChild(op0_text);
                    op0.value='0';
                    parent.appendChild(op0);

                    var op1=document.createElement("option");
                    var op1_text=document.createTextNode("世界军事");
                    op1.appendChild(op1_text);
                    op1.value='1';
                    parent.appendChild(op1);

                    var op2=document.createElement("option");
                    var op2_text=document.createTextNode("中国军事");
                    op2.appendChild(op2_text);
                    op2.value='2';
                    parent.appendChild(op2);

                    var op3=document.createElement("option");
                    var op3_text=document.createTextNode("各国军事");
                    op3.appendChild(op3_text);
                    op3.value='3';
                    parent.appendChild(op3);

                    var op4=document.createElement("option");
                    var op4_text=document.createTextNode("战略、战役、战术");
                    op4.appendChild(op4_text);
                    op4.value='4';
                    parent.appendChild(op4);

                    var op5=document.createElement("option");
                    var op5_text=document.createTextNode("军事地形学、军事地理学");
                    op5.appendChild(op5_text);
                    op5.value='5';
                    parent.appendChild(op5);

                    var op6=document.createElement("option");
                    var op6_text=document.createTextNode("军事技术");
                    op6.appendChild(op6_text);
                    op6.value='6';
                    parent.appendChild(op6);
            }break;
            case 'K':{
                    if(a.length!=1){
                        for(var i=a.length-1;i>0;i--){
                            parent.removeChild(a[i]);
                        }
                    }
                    var child=document.getElementById("origin");
                    parent.removeChild(child);
                    var op=document.createElement("option");
                    var op_text=document.createTextNode("----请选择----");
                    op.appendChild(op_text);
                    op.value='NONE';
                    op.id='origin';
                    parent.appendChild(op);

                    var op0=document.createElement("option");
                    var op0_text=document.createTextNode("史学理论");
                    op0.appendChild(op0_text);
                    op0.value='0';
                    parent.appendChild(op0);

                    var op1=document.createElement("option");
                    var op1_text=document.createTextNode("世界史");
                    op1.appendChild(op1_text);
                    op1.value='1';
                    parent.appendChild(op1);

                    var op2=document.createElement("option");
                    var op2_text=document.createTextNode("中国史");
                    op2.appendChild(op2_text);
                    op2.value='2';
                    parent.appendChild(op2);

                    var op3=document.createElement("option");
                    var op3_text=document.createTextNode("亚洲史");
                    op3.appendChild(op3_text);
                    op3.value='3';
                    parent.appendChild(op3);

                    var op4=document.createElement("option");
                    var op4_text=document.createTextNode("非洲史");
                    op4.appendChild(op4_text);
                    op4.value='4';
                    parent.appendChild(op4);

                    var op5=document.createElement("option");
                    var op5_text=document.createTextNode("欧洲史");
                    op5.appendChild(op5_text);
                    op5.value='5';
                    parent.appendChild(op5);

                    var op6=document.createElement("option");
                    var op6_text=document.createTextNode("大洋洲史");
                    op6.appendChild(op6_text);
                    op6.value='6';
                    parent.appendChild(op6);

                    var op7=document.createElement("option");
                    var op7_text=document.createTextNode("美洲史");
                    op7.appendChild(op7_text);
                    op7.value='7';
                    parent.appendChild(op7);

                    var op8=document.createElement("option");
                    var op8_text=document.createTextNode("传记");
                    op8.appendChild(op8_text);
                    op8.value='8';
                    parent.appendChild(op8);

                    var op9=document.createElement("option");
                    var op9_text=document.createTextNode("文学考古");
                    op9.appendChild(op9_text);
                    op9.value='9';
                    parent.appendChild(op9);

                    var op10=document.createElement("option");
                    var op10_text=document.createTextNode("风俗习惯");
                    op10.appendChild(op10_text);
                    op10.value='10';
                    parent.appendChild(op10);

                    var op11=document.createElement("option");
                    var op11_text=document.createTextNode("地理");
                    op11.appendChild(op11_text);
                    op11.value='11';
                    parent.appendChild(op11);
            }break;
            case 'R':{
                    if(a.length!=1){
                        for(var i=a.length-1;i>0;i--){
                            parent.removeChild(a[i]);
                        }
                    }
                    var child=document.getElementById("origin");
                    parent.removeChild(child);
                    var op=document.createElement("option");
                    var op_text=document.createTextNode("----请选择----");
                    op.appendChild(op_text);
                    op.value='NONE';
                    op.id='origin';
                    parent.appendChild(op);

                    var op0=document.createElement("option");
                    var op0_text=document.createTextNode("预防医学、卫生学");
                    op0.appendChild(op0_text);
                    op0.value='0';
                    parent.appendChild(op0);

                    var op1=document.createElement("option");
                    var op1_text=document.createTextNode("中国医学");
                    op1.appendChild(op1_text);
                    op1.value='1';
                    parent.appendChild(op1);

                    var op2=document.createElement("option");
                    var op2_text=document.createTextNode("基础医学");
                    op2.appendChild(op2_text);
                    op2.value='2';
                    parent.appendChild(op2);

                    var op3=document.createElement("option");
                    var op3_text=document.createTextNode("临床医学");
                    op3.appendChild(op3_text);
                    op3.value='3';
                    parent.appendChild(op3);

                    var op4=document.createElement("option");
                    var op4_text=document.createTextNode("内科学");
                    op4.appendChild(op4_text);
                    op4.value='4';
                    parent.appendChild(op4);

                    var op5=document.createElement("option");
                    var op5_text=document.createTextNode("外科学");
                    op5.appendChild(op5_text);
                    op5.value='5';
                    parent.appendChild(op5);

                    var op6=document.createElement("option");
                    var op6_text=document.createTextNode("特种医学");
                    op6.appendChild(op6_text);
                    op6.value='6';
                    parent.appendChild(op6);

                    var op7=document.createElement("option");
                    var op7_text=document.createTextNode("药学");
                    op7.appendChild(op7_text);
                    op7.value='7';
                    parent.appendChild(op7);
            }break;
            case 'Z':{
                    if(a.length!=1){
                        for(var i=a.length-1;i>0;i--){
                            parent.removeChild(a[i]);
                        }
                    }
                    var child=document.getElementById("origin");
                    parent.removeChild(child);
                    var op=document.createElement("option");
                    var op_text=document.createTextNode("----请选择----");
                    op.appendChild(op_text);
                    op.value='NONE';
                    op.id='origin';
                    parent.appendChild(op);

                    var op0=document.createElement("option");
                    var op0_text=document.createTextNode("丛书");
                    op0.appendChild(op0_text);
                    op0.value='0';
                    parent.appendChild(op0);

                    var op1=document.createElement("option");
                    var op1_text=document.createTextNode("百科全书、类书");
                    op1.appendChild(op1_text);
                    op1.value='1';
                    parent.appendChild(op1);

                    var op2=document.createElement("option");
                    var op2_text=document.createTextNode("辞典");
                    op2.appendChild(op2_text);
                    op2.value='2';
                    parent.appendChild(op2);

                    var op3=document.createElement("option");
                    var op3_text=document.createTextNode("论文集、全集、选集、杂著");
                    op3.appendChild(op3_text);
                    op3.value='3';
                    parent.appendChild(op3);

                    var op4=document.createElement("option");
                    var op4_text=document.createTextNode("年签、年刊");
                    op4.appendChild(op4_text);
                    op4.value='4';
                    parent.appendChild(op4);

                    var op5=document.createElement("option");
                    var op5_text=document.createTextNode("期刊、连续性出版物");
                    op5.appendChild(op5_text);
                    op5.value='5';
                    parent.appendChild(op5);

                    var op6=document.createElement("option");
                    var op6_text=document.createTextNode("图书目录、文摘、索引");
                    op6.appendChild(op6_text);
                    op6.value='6';
                    parent.appendChild(op6);
            }break;
        }
        }
        function sel_sec(x){
            if (x!='NONE'&&tip!='NONE')
            document.getElementById("book_code").value=tip+'-'+x+'-';
        }
    </script>
    </head>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg3.jpg);height:1000px;">
            <form>
                <div style="position: relative;top: 50px;">
                    <span style="font-size:150%;font-family:Microsoft YaHei;">书籍名称</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="book_name" placeholder="请输入书名" name="book_name" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;">
                </div>
                <div style="margin: 50px;position: relative;top: 50px;">
                    <span style="font-size:150%;font-family:Microsoft YaHei;">书籍作者</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="book_author" placeholder="请输入书名" name="book_author" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;">
                </div>
                <div style="margin: 50px;position: relative;top: 50px;">
                    <span style="font-size:150%;font-family:Microsoft YaHei;">书籍类型</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select style="width:100px;height:30px;vertical-align:top;" id="book_type" name="book_type" onchange="sel(this.value)">
                        <option value="NONE">----请选择----</option>
                        <option value="I">文学</option>
                        <option value="G">科技</option>
                        <option value="E">军事</option>
                        <option value="K">历史、地理</option>
                        <option value="R">医药、卫生</option>
                        <option value="Z">综合性图书</option>
                    </select>
                    <select id="book_type_sec" style="width:300px;height:30px;vertical-align:top;" onchange="sel_sec(this.value)">
                        <option value="NONE" id="origin">----请选择第一项----</option>
                    </select>
                </div>
                <div style="margin: 50px;position: relative;top: 50px;">
                    <span style="font-size:150%;font-family:Microsoft YaHei;">书籍编号</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="book_code" placeholder="请输入书籍编号" name="book_code" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;">
                </div>
                <div style="margin: 50px;position: relative;top: 50px;">
                    <span style="font-size:150%;font-family:Microsoft YaHei;">书籍出版社</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="book_pub" placeholder="请输入书籍出版社" name="book_pub" style="font-size:150%;font-family:Microsoft YaHei;width:400px;height:42px;">
                </div>
                <div style="margin: 50px;position: relative;top: 50px;height: 100px;">
                    <span style="font-size:150%;font-family:Microsoft YaHei;position: relative;top: -60px;">书籍简介</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <textarea name="book_desc" style="resize: none;width:400px;height:100px;;font-size:150%;display: inline-block;"placeholder="请输入书籍简介"></textarea>
                </div>
                <div style="margin: 50px;position: relative;top: 50px;margin-bottom: 0px;">
                    <span style="font-size:150%;font-family:Microsoft YaHei;">书籍封面</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="" type="file" id="book_cover"  name="book_name" style="font-size:100%;font-family:Microsoft YaHei;width:200px;height:42px;display: inline-block;color: red;">
                    <div style="display: inline-block;border: 5px solid wheat;width: 210px;height: 260px;"><img src="./cover/no_picture.jpg" ></div>
                </div>
                <div style="margin-top: 100px;display: inline-block;"><button type="submit" name="action" value="add" class="btn btn-success" style="width: 100px;">添加书籍</button></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div style="margin-top: 100px;display: inline-block;"><a href="management.php?id=<?php echo $id?>"><button class="btn btn-danger" type="button" style="width: 100px;">返回</button></a></div>
            </form>
        </div>
    </body>
</html>