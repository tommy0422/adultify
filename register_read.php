<?php
// var_dump($_POST);
// exit();

// 関数ファイル読み込み
include('functions.php');

// データ受け取り
$name = $_POST["name"];
$address = $_POST["address"];
$password = $_POST["pass"];
// var_dump($felica_id);
// exit();

// DB接続関数
$pdo = connect_to_db();

// ユーザ登録SQL作成
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
$sql = 'INSERT INTO wife_table(id, name, address, password, icon, is_deleted, created_at, updated_at) VALUES(NULL, :name, :address, :password, NULL, 0,  sysdate(), sysdate())';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':address', $address, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$status = $stmt->execute();
// var_dump($status);
// exit();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
    header("Location:login.php");
    exit();
}
