<?php
include('functions.php');

if (
  !isset($_POST['user_id']) || $_POST['user_id'] === '' ||
  !isset($_POST['password']) || $_POST['password'] === ''
) {
  exit('paramError');
}

$user_id = $_POST["user_id"];
$password = $_POST["password"];

$pdo = connectToDB($db_name);

$sql = "SELECT COUNT(*) FROM $users_table WHERE user_id=:user_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);

tryQuery($stmt);

if ($stmt->fetchColumn() > 0) {
  echo '<p>すでに登録されているユーザです。</p>';
  echo '<a href="login.php">ログイン画面へ戻る</a>';
  exit();
}

$sql = "INSERT INTO $users_table(id, user_id, password, created_at, updated_at, deleted_at) VALUES(NULL, :user_id, :password, now(), now(), NULL)";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

tryQuery($stmt);

header("Location:login.php");
exit();
