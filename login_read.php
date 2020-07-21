<?php
// var_dump($_POST);
// exit();

session_start();

// 外部ファイル読み込み
include('functions.php');

// DB接続します
$pdo = connect_to_db();

// データ受け取り
$address = $_POST['address'];
$password = $_POST['pass'];

$sql = 'SELECT * FROM wife_table WHERE address = :address AND password=:password AND is_deleted=0';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':address', $address, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$status = $stmt->execute();

// うまくいったらデータ（1レコード）を取得
$val = $stmt->fetch(PDO::FETCH_ASSOC);

// ユーザ情報が取得できない場合はメッセージを表示
if (!$val) {
    echo "<p>ログイン情報に誤りがあります。</p>";
    echo '<a href="login.php">login</a>';
    exit();
} else {
    // ログインできたら情報をsession領域に保存して一覧ページへ移動
    $_SESSION = array();
    // セッション変数を空にする
    $_SESSION["session_id"] = session_id();
    $_SESSION["id"] = $val["id"];
    $_SESSION["name"] = $val["name"];
    $_SESSION["icon"] = $val["icon"];
    header("Location:home.php");
    exit();
}
