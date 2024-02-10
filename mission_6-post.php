<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>New POST</title>
    
    <style media="screen">
        .new11 {
            display: flex;
        }
        .new22 {
            display: flex;
        }
        .thing{
            margin:10px 0 0 0;
        }
        .part2 {
            margin:0 0 0 30px;
        }
        textarea{
            width: 200px;
        }
    </style>
</head>
<body bgcolor="lightblue">
    <?php
        //DB接続
        $dsn = 'mysql:dbname=tb250623db;host=localhost';
        $user = 'tb-250623';
        $password = 'yRgAY42RAZ';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    ?>
    
    
    <h2>新規で投稿をする</h2>
    <h4>※貴重品の場合は、詳細の記入や画像・動画の添付をお控えください。</h4>
    <h4>※画像はjpeg方式，png方式，gif方式に対応しています。動画はmp4方式のみ対応しています。</h4>
    <form action="" method="post" enctype="multipart/form-data">
        <br>
        <div class='new11'>
        <div class='new1 part1 thing'>拾ったもの<br>
        <div class='thing'><input type="text"name="name"></div>
        </div>
        <br>
        <br>
        <div class='new1 part2'>特徴(あれば)
        <pre><textarea name="shousai" width:50px;></textarea></pre>
        </div>
        </div>
        <br>
        <div class='new22'>
        <div class='new2 part1'>拾った場所<br>
        <input type="text"name="pickup" style="width:180px">
        </div>
        <br>
        <div class='new2 part2'>拾った時間<br>
        <input type="text"name="timing">
        </div>
        </div>
        <br>
        <br>
        預けた場所・保管場所
        <pre><textarea name="place"></textarea></pre>
        <br>
        画像（任意）:
        <input type="file" name="image">
        <br>
        <br>
        <input type="submit"name="submit" value = "投稿">
        
    </form>
    
    
    <?php
    
        echo '<br>';
        echo ("<a href=\"mission_6-1.php\">ホームへ戻る</a><br/>");
        
    // 投稿チェック
        if(!empty($_POST["name"]))
        {   
            
            
            $name = $_POST["name"];
            $shousai = $_POST["shousai"];
            $pickup = $_POST["pickup"];
            $timing = $_POST["timing"];
            $place = $_POST["place"];
            $Postedtime = date("Y/m/d H:i");
            
            
            if (isset($_FILES['image']['error']) && is_int($_FILES['image']['error']) && $_FILES["image"]["name"] !== ""){
            //エラーチェック
                switch ($_FILES['image']['error']) {
                    case UPLOAD_ERR_OK: // エラーなしOK
                        break;
                    case UPLOAD_ERR_NO_FILE:   // 未選択
                        throw new RuntimeException('ファイルが選択されていません', 400);
                    case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                        throw new RuntimeException('ファイルサイズが大きすぎます', 400);
                    default:
                        throw new RuntimeException('その他のエラーが発生しました', 500);
                }

                //画像・動画をバイナリデータにする．
                $raw_data = file_get_contents($_FILES['image']['tmp_name']);

                //拡張子を見る
                $tmp = pathinfo($_FILES["image"]["name"]);
                $extension = $tmp["extension"];
            
                if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
                    $extension = "jpeg";
                }
                elseif($extension === "png" || $extension === "PNG"){
                    $extension = "png";
                }
                elseif($extension === "gif" || $extension === "GIF"){
                    $extension = "gif";
                }
                elseif($extension === "mp4" || $extension === "MP4"){
                    $extension = "mp4";
                }
                else{
                    echo "非対応ファイルです．<br/>";
                    echo ("<a href=\"mission_6-1.php\">戻る</a><br/>");
                    exit(1);
                }
            }
            //DBに格納するファイルネーム設定
            //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
            $fname = $_FILES["image"]["tmp_name"];
            $fname = hash("sha256", $fname);
            
            
            //編集or新規で分岐
            if(empty($_POST["postnum"])){
                //新規
                $sql = "INSERT INTO lostfoundtb (name, shousai, pickup, timing, place, fname, extension, raw_data, Postedtime) VALUES (:name, :shousai, :pickup, :timing, :place, :fname, :extension, :raw_data, :Postedtime)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':shousai', $shousai, PDO::PARAM_STR);
                $stmt->bindParam(':pickup', $pickup, PDO::PARAM_STR);
                $stmt->bindParam(':timing', $timing, PDO::PARAM_STR);
                $stmt->bindParam(':place', $place, PDO::PARAM_STR);
                $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
                $stmt->bindParam(':extension', $extension, PDO::PARAM_STR);
                $stmt->bindParam(':raw_data', $raw_data, PDO::PARAM_STR);
                $stmt->bindParam(':Postedtime', $Postedtime, PDO::PARAM_STR);
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
            
            echo "<br>";
            echo "投稿ありがとうございました!";
            
        }
    ?>
</body>
</html>