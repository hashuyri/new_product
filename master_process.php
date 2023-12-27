<?php
include("functions.php");

// echo "<pre>";
// var_dump(count($_GET));
// echo "<pre>";

// info_read.phpから受け取ったcustomer_idを格納
if (count($_GET) > 0) {
    $customer_id = $_GET["customer_id"];
    // テーブル
    $table_name = "T" . $customer_id;
}

// DB接続
$pdo = connectToDB();

// customer_info_input.phpから受け取った登録情報をDBに格納
if (isset($_POST["customer_id"]) && $_POST["customer_id"] != "") {
    // 登録情報を各変数に格納する
    $customer_info_array = array_keys($_POST);
    $customer_id = $_POST["customer_id"];
    $customer_name = $_POST["customer_name"];
    $representative = $_POST["representative"];
    $address_number = $_POST["address_number"];
    $address = $_POST["address"];
    $tel = $_POST["tel"];
    $mail_address = $_POST["mail_address"];
    $closing_month = $_POST["closing_month"];
    $closing_month = new DateTime("last day of $closing_month"); // 指定された月の月末を取得
    $closing_month = $closing_month->format("Y-m-d");
    $class_radio = $_POST["class_radio"];
    
    // 既に登録された法人番号かどうか確認 
    $sql = "SELECT customer_id FROM $master_table WHERE customer_id=$customer_id";
    $stmt = $pdo->prepare($sql);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result != false) {
        // 重複した法人番号を登録しようとし時、法人番号を付けて入力画面に戻る
        header("Location:customer_info_input.php?customer_id={$customer_id}");
    }

    // テーブルの作成
    $pdo->query("create table if not exists $master_table (
            $customer_info_array[0] VARCHAR(128), $customer_info_array[1] VARCHAR(128),
            $customer_info_array[2] VARCHAR(128), $customer_info_array[3] INT(7),
            $customer_info_array[4] VARCHAR(128), $customer_info_array[5] VARCHAR(12),
            $customer_info_array[6] VARCHAR(128), $customer_info_array[7] DATE,
            $customer_info_array[8] VARCHAR(128), created_at DATETIME, updated_at DATETIME,
            PRIMARY KEY ($customer_info_array[0]))
    "); // PRIMARY KEYは法人番号を設定

    // テーブルにデータを書き込み
    $sql = "INSERT INTO $master_table ($customer_info_array[0], $customer_info_array[1],
        $customer_info_array[2], $customer_info_array[3], $customer_info_array[4],
        $customer_info_array[5], $customer_info_array[6], $customer_info_array[7],
        $customer_info_array[8], created_at, updated_at)
        VALUES
        ('$customer_id', '$customer_name', '$representative',
        '$address_number', '$address', '$tel',' $mail_address',
        '$closing_month', '$class_radio', now(), now()
    )";
    $stmt = $pdo->prepare($sql);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
}

$sql = "SELECT * FROM $master_table WHERE customer_id=$customer_id";
$stmt = $pdo->prepare($sql);
// SQL実行（実行に失敗すると `sql error ...` が出力される）
tryQuery($stmt);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
// echo "<pre>";
// var_dump($result);
// echo "<pre>";

?>