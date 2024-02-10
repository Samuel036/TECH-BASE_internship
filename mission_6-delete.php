<?php
    
    try {
        
    $dsn = 'mysql:dbname=tb250623db;host=localhost';
    $username= 'tb-250623';
    $password= 'yRgAY42RAZ';
    $pdo = new PDO($dsn, $username, $password);
    
    $sql = 'DELETE FROM lostfoundtb WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $_GET["id"], PDO::PARAM_INT);
    $stmt->execute();

    echo "削除しました。";

    } catch (Exception $e) {
          echo 'エラーが発生しました。:' . $e->getMessage();
    }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>削除完了</title>
  </head>
  <body>          
  <p>
      <a href="mission_6-1.php">投稿一覧へ</a>
  </p> 
  </body>
</html>