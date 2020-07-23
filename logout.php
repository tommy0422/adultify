<?php
// セッション使うので必ず記述
session_start();

// SESSIONを初期化（空にする）
$_SESSION = array();
// Cookieに保存してある"Session
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}
// サーバ側での、セッションIDの破棄
session_destroy();
// 処理後、index.phpへリダイレクト
header('Location:login.php'); // ログインページヘ移動
exit();
