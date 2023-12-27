<?php

if (isset($_FILES["fname"])) {
    $files = $_FILES["fname"];
    $tmp_file = $files["tmp_name"];
} else {
    $tmp_file = "";
}

// クライアントの個別フォルダ作成
$directory = "./data/" . $customer_id;
if (!file_exists($directory)) {
    mkdir($directory, 0755);
}

// クライアントフォルダ内にinputフォルダ作成
$directory = $directory . "/input";
if (!file_exists($directory)) {
    mkdir($directory, 0755);
}

$comment = ""; // エラー対策で外だし

// ファイルがアップロードされているか確認
if (is_uploaded_file($tmp_file)) {
    $file_name = $directory . "/" . $files["name"]; // フォルダの指定
    // アップロードされていれば、ファイルを指定のフォルダに格納する
    if (move_uploaded_file($tmp_file, $file_name)) {
        $comment = $files["name"] . "を" . $directory . "にアップロードしました！";
    } else {
        $comment = "ファイルをアップロードできません。";
    }
}

$get_file_info = [];
$str = "";
$data = [];
$debit_array = []; // エラー対策で外だし
$credit_array = []; // エラー対策で外だし

// ディレクトリの存在確認
if (file_exists($directory)) {
    // ディレクトリに格納されてるファイル一覧を読み込む
    $dp = opendir($directory);

    // ディレクトリ内のファイル名を読み込む
    while (($item = readdir($dp))) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        // key：ファイル名、value：更新時間
        $get_file_info[$item] = filemtime($directory . "/" . $item);
    }

    closedir($dp);
    // echo "<pre>";
    // var_dump(count($get_file_info));
    // echo "<pre>";
}

// $get_file_infoに要素が一つでも入っていたら
if (count($get_file_info) > 0) {
    // 更新時間が最新のファイルを反映させる
    $maxes = array_keys($get_file_info, max($get_file_info));
    $file_name = $directory . "/" . $maxes[0];
    $file = fopen($file_name, "r");
    flock($file, LOCK_EX);

    if ($file) {
        $header = fgetcsv($file); // カラム名を格納するための配列
        foreach ($header as $key => $value) {
            $header[$key] = mb_convert_encoding($value, "UTF-8", "SJIS-win");
        }
        // echo "<pre>";
        // var_dump($header);
        // echo "<pre>";

        // DB接続
        $pdo = connectToDB();

        // テーブルの作成
        $table_name = "T" . $customer_id;
        $pdo->query("create table if not exists $table_name
                    ($header[0] INT(11), $header[1] DATE, $header[2] VARCHAR(128),
                    $header[3] VARCHAR(128), $header[4] INT(20), $header[5] VARCHAR(128),
                    $header[6] VARCHAR(128), $header[7] VARCHAR(128), $header[8] INT(20),
                    $header[9] VARCHAR(128), $header[10] VARCHAR(128), created_at DATETIME,
                    updated_at DATETIME)
        ");

        // DBを空にする
        if (isset($_FILES["fname"])) {
            $pdo->query("truncate table  $table_name");
        }

        // LOAD DATA INFILE './data/test.csv' INTO TABLE test FIELDS TERMINATED BY ',' ENCLOSED BY '"';
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
                $sql = "INSERT INTO $table_name
                        ($header[0], $header[1], $header[2], $header[3], $header[4],
                        $header[5], $header[6], $header[7], $header[8], $header[9],
                        $header[10], created_at, updated_at)
                        VALUES
                        ('$row[0]', '$entryDate', '$row[2]', '$row[3]', '$row[4]',
                        '$row[5]', '$row[6]',' $row[7]', '$row[8]', '$row[9]',
                        '$row[10]', now(), now()
                )";
                $stmt = $pdo->prepare($sql);
                // echo "<pre>";
                // var_dump($stmt);
                // echo "<pre>";

                // SQL実行（実行に失敗すると `sql error ...` が出力される）
                tryQuery($stmt);
            }
        }
    }

    

    // MySQLの借方情報をsumifする
    $sql = "SELECT `借方決算書表示名` as debit_item, SUM(`借方金額`) as total_sum FROM $table_name GROUP BY `借方決算書表示名`";
    $stmt = $pdo->prepare($sql);
    tryQuery($stmt);
    $debit_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //削除実行
    array_splice($debit_array, 0, 1);
    // echo "<pre>";
    // var_dump($debit_array);
    // echo "<pre>";

    // MySQLの貸方情報をsumifする
    $sql = "SELECT `貸方決算書表示名` as credit_item, SUM(`貸方金額`) as total_sum FROM $table_name GROUP BY `貸方決算書表示名`";
    $stmt = $pdo->prepare($sql);
    tryQuery($stmt);
    $credit_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //削除実行
    array_splice($credit_array, 0, 1);
    // echo "<pre>";
    // var_dump($credit_array);
    // echo "<pre>";



    // 仕訳帳から科目を取り出す
    foreach ($data as $value) {
        $tentative_debit_array[] = $value[$header[3]];
        $tentative_credit_array[] = $value[$header[7]];
    }

    $debit_array = array_unique($tentative_debit_array); // 重複している借方科目の削除
    $debit_array = array_diff($debit_array, array("")); //要素なしを削除
    $debit_array = array_values($debit_array); //indexを詰める
    $debit_array = array_fill_keys($debit_array, 0); // 配列から連想配列へ変換してvalueに「0」を設定

    $credit_array = array_unique($tentative_credit_array); // 重複している貸方科目の削除
    $credit_array = array_diff($credit_array, array("")); //要素なしを削除
    $credit_array = array_values($credit_array); //indexを詰める
    $credit_array = array_fill_keys($credit_array, 0); // 配列から連想配列へ変換してvalueに「0」を設定

    // 仕訳から生成した連想配列に集計した数値を格納
    foreach ($data as $value) {
        // dataの仕訳要素が「""」でなければ
        if (in_array($value[$header[3]], array_keys($debit_array))) {
            $debit_array[$value[$header[3]]] += $value[$header[4]];
        }

        if (in_array($value[$header[7]], array_keys($credit_array))) {
            $credit_array[$value[$header[7]]] += $value[$header[8]];
        }
    }

    flock($file, LOCK_UN);
    fclose($file);
}

?>