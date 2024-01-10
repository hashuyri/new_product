<?php
include("functions.php");

// DB接続
$pdo = connectToDB($db_name);

// 法人番号と事業所名と作成日を表示
$sql = "SELECT customer_id, customer_name FROM $master_table ORDER BY created_at ASC";
$stmt = $pdo->prepare($sql);
tryQuery($stmt);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = "";
foreach ($result as $record) {
  $output .= "
    <tr>
      <td>{$record["customer_id"]}</td>
      <td>{$record["customer_name"]}</td>
      <td>
        <a href='customer_main_page.php?customer_id={$record["customer_id"]}'>選択</a>
      </td>
    </tr>
  ";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>税務判断支援システム（登録情報一覧）</title>
</head>

<body>
  <fieldset>
    <legend>税務判断支援システム（登録情報一覧）</legend>
    <a href="customer_info_input.php">入力画面</a>
    <a href="logout.php" id="read_page_logout">ログアウト</a>
    <table>
      <thead>
        <tr>
          <th>法人番号</th>
          <th>事業所名</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>
</body>

</html>