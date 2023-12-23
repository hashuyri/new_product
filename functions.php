<?php
function connect_to_db()
{
    // DB接続
    $dbname = "gs_dev14_06";
    $user = "root";
    $pwd = "";
    $option = "charset=utf8";
    $dbn = "mysql:host=localhost;dbname=" . $dbname . ";" . $option . ";" . "port=3306";

    // DB接続
    try {
        return new PDO($dbn, $user, $pwd);
    } catch (PDOException $e) {
        echo json_encode(["db error" => "{$e->getMessage()}"]);
        exit();
    }
}

?>