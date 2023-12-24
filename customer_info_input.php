<?php
// echo "<pre>";
// var_dump(count($_GET));
// echo "<pre>";

// 法人番号が登録されている場合
$error_comment = "";
if (count($_GET) > 0) {
  $error_comment = $_GET["business_id"] . "は既に登録されています。";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>事業者情報（入力画面）</title>
</head>

<body>
  <p>
    <?= $error_comment ?>
  </p>
  <form action="customer_main_page.php" method="POST" enctype="multipart/form-data">
    <fieldset>
      <legend>事業者情報（入力画面）</legend>
      <a href="info_read.php">登録情報一覧</a>

      <!-- Mysqlはintの桁数が限られているためtextで代用 -->
      <div>
        法人番号： <input type="text" pattern="[0-9]{13}" name="business_id" placeholder="1234567890123" required>
        <a href="https://www.houjin-bangou.nta.go.jp/" target="_blank" rel="noopener noreferrer">
          【参考】国税庁法人番号公表サイト
        </a>
      </div>
      <div>
        会社名： <input type="text" name="business_name" required>
      </div>
      <div>
        代表者名： <input type="text" name="representative" required>
      </div>
      <div>
        郵便番号： <input type="number" pattern="[0-9]{7}" name="address_number" required>
        住所： <input type="text" name="address" required>
      </div>
      <div>
        電話番号： <input type="text" pattern="[0-9]{7-12}" name="tel" placeholder="01234567890" required>
      </div>
      <div>
        メールアドレス： <input type="email" name="mail_address" required>
      </div>
      <div>
        決算月: <input type="month" name="closing_month" required>
      </div>
      <div>
        青白区分:
        <label>
          <input type="radio" name="class_radio" value="blue" required>青色申告
        </label>
        <label>
          <input type="radio" name="class_radio" value="white" required>白色申告
        </label>
      </div>
      <div>
        <button>submit</button>
      </div>
    </fieldset>
  </form>

</body>

</html>