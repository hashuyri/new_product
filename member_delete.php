<?php
session_start();
include('functions.php');
checkSessionId();

// echo "<pre>";
// var_dump($_GET);
// var_dump($_SESSION["authority"]);
// echo "<pre>";

// 削除の選択をしたメンバーの情報
$user_id = $_GET["user_id"];
$authority = $_GET["authority"];

// 追加権限の確認
if ($_SESSION["authority"] > 1) {
    echo "<p>メンバーを削除する権限がありません。</p>";
    echo "<a href='member_authority.php'>メンバー管理画面へ戻る</a>";
    exit();
} else if($authority == 0){
    echo "<p>オーナーは削除することができません。</p>";
    echo "<a href='member_authority.php'>メンバー管理画面へ戻る</a>";
    exit();
} else if ($user_id === $_SESSION["user_id"]) {
    echo "<p>自身を削除することができません。</p>";
    echo "<a href='member_authority.php'>メンバー管理画面へ戻る</a>";
    exit();
}

// DB接続
$pdo = connectToDB($db_name);

// テーブルにデータを書き込み
$user_table_name = "UT" . $_SESSION["customer_id"];
$sql = "DELETE FROM $user_table_name WHERE user_id='$user_id'";
$stmt = $pdo->prepare($sql);
// SQL実行（実行に失敗すると `sql error ...` が出力される）
tryQuery($stmt);
header("Location:member_authority.php");

?>