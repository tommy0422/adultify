<?php
session_start();
include('functions.php');
check_session_id();

//DB接続の設定
$pdo = connect_to_db();

//データの受け取り
$user_id = $_SESSION["id"];
$name = $_POST["name"];
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] ==  0) {
    $uploadedFileName = $_FILES['upfile']['name']; //ファイル名の取得
    $tempPathName = $_FILES['upfile']['tmp_name']; //tmpフォルダの場所
    $fileDirectoryPath = 'upload/';
    $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
    $uniqueName = date('YmdHis') . md5(session_id()) . "." . $extension;
    $fileNameToSave = $fileDirectoryPath . $uniqueName;

    if (is_uploaded_file($tempPathName)) {
        if (move_uploaded_file($tempPathName, $fileNameToSave)) {
            chmod($fileNameToSave, 0644); // 権限の変更

            //sql作成＆実行
            $sql = 'UPDATE wife_table SET name=:name, icon=:icon, updated_at=sysdate() WHERE id=:user_id';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':icon', $fileNameToSave, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $status = $stmt->execute();
            // var_dump($status);
            // exit();

            if ($status == false) {
                // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
                $error = $stmt->errorInfo();
                echo json_encode(["error_msg" => "{$error[2]}"]);
                exit();
            } else {
                header("Location: home.php");
                exit();
            }
        }
    }
} else {
    //sql作成＆実行
    $sql = 'UPDATE wife_table SET name=:name, icon=NULL, updated_at=sysdate() WHERE id=:user_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $status = $stmt->execute();
    // var_dump($status);
    // exit();

    if ($status == false) {
        // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
        $error = $stmt->errorInfo();
        echo json_encode(["error_msg" => "{$error[2]}"]);
        exit();
    } else {
        header("Location: home.php");
        exit();
    }
}
