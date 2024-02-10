<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    ※投稿の際はパスワードも合わせて入力してください。<br>
    <?php
        $filename = "mission_3-5.txt";
        $i = 1;
        $edit_name = "";
        $edit_comment = "";
        $postnumber = "";
        
        if(isset($_POST["comment"]) && !empty($_POST["comment"]))
        {   
            //名前が入力されない場合名無しとして扱ってみる
            if(isset($_POST["name"]) && !empty($_POST["name"])){
                $name = $_POST["name"];
            }else{
                $name = "名無し";
            }   
            
            $message = $_POST["comment"];
            $time = date("Y/m/d H:i:s");
            $pass = $_POST["password"];
            
            
            if(!empty($_POST["postnum"])){
                $arrays = file($filename,FILE_IGNORE_NEW_LINES);
                $fil = fopen($filename, "w");
                foreach($arrays as $array){
                    $ex = explode("<>",$array);
                    $bango = $ex[0];
                    if($_POST["postnum"] == $bango){
                        $display = $bango."<>".$name."<>".$message."<>".$time."<>".$pass;
                        fwrite($fil,$display.PHP_EOL);
                    }else{
                        fwrite($fil,$array.PHP_EOL);
                    }
                }
                fclose($fil);
            }else{
                //ファイルの文字列を配列として取得し、さらに最後の配列を取得
                $arr = file($filename,FILE_IGNORE_NEW_LINES);
                $end = end($arr);
                //<>を区切りとして取り出す
                $counter = explode("<>",$end);
                //投稿番号だけを抽出して代入
                $count = (int)$counter[0] + $i;
            
                $display = $count."<>".$name."<>".$message."<>".$time."<>".$pass;
            
                $fi = fopen($filename, "a");
                fwrite($fi,$display.PHP_EOL);
                fclose($fi);
            }
        }
        
        
        if(isset($_POST["deletenumber"]) && !empty($_POST["deletenumber"])){
            $arrays = file($filename,FILE_IGNORE_NEW_LINES);
            $fil = fopen($filename, "w");
            foreach($arrays as $array){
                $ex = explode("<>",$array);
                $bango = $ex[0];
                
                if($_POST["deletenumber"] != $bango){
                    fwrite($fil,$array.PHP_EOL);
                }else if($_POST["deletenumber"] == $bango && $_POST["pass_dele"] != $ex[4]){
                    fwrite($fil,$array.PHP_EOL);
                }
            }
            fclose($fil);
        }
        
        
        if(isset($_POST["edit"]) && !empty($_POST["edit"])){
            $arrays = file($filename,FILE_IGNORE_NEW_LINES);
            $fil = fopen($filename, "a");
            foreach($arrays as $array){
                $ex = explode("<>",$array);
                $bango = $ex[0];
                if($_POST["edit"] == $bango && $_POST["pass_edit"] == $ex[4]){
                    $edit_name = $ex[1];
                    $edit_comment = $ex[2];
                    $postnumber = $bango;
                }
            }
            fclose($fil);
        }
    ?>
    
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
        <button>削除</button>
    </form>
    <form action="" method="post">
        <input type="number" name="edit" placeholder="編集対象番号" style="width:95px">
        <input type="text" name="pass_edit" placeholder="パスワード" style="width:95px">
        <button>編集</button>
    </form>
    <br>
    
    <?php
        
        if(file_exists($filename)){
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            
            foreach($lines as $line){
                $dis = explode("<>",$line);
                
                echo $dis[0]."・".$dis[1]."・".$dis[2]."・".$dis[3]."<br>";
            }
            
        }
    ?>
</body>
</html>