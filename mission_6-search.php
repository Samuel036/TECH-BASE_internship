<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    
    <style media="screen">
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
		    width: 200px;
		}
		.post{
            width: 250px;
		    border: 5px double #aaa;
            padding: 2em;
            border-radius: 15px;
            
		}
    </style>
</head>
<body>
<?php
  if(isset($_POST["search_name"]) && !empty($_POST["search_name"])){  
    try{
        //DBに接続
        $dsn = 'mysql:dbname=ユーザー名;host=localhost';
        $username= 'ユーザー名';
        $password= 'パスワード';
        $pdo = new PDO($dsn, $username, $password);

        //SQL文を実行して、結果を$stmtに代入する。
        $sql = " SELECT * FROM lostfoundtb WHERE name LIKE '%" . $_POST["search_name"] . "%' or shousai LIKE '%" . $_POST["search_name"] . "%' or pickup LIKE '%" . $_POST["search_name"] . "%' or place LIKE '%" . $_POST["search_name"] . "%' ORDER BY id DESC ";
        
        $stmt = $pdo->query($sql);

        $results = $stmt->fetchAll();
        echo "検索結果は以下";
        echo "<br>";
        echo ("<a href=\"mission_6-1.php\">戻る</a><br/>");
        echo "<br>";
        echo "<hr>";
        
        echo "<div class='display'>";
        
        foreach ($results as $row){
            
            $id = $row['id'];
            
            echo "<div class='post'>";
            
            echo '【拾ったもの】'.'<br>'.$row['name'].'<br>';
            echo '【特徴】'.'<br>'.$row["shousai"].'<br>';
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
            echo $row['Postedtime'].'<br>';
            
            echo "<form method='get' action='mission_6-delete.php'>";
            echo "<input type='hidden' name='id' value='$id'>";
            echo "<button class='delete'>投稿を削除</button>";
            echo "</form>";
            
            echo "<hr>";
            
            echo "</div>";
            
        }
        
        echo "</div>";
        
    } catch(PDOException $e){
        echo "失敗:" . $e->getMessage() . "\n";
        exit();
    }
    
  }else{
      echo "<br>";
      echo "キーワードが入力されていません！";
      echo "<br>";
      echo ("<a href=\"mission_6-1.php\">戻る</a><br/>");
  }
?>
</body>
</html>
