<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-3</title>
</head>
<body>
    <form action="" method="post">
        <input type="text"name="name" placeholder="名前">
        <input type="text"name="comment" placeholder="コメント">
        <input type="submit"name="submit">
    </form>
    <form action="" method="post">
        <input type="number"name="deletenumber" placeholder="削除対象番号">
        <input type="hidden" name="dele">
        <button >削除</button>
    </form>
    <?php
        $filename = "mission_3-3.txt";
        
        
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
            
            //ファイルの文字列を配列として取得し、さらに最後の配列を取得
            $arr = file($filename,FILE_IGNORE_NEW_LINES);
            $end = end($arr);
            //<>を区切りとして取り出す
            $counter = explode("<>",$end);
            //投稿番号だけを抽出して代入
            $count = $counter[0] + 1;
            
            $display = $count."<>".$name."<>".$message."<>".$time;
            
            $fi = fopen($filename, "a");
            fwrite($fi,$display.PHP_EOL);
            fclose($fi);
        }
        
        
        if(isset($_POST["deletenumber"]) && !empty($_POST["deletenumber"])){
            $arrays = file($filename,FILE_IGNORE_NEW_LINES);
            $fil = fopen($filename, "w");
            foreach($arrays as $array){
                $ex = explode("<>",$array);
                $bango = $ex[0];
                if($_POST["deletenumber"] != $bango){
                    fwrite($fil,$array.PHP_EOL);
                }
            }
            fclose($fil);
        }
        
        
        if(file_exists($filename)){
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            
            foreach($lines as $line){
                $newarray = explode("<>",$line);
                $dis = implode("・",$newarray);
                echo $dis."<br>";
            }
            
        }
    ?>
</body>
</html>