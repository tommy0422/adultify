 <?
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
//現在の名前の定義
$user_name = $record["name"];

//iconの定義
if ($record["icon"] != NULL) {
    $icon = $record["icon"];
} else {
    $icon = "image/no_wife.png";
}
// var_dump($user_name);
// exit();
 ?>
 <!DOCTYPE html>
 <html lang="ja">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="css/profile.css">
     <title>プロフィール画面</title>
 </head>

 <body>
     <header>
         <div id="header">
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
         <hr>
     </header>
     <form action="profile_read.php" method="POST" enctype="multipart/form-data">
         <div class="icon_image">
             <figure class="icon_box">
                 <img id="preview" src="<?= $icon ?>">
                 <input class="icon" type="file" name="upfile" accept='image/*' capture="camera" onchange="previewImage(this);">
                 <div id="button_box">
                 </div>
             </figure>
         </div>
         <div class="nickname_box">
             <input type="text" name="name" value="<?= $user_name ?>" placeholder="ニックネームを入力">
             <button type="submit">プロフィールを更新</button>
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