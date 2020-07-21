<?php
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
            <div id="setting">
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