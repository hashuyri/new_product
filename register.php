<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザ登録画面</title>
</head>

<body>
  <form action="register_act.php" method="POST">
    <fieldset>
      <legend>ユーザ登録画面</legend>
      <div>
        ユーザーid: <input type="text" name="user_id" required>
      </div>
      <div>
        password: <input type="text" name="password" required>
      </div>
      <div>
        <button>新規登録</button>
      </div>
      <a href="login.php">ログイン画面</a>
    </fieldset>
  </form>

</body>

</html>