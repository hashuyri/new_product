<?php
// グローバル変数
$db_name = "gs_dev14_06";
$master_table = "master_data_table";
$account_table = "account_item_table";
function connectToDB($db_name)
{
    $user = "root";
    $pwd = "";
    $option = "charset=utf8";
    $dbn = "mysql:host=localhost;dbname=" . $db_name . ";" . $option . ";" . "port=3306";

    // DB接続
    try {
        return new PDO($dbn, $user, $pwd);
    } catch (PDOException $e) {
        echo json_encode(["db error" => "{$e->getMessage()}"]);
        exit();
    }
}

// MySQLへの指示を実行（実行に失敗すると `sql error ...` が出力される）
function tryQuery($stmt)
{
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo json_encode(["sql error" => "{$e->getMessage()}"]);
        exit();
    }
}

// 勘定科目を標準勘定科目と突合
function findAccountItem($debit_array, $credit_array, $account_table, $pdo)
{
    // DBから標準勘定科目をすべて抽出
    $sql = "SELECT * FROM $account_table";
    $stmt = $pdo->prepare($sql);
    tryQuery($stmt);
    $account_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 標準勘定科目と一致しているか確認
    for ($i = 0; $i < count($account_array); $i++) {
        $item = $account_array[$i]["account_item"];
        $account_array[$i]["debit_sum"] = 0;
        $account_array[$i]["credit_sum"] = 0;
        unset($account_array[$i]["created_at"], $account_array[$i]["updated_at"]);
        for ($j = 0; $j < count($debit_array); $j++) {
            if ($item === $debit_array[$j]["debit_item"]) {
                // 勘定科目の情報を移植
                $account_array[$i]["debit_sum"] += $debit_array[$j]["debit_sum"];
                // 勘定科目の一致が確認出来た要素を削除
                array_splice($debit_array, $j, 1);
            }
        }
        for ($j = 0; $j < count($credit_array); $j++) {
            if ($item === $credit_array[$j]["credit_item"]) {
                // 勘定科目の情報を移植
                $account_array[$i]["credit_sum"] += $credit_array[$j]["credit_sum"];
                // 勘定科目の一致が確認出来た要素を削除
                array_splice($credit_array, $j, 1);
            }
        }
    }

    // 標準勘定科目に該当しなかった勘定科目がある場合に標準勘定科目と近似しているか確認
    for ($i = 0; $i < count($account_array); $i++) {
        $str_1 = $account_array[$i]["account_item"];
        for ($j = 0; $j < count($debit_array); $j++) {
            $str_2 = $debit_array[$j]["debit_item"];
            // レーベンシュタイン距離（文字列がどれだけ似ているか確認）
            $result = levenshtein($str_1, $str_2);
            if ($result < 4) {
                // 勘定科目の情報を移植
                $account_array[$i]["debit_item"] = $debit_array[$j]["debit_sum"];
                array_splice($debit_array, $j, 1);
            }
        }
        for ($j = 0; $j < count($credit_array); $j++) {
            $str_3 = $credit_array[$j]["credit_item"];
            // レーベンシュタイン距離（文字列がどれだけ似ているか確認）
            $result = levenshtein($str_1, $str_3);
            if ($result < 4) {
                // 勘定科目の情報を移植
                $account_array[$i]["credit_sum"] += $credit_array[$j]["credit_sum"];
                array_splice($credit_array, $j, 1);
            }
        }
    }
    // 類似する勘定科目がなかったらお知らせ
    if (count($debit_array) > 0) {
        echo "<pre>";
        var_dump($debit_array);
        echo "<pre>";
    }
    if (count($credit_array) > 0) {
        echo "<pre>";
        var_dump($credit_array);
        echo "<pre>";
    }
    $account_item_array = [];
    foreach($account_array as $value){
        if($value["debit_sum"] != 0 || $value["credit_sum"] != 0){
            $account_item_array[] = $value;
        }
    }
    return $account_item_array;
}

?>