<?php
// var_dump($_POST);
// exit();
session_start();
include('functions.php');
check_session_id();

//DB接続の設定
$pdo = connect_to_db();

//ログインユーザーのアイコンの表示
if ($_SESSION["icon"] != NULL) {
    $icon = $_SESSION["icon"];
} else {
    $icon = "image/no_image.png";
}

//検索したデータ受け取り
$keyword = $_POST["keyword"];
// var_dump($_POST["keyword"]);
// exit();

//sql作成&実行
$sql = 'SELECT * FROM playlist_table WHERE name LIKE "%' . $keyword . '%"';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
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
    // fetchAll()関数でSQLで取得したレコードを配列で取得できる
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定
    // var_dump($result);
    // exit();
    $output = "";
    // <tr><td>deadline</td><td>todo</td><tr>の形になるようにforeachで順番に$outputへデータを追加
    // `.=`は後ろに文字列を追加する，の意味
    foreach ($result as $record) {
        if ($record["image"] == NULL) {
            $record["image"] = "image/no_map.jpg";
        }
        $output .= "<div class = playlist>";
        $output .= "<img class = playlist_image src ={$record['image']}>";
        $output .= "<h2>{$record['name']}</h2>";
        $output .= "</div>";
        $output .= "<hr>";
    }
    // var_dump($record);
    // exit();

    // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
    // 今回は以降foreachしないので影響なし
    unset($record);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/search.css">
    <title>検索画面</title>
</head>

<body>
    <header>
        <div id="header">
            <div id="profile">
                <a href="profile.php"><img id="icon" src="<?= $icon ?>"></a>
            </div>
            <div id="logo">
                <img src="image/logomark.png" alt="">
            </div>
            <div class="navToggle">
                <span></span><span></span><span></span><span>menu</span>
            </div>
            <nav class="globalMenuSp">
                <ul>
                    <li><a href="home.php">ホーム</a></li>
                    <li><a href="login.php">ログアウト</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <form id="form1" action="result.php" method="POST">
        <input id="sbox" type="text" name="keyword" placeholder="キーワードを入力">
        <button id="sbtn" type="submit">検索</button>
    </form>
    <div class="playlists">
        <?= $output ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(function() {
            $('.navToggle').click(function() {
                $(this).toggleClass('active');

                if ($(this).hasClass('active')) {
                    $('.globalMenuSp').addClass('active');
                } else {
                    $('.globalMenuSp').removeClass('active');
                }
            });
        });
    </script>
</body>

</html>