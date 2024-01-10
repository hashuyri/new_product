<?php
session_start();
include('functions.php');
checkSessionId();

// echo "<pre>";
// var_dump($_GET);
// echo "<pre>";

// DB接続
$pdo = connectToDB($db_name);
// テーブルにデータを書き込み
$user_table_name = "UT" . $_SESSION["customer_id"];

$authority = $_GET["after_authority"];
// 追加権限の確認
if ($authority === "オーナー") {
    // 元のオーナーの権限を管理者へ変更する
    $user_id = $_SESSION["user_id"];
    $authority = 1;
    // 更新日の時間も更新
    $sql = "UPDATE $user_table_name SET authority='$authority',updated_at=now() WHERE user_id='$user_id'";
    $stmt = $pdo->prepare($sql);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
    $_SESSION["authority"] = $authority;
    $authority = 0;
} else if ($authority === "管理者") {
    $authority = 1;
} else {
    $authority = 2;
}

// 権限変更の選択をしたメンバーの情報
$user_id = $_GET["user_id"];

// 更新日の時間も更新
$sql = "UPDATE $user_table_name SET authority='$authority',updated_at=now() WHERE user_id='$user_id'";
$stmt = $pdo->prepare($sql);
// SQL実行（実行に失敗すると `sql error ...` が出力される）
tryQuery($stmt);
header("Location:member_authority.php");

?>