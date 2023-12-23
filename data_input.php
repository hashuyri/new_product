<?php
include("main.php");

// DB接続
$pdo = connect_to_db();

// 登録情報を各変数に格納する
$businessInfo_array = array_keys($_POST);
$business_id = $_POST["business_id"];
$business_name = $_POST["business_name"];
$representative = $_POST["representative"];
$address_number = $_POST["address_number"];
$address = $_POST["address"];
$tel = $_POST["tel"];
$mail_address = $_POST["mail_address"];
$closing_month = $_POST["closing_month"];
$closing_month = new DateTime("last day of $closing_month"); // 指定された月の月末を取得
$closing_month = $closing_month->format("Y-m-d");
$class_radio = $_POST["class_radio"];

// テーブルの作成
$tableName = "masterData_table";
$pdo->query("create table if not exists $tableName (
            $businessInfo_array[0] VARCHAR(128), $businessInfo_array[1] VARCHAR(128),
            $businessInfo_array[2] VARCHAR(128), $businessInfo_array[3] INT(7),
            $businessInfo_array[4] VARCHAR(128), $businessInfo_array[5] VARCHAR(12),
            $businessInfo_array[6] VARCHAR(128), $businessInfo_array[7] DATE,
            $businessInfo_array[8] VARCHAR(128), created_at DATETIME, updated_at DATETIME,
            PRIMARY KEY ($businessInfo_array[0]))
"); // PRIMARY KEYは法人番号を設定

// テーブルにデータを書き込み
$sql = "INSERT INTO $tableName ($businessInfo_array[0], $businessInfo_array[1],
        $businessInfo_array[2], $businessInfo_array[3], $businessInfo_array[4],
        $businessInfo_array[5], $businessInfo_array[6], $businessInfo_array[7],
        $businessInfo_array[8], created_at, updated_at)
        VALUES
        ('$business_id', '$business_name', '$representative',
        '$address_number', '$address', '$tel',' $mail_address',
        '$closing_month', '$class_radio', now(), now()
)";
$stmt = $pdo->prepare($sql);
// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
    $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    // 重複した法人番号を登録しようとし時、法人番号を付けて入力画面に戻る
    header("Location:business_info_input.php?business_id={$business_id}");
}

$sql = "SELECT * FROM $tableName WHERE business_id";
$stmt = $pdo->prepare($sql);
// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
    $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
var_dump($result);
echo "<pre>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L / R</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div id="modal_open_outbox">
        <p class="modal-open">資料のアップロードはこちら</p>
    </div>

    <div class="modal-container">
        <div class="modal-body">
            <div class="modal-close">×</div>
            <div class="modal-content">
                <div id="file_upload">
                    <div id="file_name_output">
                        「<span id="file_name"></span>」
                        <p>を選択中</p>
                    </div>
                    <form action="data_input.php" method="POST" enctype="multipart/form-data">
                        <label id="file_select_btn">
                            ファイルを選択
                            <input type="file" id="file_select" name="fname">
                        </label>
                        <label id="upload_btn">
                            実行
                            <input type="submit" value="upload">
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- アップロードコメント -->
    <p>
        <?= $comment ?>
    </p>


    <div id="result_box">
        <button type="button" id="submit">集計結果</button>
    </div>

    <!-- テーブル表示用 -->
    <div id="toggle">
        <div id="output"></div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- 他のJSファイルでPHPの値を使えるように -->
    <script>
        const debitArray = <?= json_encode($debitArray) ?>;
        const creditArray = <?= json_encode($creditArray) ?>;
    </script>
    <script src="./js/acitem.js"></script>
    <script src="./js/main.js"></script>
</body>

</html>