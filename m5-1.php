<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>

<?php

$editname = "";
$editstr = "";
$editornew = 0;


// DB接続設定
    $dsn = 'mysql:dbname=tb250159db;host=localhost';
    $user = 'tb-250159';
    $password = 'ZUdRZHNR9P';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// テーブル作成
     $sql = "CREATE TABLE IF NOT EXISTS mission5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "str TEXT,"
    . "date TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
//投稿機能   
if (isset($_POST["name"]) && !empty($_POST["name"]) && isset($_POST["str"]) && !empty($_POST["str"]) && isset($_POST["submit"])) {
    //編集した投稿かの確認
    $editornew = $_POST["editornew"];
    if ($editornew == 0) {
        // データの登録
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, str, date, pass) VALUES (:name, :str, :date, :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':str', $str, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $name = $_POST["name"];
        $str = $_POST["str"];
        $date = date("Y/n/j G:i:s");
        $pass = $_POST["pass"];
        $sql -> execute();
    } else {
        $id = $editornew;
        $name = $_POST["name"];
        $comment = $_POST["str"];
        $date = date("Y/n/j G:i:s");
        $pass = $_POST["pass"];//変更する投稿番号
        $sql = 'UPDATE mission5 SET name=:name,str=:str date=:date, pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':str', $str, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute(); 
    }
    
}

// 削除機能
if (isset($_POST["delete"]) && !empty($_POST["deleteno"]) && isset($_POST["passdel"]) && !empty($_POST["passdel"])) {
    $delete = $_POST["deleteno"];
    $passdel = $_POST["passdel"];
    
    $id = $delete;
    $pass = $passdel;
    $sql = 'SELECT * FROM mission5 WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result !== false && $result['pass'] === $pass) {
        $sql = 'DELETE FROM mission5 WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        echo "パスワードが一致しません。削除できません。";
    }
}

//編集対象番号の確認とフォームに表示
if (isset($_POST["edit"]) && !empty($_POST["editno"]) && isset($_POST["passed"]) && !empty($_POST["passed"])) {
    $edit = $_POST["editno"];
    $passed = $_POST["passed"];
    
    $id = $edit;
    $pass = $passed;
    $sql = 'SELECT * FROM mission5 WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result !== false && $result['pass'] === $pass) {
        $editname = $result['name'];
        $editstr = $result['str'];
        $editnm = $result['id'];
    }
}

?>
<header><div style="position: relative; top: 10px; left: 10px;">
<form action="" method="post">
        name：<input type="text" name="name" value="<?= isset($editname) ? htmlspecialchars($editname) : "" ?>"><br>
        コメント：<br>
        <textarea name="str" cols="60" rows="5"  ><?= isset($editstr) ? htmlspecialchars($editstr) : "" ?></textarea><br>
        PASS：<input type="text" name="pass" value="">
        <input type="hidden" name="editornew" value="<?= isset($editnm) ? htmlspecialchars($editnm) : "" ?>">
        <input type="submit" name="submit" value="送信">
    </form><br>
    <form action="" method="post">
        削除対象番号：<input type="number" size="3" name="deleteno" value="">
        　　PASS：<input type="text" name="passdel" value="">
        <input type="submit" name="delete" value="削除"><br><br>
        編集対象番号：<input type="number" size="3" name="editno" value="">
        　　PASS：<input type="text" name="passed" value="">
        <input type="submit" name="edit" value="編集">
    </form>
</div></header>
<div style="position: relative; top: 10px; left: 10px;">
<body></body><br>コメント欄：<br><hr><hr>

<?php
$sql = 'SELECT * FROM mission5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    echo "[". $row['id'].']';
    echo $row['name'].'：';
    echo $row['str'].'<br>';
    echo $row['date'].'<br>';
    echo "<hr>";
}
?>
</body>
</html>
