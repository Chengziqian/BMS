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
    $book_code_index=$_GET['id'];
    $flag=0;
    $exist=-1;
    if(isset($_POST['action'])){
        if($_POST['action']==='book_name'){
            $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_name`=:book_name WHERE `book_code_index`=:book_code_index;');
            $sql->bindValue(':book_name',$_POST['book_name']);
            $sql->bindValue(':book_code_index',$book_code_index);
            $sql->execute();
        }
        if($_POST['action']==='book_author'){
            $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_author`=:book_author WHERE `book_code_index`=:book_code_index;');
            $sql->bindValue(':book_author',$_POST['book_author']);
            $sql->bindValue(':book_code_index',$book_code_index);
            $sql->execute();
        }
        if($_POST['action']==='book_pub'){
            $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_pub`=:book_pub WHERE `book_code_index`=:book_code_index;');
            $sql->bindValue(':book_pub',$_POST['book_pub']);
            $sql->bindValue(':book_code_index',$book_code_index);
            $sql->execute();
        }
        if($_POST['action']==='book_desc'){
            $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_desc`=:book_desc WHERE `book_code_index`=:book_code_index;');
            $sql->bindValue(':book_desc',$_POST['book_desc']);
            $sql->bindValue(':book_code_index',$book_code_index);
            $sql->execute();
        }
        if($_POST['action']==='book_cover_m'){
          if($_FILES['book_cover_m']["error"]>0&&$_FILES['book_cover_m']["error"]!=4){
                $flag=1;
            }
            else if ($_FILES['book_cover_m']["error"]==4) {
                
            }
            else{
                if($_FILES['book_cover_m']['type']=="image/jpg"||$_FILES['book_cover_m']['type']=="image/png"||$_FILES['book_cover_m']['type']=="image/jpeg"){
                    if($_FILES['book_cover_m']["error"]>0){
                        $flag=1;
                    }
                    else{
                        $cover_name=$book_code_index;
                        if(file_exists("upload/".$cover_name)){
                            unlink("upload/".$cover_name);
                        }
                            move_uploaded_file($_FILES['book_cover_m']['tmp_name'],"upload/".$cover_name);
                            $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_cover_m`=:book_cover_m WHERE `book_code_index`=:book_code;');
                            $sql->bindValue(':book_cover_m',substr($_FILES['book_cover_m']['type'],6));
                            $sql->bindValue(':book_code',$book_code_index);
                            $sql->execute();
                    }
            }
            else $flag=2;
    }
        }
    }
    $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index`=:book_code_index;');
    $sql->bindValue('book_code_index',$book_code_index);
    $sql->execute();
    $book_info=$sql->fetch(PDO::FETCH_ASSOC);
    if(file_exists("upload/".$book_code_index)) $exist=1;
    else $exist=0;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="global.css">
    </head>
    <style>
        td{
            font-size: 140%;
            border: 4px solid black;
        }
        tr{
            border: 4px solid black;
        }
        table{
            background-color: white;
        }
    </style>
    <script>
        var flag=0;
        flag=<?php echo $flag?>;
        if(flag==1){
            alert("封面上传失败");
        }
        if(flag==2){
            alert("请选择 .jpg .jepg .png格式的封面图片。");
        }
        function change(obj,x,y){
            obj.style.display='none';
            document.getElementById(y).style.display='';        
            document.getElementById(x).disabled='';
        }
        function file(obj,x,y){
            obj.style.display='none';
            document.getElementById(y).style.display='';
            document.getElementById(x).click();
        }
        function preview(obj){
            var cover=document.getElementById('book_cover_m_preview');
            if(obj.files&&obj.files[0]){
                var format=/^.+\.(jpg|png|jpeg|JPG|PNG|JPEG)$/;
                var file_name=obj.value;
                if(format.test(file_name))
                {
                    cover.style.width='200px';
                    cover.style.height='250px';
                    cover.src=window.URL.createObjectURL(obj.files[0]);
                }
                else {
                    obj.outerHTML=obj.outerHTML;
                    alert("请选择 .jpg .jepg .png格式的封面图片。");
                }
            }
        }
    </script>
    <body>
        <div id="body1" style="background-image:url(./pictures/bg3.jpg);">
        <form action="edit_index.php?id=<?php echo $book_code_index?>" method="post" enctype="multipart/form-data">
            <table style="width: 50%;height: 95%;margin: 0 auto;">
                <tr style="height: 60px;">
                    <td style="width: 20%">书籍名称</td>
                    <td><input id="book_name" name="book_name" type="text" value="<?php echo $book_info['book_name']?>" style="width: 100%;height: 100%;text-align: center;" disabled="disabled"></td>
                    <td><button  class="btn btn-info" type="button" onclick="change(this,'book_name','bn_save')">修改</button>
                    <button id="bn_save" name="action" value="book_name" class="btn btn-success" type="submit" style="display:none">保存</button>
                    </td>
                </tr>
                <tr style="height: 60px;">
                    <td>索引号</td>
                    <td><input id="book_code" name="book_code_index" type="text" value="<?php echo $book_info['book_code_index']?>" style="width: 100%;height: 100%;text-align: center;" disabled="disabled"></td>
                    <td><button  class="btn btn-info" type="button" disabled="disabled">不可修改</button>
                    </td>
                </tr>
                <tr style="height: 60px;">
                    <td>书籍作者</td>
                    <td><input id="book_author" name="book_author" type="text" value="<?php echo $book_info['book_author']?>" style="width: 100%;height: 100%;text-align: center;" disabled="disabled"></td>
                    <td><button  class="btn btn-info" type="button" onclick="change(this,'book_author','ba_save')">修改</button>
                    <button id="ba_save" name="action" value="book_author" class="btn btn-success" type="submit" style="display:none">保存</button>
                    </td>
                </tr>
                <tr style="height: 60px;">
                    <td>书籍出版社</td>
                    <td><input id="book_pub" name="book_pub" type="text" value="<?php echo $book_info['book_pub']?>" style="width: 100%;height: 100%;text-align: center;" disabled="disabled"></td>
                    <td><button  class="btn btn-info" type="button" onclick="change(this,'book_pub','bp_save')">修改</button>
                    <button id="bp_save" name="action" value="book_pub" class="btn btn-success" type="submit" style="display:none">保存</button>
                    </td>
                </tr>
                <tr style="height: 250px;">
                    <td>书籍简介</td>
                    <td><textarea id="book_desc" name="book_desc" style="width: 100%;height: 100%;resize: none;" disabled="disabled"><?php echo $book_info['book_desc']?></textarea></td>
                    <td><button  class="btn btn-info" type="button" onclick="change(this,'book_desc','bd_save')">修改</button>
                    <button id="bd_save" name="action" value="book_desc" class="btn btn-success" type="submit" style="display:none">保存</button>
                    </td>
                </tr>
                <tr style="height: 210px;">
                    <td>书籍封面</td>
                    <td>
                        <div style="display: inline-block;border: 5px solid wheat;width: 210px;height: 260px;"><img id="book_cover_m_preview" src="<?php echo $exist==0?'./cover/no_picture.jpg':'./upload/'.$book_code_index ?> "style="width:200px;height:250px;" ></div>
                        <input  type="file" id="book_cover_m"  name="book_cover_m" style="display:none" onchange="preview(this)">
                    </td>
                    <td><button  class="btn btn-info" type="button" onclick="file(this,'book_cover_m','bc_save')">修改</button>
                    <button id="bc_save" name="action" value="book_cover_m" class="btn btn-success" type="submit" style="display:none">保存</button>
                    </td>
                </tr>
            </table>
        </form>
        <a href="all_history.php?id=<?php echo $id?>"><button class="btn btn-danger" style="width:200px;margin-top:30px;">返回</button></a>
        </div>
    </body>
</html>