<?php
session_start();
include('functions.php');
checkSessionId();

// echo "<pre>";
// var_dump($_POST);
// echo "<pre>";

// 追加権限の確認
if($_SESSION["authority"] > 1) {
    echo "<p>メンバーを追加する権限がありません。</p>";
    echo "<a href='member_authority.php'>メンバー管理画面へ戻る</a>";
    exit();
}

// DB接続
$pdo = connectToDB($db_name);

$user_id = $_POST["user_id"];
$authority = $_POST["authority"];

// 登録済のユーザーidかどうか確認 
$sql = "SELECT user_id FROM $users_table WHERE user_id=:user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
// SQL実行（実行に失敗すると `sql error ...` が出力される）
tryQuery($stmt);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result === false) {
    // 重複した法人番号を登録しようとし時、法人番号を付けて入力画面に戻る
    echo "<p>登録されていないユーザーです。</p>";
    echo "<p>'$user_id'をユーザー登録後メンバーに追加してください。</p>";
    echo "<a href='member_authority.php'>メンバー管理画面へ戻る</a>";
    exit();
}

// 既に企業のユーザーテーブルに追加済みでないかどうか確認
$user_table_name = "registered_user_table";
$customer_id = $_SESSION["customer_id"];
$sql = "SELECT user_id FROM $user_table_name WHERE customer_id=$customer_id AND user_id=:user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
// SQL実行（実行に失敗すると `sql error ...` が出力される）
tryQuery($stmt);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result !== false) {
    // 重複したユーザーを登録しようとし時
    echo "<p>ユーザーはすでに登録されています。</p>";
    echo "<a href='member_authority.php'>メンバー管理画面へ戻る</a>";
    exit();
}
// テーブルにデータを書き込み
$sql = "INSERT INTO $user_table_name (customer_id, user_id,
    authority)
    VALUES
    ('$customer_id', '$user_id', '$authority'
)";
$stmt = $pdo->prepare($sql);
// SQL実行（実行に失敗すると `sql error ...` が出力される）
tryQuery($stmt);
header("Location:member_authority.php");

?>