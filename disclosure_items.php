<?php
$table_name = "T" . $customer_id;
// テーブルの存在確認
$sql = "SELECT count(*) as cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table_name'";
$stmt = $pdo->prepare($sql);
tryQuery($stmt);
$find_table = $stmt->fetch(PDO::FETCH_ASSOC);
if ($find_table["cnt"] === 1) {
    // MySQLの借方情報をsumifする
    $sql = "SELECT `借方決算書表示名` as debit_item, SUM(`借方金額`) as debit_sum FROM $table_name GROUP BY `借方決算書表示名`";
    $stmt = $pdo->prepare($sql);
    tryQuery($stmt);
    $debit_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //削除実行
    array_splice($debit_array, 0, 1);

    // MySQLの貸方情報をsumifする
    $sql = "SELECT `貸方決算書表示名` as credit_item, SUM(`貸方金額`) as credit_sum FROM $table_name GROUP BY `貸方決算書表示名`";
    $stmt = $pdo->prepare($sql);
    tryQuery($stmt);
    $credit_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //削除実行
    array_splice($credit_array, 0, 1);

    // 勘定科目情報の付与
    $account_item_array = findAccountItem($debit_array, $credit_array, $account_table, $pdo);
} else {
    $account_item_array = 0;
}

?>