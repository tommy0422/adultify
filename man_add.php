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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <link rel="stylesheet" href="css/man_add.css">
    <title>男友達追加画面</title>
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
        <hr color="white">
    </header>

    <form class="form-horizontal" action="man_add_read.php" method="POST" enctype="multipart/form-data">
        <div class="map_image">
            <figure class="map_box">
                <img id="preview" src="image/no_image.png">
                <input class="map" type="file" name="upfile" accept='image/*' capture="camera" onchange="previewImage(this);">
                <div id="button_box"></div>
            </figure>
        </div>
        <div class="form-group">
            <label for="nickname" class="col-sm-2 control-label">ニックネーム</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nickname" placeholder="ニックネーム" name="nickname">
            </div>
        </div>
        <div class="form-group">
            <label for="area" class="col-sm-2 control-label">在住地域</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="area" placeholder="在住地域" name="area">
            </div>
        </div>
        <div class="form-group">
            <label for="job" class="col-sm-2 control-label">職業</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="job" placeholder="職業" name="job">
            </div>
        </div>
        <div class="form-group">
            <div class="dropdown">
                <label for="age" class="col-sm-2 control-label">年齢</label>
                <div class="col-sm-10">
                    <button type="button" class="btn btn-primary dropdown-toggle form-control" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">項目を選択してください</button>
                    <select id="age" name="age">
                        <option value="">未選択</option>
                        <option value="20〜29歳">20〜29歳</option>
                        <option value="30〜39歳">30〜39歳</option>
                        <option value="40〜49歳">40〜49歳</option>
                        <option value="50〜59歳">50〜59歳</option>
                        <option value="60歳〜">60歳〜</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="dropdown">
                <label for="tall" class="col-sm-2 control-label">身長</label>
                <div class="col-sm-10">
                    <button type="button" class="btn btn-primary dropdown-toggle form-control" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">項目を選択してください</button>
                    <select name="tall" id="tall">
                        <option value="">未選択</option>
                        <option value="150~159cm">150~159cm</option>
                        <option value="160~169cm">160~169cm</option>
                        <option value="170~179cm">170~179cm</option>
                        <option value="180~189cm">180~189cm</option>
                        <option value="190cm~">190cm~</option>
                    </select>

                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="dropdown">
                <label for="body" class="col-sm-2 control-label">体格</label>
                <div class="col-sm-10">
                    <button type="button" class="btn btn-primary dropdown-toggle form-control" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">項目を選択してください</button>
                    <select id="body" name="body">
                        <option value="">未選択</option>
                        <option value="ガリガリ">ガリガリ</option>
                        <option value="細身">細身</option>
                        <option value="細マッチョ">細マッチョ</option>
                        <option value="ゴリマッチョ">ゴリマッチョ</option>
                        <option value="普通">普通</option>
                        <option value="ポッチャリ">ポッチャリ</option>
                        <option value="デブ">デブ</option>

                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-10">
                <p><button type="submit" class="btn btn-pink">男友達登録</button></p>
            </div>
        </div>
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script>
        $(function() {
            $('.dropdown-menu .dropdown-item').click(function() {
                var visibleItem = $('.dropdown-toggle', $(this).closest('.dropdown'));
                visibleItem.text($(this).attr('value'));
            });
        });

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