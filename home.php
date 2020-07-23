<?php
session_start();
include('functions.php');
check_session_id();

//DB接続の設定
$pdo = connect_to_db();

$user_id = $_SESSION["id"];
// var_dump($_SESSION['id']);
// exit();

//sql作成＆準備&実行
$sql = 'SELECT * FROM wife_table WHERE id = :user_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($record);
    // exit();
}

//iconの定義
if ($record["icon"] != NULL) {
    $icon = $record["icon"];
} else {
    $icon = "image/no_wife.png";
}
// var_dump($_SESSION["icon"]);
// exit();

//プレイリストの表示
//sql作成 & 実行
$sql = 'SELECT * FROM playlist_table WHERE belong_wife = :user_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $playlist = "";
    // <tr><td>deadline</td><td>todo</td><tr>の形になるようにforeachで順番に$outputへデータを追加
    foreach ($list as $record) {
        if ($record["image"] == NULL) {
            $record["image"] = "image/no_map.jpg";
        }
        $playlist .= "<div class = playlist>";
        $playlist .= "<img class = playlist_image src ={$record['image']}>";
        $playlist .= "<h2>{$record['name']}</h2>";
        $playlist .= "</div>";
        $playlist .= "<hr>";
    }
}

//友達の表示
//sql作成 & 実行
$sql = 'SELECT  * FROM man_table WHERE belong_wife = :user_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
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
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($list);
    // exit();
    $friend = "";
    // <tr><td>deadline</td><td>todo</td><tr>の形になるようにforeachで順番に$outputへデータを追加
    foreach ($list as $record) {
        if ($record["icon"] == NULL) {
            $record["icon"] = "image/no_image.png";
        }
        $friend .= "<a href='man_edit.php?id={$record["id"]}'><div class = friend>";
        $friend .= "<img class = friend_icon src ={$record['icon']}>";
        $friend .= "<h2>{$record['name']}</h2>";
        $friend .= "<h3>{$record['age']}</h3>";
        $friend .= "</div></a>";
        $friend .= "<hr>";
    }
    // var_dump($friend);
    // exit();
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>ホーム画面</title>
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
                    <li><a href="search.php">検索</a></li>
                    <li><a href="logout.php">ログアウト</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="playlists">
        <p>プレイリスト</p>
        <hr>
        <?= $playlist ?>
    </div>
    <div class="friends">
        <p>友達</p>
        <hr>
        <?= $friend ?>
    </div>
    <footer>
        <!-- bottom navigation -->
        <ul class="bottom-menu">
            <li>
                <!-- ↓↓項目1.プレイリスト追加 -->
                <a href="playlist_add.php"><img src="image/playlist_add.png"></img><br><span class="mini-text"></span></a>
            </li>
            <li class="menu-width-max">
                <!-- ↓↓項目2.男性 -->
                <a href="man_add.php"><img src="image/man_add.png"></img><br><span class="mini-text"></span></a>
            </li>
        </ul>
    </footer>
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