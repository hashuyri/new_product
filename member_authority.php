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
for ($i = 0; $i < count($result); $i++) {
    if ($result[$i]["authority"] === 0) {
        $authority = "オーナー";
    } else if ($result[$i]["authority"] === 1) {
        $authority = "管理者";
    } else {
        $authority = "一般";
    }
    // idに配列内の要素識別用の番号を付してJSに渡す
    $output .= "
    <tr>
        <td>{$result[$i]["user_id"]}</td>
        <td>{$authority}</td>
        <td>
            <button class='authority_update' id=authority_$i>権限変更</button>
        </td>
        <td>
            <button class='user_id_delete' id=user_$i>削除</button>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        // メンバーの権限を変更する場合
        $(".authority_update").on("click", function () {
            const submit = confirm('本当に権限変更しますか？');
            // OKが押されたら
            if (submit) {
                // 配列の要素番号を取り出す
                const str = $(this).attr("id");
                const i = str.slice(str.indexOf("_") + 1);
                const user_array = <?= json_encode($result) ?>;
                console.log(i);
                console.log(user_array);
                location.href = `member_update.php?user_id=${user_array[i]["user_id"]}&authority=${user_array[i]["authority"]}`;
            }
        });

        // メンバーを削除する場合
        $(".user_id_delete").on("click", function () {
            const submit = confirm('本当に削除しますか？');
            // OKが押されたら
            if (submit) {
                // 配列の要素番号を取り出す
                const str = $(this).attr("id");
                const i = str.slice(str.indexOf("_") + 1);
                const user_array = <?= json_encode($result) ?>;
                location.href = `member_delete.php?user_id=${user_array[i]["user_id"]}&authority=${user_array[i]["authority"]}`;
            }
        });
    </script>
</body>

</html>