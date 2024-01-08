<?php
session_start();
include('functions.php');

// データ受け取り
$user_id = $_POST["user_id"];
$password = $_POST["password"];

// DB接続
$pdo = connectToDB($db_name);

// SQL実行
$sql = "SELECT * FROM users_table WHERE user_id=:user_id AND password=:password AND deleted_at IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

tryQuery($stmt);

// ユーザ有無で条件分岐
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo "<p>ログイン情報に誤りがあります</p>";
    echo "<a href=login.php>ログイン</a>";
    exit();
} else {
    $_SESSION = array();
    $_SESSION['session_id'] = session_id();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['authority'] = "";
    $_SESSION["customer_id"] = "";
    $_SESSION["customer_name"] = "";
    header("Location:info_read.php");
    exit();
}