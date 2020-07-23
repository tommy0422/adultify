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
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/playlist_add.css">
    <title>プレイリスト追加画面</title>
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
    <hr>
    <form action="playlist_add_read.php" method="POST" enctype="multipart/form-data">
        <div class="map_image">
            <figure class="map_box">
                <img id="preview" src="image/no_map.jpg">
                <input class="map" type="file" name="upfile" accept='image/*' capture="camera" onchange="previewImage(this);">
                <div id="button_box">
                </div>
            </figure>
        </div>
        <div class="playlist_box">
            <input type="text" name="name" placeholder="プレイリスト名を入力">
            <button type="submit">プレイリスト登録</button>
        </div>
    </form>
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

        function previewImage(obj) {
            var fileReader = new FileReader();
            fileReader.onload = (function() {
                document.getElementById('preview').src = fileReader.result;
            });
            fileReader.readAsDataURL(obj.files[0]);
        }
    </script>
</body>

</html>