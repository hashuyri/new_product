<?php
session_start();
include('functions.php');
checkSessionId();
// echo "<pre>";
// var_dump($_SESSION);
// echo "<pre>";

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>事業者情報（入力画面）</title>
</head>

<body>
  
  <form action="customer_main_page.php" method="POST" enctype="multipart/form-data">
    <fieldset>
      <legend>事業者情報（入力画面）</legend>
      <a href="info_read.php">登録情報一覧</a>

      <div>
        会社名： <input type="text" name="customer_name" autocomplete="organization" required>
      </div>
      <div>
        代表者名： <input type="text" name="representative" autocomplete="name" required>
      </div>
      <div>
        郵便番号： <input type="number" pattern="[0-9]{7}" name="address_number" autocomplete="postal-code" required>
        住所： <input type="text" name="address" required>
      </div>
      <div>
        電話番号： <input type="text" pattern="[0-9]{7-12}" name="tel" placeholder="01234567890" autocomplete="tel" required>
      </div>
      <div>
        メールアドレス： <p><?=$_SESSION["user_id"]?></p>
      </>
      <div>
        決算月: <input type="month" name="closing_month" required>
      </div>
      <div>
        <button>submit</button>
      </div>
    </fieldset>
  </form>

</body>

</html>