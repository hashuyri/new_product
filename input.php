<?php include("main.php") ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L / R</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div id="file_upload">
        <form action="input.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="fname">
            <input type="submit" value="アップロード">
        </form>

        <!-- アップロードコメント -->
        <p>
            <?= $comment ?>
        </p>
    </div>

    <button type="button" id="submit">集計結果</button>

    <!-- テーブル表示用 -->
    <div id="toggle">
        <div id="output"></div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- 他のJSファイルでPHPの値を使えるように -->
    <script>
        const debitArray = <?= json_encode($debitArray) ?>;
        const creditArray = <?= json_encode($creditArray) ?>;
    </script>
    <script src="./js/acitem.js"></script>
    <script src="./js/main.js"></script>
</body>

</html>