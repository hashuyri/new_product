<?php

if (isset($_FILES["fname"])) {
    $files = $_FILES["fname"];
    $tmpfile = $files["tmp_name"];
} else {
    $tmpfile = "";
}

$directory = "./data/input/";
$comment = ""; // エラー対策で外だし

// ファイルがアップロードされているか確認
if (is_uploaded_file($tmpfile)) {
    // echo "<pre>";
    // var_dump($files);
    // echo "<pre>";
    $filename = $directory . $files["name"]; // フォルダの指定
    // アップロードされていれば、ファイルを指定のフォルダに格納する
    if (move_uploaded_file($tmpfile, $filename)) {
        $comment = $files["name"] . "を" . $directory . "にアップロードしました！";
    } else {
        $comment = "ファイルをアップロードできません。";
    }
}

$getFileinfo = [];
$str = "";
$data = [];
$debitArray = []; // エラー対策で外だし
$creditArray = []; // エラー対策で外だし

// ディレクトリに格納されてるファイル一覧を読み込む
$dp = opendir($directory);

// ディレクトリ内のファイル名を読み込む
while (($item = readdir($dp))) {
    if ($item === '.' || $item === '..') {
        continue;
    }
    // key：ファイル名、value：更新時間
    $getFileinfo[$item] = filemtime($directory.$item);
}

closedir($dp);
// echo "<pre>";
// var_dump($getFileinfo);
// echo "<pre>";

// $getFileinfoに要素が一つでも入っていたら
if (count($getFileinfo) > 0) {
    // 更新時間が最新のファイルを反映させる
    $maxes = array_keys($getFileinfo, max($getFileinfo));
    $filename = $directory . $maxes[0];
    $file = fopen($filename, "r");
    flock($file, LOCK_EX);

    if ($file) {
        $header = fgetcsv($file); // カラム名を格納するための配列
        foreach ($header as $key => $value) {
            $header[$key] = mb_convert_encoding($value, "UTF-8", "SJIS-win");
        }
        // echo "<pre>";
        // var_dump($header);
        // echo "<pre>";

        // DB各種項目設定
        $dbname = "gs_dev14_06";
        $user = "root";
        $pwd = "";
        $option = "charset=utf8";
        $dbn = "mysql:host=localhost;dbname=" . $dbname . ";" . $option . ";" . "port=3306";

        // DB接続
        try {
            $pdo = new PDO($dbn, $user, $pwd);
        } catch (PDOException $e) {
            echo json_encode(["db error" => "{$e->getMessage()}"]);
            exit();
        }

        // テーブルの作成
        $tableName = "journalEntry_table";
        $pdo->query("create table if not exists $tableName ($header[0] INT(11), $header[1] DATE, $header[2] VARCHAR(128), $header[3] VARCHAR(128), $header[4] INT(20), $header[5] VARCHAR(128), $header[6] VARCHAR(128), $header[7] VARCHAR(128), $header[8] INT(20), $header[9] VARCHAR(128), $header[10] VARCHAR(128), created_at DATETIME, updated_at DATETIME)");

        // DBを空にする
        if (isset($_FILES["fname"])) {
            $pdo->query("truncate table  $tableName");
        }

        // ファイルの終端に達するまで行ごとに処理
        while ($row = fgetcsv($file)) {
            // 各フィールドの文字エンコーディングをUTF-8に変換
            foreach ($row as $key => $value) {
                $row[$key] = mb_convert_encoding($value, "UTF-8", "SJIS-win");
            }
            $data[] = array_combine($header, $row); // カラム名とデータを関連付けて格納

            // DBのテーブルにデータの書き込み
            if (isset($_FILES["fname"])) {
                $entryDate = new DateTime($row[1]);
                $entryDate = $entryDate->format("Y-m-d");
                $sql = "INSERT INTO $tableName ($header[0], $header[1], $header[2], $header[3], $header[4], $header[5], $header[6], $header[7], $header[8], $header[9], $header[10], created_at, updated_at) VALUES ('$row[0]', '$entryDate', '$row[2]', '$row[3]', '$row[4]', '$row[5]', '$row[6]',' $row[7]', '$row[8]', '$row[9]', '$row[10]', now(), now())";
                $stmt = $pdo->prepare($sql);
                // echo "<pre>";
                // var_dump($stmt);
                // echo "<pre>";

                // SQL実行（実行に失敗すると `sql error ...` が出力される）
                try {
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo json_encode(["sql error" => "{$e->getMessage()}"]);
                    exit();
                }
            }
        }
    }
    
    // MySQLの情報をsumifする
    // $sql = "SELECT $header[3], SUM($header[4]) FROM $tableName GROUP BY $header[3]";

    // 仕訳帳から科目を取り出す
    foreach ($data as $value) {
        $tentativeDebitArray[] = $value[$header[3]];
        $tentativeCreditArray[] = $value[$header[7]];
    }

    $debitArray = array_unique($tentativeDebitArray); // 重複している借方科目の削除
    $debitArray = array_diff($debitArray, array("")); //要素なしを削除
    $debitArray = array_values($debitArray); //indexを詰める
    $debitArray = array_fill_keys($debitArray, 0); // 配列から連想配列へ変換してvalueに「0」を設定

    $creditArray = array_unique($tentativeCreditArray); // 重複している貸方科目の削除
    $creditArray = array_diff($creditArray, array("")); //要素なしを削除
    $creditArray = array_values($creditArray); //indexを詰める
    $creditArray = array_fill_keys($creditArray, 0); // 配列から連想配列へ変換してvalueに「0」を設定

    // echo "<pre>";
    // var_dump($debitArray);
    // var_dump($creditArray);
    // echo "<pre>";

    // 仕訳から生成した連想配列に集計した数値を格納
    foreach ($data as $value) {
        // dataの仕訳要素が「""」でなければ
        if (in_array($value[$header[3]], array_keys($debitArray))) {
            $debitArray[$value[$header[3]]] += $value[$header[4]];
        }

        if (in_array($value[$header[7]], array_keys($creditArray))) {
            $creditArray[$value[$header[7]]] += $value[$header[8]];
        }
    }

    // echo "<pre>";
    // var_dump($debitArray);
    // var_dump($creditArray);
    // echo "<pre>";

    flock($file, LOCK_UN);
    fclose($file);
}

?>