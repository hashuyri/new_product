<?php
// DB接続
$pdo = connectToDB($db_name);

// info_read.phpから受け取ったcustomer_idを格納
if (count($_GET) > 0) {
    $customer_id = $_GET["customer_id"];

    // ユーザーの権限を取得
    $user_table_name = "registered_user_table";
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT authority FROM $user_table_name WHERE user_id=:user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION["authority"] = $result["authority"];
    // echo "<pre>";
    // var_dump($_SESSION);
    // echo "<pre>";

    // 企業情報を取得
    $sql = "SELECT * FROM $master_table WHERE customer_id= :customer_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":customer_id", $customer_id, PDO::PARAM_STR);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SESSION["customer_name"] !== $result["customer_name"]) {
        $_SESSION["customer_name"] = $result["customer_name"];
    }
}

// customer_info_input.phpから受け取った登録情報をDBに格納
if (isset($_POST["customer_name"]) && $_POST["customer_name"] != "") {
    // 登録情報を各変数に格納する
    $customer_info_array = array_keys($_POST);
    $customer_name = $_POST["customer_name"];
    $representative = $_POST["representative"];
    $address_number = $_POST["address_number"];
    $address = $_POST["address"];
    $tel = $_POST["tel"];
    $closing_month = $_POST["closing_month"];
    $closing_month = new DateTime("last day of $closing_month"); // 指定された月の月末を取得
    $closing_month = $closing_month->format("Y-m-d");

    // テーブルにデータを書き込み
    $sql = "INSERT INTO $master_table (customer_id, $customer_info_array[0],
        $customer_info_array[1], $customer_info_array[2], $customer_info_array[3],
        $customer_info_array[4], $customer_info_array[5], created_at, updated_at)
        VALUES
        (NULL, :customer_name, :representative,
        '$address_number', :address, :tel, '$closing_month', now(), now()
    )";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":customer_name", $customer_name, PDO::PARAM_STR);
    $stmt->bindValue(":representative", $representative, PDO::PARAM_STR);
    $stmt->bindValue(":address", $address, PDO::PARAM_STR);
    $stmt->bindValue(":tel", $tel, PDO::PARAM_STR);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);

    // customer_idを取得
    $sql = "SELECT customer_id FROM $master_table WHERE customer_name= :customer_name ORDER BY created_at DESC LIMIT 1;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":customer_name", $customer_name, PDO::PARAM_STR);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
    $customer_id = $stmt->fetch(PDO::FETCH_ASSOC);

    $user_table_name = "registered_user_table";
    $mail_address = $_SESSION["user_id"];
    // テーブルにデータを書き込み
    $sql = "INSERT INTO $user_table_name (customer_id, user_id,
        authority)
        VALUES
        ('$customer_id', '$mail_address', 0
    )";
    $stmt = $pdo->prepare($sql);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
    $_SESSION["authority"] = 0;

    if ($_SESSION["customer_name"] !== $customer_name) {
        $_SESSION["customer_name"] = $customer_name;
    }
}

if ($_SESSION["customer_id"] !== $customer_id) {
    $_SESSION["customer_id"] = $customer_id;
}

// echo "<pre>";
// var_dump($_SESSION);
// echo "<pre>";

?>