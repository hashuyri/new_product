<?php
// テーブルの存在確認
$sql = "SELECT count(*) as cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table_name'";
$stmt = $pdo->prepare($sql);
tryQuery($stmt);
$find_table = $stmt->fetch(PDO::FETCH_ASSOC);
if ($find_table["cnt"] === 1) {
    // DBから標準勘定科目をすべて抽出
    $sql = "SELECT * FROM $account_table";
    $stmt = $pdo->prepare($sql);
    tryQuery($stmt);
    $account_item_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // MySQLの借方情報をsumifする
    $sql = "SELECT `借方決算書表示名` as debit_item, SUM(`借方金額`) as total_sum FROM $table_name GROUP BY `借方決算書表示名`";
    $stmt = $pdo->prepare($sql);
    tryQuery($stmt);
    $debit_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //削除実行
    array_splice($debit_array, 0, 1);

    $debit_array = findAccountItemDebit($debit_array, $account_table, $pdo, $account_item_array);

    // MySQLの貸方情報をsumifする
    $sql = "SELECT `貸方決算書表示名` as credit_item, SUM(`貸方金額`) as total_sum FROM $table_name GROUP BY `貸方決算書表示名`";
    $stmt = $pdo->prepare($sql);
    tryQuery($stmt);
    $credit_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //削除実行
    array_splice($credit_array, 0, 1);

    $credit_array = findAccountItemCredit($credit_array, $account_table, $pdo, $account_item_array);
}

// 画面に表示（各区分を明確に分ける）

?>