<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    ※投稿の際はパスワードも合わせて入力してください<br>
    ※編集の際はパスワードも編集可<br>
    <br>
    <?php
        //DB接続
        $dsn = 'mysql:dbname=tb250623db;host=localhost';
        $user = 'tb-250623';
        $password = 'yRgAY42RAZ';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        
        //テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS techtb"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name CHAR(32),"
        . "comment TEXT,"
        . "Postedtime TEXT,"
        . "password TEXT"
        .");";
        $stmt = $pdo->query($sql);
        
        
        
        
            
        
        $edit_name = "";
        $edit_comment = "";
        $postnumber = "";
        
        
        // 投稿チェック
        if(!empty($_POST["comment"]) && !empty($_POST["password"]))
        {   
            //名前が入力されない場合名無しとして扱う
            if(isset($_POST["name"]) && !empty($_POST["name"])){
                $name = $_POST["name"];
            }else{
                $name = "名無し";
            }   
            
            $comment = $_POST["comment"];
            $Postedtime = date("Y/m/d H:i:s");
            $password = $_POST["password"];
            
            
            //編集or新規で分岐
            if(empty($_POST["postnum"])){
                //新規
                $sql = "INSERT INTO techtb (name, comment, Postedtime, password) VALUES (:name, :comment, :Postedtime, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':Postedtime', $Postedtime, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
                
            }else if(!empty($_POST["postnum"]) && !empty($_POST["password"])){
                //編集
                $id = $_POST["postnum"];
                
                $sql = 'UPDATE techtb SET name=:name,comment=:comment,password=:password,Postedtime=:Postedtime WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':Postedtime', $Postedtime, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
            }
        }
        
        
        //削除の機能
        if(!empty($_POST["deletenumber"]) && !empty($_POST["pass_dele"])){
           $deletenumber = $_POST["deletenumber"];
           $pass_dele = $_POST["pass_dele"];
           
           $sql = 'SELECT * FROM techtb WHERE id=:id';
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':id', $deletenumber, PDO::PARAM_INT); 
           $stmt->execute();
           $results = $stmt->fetchAll();
           
           foreach ($results as $row) {
            if($pass_dele == $row["password"]){
                $sql = 'DELETE FROM techtb WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $deletenumber, PDO::PARAM_INT);
                $stmt->execute();
            }
           }
        }
        
        
        //編集の機能
        if(!empty($_POST["editnumber"])){
           $editnumber = $_POST["editnumber"];
           $pass_edit = $_POST["pass_edit"];
           
           $sql = 'SELECT * FROM techtb WHERE id=:id';
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':id', $editnumber, PDO::PARAM_INT); 
           $stmt->execute();
           $results = $stmt->fetchAll();
           
           foreach ($results as $row) {
            if($pass_edit == $row["password"]){
                $edit_name = $row["name"];
                $edit_comment = $row["comment"];
                $postnumber = $row["id"];
            }
           }
        }
        
        
    
        
    ?>
    
    <!-- ３種のフォーム -->
    <form action="" method="post">
        <input type="text"name="name" value="<?php echo $edit_name; ?>" placeholder="名前">
        <input type="text"name="comment" value="<?php echo $edit_comment; ?>" placeholder="コメント" style="width:180px">
        <input type="hidden"name="postnum" value="<?php echo $postnumber; ?>" placeholder="投稿番号">
        <input type="text" name="password" placeholder="パスワード" style="width:95px">
        <input type="submit"name="submit">
    </form>
    <br>
    <form action="" method="post">
        <input type="number"name="deletenumber" placeholder="削除対象番号" style="width:95px">
        <input type="hidden" name="dele">
        <input type="text" name="pass_dele" placeholder="パスワード" style="width:95px">
        <input type="submit"name="delete" value="削除">
    </form>
    <form action="" method="post">
        <input type="number" name="editnumber" placeholder="編集対象番号" style="width:95px">
        <input type="text" name="pass_edit" placeholder="パスワード" style="width:95px">
        <input type="submit"name="edit" value="編集">
    </form>
    <br>
    <br>
    
    <?php
        //データベースの内容をブラウザに表示
        $sql = 'SELECT * FROM techtb';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].'・';
            echo $row['name'].'・';
            echo $row['comment'].'・';
            echo $row['Postedtime'].'<br>';
        echo "<hr>";
    }
    ?>
</body>
</html>