<?php
session_start();
include('functions.php');
checkSessionId();
include("master_process.php");
include("file_process.php");
include("disclosure_items.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L / R</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header>
        <h1>
            <?= $result["customer_name"] ?>
        </h1>
    </header>
    <a href="info_read.php" id="customer_all">事業者変更</a>
    <div id="modal_open_outbox">
        <p class="modal-open">資料のアップロードはこちら</p>
    </div>
    <a href="logout.php" id="logout_text">ログアウト</a>

    <div class="modal-container">
        <div class="modal-body">
            <div class="modal-close">×</div>
            <div class="modal-content">
                <div id="file_upload">
                    <div id="file_name_output">
                        「<span id="file_name"></span>」
                        <p>を選択中</p>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <label id="file_select_btn">
                            ファイルを選択
                            <input type="file" id="file_select" name="fname">
                        </label>
                        <label id="upload_btn">
                            実行
                            <input type="submit" value="upload" id="upload">
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- アップロードコメント -->
    <p>
        <?= $comment ?>
    </p>


    <div id="result_box">
        <button type="button" id="submit">集計結果</button>
    </div>

    <!-- テーブル表示用 -->
    <div id="toggle">
        <div id="output"></div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- 他のJSファイルでPHPの値を使えるように -->
    <script>
        const account_item_array = <?= json_encode($account_item_array) ?>;
    </script>
    <script src="./js/main.js"></script>
</body>

</html>