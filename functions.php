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

// 借方勘定科目を標準勘定科目と突合
function findAccountItemDebit($debit_array, $db_table, $pdo, $account_item_array)
{
    // 標準勘定科目と一致しているか確認
    for ($i = 0; $i < count($debit_array); $i++) {
        $item = $debit_array[$i]["debit_item"];
        $sql = "SELECT * FROM $db_table WHERE account_item='$item'";
        $stmt = $pdo->prepare($sql);
        tryQuery($stmt);
        // 標準勘定科目と一致していたら値が格納される
        $find = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($find) {
            // 勘定科目の情報を移植
            $debit_array[$i] = $find + $debit_array[$i];
            // 不要な要素を削除
            unset($debit_array[$i]["debit_item"], $debit_array[$i]["created_at"], $debit_array[$i]["updated_at"]);
        }
    }

    // 標準勘定科目に該当しなかった勘定科目がある場合に標準勘定科目と近似しているか確認
    for ($i = 0; $i < count($debit_array); $i++) {
        if (count($debit_array[$i]) < 3) {
            $str_1 = $debit_array[$i]["debit_item"];
            foreach ($account_item_array as $item) {
                $str_2 = $item["account_item"];
                // レーベンシュタイン距離（文字列がどれだけ似ているか確認）
                $result = levenshtein($str_1, $str_2);
                if ($result < 4) {
                    $debit_array[$i] = $item + $debit_array[$i];
                    unset($debit_array[$i]["debit_item"], $debit_array[$i]["created_at"], $credit_array[$i]["updated_at"]);
                }
            }
            // 類似する勘定科目がなかったらお知らせ
            if (count($debit_array[$i]) < 3) {
                echo "<pre>";
                var_dump($debit_array[$i]);
                echo "<pre>";
            }
        }
    }
    return $debit_array;
}

// 貸方勘定科目を標準勘定科目と突合
function findAccountItemCredit($credit_array, $db_table, $pdo, $account_item_array)
{
    // 標準勘定科目と一致しているか確認
    for ($i = 0; $i < count($credit_array); $i++) {
        $item = $credit_array[$i]["credit_item"];
        $sql = "SELECT * FROM $db_table WHERE account_item='$item'";
        $stmt = $pdo->prepare($sql);
        tryQuery($stmt);
        // 標準勘定科目と一致していたら値が格納される
        $find = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($find) {
            // 勘定科目の情報を移植
            $credit_array[$i] = $find + $credit_array[$i];
            // 不要な要素を削除
            unset($credit_array[$i]["credit_item"], $credit_array[$i]["created_at"], $credit_array[$i]["updated_at"]);
        }
    }
    
    // 標準勘定科目に該当しなかった勘定科目がある場合に標準勘定科目と近似しているか確認
    for ($i = 0; $i < count($credit_array); $i++) {
        if (count($credit_array[$i]) < 3) {
            $str_1 = $credit_array[$i]["credit_item"];
            foreach ($account_item_array as $item) {
                $str_2 = $item["account_item"];
                // レーベンシュタイン距離（文字列がどれだけ似ているか確認）
                $result = levenshtein($str_1, $str_2);
                if ($result < 4) {
                    $credit_array[$i] = $item + $credit_array[$i];
                    unset($credit_array[$i]["credit_item"], $credit_array[$i]["created_at"], $credit_array[$i]["updated_at"]);
                }
            }
            // 類似する勘定科目がなかったらお知らせ
            if (count($credit_array[$i]) < 3) {
                echo "<pre>";
                var_dump($credit_array[$i]);
                echo "<pre>";
            }
        }
    }
    return $credit_array;
}

?>