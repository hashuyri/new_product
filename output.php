<?php
// JSで集計した数値をCSVに吐き出す用

$data = json_decode(file_get_contents("php://input"), true); // 送ったデータを受け取る（GETで送った場合は、INPUT_GET）
echo json_encode($data);

?>