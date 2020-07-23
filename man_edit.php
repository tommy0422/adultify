<?
// var_dump($_GET);
// exit();

session_start();
include('functions.php');
check_session_id();

//DB接続の設定
$pdo = connect_to_db();

//データの受ける
$man_id = $_GET["id"];
$user_id = $_SESSION["id"];
// var_dump($man_id);
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

//sql作成＆準備&実行
$sql = 'SELECT * FROM man_table WHERE id = :man_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':man_id', $man_id, PDO::PARAM_STR);
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
// var_dump($record);
// exit();
if($record["icon"] == NULL){
    $record["icon"] = "image/no_image.png";
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/man_edit.css">
    <title>男友達編集画面</title>
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
    <form class="form-horizontal" action="man_update.php" method="POST" enctype="multipart/form-data">
        <div class="map_image">
            <figure class="map_box">
                <img id="preview" src="<?= $record["icon"] ?>">
                <input class="map" type="file" name="upfile" accept='image/*' capture="camera" onchange="previewImage(this);">
                <div id="button_box"></div>
            </figure>
        </div>
        <div class="form-group">
            <label for="nickname" class="col-sm-2 control-label">ニックネーム</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nickname" value="<?= $record["name"] ?>" placeholder="ニックネーム" name="nickname">
            </div>
        </div>
        <div class="form-group">
            <label for="area" class="col-sm-2 control-label">在住地域</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="area" value="<?= $record["area"] ?>" placeholder="在住地域" name="area">
            </div>
        </div>
        <div class="form-group">
            <label for="job" class="col-sm-2 control-label">職業</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="job" value="<?= $record["job"] ?>" placeholder="職業" name="job">
            </div>
        </div>
        <div class="form-group">
            <div class="dropdown">
                <label for="age" class="col-sm-2 control-label">年齢</label>
                <div class="col-sm-10">
                    <button type="button" class="btn btn-primary dropdown-toggle form-control" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">項目を選択してください</button>
                    <select id="age" name="age">
                        <option value=""><?= $record["age"] ?></option>
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
                        <option value=""><?= $record["height"] ?></option>
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
                        <option value=""><?= $record["physique"] ?></option>
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
        <input type="hidden" name="id" value="<?= $record["id"] ?>">
        <div class="form-group">
            <div class="col-sm-10">
                <p><button type="submit" class="btn btn-pink">男友達登録</button></p>
            </div>
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