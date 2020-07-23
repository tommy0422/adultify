<?php
session_start();
include('functions.php');
check_session_id();

//DB接続の設定
$pdo = connect_to_db();

//データの受け取り
$user_id = $_SESSION["id"];
$name = $_POST["nickname"];
$area = $_POST["area"];
$job = $_POST["job"];
$age = $_POST["age"];
$tall = $_POST["tall"];
$body = $_POST["body"];

// 項目入力のチェック
// 値が存在しないor空で送信されてきた場合はNGにする
if (
    !isset($_POST['nickname']) || $_POST['nickname'] == '' ||
    !isset($_POST['area']) || $_POST['area'] == '' ||
    !isset($_POST['job']) || $_POST['job'] == '' ||
    !isset($_POST['age']) || $_POST['age'] == '' ||
    !isset($_POST['tall']) || $_POST['tall'] == '' ||
    !isset($_POST['body']) || $_POST['body'] == ''
) {
    // 項目が入力されてzいない場合はここでエラーを出力し，以降の処理を中止する
    echo json_encode(["error_msg" => "no input"]);
    exit();
}

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
            $sql = 'INSERT INTO man_table(id, name, icon, age, area, height, physique, job, belong_wife, belong_playlist, is_deleted, created_at, updated_at) 
            VALUES (NULL, :name, :image, :age, :area, :tall, :body, :job, :user_id, NULL, 0, sysdate(), sysdate())';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':image', $fileNameToSave, PDO::PARAM_STR);
            $stmt->bindValue(':age', $age, PDO::PARAM_STR);
            $stmt->bindValue(':area', $area, PDO::PARAM_STR);
            $stmt->bindValue(':tall', $tall, PDO::PARAM_STR);
            $stmt->bindValue(':body', $body, PDO::PARAM_STR);
            $stmt->bindValue(':job', $job, PDO::PARAM_STR);
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
    $sql = 'INSERT INTO man_table(id, name, icon, age, area, height, physique, job, belong_wife, belong_playlist, is_deleted, created_at, updated_at) 
    VALUES (NULL, :name, NULL, :age, :area, :tall, :body, :job, :user_id, NULL, 0, sysdate(), sysdate())';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':age', $age, PDO::PARAM_STR);
    $stmt->bindValue(':area', $area, PDO::PARAM_STR);
    $stmt->bindValue(':tall', $tall, PDO::PARAM_STR);
    $stmt->bindValue(':body', $body, PDO::PARAM_STR);
    $stmt->bindValue(':job', $job, PDO::PARAM_STR);
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
