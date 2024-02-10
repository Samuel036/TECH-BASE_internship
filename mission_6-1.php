<!DOCTYPE html>
<html lang="ja">
<head>
    
    <meta charset="UTF-8">
    <title>Lost & Found</title>

    <style media="screen">
    
		*{/* 初期化 */
			padding: 0px;
		}
		.form{
		    display: flex;
		    justify-content: space-between;
		}
		.midashi{
		    text-align: center;
		}
		.search{
		    text-align: center;
		    width: 800px;
		}
		.new{
		    text-align: center;
			width: 800px;
			height: 70px;
		}
		.line{
		    background-color: lightgreen;
		    height: 75px;
		    position: relative;
		}
		.namae{
		    color: white;
		    right:0;
		    bottom:0;
		    position: absolute;
		}
		.current{
		    font-size: 25px;
		    left: 20px;
		}
		.delete{
		    padding: 0.5em 1em;
            margin: 2em 0;
            color: white;
            background: lightgreen;
            border-bottom: solid 2px black;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
            border-radius: 9px;
		}
		.display{
		    padding: 0px;
		    display: flex;
            align-items:stretch;  
            justify-content:center;
            flex-wrap: wrap; 
            align-content:stretch;
        }
		.post img {
		    width: 150px;
		}
		.post{
            width: 200px;
		    border: 5px double #aaa;
            padding: 2em;
            border-radius: 15px;
            
		}
		
		
	</style>
</head>

<body>
    
    <div class="midashi" style="font-size:40px;color:white;background-color:lightgreen;">忘れ物掲示板</div>
    <?php
        //DB接続
        $dsn = 'mysql:dbname=tb250623db;host=localhost';
        $user = 'tb-250623';
        $password = 'yRgAY42RAZ';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        
        //テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS lostfoundtb"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name CHAR(32),"
        . "shousai TEXT,"
        . "pickup TEXT,"
        . "timing TEXT,"
        . "place TEXT,"
        . "fname TEXT,"
        . "extension TEXT,"
        . "raw_data LONGBLOB,"
        . "Postedtime TEXT"
        .");";
        $stmt = $pdo->query($sql);
        
        
        
        
            
        
        $edit_name = "";
        $edit_comment = "";
        $postnumber = "";
        
        
        // 投稿チェック
        if(!empty($_POST["comment"]))
        {   
            
            $shousai = $_POST["shousai"];
            $comment = $_POST["comment"];
            $Postedtime = date("Y/m/d H:i");
            $timing = $_POST["timing"];
            $place = $_POST["place"];
            
            
            //編集or新規で分岐
            if(empty($_POST["postnum"])){
                //新規
                $sql = "INSERT INTO lostfoundtb (name, shousai, comment, Postedtime, password, timing, place) VALUES (:name, :shousai, :comment, :Postedtime, :password, :timing, :place)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':shousai', $shousai, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':Postedtime', $Postedtime, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':timing', $timing, PDO::PARAM_STR);
                $stmt->bindParam(':place', $place, PDO::PARAM_STR);
                $stmt->execute();
                
            }else if(!empty($_POST["postnum"]) && !empty($_POST["password"])){
                //編集
                $id = $_POST["postnum"];
                
                $sql = 'UPDATE lostfoundtb SET name=:name,comment=:comment,password=:password,Postedtime=:Postedtime WHERE id=:id';
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
           
           $sql = 'SELECT * FROM lostfoundtb WHERE id=:id';
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':id', $deletenumber, PDO::PARAM_INT); 
           $stmt->execute();
           $results = $stmt->fetchAll();
           
           foreach ($results as $row) {
            if($pass_dele == $row["password"]){
                $sql = 'DELETE FROM lostfoundtb WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $deletenumber, PDO::PARAM_INT);
                $stmt->execute();
            }else{
                echo "パスワードが違います！<br>";
            }
           }
        }
        
        
        //編集の機能
        if(!empty($_POST["editnumber"])){
           $editnumber = $_POST["editnumber"];
           $pass_edit = $_POST["pass_edit"];
           
           $sql = 'SELECT * FROM lostfoundtb WHERE id=:id';
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':id', $editnumber, PDO::PARAM_INT); 
           $stmt->execute();
           $results = $stmt->fetchAll();
           
           foreach ($results as $row) {
            if($pass_edit == $row["password"]){
                $edit_name = $row["name"];
                $edit_comment = $row["comment"];
                $postnumber = $row["id"];
            }else{
                echo "パスワードが違います！<br>";
            }
           }
        }
        
        
    
        
    ?>
    
    <br>
    <br>
    
    <div class="form">
        
        <div class="search">
        
        <h2>検索</h2>
        <form action="mission_6-search.php" method="post">
        <!-- 任意の<input>要素＝入力欄などを用意する -->
        <input type="text" name="search_name" placeholder="キーワード">
        <!-- 送信ボタンを用意する -->
        <input type="submit" name="submit" value="Search">
        </form>
    
        </div>
    
        <br>
    
    
    <!-- ３種のフォーム -->
        <div class="new">
            <h2>新規投稿</h2>
            <a href='https://tech-base.net/tb-250623/mission_6-post.php' target="_blank">こちらから</a>
        </div>
        
    </div>    
    
    <br>
    <br>
    
    <div class="line">
        <div class="namae">Osamu TAKASHIMA</div>
    </div>
    
    <br>
    <br>
    <br>
    <br>
    
    <div class="current">〜現在投稿されている忘れ物・落とし物〜</div>
    
    <br>
    <br>
    
    <div class="display">
    
    <?php
        //データベースの内容をブラウザに表示
        //降順に並べ替えて抽出
        $sql = 'SELECT * FROM lostfoundtb ORDER BY id DESC';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
    
        foreach ($results as $row){
            
            $id = $row['id'];
            
            echo "<div class='post'>";
            
            echo '【拾ったもの】'.'<br>'.$row['name'].'<br>';
            echo '【特徴】'.'<br>'.nl2br($row["shousai"], false).'<br>';
            echo '【拾った場所】'.'<br>'.$row['pickup'].'<br>';
            echo '【拾った時間】'.'<br>'.$row['timing'].'<br>';
            echo '【預けた場所・保管場所】'.'<br>'.nl2br($row['place'], false).'<br>';
            
            //動画と画像で場合分け
            $target = $row["fname"];
            if($row["extension"] == "mp4"){
                echo ("<video src=\"import_media.php?target=$target\" width=\"426\" height=\"240\" controls></video>");
            }
            elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
                echo ("<img src='mission_6-import_media.php?target=$target' width='200px'>");
            }
            
            
            echo "<br>";
            echo '投稿日時 : '.$row['Postedtime'].'<br>';
            
            echo "<form method='get' action='mission_6-delete.php'>";
            echo "<input type='hidden' name='id' value='$id'>";
            echo "<button class='delete'>投稿を削除</button>";
            echo "</form>";
            
            echo "<hr>";
            
            echo "</div>";
        }
    ?>
    
    </div>
</body>
</html>
    