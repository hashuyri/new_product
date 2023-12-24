<?php
function connect_to_db()
{
    // DB接続
    $db_name = "gs_dev14_06";
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

// グローバル変数
$master_table = "master_data_table";

?>