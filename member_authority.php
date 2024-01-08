<?php
session_start();
include('functions.php');
checkSessionId();

// DB接続
$pdo = connectToDB($db_name);

$user_table_name = "UT" . $_SESSION["customer_id"];
// 法人番号と事業所名と作成日を表示
$sql = "SELECT user_id, authority FROM $user_table_name WHERE deleted_at IS NULL ORDER BY authority ASC";
$stmt = $pdo->prepare($sql);
tryQuery($stmt);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = "";
foreach ($result as $record) {
    if ($record["authority"] === 0) {
        $authority = "オーナー";
    } else if ($record["authority"] === 1) {
        $authority = "管理者";
    } else {
        $authority = "一般";
    }
    $output .= "
    <tr>
        <td>{$record["user_id"]}</td>
        <td>{$authority}</td>
        <td>
            <a href='member_update.php?user_id={$record["user_id"]}'>権限変更</a>
        </td>
        <td>
            <a href='member_delete.php?user_id={$record["user_id"]}'>削除</a>
        </td>
    </tr>";
}
// echo "<pre>";
// var_dump($_SESSION);
// echo "<pre>";

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>税務判断支援システム（メンバー管理）</title>
</head>

<body>
    <fieldset>
        <legend>メンバー管理</legend>
        <form action="member_addition.php" method="POST">
            <fieldset>
                <legend>メンバー追加</legend>
                <div>
                    ユーザーid: <input type="email" name="user_id" required>
                </div>
                <div>
                    権限:
                    <select name="authority" required>
                        <option value="" selected disabled>選択してください</option>
                        <option value=1>管理</option>
                        <option value=2>一般</option>
                    </select>
                </div>
                <div>
                    <button>追加</button>
                </div>
            </fieldset>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ユーザーid</th>
                    <th>権限</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?= $output ?>
            </tbody>
        </table>
        <a href='customer_main_page.php?customer_id=<?= $_SESSION["customer_id"] ?>'>戻る</a>
    </fieldset>
</body>

</html>