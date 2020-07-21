<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <style>
        .box {
            width: 320px;
            height: 400px;
        }
    </style>
    <title>新規会員登録画面</title>
</head>

<body>
    <div class="logo"><img src="image/logo.png"></div>
    <div class="box">
        <h1>新規会員登録</h1>
        <form class="" action="register_read.php" method="POST">
            <p>ニックネーム</p>
            <input type="text" name="name" placeholder="ニックネームを入力">
            <p>メールアドレス</p>
            <input type="text" name="address" placeholder="新しいメールアドレスを入力">
            <p>新しいパスワード</p>
            <input type="password" name="pass" placeholder="新しいパスワードを入力">
            <input type="submit" name="" value="新規登録">
            <a href="login.php">戻る</a>
        </form>
    </div>
</body>

</html>