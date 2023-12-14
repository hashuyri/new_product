<?php

if ($_FILES["fname"] !== NULL) {
    $files = $_FILES["fname"];
    $tmpfile = $files["tmp_name"];
}

$comment = ""; // エラー対策で外だし

// ファイルがアップロードされているか確認
if (is_uploaded_file($tmpfile)) {
    $filename = "./data/input/" . $files["name"]; // フォルダの指定
    // アップロードされていれば、ファイルを指定のフォルダに格納する
    if (move_uploaded_file($tmpfile, $filename)) {
        $comment = $filename . "をアップロードしました！";
    } else {
        $comment = "ファイルをアップロードできません。";
    }
}

$getFilename = [];
$str = "";
$data = [];
$debitArray = []; // エラー対策で外だし
$creditArray = []; // エラー対策で外だし

// ディレクトリに格納されてるファイル一覧を読み込む
$dp = opendir("./data/input/");

// ディレクトリ内のファイル名を読み込む
while (($item = readdir($dp))) {
    if ($item === '.' || $item === '..') {
        continue;
    }
    $getFilename[] = $item;
}
// echo "<pre>";
// var_dump($getFilename);
// echo "<pre>";

// $getFilenameに要素が一つでも入っていたら
if (count($getFilename) > 0) {
    $filename = "./data/input/" . $getFilename[0];
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

        // ファイルの終端に達するまで行ごとに処理
        while ($row = fgetcsv($file)) {
            // 各フィールドの文字エンコーディングをUTF-8に変換
            foreach ($row as $key => $value) {
                $row[$key] = mb_convert_encoding($value, "UTF-8", "SJIS-win");
            }

            $data[] = array_combine($header, $row); // カラム名とデータを関連付けて格納
        }
    }

    // 仕訳帳から科目を取り出す
    foreach ($data as $value) {
        $tentativeDebitArray[] = $value["借方決算書表示名"];
        $tentativeCreditArray[] = $value["貸方決算書表示名"];
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
        if (in_array($value["借方決算書表示名"], array_keys($debitArray))) {
            $debitArray[$value["借方決算書表示名"]] += $value["借方金額"];
        }

        if (in_array($value["貸方決算書表示名"], array_keys($creditArray))) {
            $creditArray[$value["貸方決算書表示名"]] += $value["貸方金額"];
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