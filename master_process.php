<?php
// DB接続
$pdo = connectToDB($db_name);

// info_read.phpから受け取ったcustomer_idを格納
if (count($_GET) > 0) {
    $customer_id = $_GET["customer_id"];

    // ユーザーの権限を取得
    $user_table_name = "UT" . $customer_id;
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
}

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
    $closing_month = $_POST["closing_month"];
    $closing_month = new DateTime("last day of $closing_month"); // 指定された月の月末を取得
    $closing_month = $closing_month->format("Y-m-d");
    $class_radio = $_POST["class_radio"];

    // 既に登録された法人番号かどうか確認 
    $sql = "SELECT customer_id FROM $master_table WHERE customer_id=:customer_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_STR);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
        // 重複した法人番号を登録しようとし時、法人番号を付けて入力画面に戻る
        echo "<p>会社情報はすでに登録されています。</p>";
        echo "<p>'$customer_id'の管理者にメンバーの追加を依頼してください。</p>";
        echo "<a href='customer_info_input.php'>情報登録画面へ戻る</a>";
        exit();
    } else {
        $result = $_POST;
    }

    // テーブルにデータを書き込み
    $sql = "INSERT INTO $master_table ($customer_info_array[0], $customer_info_array[1],
        $customer_info_array[2], $customer_info_array[3], $customer_info_array[4],
        $customer_info_array[5], $customer_info_array[6], $customer_info_array[7],
        created_at, updated_at)
        VALUES
        (:customer_id, :customer_name, :representative,
        '$address_number', :address, :tel, '$closing_month',
        '$class_radio', now(), now()
    )";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":customer_id", $customer_id, PDO::PARAM_STR);
    $stmt->bindValue(":customer_name", $customer_name, PDO::PARAM_STR);
    $stmt->bindValue(":representative", $representative, PDO::PARAM_STR);
    $stmt->bindValue(":address", $address, PDO::PARAM_STR);
    $stmt->bindValue(":tel", $tel, PDO::PARAM_STR);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);

    $user_table_name = "UT" . $customer_id;
    // テーブルの作成
    $pdo->query("create table if not exists $user_table_name (
        id int(12) not null primary key auto_increment,
        user_id varchar(128) not null,
        authority int(1) not null,
        created_at datetime not null,
        updated_at datetime not null,
        deleted_at datetime null)");

    $mail_address = $_SESSION["user_id"];
    // テーブルにデータを書き込み
    $sql = "INSERT INTO $user_table_name (id, user_id,
        authority, created_at, updated_at, deleted_at)
        VALUES
        (NULL, '$mail_address', 0, now(), now(), NULL
    )";
    $stmt = $pdo->prepare($sql);
    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    tryQuery($stmt);
    $_SESSION["authority"] = 0;
}

if ($_SESSION["customer_id"] !== $customer_id) {
    $_SESSION["customer_id"] = $customer_id;
}
if ($_SESSION["customer_name"] !== $result["customer_name"]) {
    $_SESSION["customer_name"] = $result["customer_name"];
}

// echo "<pre>";
// var_dump($_SESSION);
// echo "<pre>";

?>